<?php
/**
 * Give - Razorpay Customers.
 *
 * @since 1.3.0
 */

use Razorpay\Api\Customer;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class_exists check ensures that the `Give_RazorPay_Customers` doesn't already loaded.
 *
 * @since 1.3.0
 */
if ( ! class_exists( 'Give_RazorPay_Customers' ) ) {

	/**
	 * Class Give_RazorPay_Customers
	 *
	 * @since 1.3.0
	 */
	class Give_RazorPay_Customers {

		/**
		 * RazorPay API.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @var Razorpay\Api\Api
		 */
		public $razorpay_api;

		/**
		 * Give_RazorPay_Customers constructor.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return void
		 */
		public function __construct() {

			$form_id = ! empty( $_POST['formId'] ) ? absint( $_POST['formId'] ) : 0;

			// Get form id when actions triggered from admin.
			if ( is_admin() ) {
				$get_data             = give_clean( $_GET );
				$give_subscription_id = ! empty( $get_data['id'] ) ? absint( $get_data['id'] ) : 0;

				if ( $give_subscription_id ) {
					$give_subscription = new Give_Subscription( $give_subscription_id);
					$form_id           = $give_subscription->form_id;
				}
			}

			// Setup RazorPay API.
			$this->razorpay_api = give_razorpay_get_api( $form_id );

		}

		/**
		 * This function is used to create customer for RazorPay.
		 *
		 * @param array $args List of RazorPay plan arguments.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return mixed
		 */
		public function create( $args ) {

			// Set customer to false by default.
			$customer = false;

			// Prepare Customer arguments.
			$args = apply_filters( 'give_razorpay_customer_args', $args );

			// Adding this parameter to the customer argument will prevent error when customer already exists.
			$args['fail_existing'] = 0;

			try {
				$customer = $this->razorpay_api->customer->create( $args );
			} catch( Exception $e ) {

				// Record payment gateway error.
				give_record_gateway_error(
					__( 'Razorpay Customer Creation Error', 'give-razorpay' ),
					sprintf(
						'%1$s: %2$s',
						__( 'Unable to create customer. Details', 'give-razorpay' ),
						$e->getMessage()
					)
				);

				// Set Error to show on sending back to checkout page.
				give_set_error(
					'give-razorpay',
					__( 'An error occurred while creating a customer. Please try again.', 'give-razorpay' )
				);

				// Problems? Send back.
				give_send_back_to_checkout();
			}

			return $customer;
		}

		/**
		 * This function is used to fetch all the customers from RazorPay.
		 *
		 * @param array $options List of options to pass.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return Customer
		 */
		public function list_all( $options ) {

			$customers = false;

			try {
				$customers = $this->razorpay_api->customer->all( $options );
			} catch( Exception $e ) {

				// Record payment gateway error.
				give_record_gateway_error(
					__( 'Razorpay Customer Fetching Error', 'give-razorpay' ),
					sprintf(
						'%1$s: %2$s',
						__( 'Unable to fetch customer. Details', 'give-razorpay' ),
						$e->getMessage()
					)
				);

				// Set Error to show on sending back to checkout page.
				give_set_error(
					'give-razorpay',
					__( 'An error occurred while fetching all the customers. Please try again.', 'give-razorpay' )
				);

				// Problems? Send back.
				give_send_back_to_checkout();
			}

			return $customers;
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
		public function save_razorpay_customer_id( $donation_id, $customer_id ) {
			give_razorpay_save_customer_id( $donation_id, $customer_id );
		}

		/**
		 * Get Razorpay Customer ID.
		 *
		 * @param int $donation_id Donation ID.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return string
		 */
		public function get_razorpay_customer_id( $donation_id ) {

			$customer_id = false;

			// Update customer meta.
			if ( class_exists( 'Give_DB_Donor_Meta' ) ) {

				$donor_id = give_get_payment_donor_id( $donation_id );

				// Get the Give donor.
				$donor = new Give_Donor( $donor_id );

				// Update donor meta.
				$customer_id = $donor->get_meta( give_razorpay_get_customer_key() );

			} elseif ( is_user_logged_in() ) {

				// Support saving to legacy method of user method.
				$customer_id = get_user_meta( get_current_user_id(), give_razorpay_get_customer_key() );
			}

			return $customer_id;
		}
	}
}
