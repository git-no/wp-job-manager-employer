<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.swissmation.com
 * @since      1.0.0
 *
 * @package    Wp_Job_Manager_Employer
 * @subpackage Wp_Job_Manager_Employer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Job_Manager_Employer
 * @subpackage Wp_Job_Manager_Employer/includes
 * @author     swissmation <AUTHORMAIL>
 */
class Wp_Job_Manager_Employer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-job-manager-employer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
