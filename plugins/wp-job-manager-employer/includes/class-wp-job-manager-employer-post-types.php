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
class WP_Job_Manager_Employer_Post_Types {

    /**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ), 0 );
    }
    
    /**
	 * Sets up actions related to the employer post type.
	 */
	public function init_post_types() {
		add_action( 'wp', array( $this, 'download_resume_handler' ) );
		add_filter( 'admin_head', array( $this, 'admin_head' ) );
		add_filter( 'the_title', array( $this, 'resume_title' ), 10, 2 );
		add_filter( 'single_post_title', array( $this, 'resume_title' ), 10, 2 );
		add_filter( 'the_content', array( $this, 'resume_content' ) );
		if ( resume_manager_discourage_resume_search_indexing() ) {
			add_filter( 'wp_head', array( $this, 'add_no_robots' ) );
		}

		add_filter( 'the_resume_description', 'wptexturize'        );
		add_filter( 'the_resume_description', 'convert_smilies'    );
		add_filter( 'the_resume_description', 'convert_chars'      );
		add_filter( 'the_resume_description', 'wpautop'            );
		add_filter( 'the_resume_description', 'shortcode_unautop'  );
		add_filter( 'the_resume_description', 'prepend_attachment' );

		add_action( 'resume_manager_contact_details', array( $this, 'contact_details_email' ) );

		add_action( 'pending_to_publish', array( $this, 'setup_autohide_cron' ) );
		add_action( 'preview_to_publish', array( $this, 'setup_autohide_cron' ) );
		add_action( 'draft_to_publish', array( $this, 'setup_autohide_cron' ) );
		add_action( 'auto-draft_to_publish', array( $this, 'setup_autohide_cron' ) );
		add_action( 'hidden_to_publish', array( $this, 'setup_autohide_cron' ) );
		add_action( 'expired_to_publish', array( $this, 'setup_autohide_cron' ) );
		add_action( 'save_post', array( $this, 'setup_autohide_cron' ) );
		add_action( 'auto-hide-resume', array( $this, 'hide_resume' ) );

		add_action( 'update_post_meta', array( $this, 'maybe_update_menu_order' ), 10, 4 );
		add_filter( 'wp_insert_post_data', array( $this, 'fix_post_name' ), 10, 2 );
		add_action( 'pending_payment_to_publish', array( $this, 'set_expiry' ) );
		add_action( 'pending_to_publish', array( $this, 'set_expiry' ) );
		add_action( 'preview_to_publish', array( $this, 'set_expiry' ) );
		add_action( 'draft_to_publish', array( $this, 'set_expiry' ) );
		add_action( 'auto-draft_to_publish', array( $this, 'set_expiry' ) );
		add_action( 'expired_to_publish', array( $this, 'set_expiry' ) );
		add_action( 'resume_manager_check_for_expired_resumes', array( $this, 'check_for_expired_resumes' ) );

		add_action( 'save_post', array( $this, 'flush_get_resume_listings_cache' ) );
		add_action( 'delete_post', array( $this, 'flush_get_resume_listings_cache' ) );
		add_action( 'trash_post', array( $this, 'flush_get_resume_listings_cache' ) );

		add_action( 'resume_manager_my_resume_do_action', array( $this, 'resume_manager_my_resume_do_action' ) );
    }
    
    /**
	 * register_post_types function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_post_types() {

		if ( post_type_exists( "employer" ) )
			return;

		$admin_capability = 'manage_employers';

	    /**
		 * Post types
		 */
		$singular  = __( 'Employer', 'wp-job-manager-employers' );
		$plural    = __( 'Employers', 'wp-job-manager-employers' );

		// if ( current_theme_supports( 'resume-manager-templates' ) ) {
		// 	$has_archive = _x( 'resumes', 'Post type archive slug - resave permalinks after changing this', 'wp-job-manager-employers' );
		// } else {
		// 	$has_archive = false;
		// }

		$rewrite     = array(
			'slug'       => _x( 'employer', 'Employer permalink - resave permalinks after changing this', 'wp-job-manager-employers' ),
			'with_front' => false,
			'feeds'      => false,
			'pages'      => false
		);

		register_post_type( "employer",
			apply_filters( "register_post_type_employer", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $plural,
					'all_items'             => sprintf( __( 'All %s', 'wp-job-manager-employers' ), $plural ),
					'add_new' 				=> __( 'Add New', 'wp-job-manager-employers' ),
					'add_new_item' 			=> sprintf( __( 'Add %s', 'wp-job-manager-employers' ), $singular ),
					'edit' 					=> __( 'Edit', 'wp-job-manager-employers' ),
					'edit_item' 			=> sprintf( __( 'Edit %s', 'wp-job-manager-employers' ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', 'wp-job-manager-employers' ), $singular ),
					'view' 					=> sprintf( __( 'View %s', 'wp-job-manager-employers' ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', 'wp-job-manager-employers' ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', 'wp-job-manager-employers' ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', 'wp-job-manager-employers' ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'wp-job-manager-employers' ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', 'wp-job-manager-employers' ), $singular )
				),
				'description' => __( 'This is where you can create and manage employers.', 'wp-job-manager-employers' ),
				'public' 				=> true,
				// Hide the UI when the plugin is secretly disabled because WPJM core isn't activated.
				'show_ui' 				=> class_exists( 'WP_Job_Manager' ),
				'capability_type' 		=> 'post',
				'capabilities' => array(
					'publish_posts' 		=> $admin_capability,
					'edit_posts' 			=> $admin_capability,
					'edit_others_posts' 	=> $admin_capability,
					'delete_posts' 			=> $admin_capability,
					'delete_others_posts'	=> $admin_capability,
					'read_private_posts'	=> $admin_capability,
					'edit_post' 			=> $admin_capability,
					'delete_post' 			=> $admin_capability,
					'read_post' 			=> $admin_capability
				),
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> true,
				'hierarchical' 			=> false,
				'rewrite' 				=> $rewrite,
				'query_var' 			=> true,
				'supports' 				=> array( 'title', 'editor', 'custom-fields' ),
				'has_archive' 			=> $has_archive,
				'show_in_nav_menus' 	=> false,
				'delete_with_user'		=> true,
			) )
		);

		register_post_status( 'hidden', array(
			'label'                     => _x( 'Hidden', 'post status', 'wp-job-manager-employers' ),
			'public'                    => true,
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Hidden <span class="count">(%s)</span>', 'Hidden <span class="count">(%s)</span>', 'wp-job-manager-employers' ),
		) );
	}

}