<?php
/**
 * example-wp-plugin-urls.php
 *
 * Copyright (c) 2024 Joseph Francis / hookedup, inc.
 * @author Joseph Francis
 *
 * Plugin Name: Example Plugin URLs
 * Plugin URI: https://github.com/hookedupjoe/example-wp-plugin-urls
 * Description: A simple exmaple of adding url endpoints in a plugin
 * Author: Joseph Francis
 * License: MIT
 * 
 * Version: 1.0.1
 */

class ExamplePluginUrlModule {
	/**
	 * The name of the entrypoint to create in this process.
	 * 
	 * NOTE: Change the entrypoint from 'forexample'
	 */
	protected static $postname = 'forexample';

	
	/**
	* Create posts needed to hook in page functionality
	*
    * NOTE: Change the list of pages you expect and also
	*       update the pagerouter.php
	*/
	public static function plugin_initialize() {
		$post_type = self::$postname;

		$slug = 'welcome';
        $title = 'Welcome';
        $content = 'Endpoint Stub';
		self::assure_doc($slug, $post_type, $title, $content);
		
		$slug = 'dashboard';
        $title = 'Dashboard';
		$content = 'Endpoint Stub';
		self::assure_doc($slug, $post_type, $title, $content);

	}

	
	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new ExamplePluginUrlModule();
		}

		return self::$instance;

	}

	protected static function assure_doc($slug, $post_type, $title, $content){
		$author_id = 1;
		if( !self::post_exists_by_slug( $slug, $post_type ) ) {
            // Set the post ID
            wp_insert_post(
                array(
                    'comment_status'    =>   'closed',
                    'ping_status'       =>   'closed',
                    'post_author'       =>   $author_id,
                    'post_name'         =>   $slug,
                    'post_title'        =>   $title,
                    'post_content'      =>  $content,
                    'post_status'       =>   'publish',
                    'post_type'         =>   $post_type
                )
			);
		}
	}

	
	 /**
	 * post_exists_by_slug.
	 *
	 * @return mixed boolean false if no post exists; post ID otherwise.
	 */
	protected static function post_exists_by_slug( $post_slug, $post_type = 'post') {
		$args_posts = array(
			'post_type'      => $post_type,
			'post_status'    => 'any',
			'name'           => $post_slug,
			'posts_per_page' => 1,
		);
		$loop_posts = new WP_Query( $args_posts );
		if ( ! $loop_posts->have_posts() ) {
			return false;
		} else {
			$loop_posts->the_post();
			return $loop_posts->post->ID;
		}
	}
	
	/**
	 * Runs when plugin is activated
	 */
	public static function activation_hook() {
		self::init();
		flush_rewrite_rules(); 
		self::plugin_initialize();
	}
	/**
	 * Init it
	 */
	public static function init() {
		self::custom_post_init();
	}

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {

		// Override the template for our own custom post types
		add_filter(
			'template_include',
			array( $this, 'override_tpl')
		);
		
	}

	public function override_tpl($template){
		$post_types = array( self::$postname );

		if ( is_singular( $post_types ) && file_exists( plugin_dir_path(__FILE__) . 'tpl/pagerouter.php' ) ){
			$template = plugin_dir_path(__FILE__) . 'tpl/pagerouter.php';
			return $template;
		}

		return $template;
	}

	public static function admin_menu() {
		//--- Remove this hidden post type from the admin list
		remove_menu_page( 'edit.php?post_type=' . self::$postname );
	}

	private static function custom_post_init() {

		$labels = array(
		'name'               => __( 'HiddenPosts' ),
		'singular_name'      => __( 'HiddenPost' ),
		'add_new'            => __( 'Add New HiddenPost' ),
		'add_new_item'       => __( 'Add New HiddenPost' ),
		'edit_item'          => __( 'Edit HiddenPost' ),
		'new_item'           => __( 'New HiddenPost' ),
		'all_items'          => __( 'All HiddenPosts' ),
		'view_item'          => __( 'View HiddenPost' ),
		'search_items'       => __( 'Search HiddenPost' ),
		'featured_image'     => 'Poster',
		'set_featured_image' => 'Add Poster'
		);

		$args = array(
		'labels'            => $labels,
		'description'       => 'Creates a landing page for plugin functionality',
		'public'            => true,
		'menu_position'     => 5,
		'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
		'has_archive'       => false,
		'show_in_admin_bar' => false,
		'show_in_nav_menus' => false,
		'query_var'         => true,
		);

		register_post_type( self::$postname, $args);

	}

}

register_activation_hook( __FILE__, array( 'ExamplePluginUrlModule', 'activation_hook' ) );

add_action( 'plugins_loaded', array( 'ExamplePluginUrlModule', 'get_instance' ) );
add_action( 'init', array( 'ExamplePluginUrlModule', 'init' ) );
add_action( 'admin_menu', array( 'ExamplePluginUrlModule', 'admin_menu' ));