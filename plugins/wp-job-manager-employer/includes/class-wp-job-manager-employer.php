<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.swissmation.com
 * @since      1.0.0
 *
 * @package    WP_Job_Manager_Employer
 * @subpackage WP_Job_Manager_Employer/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Job_Manager_Employer
 * @subpackage WP_Job_Manager_Employer/includes
 * @author     swissmation <AUTHORMAIL>
 */
class WP_Job_Manager_Employer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WP_Job_Manager_Employer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_JOB_MANAGER_EMPLOYER_VERSION' ) ) {
			$this->version = WP_JOB_MANAGER_EMPLOYER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-job-manager-employer';

		$this->load_dependencies();
		
		$this->define_post_types();

		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_Job_Manager_Employer_Loader. Orchestrates the hooks of the plugin.
	 * - WP_Job_Manager_Employer_i18n. Defines internationalization functionality.
	 * - WP_Job_Manager_Employer_Admin. Defines all hooks for the admin area.
	 * - WP_Job_Manager_Employer_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-job-manager-employer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-job-manager-employer-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-job-manager-employer-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-job-manager-employer-public.php';

		/**
		 * Additional classes responsible for defining the plugin functionallity.
		 * 
		 */


		// *** todo: sortieren/dokumentieren
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-job-manager-functions';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-job-manager-template';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-job-manager-employer-post-types.php';


		$this->loader = new WP_Job_Manager_Employer_Loader();

		
		// todo: sortieren/dokumentieren
		// Initialize post types.
		$this->post_types = new WP_Job_Manager_Employer_Post_Types();

		// todo: sortieren
		$this->loader->add_action( 'switch_theme', $null, 'flush_rewrite_rules', 15 );

		add_action( 'switch_theme', 'flush_rewrite_rules', 15 );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_Job_Manager_Employer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WP_Job_Manager_Employer_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WP_Job_Manager_Employer_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WP_Job_Manager_Employer_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Register all additional functionalities
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	// private function define_post_types() {

	// 	$plugin_public = new WP_Job_Manager_Employer_Public( $this->get_plugin_name(), $this->get_version() );

	// 	$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
	// 	$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	// }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WP_Job_Manager_Employer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}