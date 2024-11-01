<?php
/**
 * Class to display the different settings on the theme options page
 * 
 * @author Paul Underwood http://www.paulund.co.uk
 */
class Display_Setting{
	
	/**
	 * Option name to define storing Wordpress
	 */
	private $option_name = FALSE;
	
	/**
	 * Class arguements
	 */
	private $args = array();
	
	/**
	 * Contructor needs the theme option name and the args needed to display
	 * 
	 * @param $option_name - The option name from the wordpress DB
	 * @param $args - The class arguements 
	 */
	public function __construct($option_name, $args){
		$this->option_name = $option_name;
		$this->args = $args;
		
		$this->Display_Option();
	}
	
	/**
	 * Display the option on the page
	 */
	private function Display_Option(){
		extract( $this->args );
		
		$options = get_option( $this->option_name );
		
		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
		
		// switch html display based on the setting type.  
	    switch ( $type ) {  
	        case 'text':  
	            $options[$id] = stripslashes($options[$id]);  
	            $options[$id] = esc_attr( $options[$id]);  
	            echo "<input class='regular-text$field_class' type='text' id='$id' name='" . $this->option_name . "[$id]' value='$options[$id]' />";  
	            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
	        break;  
	  
	        case "multi-text":  
	            foreach($choices as $item) {  
	                $item = explode("|",$item); // cat_name|cat_slug  
	                $item[0] = esc_html__($item[0], 'wptuts_textdomain');  
	                if (!emptyempty($options[$id])) {  
	                    foreach ($options[$id] as $option_key => $option_val){  
	                        if ($item[1] == $option_key) {  
	                            $value = $option_val;  
	                        }  
	                    }  
	                } else {  
	                    $value = '';  
	                }  
	                echo "<span>$item[0]:</span> <input class='$field_class' type='text' id='$id|$item[1]' name='" . $this->option_name . "[$id|$item[1]]' value='$value' /><br/>";  
	            }  
	            echo ($desc != '') ? "<span class='description'>$desc</span>" : "";  
	        break;  
	  
	        case 'textarea':  
	            $options[$id] = stripslashes($options[$id]);  
	            $options[$id] = esc_html( $options[$id]);  
	            echo "<textarea class='textarea$field_class' type='text' id='$id' name='" . $this->option_name . "[$id]' rows='5' cols='30'>$options[$id]</textarea>";  
	            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
	        break;  
	  
	        case 'select':  
	            echo "<select id='$id' class='select$field_class' name='" . $this->option_name . "[$id]'>";  
	                foreach($choices as $k => $v) {  
	                    $value  = esc_attr($k, 'wptuts_textdomain');  
	                    $item   = esc_html($v, 'wptuts_textdomain');  
	  
	                    $selected = ($options[$id]==$value) ? 'selected="selected"' : '';  
	                    echo "<option value='$value' $selected>$item</option>";  
	                }  
	            echo "</select>";  
	            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
	        break;  
	  
	        case 'select2':  
	            echo "<select id='$id' class='select$field_class' name='" . $this->option_name . "[$id]'>";  
	            foreach($choices as $item) {  
	  
	                $item = explode("|",$item);  
	                $item[0] = esc_html($item[0], 'wptuts_textdomain');  
	  
	                $selected = ($options[$id]==$item[1]) ? 'selected="selected"' : '';  
	                echo "<option value='$item[1]' $selected>$item[0]</option>";  
	            }  
	            echo "</select>";  
	            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
	        break;  
	  
	        case 'checkbox':  
	            echo "<input class='checkbox$field_class' type='checkbox' id='$id' name='" . $this->option_name . "[$id]' value='1' " . checked( $options[$id], 1, false ) . " />";  
	            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
	        break;  
	  
	        case "multi-checkbox":  
	            foreach($choices as $item) {  
	  
	                $item = explode("|",$item);  
	                $item[0] = esc_html($item[0], 'wptuts_textdomain');  
	  
	                $checked = '';  
	  
	                if ( isset($options[$id][$item[1]]) ) {  
	                    if ( $options[$id][$item[1]] == 'true') {  
	                        $checked = 'checked="checked"';  
	                    }  
	                }  
	  
	                echo "<input class='checkbox$field_class' type='checkbox' id='$id|$item[1]' name='" . $this->option_name . "[$id|$item[1]]' value='1' $checked /> $item[0] <br/>";  
	            }  
	            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
	        break;  
	        
			case "file":
				$options[$id] = stripslashes($options[$id]);  
	            $options[$id] = esc_attr( $options[$id]); 
				if($options[$id] != ""){
					echo "<img src='$options[$id]'/><br/>";	
				}
				
	            echo "<input class='regular-file$field_class' type='file' id='$id' name='$id' value='$options[$id]' />
	            <input class='regular-file$field_class' type='hidden' id='hidden_$id' name='hidden_$id' value='$options[$id]' />";  
	            echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : ""; 	
			break;
			
			case "h1":
			case "h2":
			case "h3":
			case "h4":
			case "h5":
			case "h6":
				if($class != ''){
					$class = ' class="'.$class.'"';
				}
				if($id != ''){
					$id = ' id="'.$id.'"';
				}
				
				echo "<".$type.$field_class.$id.">".$desc."</".$type.">";
			break; 
	    }
	}
}
?>