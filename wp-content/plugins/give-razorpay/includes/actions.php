<?php
// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Give\Helpers\Form\Utils as FormUtils;

/**
 * Print cc field in donation form conditionally.
 *
 * @param $form_id
 *
 * @return bool
 * @since 1.0
 */
function give_razorpay_cc_form_callback( $form_id ) {

	if ( give_is_setting_enabled( give_get_option( 'razorpay_billing_details' ) ) ) {
		give_default_cc_address_fields( $form_id );

		return true;
	}

	// Check that new template related fns exist (introduced in GiveWP core 2.7.0)
	if ( ! class_exists('Give\Helpers\Form\Utils') || FormUtils::isLegacyForm() ) {
		return false;
	}

	// If no fields are rendered, output a note explaining the
	// process of completing a Razorpay donation offsite
	printf(
		'
				<fieldset class="no-fields">
					<div style="display: flex; justify-content: center; margin-top: 20px;">
						<svg width="316" height="67" viewBox="0 0 316 67" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M20.4385 17.7L17.8135 27.3617L32.8385 17.645L23.0118 54.3083L32.9918 54.3167L47.5085 0.16333" fill="#3395FF"/>
							<path d="M4.26712 38.9033L0.133789 54.3167H20.5888L28.9588 22.9617L4.26712 38.9033ZM75.3205 25.3333C74.8205 27.1917 73.8571 28.5567 72.4205 29.4283C70.9871 30.2983 68.9755 30.735 66.3788 30.735H58.1288L61.0255 19.935H69.2755C71.8688 19.935 73.6505 20.3683 74.6171 21.2517C75.5838 22.135 75.8171 23.485 75.3205 25.3517V25.3333ZM83.8621 25.1183C84.9121 21.2183 84.4788 18.2183 82.5588 16.1183C80.6421 14.035 77.2788 12.985 72.4788 12.985H54.0671L42.9838 54.335H51.9288L56.3955 37.6683H62.2621C63.5788 37.6683 64.6155 37.885 65.3721 38.3017C66.1305 38.735 66.5755 39.485 66.7121 40.5683L68.3088 54.335H77.8921L76.3388 41.5017C76.0221 38.635 74.7105 36.9517 72.4055 36.4517C75.3438 35.6017 77.8055 34.185 79.7888 32.2183C81.7583 30.266 83.1654 27.8192 83.8621 25.135V25.1183ZM105.605 39.535C104.855 42.335 103.705 44.4517 102.15 45.935C100.594 47.4183 98.7338 48.1517 96.5638 48.1517C94.3538 48.1517 92.8555 47.435 92.0638 45.985C91.2705 44.535 91.2438 42.435 91.9805 39.685C92.7171 36.935 93.8921 34.785 95.5088 33.235C97.1255 31.685 99.0155 30.91 101.185 30.91C103.352 30.91 104.835 31.66 105.585 33.1483C106.352 34.6433 106.369 36.7817 105.619 39.565L105.605 39.535ZM109.525 24.9017L108.405 29.085C107.922 27.585 106.984 26.385 105.597 25.485C104.207 24.6017 102.487 24.1517 100.435 24.1517C97.9188 24.1517 95.5021 24.8017 93.1855 26.1017C90.8688 27.4017 88.8355 29.235 87.1021 31.6017C85.3688 33.9683 84.1021 36.6517 83.2855 39.6683C82.4855 42.7017 82.3188 45.3517 82.8021 47.6517C83.3021 49.9683 84.3521 51.735 85.9688 52.9683C87.6021 54.2183 89.6855 54.835 92.2355 54.835C94.2617 54.8453 96.2652 54.407 98.1021 53.5517C99.9183 52.7325 101.534 51.5266 102.835 50.0183L101.669 54.3783H110.319L118.217 24.9167H109.55L109.525 24.9017ZM149.3 24.9017H124.145L122.387 31.4683H137.024L117.674 48.185L116.02 54.3517H141.987L143.745 47.785H128.062L147.709 30.8183L149.3 24.9017ZM171.442 39.485C170.664 42.385 169.509 44.565 167.984 45.985C166.459 47.4183 164.612 48.135 162.444 48.135C157.91 48.135 156.42 45.2517 157.967 39.485C158.734 36.6183 159.894 34.4633 161.444 33.0117C162.994 31.555 164.872 30.8283 167.08 30.8283C169.247 30.8283 170.71 31.55 171.464 33.0033C172.217 34.4533 172.21 36.615 171.442 39.4817V39.485ZM176.505 26.01C174.514 24.77 171.972 24.15 168.872 24.15C165.734 24.15 162.829 24.7667 160.155 26C157.493 27.2256 155.154 29.0552 153.322 31.3433C151.439 33.66 150.084 36.3767 149.252 39.4767C148.435 42.565 148.335 45.2767 148.969 47.5983C149.602 49.915 150.935 51.6983 152.935 52.9317C154.952 54.175 157.519 54.7933 160.669 54.7933C163.769 54.7933 166.652 54.17 169.302 52.93C171.952 51.6833 174.219 49.9133 176.102 47.58C177.985 45.2567 179.335 42.5467 180.169 39.4467C181.002 36.3467 181.102 33.64 180.469 31.3133C179.835 28.9967 178.519 27.2133 176.535 25.9717L176.505 26.01ZM207.385 32.7717L209.602 24.755C208.852 24.3717 207.869 24.1717 206.635 24.1717C204.652 24.1717 202.752 24.6617 200.919 25.655C199.342 26.4983 198.002 27.6883 196.869 29.1783L198.019 24.8617L195.507 24.8717H189.34L181.39 54.3217H190.162L194.287 38.9267C194.887 36.6883 195.967 34.9267 197.525 33.6767C199.075 32.4217 201.009 31.7933 203.342 31.7933C204.775 31.7933 206.109 32.1217 207.375 32.7767L207.385 32.7717ZM231.792 39.6267C231.042 42.3767 229.909 44.4767 228.359 45.9267C226.809 47.3833 224.942 48.11 222.775 48.11C220.609 48.11 219.125 47.3767 218.342 45.91C217.542 44.435 217.525 42.31 218.275 39.5167C219.025 36.725 220.175 34.5833 221.759 33.1C223.342 31.605 225.209 30.8583 227.375 30.8583C229.509 30.8583 230.942 31.625 231.709 33.175C232.475 34.725 232.492 36.875 231.755 39.625L231.792 39.6267ZM237.889 26.0583C236.264 24.7583 234.189 24.1083 231.672 24.1083C229.467 24.1083 227.365 24.6083 225.372 25.6183C223.38 26.6267 221.764 28.0017 220.522 29.7417L220.552 29.5417L222.024 24.8583H213.457L211.274 33.0083L211.207 33.2917L202.207 66.865H210.99L215.524 49.965C215.974 51.4683 216.89 52.6483 218.29 53.5017C219.69 54.3517 221.419 54.7733 223.474 54.7733C226.024 54.7733 228.457 54.1567 230.765 52.9233C233.082 51.6867 235.082 49.9067 236.782 47.6067C238.482 45.3067 239.744 42.64 240.549 39.6233C241.365 36.6017 241.532 33.9067 241.065 31.5483C240.59 29.1867 239.539 27.3583 237.915 26.065L237.889 26.0583ZM267.024 39.5067C266.274 42.29 265.124 44.4233 263.574 45.89C262.024 47.3667 260.157 48.1017 257.99 48.1017C255.774 48.1017 254.274 47.385 253.49 45.935C252.69 44.485 252.674 42.385 253.407 39.635C254.14 36.885 255.31 34.735 256.927 33.185C258.544 31.635 260.435 30.8617 262.605 30.8617C264.772 30.8617 266.239 31.6117 267.005 33.095C267.772 34.5833 267.777 36.7217 267.03 39.5117L267.024 39.5067ZM270.94 24.865L269.819 29.0483C269.335 27.54 268.402 26.34 267.019 25.4483C265.619 24.5583 263.902 24.115 261.852 24.115C259.335 24.115 256.905 24.765 254.585 26.065C252.269 27.365 250.235 29.1883 248.502 31.5483C246.769 33.9083 245.502 36.5983 244.685 39.615C243.877 42.6433 243.719 45.2983 244.202 47.6083C244.69 49.9083 245.742 51.685 247.369 52.925C248.992 54.1583 251.085 54.7817 253.635 54.7817C255.685 54.7817 257.644 54.355 259.502 53.4983C261.314 52.6754 262.925 51.468 264.224 49.96L263.057 54.3233H271.707L279.604 24.8733H270.954L270.94 24.865ZM315.919 24.875L315.924 24.8667H310.607C310.437 24.8667 310.287 24.875 310.132 24.8783H307.374L305.957 26.845L305.607 27.3117L305.457 27.545L294.249 43.1583L291.932 24.875H282.752L287.402 52.6583L277.135 66.875H286.285L288.769 63.3533C288.839 63.25 288.902 63.1633 288.985 63.0533L291.885 58.9367L291.969 58.82L304.957 40.4033L315.907 24.9033L315.924 24.8933H315.919V24.875Z" fill="#072654"/>
						</svg>
					</div>
					<p style="text-align: center;"><b>%1$s</b></p>
					<p style="text-align: center;">
						<b>%2$s</b> %3$s
					</p>
				</fieldset>
				',
		__( 'Make your donation quickly and securely with Razorpay', 'give-razorpay' ),
		__( 'How it works:', 'give-razorpay' ),
		__( 'A Razorpay window will open after you click the Donate Now button where you can securely make your donation. You will then be brought back to this page to view your receipt. ', 'give-razorpay' )
	);

	return true;
}

