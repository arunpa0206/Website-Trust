<?php
// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get payment method label.
 *
 * @since 1.0
 * @return string
 */
function give_razorpay_get_payment_method_label() {
	$give_settings    = give_get_settings();
	$gateways_label   = array_key_exists( 'gateways_label', $give_settings ) ?
		$give_settings['gateways_label'] :
		array();

	$label = ! empty( $gateways_label['razorpay'] )
		? $gateways_label['razorpay']
		: give_get_option( 'razorpay_payment_method_label', __( 'Razorpay', 'give-razorpay' ) );

	return $label;
}


/**
 * Check if sandbox mode is enabled or disabled.
 *
 * @since 1.0
 * @return bool
 */
function give_razorpay_is_test_mode_enabled() {
	return give_is_test_mode();
}


/**
 * Get razorpay merchant credentials.
 *
 * @since 1.0
 *
 * @param int $form_id Donation Form ID.
 *
 * @return array
 */
function give_razorpay_get_merchant_credentials( $form_id = 0 ) {

	// Sanitize Post Data.
	$post_data = give_clean( $_POST );

	// This ensures that form id is received even during ajax calls.
	if ( ! $form_id ) {
		$form_id = ! empty( $post_data['form'] )
			? absint( $post_data['form'] )
			: ( ! empty( $post_data['give-form-id'] ) ? absint( $post_data['give-form-id'] ) : get_the_ID() );
	}

	$is_form_credentials = give_is_setting_enabled( give_get_meta( $form_id, 'razorpay_per_form_account_options', true ) );

	if( give_razorpay_is_test_mode_enabled() ){
		// Global Test Credentials
		$credentials = array(
			'merchant_key_id'     => give_get_option( 'razorpay_test_merchant_key_id', '' ),
			'merchant_secret_key' => give_get_option( 'razorpay_test_merchant_secret_key', '' ),
		);

		// Per Form Test Credentials.
		if ( $is_form_credentials ) {
			$merchant_key    = give_get_meta( $form_id, 'razorpay_per_form_test_merchant_key_id', true );
			$merchant_secret = give_get_meta( $form_id, 'razorpay_per_form_test_merchant_secret_key', true );

			if ( ! empty( $merchant_key ) && ! empty( $merchant_secret ) ) {
				$credentials = array(
					'merchant_key_id'     => $merchant_key,
					'merchant_secret_key' => $merchant_secret,
				);
			}
		}
	}else {
		// Global Live Credentials.
		$credentials = array(
			'merchant_key_id'     => give_get_option( 'razorpay_live_merchant_key_id', '' ),
			'merchant_secret_key' => give_get_option( 'razorpay_live_merchant_secret_key', '' ),
		);

		// Per Form Live Credentials.
		if ( $is_form_credentials ) {
			$merchant_key    = give_get_meta( $form_id, 'razorpay_per_form_live_merchant_key_id', true );
			$merchant_secret = give_get_meta( $form_id, 'razorpay_per_form_live_merchant_secret_key', true );

			if ( ! empty( $merchant_key ) && ! empty( $merchant_secret ) ) {
				$credentials = array(
					'merchant_key_id'     => $merchant_key,
					'merchant_secret_key' => $merchant_secret,
				);
			}
		}
	}

	return $credentials;

}

/**
 * Check if the Razorpay payment gateway is active or not.
 *
 * @since 1.0
 * @return bool
 */
function give_razorpay_is_active() {
	$give_settings = give_get_settings();
	$is_active     = false;

	if (
		array_key_exists( 'razorpay', $give_settings['gateways'] )
		&& ( 1 == $give_settings['gateways']['razorpay'] )
	) {
		$is_active = true;
	}

	return $is_active;
}


/**
 * Get razorpay api object
 *
 * @since 1.1.0
 *
 * @param int $form_id Donation Form ID.
 *
 * @return Razorpay\Api\Api|null $api
 */
function give_razorpay_get_api( $form_id = 0 ) {
	$merchant = give_razorpay_get_merchant_credentials( $form_id );

	try {
		// Use your key_id and key secret.
		$api = new \Razorpay\Api\Api( $merchant['merchant_key_id'], $merchant['merchant_secret_key'] );
	} catch ( Exception $e ) {
		$api = null;
	}

	return $api;
}

/**
 * Verify payment.
 *
 * @since  1.1.0
 * @access public
 *
 * @param int   $form_id
 * @param array $razorpay_response
 *
 * @return bool
 */
function give_razorpay_validate_payment( $form_id, $razorpay_response ) {


	if (
		empty( $razorpay_response['razorpay_payment_id'] ) ||
		empty( $razorpay_response['razorpay_signature'] )
	) {
		return false;
	}

	// Setup Razorpay API.
	$api = give_razorpay_get_api( $form_id );

	/* @var  \Razorpay\Api\Utility $utility */
	$utility = new \Razorpay\Api\Utility();

	// Verify response signature.
	try {

		$utility->verifyPaymentSignature( $razorpay_response );
	} catch ( Exception $e ) {

		// Record error.
		give_record_gateway_error(
			__( 'Razorpay Error', 'give-razorpay' ),
			__( 'Transaction Failed.', 'give-razorpay' )
			. '<br><br>' . sprintf( esc_attr__( 'Error Detail: %s', 'give-razorpay' ), '<br>' . print_r( $e->getMessage(), true ) )
			. '<br><br>' . sprintf( esc_attr__( 'Razorpay Response: %s', 'give-razorpay' ), '<br>' . print_r( $razorpay_response, true ) )
		);

		give_set_error( 'give-razorpay', __( 'An error occurred while processing your payment. Please try again.', 'give-razorpay' ) );

		return false;
	}

	return true;
}

