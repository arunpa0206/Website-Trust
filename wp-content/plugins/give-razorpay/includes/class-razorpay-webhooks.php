<?php
/**
 * Give - Razorpay | Process Webhooks
 *
 * @since 1.3.0
 *
 * @package    Give
 * @subpackage Razorpay
 * @copyright  Copyright (c) 2019, GiveWP
 * @license    https://opensource.org/licenses/gpl-license GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Give_Razorpay_Webhooks' ) ) {

	/**
	 * Class Give_Razorpay_Webhooks
	 *
	 * @since 1.3.0
	 */
	class Give_Razorpay_Webhooks {

		/**
		 * Give_Razorpay_Webhooks constructor.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'listen' ) );
		}

		/**
		 * Listen for Razorpay webhook events.
		 *
		 * @access public
		 * @since  1.3.0
		 *
		 * @return void
		 */
		public function listen() {

			$give_listener = give_clean( filter_input( INPUT_GET, 'give-listener' ) );

			// Must be a stripe listener to proceed.
			if ( ! isset( $give_listener ) || 'razorpay' !== $give_listener ) {
				return;
			}

			// Retrieve the request's body and parse it as JSON.
			$body  = @file_get_contents( 'php://input' );
			$event = json_decode( $body );

			$processed_event = $this->process( $event );

			if ( false === $processed_event ) {
				$message = __( 'Something went wrong with processing the payment gateway event.', 'give-razorpay' );
			} else {
				$message = sprintf(
				/* translators: 1. Processing result. */
					__( 'Processed event: %s', 'give-razorpay' ),
					$processed_event
				);

				give_record_gateway_error(
					__( 'Razorpay - Webhook Received', 'give-razorpay' ),
					sprintf(
					/* translators: 1. Event ID 2. Event Type 3. Message */
						__( 'Webhook received with ID %1$s and TYPE %2$s which processed and returned a message %3$s.', 'give-razorpay' ),
						$event->id,
						$event->type,
						$message
					)
				);
			}

			status_header( 200 );
			exit( $message );
		}

		/**
		 * Process Webhooks.
		 *
		 * @since  1.3.0
		 * @access public
		 *
		 * @param \Stripe\Event $event_json Event.
		 *
		 * @return bool|string
		 */
		public function process( $event_json ) {

			// Next, proceed with additional webhooks.
			if ( ! empty( $event_json->entity ) && 'event' === $event_json->entity ) {

				status_header( 200 );

				// Update time of webhook received whenever the event is retrieved.
				give_update_option( 'give_razorpay_last_webhook_received_timestamp', current_time( 'timestamp', 1 ) );

				// Bailout, if event type doesn't exists.
				if ( empty( $event_json->event ) ) {
					return false;
				}

				$event_type = $event_json->event;

				/**
				 * @todo Add switch case here in case any webhook trigger is required for one-time donations.
				 */

				do_action( 'give_razorpay_event_' . $event_type, $event_json );

				return $event_type;

			}

			// If failed.
			status_header( 500 );
			die( '-1' );
		}
	}

	// Initialize Razorpay Webhooks.
	new Give_Razorpay_Webhooks();
}
