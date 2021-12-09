<?php
/**
 * Give - Razorpay Subscriptions.
 *
 * @since 1.3.0
 */

use Razorpay\Api\Subscription;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class exists check ensure that Recurring donations add-on is installed and the Give_RazorPay_Subscriptions class not exists.
 *
 * @since 1.3.0
 */
if ( ! class_exists( 'Give_RazorPay_Subscriptions' ) ) {

	/**
	 * Class Give_RazorPay_Subscriptions
	 *
	 * @since 1.3.0
	 */
	class Give_RazorPay_Subscriptions {

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
		 * Give_RazorPay_Subscriptions constructor.
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
				$give_subscription_id = ! empty( $get_data['id'] ) ? absint( $get_data['id']  ): 0;

				if ( $give_subscription_id ) {
					$give_subscription = new Give_Subscription( $give_subscription_id);
					$form_id           = $give_subscription->form_id;
				}
			}

			// Setup RazorPay API.
			$this->razorpay_api = give_razorpay_get_api( $form_id );

		}

		/**
		 * This function is used to create plan for RazorPay.
		 *
         * @param array $args List of RazorPay plan arguments.
         *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return mixed
		 */
		public function create_plan( $args ) {

			$plan = false;

			// Prepare Plan arguments.
			$args = apply_filters( 'give_razorpay_create_plan_args', $args );

			try {
				$plan = $this->razorpay_api->plan->create( $args );
			} catch( Exception $e ) {

				// Record payment gateway error.
				give_record_gateway_error(
					__( 'Razorpay Plan Error', 'give-razorpay' ),
					sprintf(
						'%1$s: %2$s',
						__( 'Unable to create plan. Details', 'give-razorpay' ),
						$e->getMessage()
					)
				);
			}

			return $plan;

		}

		/**
		 * This function is used to get plan for RazorPay.
		 *
		 * @param array $id Plan ID.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return mixed
		 */
		public function get_plan( $id ) {

			$plan = false;

			try {
				$plan = $this->razorpay_api->plan->fetch( $id );
			} catch( Exception $e ) {

				// Record payment gateway error.
				give_record_gateway_error(
					__( 'Razorpay Plan Error', 'give-razorpay' ),
					sprintf(
						'%1$s: %2$s',
						__( 'Unable to fetch plan. Details', 'give-razorpay' ),
						$e->getMessage()
					)
				);
			}

			return $plan;

		}

		/**
		 * This function is used to create subscription for RazorPay.
		 *
		 * @param array $args List of subscription arguments.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return mixed
		 */
		public function create_subscription( $args ) {

			$subscription = false;

			// Prepare Subscription arguments.
			$args = apply_filters( 'give_razorpay_subscription_args', $args );

			try {
				$subscription = $this->razorpay_api->subscription->create( $args );
			} catch( Exception $e ) {

				// Record payment gateway error.
				give_record_gateway_error(
					__( 'Razorpay Subscription Error', 'give-razorpay' ),
					sprintf(
						'%1$s: %2$s',
						__( 'Unable to create subscription. Details', 'give-razorpay' ),
						$e->getMessage()
					)
				);
			}

			return $subscription;

		}

		/**
		 * This function will be used to fetch existing subscription details.
		 *
		 * @param string $subscription_id Subscription ID from Razorpay.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return Subscription
		 */
		public function get_subscription( $subscription_id ) {

			// Set default value for subscription.
			$subscription = false;

			try {
				$subscription = $this->razorpay_api->subscription->fetch( $subscription_id );
			} catch( Exception $e ) {

				// Record payment gateway error.
				give_record_gateway_error(
					__( 'Razorpay Subscription Error', 'give-razorpay' ),
					sprintf(
						'%1$s: %2$s',
						__( 'Unable to fetch subscription. Details', 'give-razorpay' ),
						$e->getMessage()
					)
				);
			}

			return $subscription;
		}

		/**
		 * This function will be used to cancel existing subscription.
		 *
		 * @param string $subscription_id Subscription ID from Razorpay.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return bool
		 */
		public function cancel_subscription( $subscription_id ) {

			try {
				$subscription = $this->get_subscription( $subscription_id );

				// Cancel Subscription immediately.
				$subscription->cancel();

				return true;
			} catch( Exception $e ) {

				// Record payment gateway error.
				give_record_gateway_error(
					__( 'Razorpay Subscription Error', 'give-razorpay' ),
					sprintf(
						'%1$s: %2$s',
						__( 'Unable to cancel subscription. Details', 'give-razorpay' ),
						$e->getMessage()
					)
				);
				return false;
			}
		}

		/**
		 * This function is used to get updated period based on the gateway acceptance.
		 *
		 * @param string $period   Period at which the recurring donaiton should occur.
		 * @param int    $interval Interval at which the recurring donation should occur.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return string
		 */
		public function get_period( $period, $interval ) {

			switch ( $period ) {

				case 'day':
					$period = 'daily';
					break;

				case 'week':
					$period = 'weekly';
					break;

				case 'month':
				case 'quarter':
					$period = 'monthly';
					break;

				case 'year':
					$period = 'yearly';
					break;
			}

			return $period;
		}

		/**
		 * This function is used to get updated frequency based on the gateway acceptance.
		 *
		 * @param string $period   Period at which the recurring donaiton should occur.
		 * @param int    $interval Interval at which the recurring donation should occur.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return string
		 */
		public function get_frequency( $period, $interval ) {

			switch ( $period ) {

				case 'quarter':
					$interval = 3 * $interval;
					break;
			}

			return $interval;
		}
	}
}
