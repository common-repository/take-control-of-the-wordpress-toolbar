<?php
/*
Plugin Name: Take Control Of The Wordpress Toolbar
Plugin URI: http://www.paulund.co.uk
Description: Gives you full control over what is placed in your Wordpress toolbar.
Author: Paul Underwood
Version: 1.4
Stable tag: 1.4
Author URI: http://www.paulund.co.uk/
*/

// Create the Paulund toolbar
$toolbar = new Paulund_Toolbar();

// If on the admin page run the admin method
if( is_admin() ){
	$toolbar->Admin();
}

// If displaying the admin bar then change the toolbar
$toolbar->Change_Toolbar();

/**
 * The Paulund toolbar class to customise the toolbars
 */
class Paulund_Toolbar{

	/**
	 * Define the admin property to store the admin class
	 */
	private $admin = false;

	/**
	 * Create the admin area
	 */
	public function Admin(){
		require_once 'paulund-admin-toolbar.php';

		$this->admin = new Paulund_Admin_Toolbar();

		add_action( 'admin_menu', array(&$this,'Admin_Menu') );
	}

	/**
	 * Function for the admin menu to create a menu item in the settings tree
	 */
	public function Admin_Menu(){
		add_submenu_page(
			'options-general.php',
			'Take Control Of The Wordpress Toolbar',
			'Control Wordpress Toolbar',
			'manage_options',
			'take-control-of-the-wordpress-toolbar',
			array(&$this,'Display_Admin_Page'));
	}

	/**
	 * Display the admin page
	 */
	public function Display_Admin_Page(){
		$this->admin->Display();
	}

	/**
	 * On the wp before admin bar render action run the remove admin bar links function
	 */
	public function Change_Toolbar(){
		add_action( 'wp_before_admin_bar_render', array(&$this,'Remove_Admin_Bar_Links') );
	}

	/**
	 * Gets the values from the database and updates the toolbar with the settings from the admin page
	 */
	public function Remove_Admin_Bar_Links(){
		if (!is_admin_bar_showing() ){
			return;
		}

		global $wp_admin_bar;

		$options = get_option( "paulund_toolbar" );

		// Remove the WordPress logo
		if( $options['remove_wp_logo'] == 1){
			$wp_admin_bar->remove_menu('wp-logo');
		}

		// Remove the about WordPress link
		if( $options['about_wordpress'] == 1){
			$wp_admin_bar->remove_menu('about');
		}

		// Remove the WordPress.org link
		if( $options['wordpress_org'] == 1){
			$wp_admin_bar->remove_menu('wporg');
		}

		// Remove the WordPress documentation link
		if( $options['wordpress_documentation'] == 1){
			$wp_admin_bar->remove_menu('documentation');
		}

		// Remove the support forums link
		if( $options['support_forums'] == 1){
			$wp_admin_bar->remove_menu('support-forums');
		}

		// Remove the feedback link
		if( $options['feedback'] == 1){
			$wp_admin_bar->remove_menu('feedback');
		}

		// Remove the site name menu
		if( $options['site_name'] == 1){
			$wp_admin_bar->remove_menu('site-name');
		}

		// Remove the view site link
		if( $options['view_site'] == 1){
			$wp_admin_bar->remove_menu('view-site');
		}

		// Remove the updates link
		if( $options['updates'] == 1){
			$wp_admin_bar->remove_menu('updates');
		}

		// Remove the comments link
		if( $options['comments'] == 1){
			$wp_admin_bar->remove_menu('comments');
		}

		// Remove the content link
		if( $options['new_content'] == 1){
			$wp_admin_bar->remove_menu('new-content');
		}

		// Remove the user details tab
		if( $options['my_account'] == 1){
			$wp_admin_bar->remove_menu('my-account');
		} else {
			$my_account = $wp_admin_bar->get_node('my-account');

			$newtitle = str_replace( 'Howdy,', $options['howdy_text'], $my_account->title );

			$wp_admin_bar->add_node( array(
		        'id' => 'my-account',
		        'title' => $newtitle,
		        'meta' 	=> 	array()
		    ) );
		}

		if( $options['wordpress_menu'] != '' ){
			$menu = wp_get_nav_menu_object( $options['wordpress_menu'] );
			$menu_items = wp_get_nav_menu_items( $menu->term_id );

			foreach ($menu_items as $items) {
				$args = array( 'id' => 	$items->ID,
					'title' => 	$items->title,
					'parent' => $items->menu_item_parent,
					'href' 	=> 	$items->url,
					'meta' 	=> 	array( 'target' => $items->target )
				);

				$wp_admin_bar->add_node( $args );
			}
		}

	}
}
?>