add_action( 'give_razorpay_cc_form', 'give_razorpay_cc_form_callback' );

/**
 * Add phone field.
 *
 * @param $form_id
 *
 * @return bool
 * @since 1.0
 */
function give_razorpay_add_phone_field( $form_id ) {

	// Bailout.
	if (
		'razorpay' !== give_get_chosen_gateway( $form_id )
		|| ! give_is_setting_enabled( give_get_option( 'razorpay_phone_field' ) )
	) {
		return false;
	}
	?>
	<p id="give-phone-wrap" class="form-row form-row-wide">
		<label class="give-label" for="give-phone">
			<?php esc_html_e( 'Phone', 'give-razorpay' ); ?>
			<span class="give-required-indicator">*</span>
			<span class="give-tooltip give-icon give-icon-question"
				  data-tooltip="<?php esc_attr_e( 'Enter your phone number.', 'give-razorpay' ); ?>"></span>

		</label>

		<input
				class="give-input required"
				type="tel"
				name="give_razorpay_phone"
				id="give-phone"
				value="<?php echo isset( $give_user_info['give_phone'] ) ? $give_user_info['give_phone'] : ''; ?>"
				required
				aria-required="true"
				maxlength="10"
				pattern="\d{10}"
		/>
	</p>
	<?php
}

