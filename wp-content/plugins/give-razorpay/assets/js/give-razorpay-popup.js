/* globals jQuery, Give, give_global_vars, give_razorpay_vars */
/**
 * Give - Razorpay Popup Checkout JS
 */

/**
 * On document ready setup Razorpay events.
 */
jQuery(document).ready(function ($) {
	// Cache donation button title early to reset it if razorpay checkout popup close.
	var donate_button_titles = [], razorpay_handler = [];

	$('input[type="submit"].give-submit').each(function (index, $submit_btn) {
		$submit_btn = $($submit_btn);
		var $form   = $submit_btn.parents('form'),
			form_id = $('input[name="give-form-id"]', $form).val();

		donate_button_titles[form_id] = $submit_btn.val();
	});

	/**
	 * On form submit prevent submission for Razorpay only.
	 */
	$('form[id^=give-form]').on('submit', function (e) {

		// Form that has been submitted.
		var $form = $(this);

		// Check that Razorpay is indeed the gateway chosen.
		if (
			!give_is_razorpay_gateway_selected($form)
			|| ( typeof $form[0].checkValidity === "function" && $form[0].checkValidity() === false )
		) {
			return true;
		}

		e.preventDefault();

		return false;
	});

	/**
	 * When the submit button is clicked.
	 */
	$(document).on('give_form_validation_passed', function (e) {

		var $form          = $(e.target),
			$submit_button = $form.find('input[type="submit"]'),
			$isRecurring   = $form.find( '#_give_is_donation_recurring' ).val(),
			form_id        = $('input[name="give-form-id"]', $form).val(),
			$priceId       = $form.find('input[name="give-price-id"]').val(),
			$lastName      = $form.find('input[name="give_last"]'),
			firstName      = $form.find('input[name="give_first"]').val().trim(),
			lastName       = $lastName.length ? $lastName.val().trim() : '', // Last name is not required field
			donor_name     = (firstName + ' ' + lastName).trim(),
			donor_email    = $form.find('input[name="give_email"]').val(),
			donor_contact  = $form.find('input[name="give_razorpay_phone"]').val(),
			form_name      = $form.find('input[name="give-form-title"]').val(),
			amount         = $form.find( '.give-final-total-amount' ).attr( 'data-total' ),
			merchant_key   = $form.find('input[name="give_razorpay_form_merchant_id"]').val(),
			currency       = Give.form.fn.getInfo('currency_code', $form),
			nonce          = Give.form.fn.getNonce($form);

		let data = {};
		let handlerData = {
			'key'   : merchant_key,
			'name'  : form_name,
			'currency': currency,
			'image' : give_razorpay_vars.popup.image || '',
			'handler': function (response) {
				// Insert the token into the form so it gets submitted to the server.
				$form.prepend('<input type="hidden" name="give_razorpay_response" value="' + encodeURI( JSON.stringify( response ) ) + '" />');

				// Remove loading animations.
				$form.find('.give-loading-animation').hide();

				// Re-enable submit button and add back text.
				$submit_button.prop('disabled', false).val(donate_button_titles[form_id]);

				// Submit form after charge token brought back from Razorpay.
				$form.get(0).submit();
			},
			'prefill': {
				'name'   : donor_name,
				'email'  : donor_email,
				'contact': donor_contact
			},
			'notes': {
				'name'     : donor_name,
				'address'  : $form.find('input[name="card_address"]').val(),
				'address_2': $form.find('input[name="card_address_2"]').val(),
				'city'     : $form.find('input[name="card_city"]').val(),
				'state'    : $form.find('input[name="card_state"]').val(),
				'country'  : $form.find('input[name="billing_country"]').val(),
				'zipcode'  : $form.find('input[name="card_zip"]').val()
			},
			'modal': {
				'ondismiss': function () {
					// Remove loading animations.
					$form.find('.give-loading-animation').hide();

					// Re-enable submit button and add back text.
					$submit_button.prop('disabled', false).val(donate_button_titles[form_id]);
				}
			},
			'theme': {
				'color': give_razorpay_vars.popup.color
			}
		};

		// Check that Razorpay is indeed the gateway chosen.
		if (
			! give_is_razorpay_gateway_selected( $form ) ||
			( ( typeof $form[0].checkValidity === "function" && $form[0].checkValidity() === false ) )
		) {
			return false;
		}

		if ( '1' === $isRecurring ) {
			const recurringType = $form.find( 'input[name="_give_is_donation_recurring"]' ).attr( 'data-_give_recurring' );
			data = {
				action: 'give_razorpay_setup_subscription',
				amount: amount,
				currency: currency,
				name: donor_name,
				email: donor_email,
				phone: donor_contact,
				formId: form_id,
				recurringType: recurringType,
				priceId: $priceId,
				donorChoice: $form.find( '.give-recurring-donors-choice-period' ).val(),
				nonce: nonce
			};
		} else {

			data = {
				action: 'give_razorpay_setup_order',
				form: form_id,
				amount: amount,
				currency:currency,
				nonce: nonce
			};
		}

		// General AJAX call for one-time as well as recurring donations via Razorpay.
		$.post( give_global_vars.ajaxurl, data ).done( function( response ) {

			// Bailout, if error response.
			if( ! response.success ) {
				return give_razorpay_handle_checkout_errors( $form, response );
			}

			// Increase razorpay's z-index to appear above Give's modal.
			$('.razorpay-container').css('z-index', '2150483543');

			// Set razorpay handler for form.
			if ('undefined' !== razorpay_handler[form_id]) {

				// Pass data based on the response.
				if ( '1' === $isRecurring ) {
					handlerData['subscription_id'] = response.data.id;
				} else {
					handlerData['order_id'] = response.data.order_id;
				}

				handlerData['amount']   = response.data.amount;

				razorpay_handler[form_id] = new Razorpay( handlerData );
			}

			// Open checkout
			razorpay_handler[form_id].open({});

		}).fail( function() {

		}).always( function() {
			// Reset nonce because razorpay create a session for donor if not already created which cause of deprecated existing nonce.
			Give.form.fn.resetAllNonce( $form );

			// Enable form submit button.
			$submit_button.prop('disabled', false);
		});

		e.preventDefault();
	});

	/**
	 * Check if razorpay gateway selected or not
	 *
	 * @param $form
	 * @returns {boolean}
	 */
	function give_is_razorpay_gateway_selected($form) {
		return ( $('input[name="give-gateway"]', $form).val() === 'razorpay' )
	}

	/**
	 * This function is used to handle the checkout errors, if any.
	 *
	 * @param {object} $form    Form Object.
	 * @param {object} response Response.
	 *
	 * @since 1.3.0
	 *
	 * @returns {boolean}
	 */
	function give_razorpay_handle_checkout_errors( $form, response ) {

		const $submit_button = $form.find( 'input[type="submit"]' );

		$form.find( '.give_errors' ).remove();
		$submit_button.before( response.data.error_msg );

		$form.find( 'input[type="submit"].give-submit + .give-loading-animation' ).fadeOut();
		$submit_button.val( $submit_button.attr('data-before-validation-label') );
		Give.form.fn.disable( $form, false );

		return false;
	}
});
