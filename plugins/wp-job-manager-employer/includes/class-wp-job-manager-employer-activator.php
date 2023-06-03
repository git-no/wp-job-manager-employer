<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.swissmation.com
 * @since      1.0.0
 *
 * @package    WP_Job_Manager_Employer
 * @subpackage WP_Job_Manager_Employer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Job_Manager_Employer
 * @subpackage WP_Job_Manager_Employer/includes
 * @author     swissmation <AUTHORMAIL>
 */
class WP_Job_Manager_Employer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// todo: sorting
		// Activation - works with symlinks.
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array( $this->post_types, 'register_post_types' ), 10 );
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array( $this, 'install' ), 10 );
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), 'flush_rewrite_rules', 15 );
		
	}

}