add_action( 'give_donation_form_after_email', 'give_razorpay_add_phone_field' );


/**
 * This function will be used to setup Razorpay order.
 *
 * Note: This function is used for internal purposes only.
 *
 * @return string
 * @since 1.1.0
 */
function give_razorpay_setup_order() {
	give_razorpay_validate_nonce( absint( $_POST['form'] ) );

	// Bailout.
	if (
		empty( $_POST['form'] ) ||
		empty( $_POST['amount'] ) ||
		! ( $api = give_razorpay_get_api( absint( $_POST['form'] ) ) )
	) {
		wp_send_json_error( array( 'error_code' => 1 ) );
	}

	// start session.
	Give()->session->maybe_start_session();

	// Get data from POST
	$form_id         = absint( $_POST['form'] );
	$donation_amount = give_razorpay_format_amount( give_clean( $_POST['amount'] ) );

	// Get data from session.
	$razorpay_session_data = Give()->session->get( 'razorpay' );
	$donation_order_key    = "donation_{$form_id}";
	$is_form_session_exist = ! empty( $razorpay_session_data[ $donation_order_key ] ) && ( (float) $razorpay_session_data[ $donation_order_key ]['amount'] === $donation_amount );

	// Check if already payment done with same order id.
	if ( $is_form_session_exist ) {
		$args = array(
			'meta_key'   => '_give_payment_transaction_id',
			'meta_value' => $razorpay_session_data[ $donation_order_key ]->id,
		);

		$payments = new Give_Payments_Query( $args );
		$payments = $payments->get_payments();

		if ( ! empty( $payments ) ) {
			// Flush session data.
			unset( $razorpay_session_data[ $donation_order_key ] );
			Give()->session->set( 'razorpay', $razorpay_session_data );

			$is_form_session_exist = false;
		}
	}

	// Create new order.
	if ( ! $is_form_session_exist ) {

		// Receipt id.
		$receipt_id = uniqid( 'give-razorpay' );

		try {
			$razorpay_session_data[ $donation_order_key ] = $api->order->create(
				array(
					'receipt'         => $receipt_id,
					'amount'          => $donation_amount,
					'currency'        => give_clean( $_POST['currency'] ),
					'payment_capture' => true,
				)
			);

			Give()->session->set( 'razorpay', $razorpay_session_data );

		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'error_code' => 2,
					'error_msg'  => Give_Notices::print_frontend_notice( $e->getMessage(), false ),
				)
			);

			// Record error.
			give_record_gateway_error(
				__( 'Razorpay Error', 'give-razorpay' ),
				__( 'Unable to setup order.', 'give-razorpay' )
				. '<br><br>' . sprintf( esc_attr__( 'Error Detail: %s', 'give-razorpay' ), '<br>' . print_r( $e->getMessage(), true ) )
				. '<br><br>' . sprintf( esc_attr__( 'POST Data: %s', 'give-razorpay' ), '<br>' . print_r( give_clean( $_POST ), true ) )
			);
		}
	}

	wp_send_json_success(
		array(
			'order_id' => $razorpay_session_data[ $donation_order_key ]->id,
			'amount'   => $donation_amount,
		)
	);
}

