<?php

/**
 * Class to process the settings for a form
 */
class Create_Settings{
	
	/**
	 * Option name to define storing Wordpress
	 */
	private $option_name = FALSE;
	
	/**
	 * Defines the sections
	 */
	private $sections = FALSE;
	
	/**
	 * Defines the settings
	 */
	private $settings = FALSE;
	
	/**
	 * Defines the page slug
	 */
	private $page_slug = FALSE;
	
	/**
	 * Defines the class to validate the settings
	 */
	private $validate_settings = FALSE;
	
	/**
	 * Constructor to create the settings record
	 * 
	 * @param $option_name - The option name for Wordpress records
	 * @param $sections - The different sections on the form
	 * @param $settings - All the settings for the form
	 * @param $page_slug - The current page slug the settings are adding
	 */
	public function __construct($option_name = NULL, $sections = NULL, $settings = NULL, $page_slug = __FILE__){
		
		$this->option_name = $option_name;
		$this->sections = $sections;
		$this->settings = $settings;
		$this->page_slug = $page_slug;	
		
		require_once 'validate-settings.php';
		
		$this->validate_settings = new Validate_Settings($this->option_name, $this->settings);
		
		// Register Settings
		add_action( 'admin_init', array( &$this, 'Register_Settings' ) );
	}
	
	/**
	 * Create the default setting
	 * @args - The default settings
	 */
	public function Create_Setting( $args = array() ) {
		
		$defaults = array(
			'id'      => 'default_field',
			'name'    => '',
			'title'   => __( 'Default Field' ),
			'desc'    => __( 'This is a default description.' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'plugin-settings',
			'choices' => array(),
			'class'   => ''
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'name'      => $name,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);
		
		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;
		
		// Add the settings field
		add_settings_field( $id, $title, array( $this, 'display_setting' ), $this->page_slug, $section, $field_args );
	}
	
	/**
	 * Display the setting on the page
	 * 
	 * @param $args - The default settings
	 */
	public function Display_Setting($args = array()){
		require_once 'display-setting.php';
		
		$display_setting = new Display_Setting( $this->option_name, $args );
	}
		
	/**
	 * Display output message to the user from submitting the form
	 * 
	 * @param $message - Text to message
	 * @param $msgclass - The class on the message
	 */
	public function Paulund_Show_Msg($message, $msgclass = 'info') {  
	    echo "<div id='message' class='$msgclass'>$message</div>";  
	}
	
	/** 
	 * Callback function for displaying admin messages 
	 * 
	 * @return calls wptuts_show_msg() 
	 */  
	public function Paulund_Admin_Msgs() {  
	   
	    // collect setting errors/notices: //http://codex.wordpress.org/Function_Reference/get_settings_errors  
	    $set_errors = get_settings_errors();   
	  
	    //display admin mes<e admin to see, only on our settings page and only when setting errors/notices are returned!  
	    if(current_user_can ('administrator') && !empty($set_errors)){  
	        // have our settings succesfully been updated?  
	        if($set_errors[0]['code'] == 'settings_updated' && isset($_GET['settings-updated'])){  
	            $this->Paulund_Show_Msg("<p>" . $set_errors[0]['message'] . "</p>", 'updated');  
	  
	        // have errors been found?  
	        }else{  
	            // there maybe more than one so run a foreach loop.  
	            foreach($set_errors as $set_error){  
	                // set the title attribute to match the error "setting title" - need this in js file  
	                $this->Paulund_Show_Msg("<p class='setting-error-message' title='" . $set_error['setting'] . "'>" . $set_error['message'] . "</p>", 'error');  
	            }  
	        }  
	    }  
	}

	/**
	 * Register the settings to the Wordpress Settings API
	 * Adds all the sections to the settings API
	 */
	public function Register_Settings() {
		
		// Register the settings with Validation callbacl
		register_setting( $this->option_name, $this->option_name, array ( &$this, 'Validate_Settings' ) );
				
		// Register the different sections on the page with the function to display the data		
		if(!empty($this->sections)){
			foreach ( $this->sections as $slug => $title ) {
				add_settings_section( $slug, '', array( &$this, "Display_Section"), $this->page_slug );
			}	
		}			
		
		//fields  
	    if(!empty($this->settings)){    
	        foreach ($this->settings as $option) {  
	            $this->Create_Setting($option);  
	        }  
	    }	
	}

	/**
	 * Default callback function to use on displaying a section
	 * 
	 * @param $section - Data of the section
	 */
	public function Display_Section($section){ 
	}

	/**
	 * Validate the settings will pass through the current input item
	 * 
	 * @param $input - The current input item
	 * 
	 * @return Returns the validated input
	 */
	public function Validate_Settings($input){
		return $this->validate_settings->Validate_Settings($input);
	}
}

?>