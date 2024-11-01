<?php
/**
 * Class to validate the setting from the user input
 * 
 * @author Paul Underwood http://www.paulund.co.uk
 */
class Validate_Settings{
		
	/**
	 * Option name to define storing Wordpress
	 */
	private $option_name = FALSE;
	
	/**
	 * The settings to validate
	 */
	private $settings = FALSE;
	
	/**
	 * Constructor needs parameter of all the settings to validate
	 * 
	 * @param $option_name - The option name stored in the DB
	 * @param $settings - The settings to validate
	 */
	public function __construct( $option_name, $settings ){
		$this->option_name = $option_name;
		$this->settings = $settings;
	}
	
	/**
	 * Validate the input
	 * 
	 * @param Input from field
	 * 
	 * @return Valid field
	 */
    public function Validate_Settings($input){
    	// for enhanced security, create a new empty array  
   	 	$valid_input = array();  
  
        // run a foreach and switch on option type  
        foreach ($this->settings as $option) {  
  
            switch ( $option['type'] ) {  
                case 'text':  
                    //switch validation based on the class!  
                    switch ( $option['class'] ) {  
                        //for numeric  
                        case 'numeric':  
                            //accept the input only when numeric!  
                            $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace  
                            $valid_input[$option['id']] = (is_numeric($input[$option['id']])) ? $input[$option['id']] : 'Expecting a Numeric value!';  
  
                            // register error  
                            if(is_numeric($input[$option['id']]) == FALSE) {  
                                add_settings_error(  
                                    $option['id'], // setting title  
                                    'txt_numeric_error', // error ID  
                                    'Expecting a Numeric value! Please fix.', // error message  
                                    'error' // type of message  
                                );  
                            }  
                        break;  
  
                        //for multi-numeric values (separated by a comma)  
                        case 'multinumeric':  
                            //accept the input only when the numeric values are comma separated  
                            $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace  
  
                            if($input[$option['id']] !=''){  
                                // /^-?\d+(?:,\s?-?\d+)*$/ matches: -1 | 1 | -12,-23 | 12,23 | -123, -234 | 123, 234  | etc.  
                                $valid_input[$option['id']] = (preg_match('/^-?\d+(?:,\s?-?\d+)*$/', $input[$option['id']]) == 1) ? $input[$option['id']] : __('Expecting comma separated numeric values','wptuts_textdomain');  
                            }else{  
                                $valid_input[$option['id']] = $input[$option['id']];  
                            }  
  
                            // register error  
                            if($input[$option['id']] !='' && preg_match('/^-?\d+(?:,\s?-?\d+)*$/', $input[$option['id']]) != 1) {  
                                add_settings_error(  
                                    $option['id'], // setting title  
                                    'txt_multinumeric_error', // error ID  
                                    'Expecting comma separated numeric values! Please fix.', // error message  
                                    'error' // type of message  
                                );  
                            }  
                        break;  
  
                        //for no html  
                        case 'nohtml':  
                            //accept the input only after stripping out all html, extra white space etc!  
                            $input[$option['id']]       = sanitize_text_field($input[$option['id']]); // need to add slashes still before sending to the database  
                            $valid_input[$option['id']] = addslashes($input[$option['id']]);  
                        break;  
  
                        //for url  
                        case 'url':  
                            //accept the input only when the url has been sanited for database usage with esc_url_raw()  
                            $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace  
                            $valid_input[$option['id']] = esc_url_raw($input[$option['id']]);  
                        break;  
  
                        //for email  
                        case 'email':  
                            //accept the input only after the email has been validated  
                            $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace  
                            if($input[$option['id']] != ''){  
                                $valid_input[$option['id']] = (is_email($input[$option['id']])!== FALSE) ? $input[$option['id']] : __('Invalid email! Please re-enter!','wptuts_textdomain');  
                            }elseif($input[$option['id']] == ''){  
                                $valid_input[$option['id']] = __('This setting field cannot be empty! Please enter a valid email address.','wptuts_textdomain');  
                            }  
  
                            // register error  
                            if(is_email($input[$option['id']])== FALSE || $input[$option['id']] == '') {  
                                add_settings_error(  
                                    $option['id'], // setting title  
                                    'txt_email_error', // error ID  
                                    'Please enter a valid email address.', // error message  
                                    'error' // type of message  
                                );  
                            }  
                        break;  
  
                        // a "cover-all" fall-back when the class argument is not set  
                        default:  
                            // accept only a few inline html elements  
                            $allowed_html = array(  
                                'a' => array('href' => array (),'title' => array ()),  
                                'b' => array(),  
                                'em' => array (),  
                                'i' => array (),  
                                'strong' => array()  
                            );  
  
                            $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace  
                            $input[$option['id']]       = force_balance_tags($input[$option['id']]); // find incorrectly nested or missing closing tags and fix markup  
                            $input[$option['id']]       = wp_kses( $input[$option['id']], $allowed_html); // need to add slashes still before sending to the database  
                            $valid_input[$option['id']] = addslashes($input[$option['id']]);  
                        break;  
                    }  
                break;  
  
                case "multi-text":  
                    // this will hold the text values as an array of 'key' => 'value'  
                    unset($textarray);  
  
                    $text_values = array();  
                    foreach ($option['choices'] as $k => $v ) {  
                        // explode the connective  
                        $pieces = explode("|", $v);  
  
                        $text_values[] = $pieces[1];  
                    }  
  
                    foreach ($text_values as $v ) {       
  
                        // Check that the option isn't empty  
                        if (!empty($input[$option['id'] . '|' . $v])) { 
                            // If it's not null, make sure it's sanitized, add it to an array 
                            switch ($option['class']) { 
                                // different sanitation actions based on the class create you own cases as you need them 
 
                                //for numeric input 
                                case 'numeric': 
                                    //accept the input only if is numberic! 
                                    $input[$option['id'] . '|' . $v]= trim($input[$option['id'] . '|' . $v]); // trim whitespace 
                                    $input[$option['id'] . '|' . $v]= (is_numeric($input[$option['id'] . '|' . $v])) ? $input[$option['id'] . '|' . $v] : ''; 
                                break; 
 
                                // a "cover-all" fall-back when the class argument is not set 
                                default: 
                                    // strip all html tags and white-space. 
                                    $input[$option['id'] . '|' . $v]= sanitize_text_field($input[$option['id'] . '|' . $v]); // need to add slashes still before sending to the database 
                                    $input[$option['id'] . '|' . $v]= addslashes($input[$option['id'] . '|' . $v]); 
                                break; 
                            } 
                            // pass the sanitized user input to our $textarray array 
                            $textarray[$v] = $input[$option['id'] . '|' . $v]; 
 
                        } else { 
                            $textarray[$v] = ''; 
                        } 
                    } 
                    // pass the non-empty $textarray to our $valid_input array 
                    if (!empty($textarray)) { 
                        $valid_input[$option['id']] = $textarray; 
                    } 
                break; 
 
                case 'textarea': 
                    //switch validation based on the class! 
                    switch ( $option['class'] ) { 
                        //for only inline html 
                        case 'inlinehtml': 
                            // accept only inline html 
                            $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace 
                            $input[$option['id']]       = force_balance_tags($input[$option['id']]); // find incorrectly nested or missing closing tags and fix markup 
                            $input[$option['id']]       = addslashes($input[$option['id']]); //wp_filter_kses expects content to be escaped! 
                            $valid_input[$option['id']] = wp_filter_kses($input[$option['id']]); //calls stripslashes then addslashes 
                        break; 
 
                        //for no html 
                        case 'nohtml': 
                            //accept the input only after stripping out all html, extra white space etc! 
                            $input[$option['id']]       = sanitize_text_field($input[$option['id']]); // need to add slashes still before sending to the database 
                            $valid_input[$option['id']] = addslashes($input[$option['id']]); 
                        break; 
 
                        //for allowlinebreaks 
                        case 'allowlinebreaks': 
                            //accept the input only after stripping out all html, extra white space etc! 
                            $input[$option['id']]       = wp_strip_all_tags($input[$option['id']]); // need to add slashes still before sending to the database 
                            $valid_input[$option['id']] = addslashes($input[$option['id']]); 
                        break; 
 
                        // a "cover-all" fall-back when the class argument is not set 
                        default: 
                            // accept only limited html 
                            //my allowed html 
                            $allowed_html = array( 
                                'a'             => array('href' => array (),'title' => array ()), 
                                'b'             => array(), 
                                'blockquote'    => array('cite' => array ()), 
                                'br'            => array(), 
                                'dd'            => array(), 
                                'dl'            => array(), 
                                'dt'            => array(), 
                                'em'            => array (), 
                                'i'             => array (), 
                                'li'            => array(), 
                                'ol'            => array(), 
                                'p'             => array(), 
                                'q'             => array('cite' => array ()), 
                                'strong'        => array(), 
                                'ul'            => array(), 
                                'h1'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()), 
                                'h2'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()), 
                                'h3'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()), 
                                'h4'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()), 
                                'h5'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()), 
                                'h6'            => array('align' => array (),'class' => array (),'id' => array (), 'style' => array ()) 
                            ); 
 
                            $input[$option['id']]       = trim($input[$option['id']]); // trim whitespace 
                            $input[$option['id']]       = force_balance_tags($input[$option['id']]); // find incorrectly nested or missing closing tags and fix markup 
                            $input[$option['id']]       = wp_kses( $input[$option['id']], $allowed_html); // need to add slashes still before sending to the database 
                            $valid_input[$option['id']] = addslashes($input[$option['id']]); 
                        break; 
                    } 
                break; 
 
                case 'select': 
                    // check to see if the selected value is in our approved array of values! 
                    $valid_input[$option['id']] = (in_array( $input[$option['id']], $option['choices']) ? $input[$option['id']] : '' ); 
                break; 
 
                case 'select2': 
                    // process $select_values 
                        $select_values = array(); 
                        foreach ($option['choices'] as $k => $v) { 
                            // explode the connective 
                            $pieces = explode("|", $v); 
 
                            $select_values[] = $pieces[1]; 
                        } 
                    // check to see if selected value is in our approved array of values! 
                    $valid_input[$option['id']] = (in_array( $input[$option['id']], $select_values) ? $input[$option['id']] : '' ); 
                break; 
 
                case 'checkbox': 
                    // if it's not set, default to null!  
                    if (!isset($input[$option['id']])) {  
                        $input[$option['id']] = null;  
                    }  
                    // Our checkbox value is either 0 or 1  
                    $valid_input[$option['id']] = ( $input[$option['id']] == 1 ? 1 : 0 );  
                break;  
  
                case 'multi-checkbox':  
                    unset($checkboxarray);  
                    $check_values = array();  
                    foreach ($option['choices'] as $k => $v ) {  
                        // explode the connective  
                        $pieces = explode("|", $v);  
  
                        $check_values[] = $pieces[1];  
                    }  
  
                    foreach ($check_values as $v ) {          
  
                        // Check that the option isn't null  
                        if (!empty($input[$option['id'] . '|' . $v])) { 
                            // If it's not null, make sure it's true, add it to an array 
                            $checkboxarray[$v] = 'true'; 
                        } 
                        else { 
                            $checkboxarray[$v] = 'false'; 
                        } 
                    } 
                    // Take all the items that were checked, and set them as the main option 
                    if (!empty($checkboxarray)) { 
                        $valid_input[$option['id']] = $checkboxarray;  
                    }  
                break;  
				
				case "file":
					$keys = array_keys($_FILES);
					$i = 0;
					
					foreach ($_FILES as $image) {
						// If file is uploaded
						if( $image['size'] && $keys[$i] == $option['name']){
							
							// Check if its an image
							if( preg_match("/{jpg|jpeg|png|gif}$/i", $image['type']) ){
								$override = array('test_form' => FALSE );
						
								$file = wp_handle_upload($image, $override);
								//print_r($file);
								
								
								$valid_input[$option['id']] = $file['url'];
								
							} else {
								add_settings_error(  
                                    $option['id'], // setting title  
                                    'file_image_error', // error ID  
                                    'Error in uploading image', // error message  
                                    'error' // type of message  
                                );
							}
						} else {
							$stored_option = get_option($this->option_name);
							
							if($stored_option[$option['id']] != ""){
								$valid_input[$option['id']] = $stored_option[$option['id']];
							}
						}
						
						$i++;
					}			
				break;
  
            }  
        }  

		return $valid_input; // return validated input
    }
}
?>