add_action( 'wp_ajax_give_razorpay_setup_order', 'give_razorpay_setup_order' );
add_action( 'wp_ajax_nopriv_give_razorpay_setup_order', 'give_razorpay_setup_order' );


/**
 * This function is used to setup subscription.
 *
 * Note: This function is used for internal purposes only.
 *
 * @since 1.3.0
 * @since 1.4.5 Add code to set billing cycle count for ongoing subscription.
 *
 * @return mixed
 */
function give_razorpay_setup_subscription() {
	give_razorpay_validate_nonce( absint( $_POST['formId'] ) );

	// Start session.
	Give()->session->maybe_start_session();

	// Sanitize the posted variables of the donation form.
	$post_data = give_clean( $_POST );

	$recurring_type = $post_data['recurringType'];
	$form_id        = $post_data['formId'];
	$form           = new Give_Donate_Form( $form_id );
	$amount         = $post_data['amount'];
	$price_id       = isset( $post_data['priceId'] ) ? $post_data['priceId'] : '';
	$period         = '';
	$interval       = '';
	$bill_times     = '';
	$donor_email    = $post_data['email'];
	$donor_name     = $post_data['name'];
	$donor_phone    = ! empty( $post_data['phone'] ) ? $post_data['phone'] : '';

	$customer_args = array(
		'name'    => $donor_name,
		'email'   => $donor_email,
		'contact' => $donor_phone,
	);

	if ( 'yes_donor' === $recurring_type ) {
		$period_type = give_get_meta( $form_id, '_give_period_functionality', true );
		$interval    = give_get_meta( $form_id, '_give_period_interval', true );
		$period      = 'donors_choice' === $period_type
			? give_get_meta( $form_id, '_give_period_default_donor_choice', true )
			: give_get_meta( $form_id, '_give_period', true );
		$bill_times  = give_get_meta( $form_id, '_give_times', true );

		// If period type is `donor_choice` then only period will change based on donor selection.
		if ( 'donors_choice' === $period_type ) {
			$period = $post_data['donorChoice'];
		}

	} elseif ( 'yes_admin' === $recurring_type ) {
		if ( 'multi' === Give()->form_meta->get_meta( $form_id, '_give_price_option', true ) ) {
			$form  = new Give_Donate_Form( $form_id );
			$price = $form->get_level_info( $price_id );

			if ( 'custom' === $price_id ) {
				$period     = give_get_meta( $form_id, '_give_recurring_custom_amount_period', true, 'month' );
				$interval   = give_get_meta( $form_id, '_give_recurring_custom_amount_interval', true, '1' );
				$bill_times = give_get_meta( $form_id, '_give_recurring_custom_amount_times', true, '0' );

			}
			if ( $price ) {
				$period     = $price['_give_period'];
				$interval   = $price['_give_period_interval'];
				$bill_times = $price['_give_times'];
			}

		} else {
			$interval   = give_get_meta( $form_id, '_give_period_interval', true );
			$period     = give_get_meta( $form_id, '_give_period', true );
			$bill_times = give_get_meta( $form_id, '_give_times', true );
		}
	}

	// If bill times is 0 or less than 0 then set bill times to Razorpay compatible for ongoing subscriptions.
	if ( 0 >= $bill_times ) {
		try{
			// billing cycle upto 100 years, `total_count` supported by Razorpay.
			$bill_times = give_razorpay_get_max_billing_cycle_count_by_period( $period, $interval );
		}catch ( Exception $e ) {
			$subscription_error = Give_Notices::print_frontend_notice(
				esc_html__( 'There was an error processing recurring donation with Razorpay. Please try again.', 'give-razorpay' ),
				false,
				'error'
			);

			wp_send_json_error(
				array(
					'error_msg' => $subscription_error,
				)
			);
		}
	}

	// Get data from session.
	$razorpay_session_data = Give()->session->get( 'razorpay' );
	$donation_session_key = "recurring_donation_{$form_id}";
	$is_form_session_exist = ! empty( $razorpay_session_data[ $donation_session_key ] ) && ( md5( serialize( $razorpay_session_data[ $donation_session_key ]['postData'] ) ) === md5( serialize( $post_data ) ) );

	// Check if already payment done with same order id.
	if ( $is_form_session_exist ) {
		$subscription = new Give_Subscription( $razorpay_session_data[ $donation_session_key ]['subscription_id'], true );

		if ( ! $subscription->id ) {
			// Send the subscription data.
			wp_send_json_success(
				array(
					'id'     => $razorpay_session_data[ $donation_session_key ]['subscription_id'],
					'amount' => give_razorpay_format_amount( $amount ),
					'cached' => true
				)
			);

			give_die();

		} else {
			// Flush session data.
			unset( $razorpay_session_data[ $donation_session_key ] );
			Give()->session->set( 'razorpay', $razorpay_session_data );
		}
	}

	// Setup and create customer.
	$razorpay_customers = new Give_RazorPay_Customers();
	$customer_details   = $razorpay_customers->create( $customer_args );
	$customer_id        = ! empty( $customer_details->id ) ? $customer_details->id : false;

	// Prepare for Plan.
	$razorpay_subscriptions = new Give_RazorPay_Subscriptions();
	$plan_args              = array(
		'period'   => $razorpay_subscriptions->get_period( $period, $interval ),
		'interval' => $razorpay_subscriptions->get_frequency( $period, $interval ),
		'item'     => array(
			'name'        => $form->post_title,
			'description' => __( 'Donation processed via Give - Donations plugin for WordPress', 'give-razorpay' ),
			'amount'      => give_razorpay_format_amount( $amount ),
			'currency'    => give_clean( $_POST['currency'] ),
		),
	);

	$plan_details = $razorpay_subscriptions->create_plan( $plan_args );
	$plan_id      = $plan_details->id;

	// If subscription id don't exists, then throw error.
	if ( empty( $plan_id ) ) {
		$plan_error = Give()->notices->print_frontend_notice(
			__( 'There was an error processing recurring donation with Razorpay. Please try again.', 'give-razorpay' ),
			false,
			'error'
		);

		wp_send_json_error(
			array(
				'error_msg' => $plan_error,
			)
		);
	}

	// Prepare for subscription.
	$subscription_args = array(
		'plan_id'         => $plan_id,
		'customer_notify' => 0,
		'total_count'     => $bill_times,
		'customer_id'     => $customer_id,
	);

	// Create a new subscription.
	$subscription_details = $razorpay_subscriptions->create_subscription( $subscription_args );
	$subscription_id      = $subscription_details->id;

	// If subscription id don't exists, then throw error.
	if ( empty( $subscription_id ) ) {
		$subscription_error = Give()->notices->print_frontend_notice(
			__( 'There was an error processing recurring donation with Razorpay. Please try again.', 'give-razorpay' ),
			false,
			'error'
		);

		wp_send_json_error(
			array(
				'error_msg' => $subscription_error,
			)
		);
	}

	$razorpay_session_data[ $donation_session_key ] = array(
		'plan_id'         => $plan_id,
		'subscription_id' => $subscription_id,
		'customer_id'     => $customer_id,
		'postData'        => $post_data,
	);

	// Setup session data.
	Give()->session->set( 'razorpay', $razorpay_session_data );

	// Send the subscription data.
	wp_send_json_success(
		array(
			'id'     => $subscription_id,
			'amount' => give_razorpay_format_amount( $amount ),
		)
	);

	give_die();
}