/**
 * Determines if the shop is using a zero-decimal currency.
 *
 * @since 1.3.0
 *
 * @return bool
 */
function give_razorpay_is_zero_decimal_currency() {

	$ret      = false;
	$currency = give_get_currency();

	switch ( $currency ) {
		case 'BIF':
		case 'CLP':
		case 'DJF':
		case 'GNF':
		case 'JPY':
		case 'KMF':
		case 'KRW':
		case 'MGA':
		case 'PYG':
		case 'RWF':
		case 'VND':
		case 'VUV':
		case 'XAF':
		case 'XOF':
		case 'XPF':
			$ret = true;
			break;
	}

	return $ret;
}

/**
 * This function is used to format the amount based on the type of currency.
 *
 * @param float|int $amount Donation Amount.
 *
 * @since 1.3.0
 *
 * @return float|int
 */
function give_razorpay_format_amount( $amount ) {
	$amount = (float) give_maybe_sanitize_amount( $amount );

	// Get the donation amount.
	if ( give_razorpay_is_zero_decimal_currency() ) {
		return $amount;
	}

	return $amount * 100;
}

/**
 * This function is used to unformat the amount based on the type of currency.
 *
 * @param float|int $amount Donation Amount.
 *
 * @since 1.3.0
 *
 * @return float|int
 */
function give_razorpay_unformat_amount( $amount ) {

	// Get the donation amount.
	if ( give_razorpay_is_zero_decimal_currency() ) {
		return $amount;
	} else {
		return $amount / 100;
	}
}

/**
 * Get the meta key for storing Razorpay customer IDs in.
 *
 * @since 1.3.0
 *
 * @return string $key
 */
function give_razorpay_get_customer_key() {

	$key = '_give_razorpay_customer_id';

	// If test mode, append additional text to the key.
	if ( give_is_test_mode() ) {
		$key .= '_test';
	}

	return $key;
}

/**
 * Save Razorpay Customer ID.
 *
 * @param int    $donation_id Donation ID.
 * @param string $customer_id Customer ID received from Razorpay.
 *
 * @since  1.3.0
 * @access public
 *
 * @return void
 */
function give_razorpay_save_customer_id( $donation_id, $customer_id ) {

	// Update customer meta.
	if ( class_exists( 'Give_DB_Donor_Meta' ) ) {

		$donor_id = give_get_payment_donor_id( $donation_id );

		// Get the Give donor.
		$donor = new Give_Donor( $donor_id );

		// Update donor meta.
		$donor->update_meta( give_razorpay_get_customer_key(), $customer_id );

	} elseif ( is_user_logged_in() ) {

		// Support saving to legacy method of user method.
		update_user_meta( get_current_user_id(), give_razorpay_get_customer_key(), $customer_id );
	}
}

/**
 * This function is used to check whether the subscription is completed or not for Razorpay.
 *
 * @param Give Subscription $subscription   Subscription object of Give.
 * @param int               $total_payments Total payments count of a subscription.
 * @param int               $bill_times     Number of times the subscription is actually billed.
 *
 * @since 1.3.0
 *
 * @return bool
 */
function give_razorpay_is_subscription_completed( $subscription, $total_payments, $bill_times ) {

	if ( $total_payments >= $bill_times && 0 !== $bill_times ) {

		// Cancel subscription in stripe if the subscription has run its course.
		give_recurring_stripe_cancel_subscription( $subscription );

		// Complete the subscription w/ the Give_Subscriptions class.
		$subscription->complete();

		return true;
	}

	return false;
}

/**
 * Return maximum number of billing cycle per period and interval.
 *
 * Razorpay allow billing cycles for 100 years duration.
 * @see https://razorpay.com/docs/api/subscriptions/#create-a-subscription
 *
 * @since 1.4.5
 *
 * @param  string  $period   Subscription period.
 * @param  int     $interval Subscription billing interval
 *
 * @return int
 */
function give_razorpay_get_max_billing_cycle_count_by_period( $period, $interval = 1 ){
	switch ( $period  ){
		case 'day':
			$billing_cycle = 36500;
			break;

		case 'week':
			$billing_cycle = 5200;
			break;

		case 'month':
			$billing_cycle = 1200;
			break;

		case 'quarter':
			$billing_cycle = 400;
			break;

		case 'year':
			$billing_cycle = 100;
			break;

		default:

			// Record payment gateway error.
			give_record_gateway_error(
				esc_html__( 'Razorpay Billing Cycle Error', 'give-razorpay' ),
				sprintf(
					esc_html__( 'Unable to create  billing cycle count for %1$s (%2$s)', 'give-razorpay' ),
					$period,
					$interval
				)
			);

			throw new InvalidArgumentException( 'Please provide valid subscription period.' );
	}

	// Adjusts the billing cycle based on the interval, so every 3 months has a maximum of 400 billing cycles
	if ( $interval < 1 ) {
		throw new InvalidArgumentException('Interval must be a positive integer');
	}

	$billing_cycle = floor( $billing_cycle / $interval );

	return $billing_cycle;
}
