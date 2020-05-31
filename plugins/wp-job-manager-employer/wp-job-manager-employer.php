<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.swissmation.com
 * @since             1.0.0
 * @package           Wp_Job_Manager_Employer
 *
 * @wordpress-plugin
 * Plugin Name:       Employer Branding for WP Job Manager
 * Plugin URI:        PLUGINURI
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            swissmation
 * Author URI:        https://www.swissmation.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-job-manager-employer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_JOB_MANAGER_EMPLOYER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-job-manager-employer-activator.php
 */
function activate_wp_job_manager_employer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-employer-activator.php';
	Wp_Job_Manager_Employer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-job-manager-employer-deactivator.php
 */
function deactivate_wp_job_manager_employer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-employer-deactivator.php';
	Wp_Job_Manager_Employer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_job_manager_employer' );
register_deactivation_hook( __FILE__, 'deactivate_wp_job_manager_employer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-employer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_job_manager_employer() {

	$plugin = new Wp_Job_Manager_Employer();
	$plugin->run();

}
run_wp_job_manager_employer();