<?php
/**
 * Plugin Name: Give - Razorpay
 * Plugin URI: https://github.com/impress-org/give-razorpay
 * Description: Process online donations via the Razorpay payment gateway.
 * Author: GiveWP
 * Author URI: https://givewp.com
 * Version: 1.4.5
 * Text Domain: give-razorpay
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/impress-org/give-razorpay
 */

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Give_Razorpay_Gateway
 *
 * @since 1.0
 */
final class Give_Razorpay_Gateway {

	/**
	 * @since  1.0
	 * @access static
	 * @var Give_Razorpay_Gateway $instance
	 */
	static private $instance;

	/**
	 * Notices (array)
	 *
	 * @since 1.2.1
	 *
	 * @var array
	 */
	public $notices = array();

	/**
	 * Singleton pattern.
	 *
	 * Give_Razorpay_Gateway constructor.
	 */
	private function __construct() {
	}


	/**
	 * Get instance
	 *
	 * @since  1.0
	 * @access static
	 * @return Give_Razorpay_Gateway|static
	 */
	static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->setup();
		}

		return self::$instance;
	}

	/**
	 * Setup Give Mollie.
	 *
	 * @since  1.2.1
	 * @access private
	 */
	private function setup() {

		// Setup constants.
		$this->setup_constants();

		// Give init hook.
		add_action( 'plugins_loaded', array( $this, 'init' ), 10 );
		add_action( 'admin_init', array( $this, 'check_environment' ), 999 );
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
	}

	/**
	 * Init the plugin in give_init so environment variables are set.
	 *
	 * @since 1.2.1
	 */
	public function init() {
		if ( ! $this->get_environment_warning() ) {
			return;
		}

		if ( is_admin() ) {
			// Process plugin activation.
			require_once GIVE_RAZORPAY_DIR . 'includes/admin/plugin-activation.php';
		}

		$this->load_files();
		$this->setup_hooks();
		$this->load_textdomain();
		$this->activation_banner();


		// Add license.
		if ( class_exists( 'Give_License' ) ) {
			new Give_License( GIVE_RAZORPAY_FILE, 'Razorpay Gateway', GIVE_RAZORPAY_VERSION, 'WordImpress' );
		}

	}

	/**
	 * Setup constants.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function setup_constants() {

		// Global Params.
		if ( ! defined( 'GIVE_RAZORPAY_VERSION' ) ) {
			define( 'GIVE_RAZORPAY_VERSION', '1.4.5' );
		}

		if ( ! defined( 'GIVE_RAZORPAY_MIN_GIVE_VER' ) ) {
			define( 'GIVE_RAZORPAY_MIN_GIVE_VER', '2.6.0' );
		}

		if ( ! defined( 'GIVE_RAZORPAY_FILE' ) ) {
			define( 'GIVE_RAZORPAY_FILE', __FILE__ );
		}

		if ( ! defined( 'GIVE_RAZORPAY_BASENAME' ) ) {
			define( 'GIVE_RAZORPAY_BASENAME', plugin_basename( GIVE_RAZORPAY_FILE ) );
		}

		if ( ! defined( 'GIVE_RAZORPAY_URL' ) ) {
			define( 'GIVE_RAZORPAY_URL', plugins_url( '/', GIVE_RAZORPAY_FILE ) );
		}

		if ( ! defined( 'GIVE_RAZORPAY_DIR' ) ) {
			define( 'GIVE_RAZORPAY_DIR', plugin_dir_path( GIVE_RAZORPAY_FILE ) );
		}
	}

	/**
	 * Load files.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_Razorpay_Gateway
	 */
	public function load_files() {

		// Load Razorpay SDK for PHP.
		require_once GIVE_RAZORPAY_DIR . 'vendor/autoload.php';

		// Load helper functions.
		require_once GIVE_RAZORPAY_DIR . 'includes/functions.php';

		// Load plugin settings.
		require_once GIVE_RAZORPAY_DIR . 'includes/admin/admin-settings.php';

		// Load frontend actions.
		require_once GIVE_RAZORPAY_DIR . 'includes/actions.php';

		// Process payment
		require_once GIVE_RAZORPAY_DIR . 'includes/process-payment.php';
		require_once GIVE_RAZORPAY_DIR . 'includes/class-razorpay-customers.php';
		require_once GIVE_RAZORPAY_DIR . 'includes/class-razorpay-subscriptions.php';
		require_once GIVE_RAZORPAY_DIR . 'includes/class-razorpay-webhooks.php';

		if ( is_admin() ) {
			// Load admin actions..
			require_once GIVE_RAZORPAY_DIR . 'includes/admin/actions.php';
		}

		return self::$instance;
	}


	/**
	 * Setup hooks.
	 *
	 * @since  1.0
	 * @access public
	 * @return Give_Razorpay_Gateway
	 */
	public function setup_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

		return self::$instance;
	}

	/**
	 * Load frontend scripts
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return void
	 */
	public function frontend_enqueue() {

		// Bailout, if Razorpay gateway is not active.
		if ( ! give_razorpay_is_active() ) {
			return;
		}

		wp_register_script( 'razorpay-js', 'https://checkout.razorpay.com/v1/checkout.js' );
		wp_enqueue_script( 'razorpay-js' );

		wp_register_script( 'give-razorpay-popup-js', GIVE_RAZORPAY_URL . 'assets/js/give-razorpay-popup.js', array( 'jquery', 'razorpay-js' ), false, GIVE_RAZORPAY_VERSION );
		wp_enqueue_script( 'give-razorpay-popup-js' );

		$merchant = give_razorpay_get_merchant_credentials();
		$data     = array(
			'merchant_key_id' => $merchant['merchant_key_id'],
			'popup'           => array(
				'color' => give_get_option( 'razorpay_popup_theme_color' ),
				'image' => give_get_option( 'razorpay_popup_image' ),
			),
			'setup_order_url' => add_query_arg( array( 'give_action' => 'give_process_razorpay' ), home_url() ),
			'clear_order_url' => add_query_arg( array( 'give_action' => 'give_clear_order' ), home_url() ),
		);

		wp_localize_script( 'give-razorpay-popup-js', 'give_razorpay_vars', $data );
	}

	/**
	 * Load frontend scripts
	 *
	 * @since  1.3.2
	 * @access public
	 *
	 * @return void
	 */
	public function admin_enqueue() {

		// Bailout, if Razorpay gateway is not active.
		if ( ! give_razorpay_is_active() ) {
			return;
		}

		wp_register_script( 'give-razorpay-admin', GIVE_RAZORPAY_URL . 'assets/js/give-razorpay-admin.js', array( 'jquery' ), false, GIVE_RAZORPAY_VERSION );
		wp_enqueue_script( 'give-razorpay-admin' );
	}


	/**
	 * Check plugin environment.
	 *
	 * @since  1.2.1
	 * @access public
	 *
	 * @return bool
	 */
	public function check_environment() {
		// Flag to check whether plugin file is loaded or not.
		$is_working = true;

		// Load plugin helper functions.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		/* Check to see if Give is activated, if it isn't deactivate and show a banner. */
		// Check for if give plugin activate or not.
		$is_give_active = defined( 'GIVE_PLUGIN_BASENAME' ) ? is_plugin_active( GIVE_PLUGIN_BASENAME ) : false;

		if ( empty( $is_give_active ) ) {
			// Show admin notice.
			$this->add_admin_notice( 'prompt_give_activate', 'error', sprintf( __( '<strong>Activation Error:</strong> You must have the <a href="%s" target="_blank">Give</a> plugin installed and activated for the Razorpay add-on to activate.', 'give-razorpay' ), 'https://givewp.com' ) );
			$is_working = false;
		}

		return $is_working;
	}

	/**
	 * Check plugin for Give environment.
	 *
	 * @since  1.2.1
	 * @access public
	 *
	 * @return bool
	 */
	public function get_environment_warning() {
		// Flag to check whether plugin file is loaded or not.
		$is_working = defined( 'GIVE_VERSION' );

		// Verify dependency cases.
		if ( $is_working && version_compare( GIVE_VERSION, GIVE_RAZORPAY_MIN_GIVE_VER, '<' ) ) {

			/* Min. Give. plugin version. */
			// Show admin notice.
			$this->add_admin_notice(
				'prompt_give_incompatible',
				'error',
				sprintf(
					__( '<strong>Activation Error:</strong> You must have the <a href="%s" target="_blank">Give</a> core version %s for the Razorpay add-on to activate.', 'give-razorpay' ),
					'https://givewp.com',
					GIVE_RAZORPAY_MIN_GIVE_VER
				)
			);

			$is_working = false;
		}

		return $is_working;
	}


	/**
	 * Load the text domain.
	 *
	 * @access private
	 * @since  1.0
	 *
	 * @return void
	 */
	public function load_textdomain() {

		// Set filter for plugin's languages directory.
		$give_razorpay_lang_dir = dirname( plugin_basename( GIVE_RAZORPAY_FILE ) ) . '/languages/';
		$give_razorpay_lang_dir = apply_filters( 'give_razorpay_languages_directory', $give_razorpay_lang_dir );

		// Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale', get_locale(), 'give-razorpay' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'give-razorpay', $locale );

		// Setup paths to current locale file
		$mofile_local  = $give_razorpay_lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/give-razorpay/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/give-razorpay folder
			load_textdomain( 'give-razorpay', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/give-razorpay/languages/ folder
			load_textdomain( 'give-razorpay', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'give-razorpay', false, $give_razorpay_lang_dir );
		}

	}


	/**
	 * Allow this class and other classes to add notices.
	 *
	 * @since 1.2.1
	 *
	 * @param $slug
	 * @param $class
	 * @param $message
	 */
	public function add_admin_notice( $slug, $class, $message ) {
		$this->notices[ $slug ] = array(
			'class'   => $class,
			'message' => $message,
		);
	}

	/**
	 * Display admin notices.
	 *
	 * @since 1.2.1
	 */
	public function admin_notices() {

		$allowed_tags = array(
			'a'      => array(
				'href'  => array(),
				'title' => array(),
				'class' => array(),
				'id'    => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'span'   => array(
				'class' => array(),
			),
			'strong' => array(),
		);

		foreach ( (array) $this->notices as $notice_key => $notice ) {
			echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
			echo wp_kses( $notice['message'], $allowed_tags );
			echo '</p></div>';
		}

	}

	/**
	 * Give Display Donors Activation Banner
	 *
	 * Includes and initializes Give activation banner class.
	 *
	 * @since 1.2.1
	 */
	function activation_banner() {

		// Check for activation banner inclusion.
		if (
			! class_exists( 'Give_Addon_Activation_Banner' )
			&& file_exists( GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php' )
		) {
			include GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php';
		}

		// Initialize activation welcome banner.
		if ( class_exists( 'Give_Addon_Activation_Banner' ) ) {

			$args = array(
				'file'              => GIVE_RAZORPAY_FILE,
				'name'              => esc_html__( 'Razorpay Gateway', 'give-razorpay' ),
				'version'           => GIVE_RAZORPAY_VERSION,
				'settings_url'      => admin_url( 'edit.php?post_type=give_forms&page=give-settings&tab=gateways&section=razorpay' ),
				'documentation_url' => 'http://docs.givewp.com/addon-razorpay',
				'support_url'       => 'https://givewp.com/support/',
				'testing'           => false, // Never leave true.
			);

			new Give_Addon_Activation_Banner( $args );
		}

		return false;

	}
}

if ( ! function_exists( 'Give_Razorpay_Gateway' ) ) {
	function Give_Razorpay_Gateway() {
		return Give_Razorpay_Gateway::get_instance();
	}

	Give_Razorpay_Gateway();
}
