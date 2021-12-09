<?php
// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Show transaction ID under donation meta.
 *
 * @param string $transaction_id
 * @param int    $donation_id
 *
 * @since 1.0
 *
 */
function give_razorpay_link_transaction_id( $transaction_id, $donation_id ) {
	$razorpay_trans_url = 'https://dashboard.razorpay.com/#/app/orders/';

	if ( true === (bool) Give()->payment_meta->get_meta( $donation_id, '_give_subscription_payment', true ) ) {
		$razorpay_trans_url = 'https://dashboard.razorpay.com/#/app/payments/';
	}

	printf(
		'<a href="%1$s" target="_blank">%2$s</a>',
		$razorpay_trans_url . $transaction_id,
		$transaction_id
	);
}

add_filter( 'give_payment_details_transaction_id-razorpay', 'give_razorpay_link_transaction_id', 10, 2 );

/**
 * Add razorpay donor detail to "Donor Detail" metabox
 *
 * @since 1.0
 *
 * @param $payment_id
 *
 * @return bool
 */
function give_razorpay_view_details( $payment_id ) {
	// Bailout.
	if ( 'razorpay' !== give_get_payment_gateway( $payment_id ) ) {
		return false;
	}

	$donor_phone = get_post_meta( absint( $_GET['id'] ), 'donor_phone', true );

	// Check if contact exit in razorpay response.
	if ( empty( $donor_phone ) ) {
		return false;
	}
	?>
	<div class="column">
		<p>
			<strong><?php _e( 'Phone', 'give-razorpay' ); ?></strong><br>
			<?php echo $donor_phone; ?>
		</p>
	</div>
	<?php
}

add_action( 'give_payment_view_details', 'give_razorpay_view_details' );

/**
 * This function is used to add per form RazorPay account to metabox settings.
 *
 * @since 1.3.2
 *
 * @param array $settings Settings List.
 * @param int   $post_id  Post ID/Form ID.
 *
 * @return array
 */
function give_add_metabox_setting_fields( $settings, $post_id ) {
	// Bailout, if Razorpay gateway is not active.
	if ( ! give_razorpay_is_active() ) {
		return $settings;
	}

    $is_form_account = give_is_setting_enabled( give_get_meta( $post_id, 'razorpay_per_form_account_options', true ) );

	$settings['razorpay_form_account_options'] = array(
		'id'        => 'razorpay_form_account_options',
		'title'     => __( 'RazorPay Account', 'give-razorpay' ),
		'icon-html' => '<span class="dashicons dashicons-admin-users"></span>',
		'fields'    => array(
			array(
				'name'    => __( 'Account Options', 'give-razorpay' ),
				'id'      => 'razorpay_per_form_account_options',
				'type'    => 'radio_inline',
				'default' => 'global',
				'options' => array(
					'global'  => __( 'Global Options', 'give-razorpay' ),
					'enabled' => __( 'Customize', 'give-razorpay' ),
				),
			),
			array(
				'id'            => 'razorpay_per_form_live_merchant_key_id',
				'name'          => esc_html__( 'Live Key ID', 'give-razorpay' ),
				'desc'          => esc_html__( 'Required live merchant key id provided by Razorpay.', 'give-razorpay' ),
				'type'          => 'text',
				'wrapper_class' => 'give-razorpay-meta-field ' . ( ( $is_form_account ) ? '' : 'give-hidden' ) ,
			),
			array(
				'id'            => 'razorpay_per_form_live_merchant_secret_key',
				'name'          => esc_html__( 'Live Secret Key', 'give-razorpay' ),
				'desc'          => esc_html__( 'Required live merchant secret key provided by Razorpay.', 'give-razorpay' ),
				'type'          => 'text',
				'wrapper_class' => 'give-razorpay-meta-field ' . ( ( $is_form_account ) ? '' : 'give-hidden' ),
			),
			array(
				'id'            => 'razorpay_per_form_test_merchant_key_id',
				'name'          => esc_html__( 'Test Key ID', 'give-razorpay' ),
				'desc'          => esc_html__( 'Required test merchant key id provided by Razorpay.', 'give-razorpay' ),
				'type'          => 'text',
				'wrapper_class' => 'give-razorpay-meta-field ' . ( ( $is_form_account ) ? '' : 'give-hidden' ),
			),
			array(
				'id'            => 'razorpay_per_form_test_merchant_secret_key',
				'name'          => esc_html__( 'Test Secret Key', 'give-razorpay' ),
				'desc'          => esc_html__( 'Required test merchant secret key provided by Razorpay.', 'give-razorpay' ),
				'type'          => 'text',
				'wrapper_class' => 'give-razorpay-meta-field ' . ( ( $is_form_account ) ? '' : 'give-hidden' ),
			),
		),
	);

	return $settings;
}

add_filter( 'give_metabox_form_data_settings', 'give_add_metabox_setting_fields', 10, 2 );