add_action( 'wp_ajax_give_razorpay_setup_subscription', 'give_razorpay_setup_subscription' );
add_action( 'wp_ajax_nopriv_give_razorpay_setup_subscription', 'give_razorpay_setup_subscription' );

/**
 * Add hidden fields to send merchant keys to JS.
 *
 * @param int $form_id Form ID.
 *
 * @return mixed
 * @since 1.3.2
 */
function give_razorpay_add_hidden_fields( $form_id ) {
	if ( ! give_razorpay_is_active() ) {
		return;
	}

	$is_form_account = give_is_setting_enabled( give_get_meta( $form_id, 'razorpay_per_form_account_options', true ) );

	if ( $is_form_account ) {
		// Live Merchant Key Per Form.
		$merchant_key_id = give_get_meta( $form_id, 'razorpay_per_form_live_merchant_key_id', true );

		// Test Merchant Key Per Form.
		if ( give_razorpay_is_test_mode_enabled() ) {
			$merchant_key_id = give_get_meta( $form_id, 'razorpay_per_form_test_merchant_key_id', true );
		}
	} else {
		// Live Merchant Key.
		$merchant_key_id = give_get_option( 'razorpay_live_merchant_key_id' );

		// Test Merchant Key.
		if ( give_razorpay_is_test_mode_enabled() ) {
			$merchant_key_id = give_get_option( 'razorpay_test_merchant_key_id' );
		}
	}

	echo sprintf(
		'<input type="hidden" class="%1$s" name="%2$s" value="%3$s" />',
		'give-razorpay-form-merchant-key-id',
		'give_razorpay_form_merchant_id',
		$merchant_key_id
	);
}

add_action( 'give_hidden_fields_after', 'give_razorpay_add_hidden_fields' );


/**
 * Helper fn to validate nonce
 * Note: only for internal use
 *
 * @since 1.4.1
 *
 * @param int $form_id
 */
function give_razorpay_validate_nonce( $form_id ) {
	if (
		empty( $_POST['nonce'] ) ||
		! give_verify_donation_form_nonce( $_POST['nonce'], $form_id )
	) {
		wp_send_json_error(
			array(
				'error_code' => 'form_nonce_invalidate',
				'error_msg'  => Give_Notices::print_frontend_notice( __( 'We\'re unable to recognize your session. Please refresh the screen to try again; otherwise contact your website administrator for assistance.', 'give-razorpay' ), false, 'error' ),
			)
		);
	}

}

