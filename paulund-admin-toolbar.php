<?php
/**
 * The admin area for the Paulund Toolbar plugin
 * This will create the admin page add the settings and display the form to customise your toolbar
 */
class Paulund_Admin_Toolbar{
	
	/**
	 * Plugin Option name
	 */
	private $option_name = "paulund_toolbar";
	
	/**
	 * Sections for the admin screen
	 */
	private $sections = array();
	
	/**
	 * Settings for the admin screen
	 */
	private $settings = array();
	
	/**
	 * Get settings object
	 */
	private $settingsobj = FALSE;
	
	/**
	 * Constructor
	 * Define the sections
	 * Get the settings
	 * Create the settings object, pass through all the settings to add to the page
	 * Start plugin scripts
	 */
	public function __construct(){
		// Define the sections					
		$this->sections['remove-default']   = __( 'Remove Default' );
		$this->sections['content-section']	= __( 'Content' );
		$this->sections['wordpress-menu']   = __( 'Wordpress Menu' );
		
		// Get all the settings
		$this->Get_Settings();
		
		// Construct the settings and display the settings
		require_once 'settings/create-settings.php';
		$this->settingsobj = new Create_Settings($this->option_name, $this->sections, $this->settings, 'take-control-of-the-wordpress-toolbar' );
		
		if( isset($_GET["page"]) && $_GET["page"] == "take-control-of-the-wordpress-toolbar" ){
			add_action( 'admin_init', array( &$this, "Plugin_Settings_Scripts") );	
		}
		
	}
	
	/**
	 * Add the custom CSS and JS to the page
	 */
	public function Plugin_Settings_Scripts(){				  
	    wp_register_style( 'plugin_settings_css', plugins_url( '/paulund-toolbar/css/style.css' ));
		wp_register_style( 'font_settings', 'http://fonts.googleapis.com/css?family=Droid+Sans|Ubuntu' );
		
		wp_enqueue_style( 'plugin_settings_css' );
		wp_enqueue_style( 'font_settings' );
	      
	    //wp_enqueue_script( 'plugin_settings_js', '');
	}
	
	/**
	 * Define all the settings on the page
	 */
	private function Get_Settings(){
		$this->settings[] = array(
			'type'		=> 'h2',
			'title'		=> '',
			'desc'   	=> __( 'Remove Default' ),
			'section' 	=> 'remove-default'
		);
				
		$this->settings[] = array(
			'id'      => 'remove_wp_logo',
			'name'	  => 'remove_wp_logo',
			'title'   => __( 'Remove Wordpress Logo' ),
			'desc'    => __( 'Remove the Wordpress Logo link group' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'remove-default'
		);
		
		$this->settings[] = array(
			'id'      => 'about_wordpress',
			'name'	  => 'about_wordpress',
			'title'   => __( 'About Wordpress Link' ),
			'desc'    => __( 'Remove the about Wordpress link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'remove-default'
		);
		
		$this->settings[] = array(
			'id'      => 'wordpress_org',
			'name'	  => 'wordpress_org',
			'title'   => __( 'Wordpress.org Link' ),
			'desc'    => __( 'Remove the Wordpress.org link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'remove-default'
		);
		
		$this->settings[] = array(
			'id'      => 'wordpress_documentation',
			'name'	  => 'wordpress_documentation',
			'title'   => __( 'Wordpress Documentation Link' ),
			'desc'    => __( 'Remove the Wordpress Documentation link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'remove-default'
		);
		
		$this->settings[] = array(
			'id'      => 'support_forums',
			'name'	  => 'support_forums',
			'title'   => __( 'Wordpress Support Forums Link' ),
			'desc'    => __( 'Remove the Wordpress Support Forums link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'remove-default'
		);
		
		$this->settings[] = array(
			'id'      => 'feedback',
			'name'	  => 'feedback',
			'title'   => __( 'Wordpress Feedback Link' ),
			'desc'    => __( 'Remove the Wordpress Feedback link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'remove-default'
		);
		
		$this->settings[] = array(
			'id'      => 'site_name',
			'name'	  => 'site_name',
			'title'   => __( 'Site Name Link' ),
			'desc'    => __( 'Remove the Wordpress Site Name link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'remove-default'
		);
		
		$this->settings[] = array(
			'id'      => 'view_site',
			'name'	  => 'view_site',
			'title'   => __( 'View Site Link' ),
			'desc'    => __( 'Remove the View Site link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'remove-default'
		);
		
		$this->settings[] = array(
			'type'		=> 'h2',
			'title'   	=> '',
			'desc'		=>__( 'Wordpress Content' ),
			'section' 	=> 'content-section'
		);
		
		$this->settings[] = array(
			'id'      => 'updates',
			'name'	  => 'updates',
			'title'   => __( 'Updates Link' ),
			'desc'    => __( 'Remove the Updates link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'content-section'
		);
		
		$this->settings[] = array(
			'id'      => 'comments',
			'name'	  => 'comments',
			'title'   => __( 'Comments Link' ),
			'desc'    => __( 'Remove the Comments link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'content-section'
		);
		
		$this->settings[] = array(
			'id'      => 'new_content',
			'name'	  => 'new_content',
			'title'   => __( 'New Content Link' ),
			'desc'    => __( 'Remove the New Content link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'content-section'
		);
		
		$this->settings[] = array(
			'id'      => 'my_account',
			'name'	  => 'my_account',
			'title'   => __( 'Wordpress My Account Link' ),
			'desc'    => __( 'Remove the Wordpress My Account link' ),
			'std'     => '1',
			'type'    => 'checkbox',
			'class'   => 'checkbox',
			'section' => 'content-section'
		);
		
		$this->settings[] = array(
			'id'      => 'howdy_text',
			'name'	  => 'howdy_text',
			'title'   => __( 'Change Howdy text' ),
			'desc'    => __( 'Change the default howdy text' ),
			'type'    => 'text',
			'class'   => 'text',
			'section' => 'content-section'
		);
		
		$this->settings[] = array(
			'type'		=> 'h2',
			'title'   	=> '',
			'desc'		=>__( 'Wordpress Menu' ),
			'section' 	=> 'wordpress-menu',
			'meta'		=> FALSE
		);
		
		// Get all the Wordpress navigation menus
		$all_menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		
		$nav_menu = array();
		$nav_menu[''] = "Select Menu";
		
		foreach($all_menus as $menus){
			$nav_menu[$menus->name] = $menus->name;	
		}
		
		$this->settings[] = array(
			'id'      => 'wordpress_menu',
			'name'	  => 'wordpress_menu',
			'title'   => __( 'Wordpress menu' ),
			'desc'    => __( 'Which Wordpress Menu' ),
			'std'     => 'Which menu',
			'type'    => 'select',
			'choices' => $nav_menu,
			'class'   => 'text',
			'section' => 'wordpress-menu'
		);
	}
	
	/**
	 * The display function to add the settings to the admin area
	 */
	public function Display(){
		?>
		<div class="section panel">
			<h1>Take Control Of The Wordpress Toolbar</h1>
			<form method="post" enctype="multipart/form-data" action="options.php">
				<?php 
					settings_fields($this->option_name); 
				
					do_settings_sections('take-control-of-the-wordpress-toolbar');
				?>
            <p class="submit">  
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  
            </p>	
            
			</form>
			
			<p>Created by <a href="http://www.paulund.co.uk">Paulund</a>.</p>
		</div>
		<?php
	}
}

?>