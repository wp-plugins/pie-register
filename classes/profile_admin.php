<?php
require_once('base.php');
class Profile_admin extends Base
{
    var $field;
    var $user_id;
    var $slug;
    var $type;
    var $name;
    var $id;
    var $data;
	
    function __construct()
    {
        $this->data = $this->getCurrentFields();
		$this->default_fields = FALSE;
    }
    function addTextField()
    {
        echo '<input id="' . $this->id . '" name="' . $this->slug . '" class="' . $this->field['css'] . '"  placeholder="' . $this->field['placeholder'] . '" type="text" value="' . get_usermeta($this->user_id, $this->slug) . '" />';
		
		
    }
    function addTextArea()
    {
        echo '<textarea id="' . $this->id . '" name="' . $this->slug . '" rows="' . $this->field['rows'] . '" cols="' . $this->field['cols'] . '"  class="' . $this->field['css'] . '"  placeholder="' . $this->field['placeholder'] . '">' . get_usermeta($this->user_id, $this->slug) . '</textarea>';
    }
    function addDropdown()
    {
        $sel_options = get_usermeta($this->user_id, $this->slug);
        $multiple    = "";
        if ($this->type == "multiselect") {
            $multiple = 'multiple';
            $this->slug .= "[]";
        }
        echo '<select ' . $multiple . ' id="' . $this->id . '" name="' . $this->slug . '" class="' . $this->field['css'] . '" >';
        if (sizeof($this->field['value']) > 0) {
            for ($a = 0; $a < sizeof($this->field['value']); $a++) {
                $selected = '';
                if (in_array($this->field['value'][$a], $sel_options)) {
                    $selected = 'selected="selected"';
                }
                if ($this->field['value'][$a] != "" && $this->field['display'][$a] != "")
                    echo '<option ' . $selected . ' value="' . $this->field['value'][$a] . '">' . $this->field['display'][$a] . '</option>';
            }
        }
        echo '</select>';
    }
    function addNumberField()
    {
        echo '<input id="' . $this->id . '" name="' . $this->slug . '" class="' . $this->field['css'] . '"  placeholder="' . $this->field['placeholder'] . '" min="' . $this->field['min'] . '" max="' . $this->field['max'] . '" type="number" value="' . get_usermeta($this->user_id, $this->slug) . '" />';
    }
    function addCheckRadio()
    {
        if (sizeof($this->field['value']) > 0) {
            $val = get_usermeta($this->user_id, $this->slug);
            for ($a = 0; $a < sizeof($this->field['value']); $a++) {
                $checked = '';
                if (is_array($val) && in_array($this->field['value'][$a], $val)) {
                    $checked = 'checked="checked"';
                }
                echo '<span style="margin-left:5px;">'.$this->field['display'][$a].'</span>';
                echo '<input style="margin-left:5px;" value="' . $this->field['value'][$a] . '" ' . $checked . ' type="' . $this->type . '" ' . $multiple . ' name="' . $this->slug . '[]" class="' . $this->field['css'] . '"  >';
            }
        }
    }
    function addHTML()
    {
        echo $this->field['html'];
    }
   
	function addUpload()
	{
		$val = get_usermeta($this->user_id, $this->slug);
				
		echo '<input name="' . $this->slug . '" type="text" value="'.$val .'">';
	}
   
    function addAddress()
    {
        $address = get_usermeta($this->user_id, $this->slug);
        echo '<div class="address">
		  <input type="text" name="' . $this->slug . '[address]" id="' . $this->id . '" value="' . $address['address'] . '" >
		  <label>Street Address</label>
		</div>';
        if (!$this->field['hide_address2']) {
            echo '<div class="address">
			  <input type="text" name="' . $this->slug . '[address2]" id="address2_' . $this->id . '" value="' . $address['address2'] . '" >
			  <label>Address Line 2</label>
			</div>';
        }
        echo '<div class="address">
		  <div class="address2">
			<input type="text" name="' . $this->slug . '[city]" id="city_' . $this->id . '" value="' . $address['city'] . '">
			<label>City</label>
		  </div>';
        if (!$this->field['hide_state']) {
            if ($this->field['address_type'] == "International") {
                echo '<div class="address2"  >
					<input type="text" name="' . $this->slug . '[state]" id="state_' . $this->id . '" value="' . $address['state'] . '">
					<label>State / Province / Region</label>
				 	 </div>';
            } else if ($this->field['address_type'] == "United States") {
                $us_states = get_option("pie_us_states");
                $options   = $this->createDropdown($us_states, $address['state']);
                echo '<div class="address2"  >
					<select id="state_' . $this->id . '" name="' . $this->slug . '[state]">
					 ' . $options . ' 
					</select>
					<label>State</label>
				  </div>';
            } else if ($this->field['address_type'] == "Canada") {
                $can_states = get_option("pie_can_states");
                $options    = $this->createDropdown($can_states, $address['state']);
                echo '<div class="address2">
						<select id="state_' . $this->id . '" name="' . $this->slug . '[state]">
						  ' . $options . '
						</select>
						<label>Province</label>
					  </div>';
            }
        }
        echo '</div>';
        echo '<div class="address">';
        echo ' <div class="address2">
		<input id="zip_' . $this->id . '" name="' . $this->slug . '[zip]" type="text" value="' . $address['zip'] . '" >
		<label>Zip / Postal Code</label>
		 </div>';
        if ($this->field['address_type'] == "International") {
            $countries = get_option("pie_countries");
            $options   = $this->createDropdown($countries, $address['country']);
            echo '<div  class="address2" >
					<select id="country_' . $this->id . '" name="' . $this->slug . '[country]" >
                    <option>Select Country</option>
					' . $options . '
					 </select>
					<label>Country</label>
		  		</div>';
        }
        echo '</div>';
    }
    function addPhone()
    {
        
		echo '<input id="' . $this->id . '"  name="' . $this->slug . '" class="input_fields"  placeholder="' . $field['placeholder'] . '" type="text" value="' . get_usermeta($this->user_id, $this->slug) . '" />';
    }
    function addTime()
    {
        $time = get_usermeta($this->user_id, $this->slug);
        echo '<input size="2" maxlength="2" id="hh_' . $this->id . '" name="' . $this->slug . '[hh]" type="text" value="' . $time['hh'] . '"> : <input size="2" maxlength="2" id="mm_' . $this->id . '" type="text" name="' . $this->slug . '[mm]"  value="' . $time['mm'] . '">';
        if ($this->field['time_type'] == "12") {
            $time_format = $time['time_format'];
            echo '<select name="' . $this->slug . '[time_format]" >			
			<option ' . ($time_format == "am" ? 'selected="selected"' : "") . ' value="am">AM</option>
			<option ' . ($time_format == "pm" ? 'selected="selected"' : "") . ' value="pm">PM</option>			
			</select>';
        }
        echo '</div>';
    }
    function addDate()
    {
        $date = get_usermeta($this->user_id, $this->slug);
		if(!$date)
		{
			$date['date']['mm'] = "";
			$date['date']['dd'] = "";
			$date['date']['yy'] = "";	
		}
		
        if ($this->field['date_type'] == "datefield") {
            if ($this->field['date_format'] == "mm/dd/yy") {
                echo '<div class="time date_format_field">
				  <div class="time_fields">
					<input id="mm_' . $this->id . '" name="' . $this->slug . '[date][mm]" maxlength="2" type="text" value="' . $date['date']['mm'] . '" >
					<label>MM</label>
				  </div>
				  <div class="time_fields">
					<input id="dd_' . $this->id . '" name="' . $this->slug . '[date][dd]" maxlength="2"  type="text" value="' . $date['date']['dd'] . '">
					<label>DD</label>
				  </div>
				  <div class="time_fields">
					<input id="yy_' . $this->id . '" name="' . $this->slug . '[date][yy]" maxlength="4"  type="text" value="' . $date['date']['yy'] . '">
					<label>yy</label>
				  </div>
				</div>';
            } else if ($this->field['date_format'] == "yy/mm/dd" || $this->field['date_format'] == "yy.mm.dd") {
                echo '<div class="time date_format_field">
				 <div class="time_fields">
					<input id="yy_' . $this->id . '" name="' . $this->slug . '[date][yy]" maxlength="4"  type="text" value="' . $date['date']['yy'] . '">
					<label>yy</label>
				  </div>
				  <div class="time_fields">
					<input id="mm_' . $this->id . '" name="' . $this->slug . '[date][mm]" maxlength="2" type="text" value="' . $date['date']['mm'] . '">
					<label>MM</label>
				  </div>
				  <div class="time_fields">
					<input id="dd_' . $this->id . '" name="' . $this->slug . '[date][dd]" maxlength="2"  type="text" value="' . $date['date']['dd'] . '">
					<label>DD</label>
				  </div>				  
				</div>';
            } else {
                echo '<div class="time date_format_field">
				 <div class="time_fields">
					<input id="dd_' . $this->id . '" name="' . $this->slug . '[date][dd]" maxlength="2"  type="text" value="' . $date['date']['dd'] . '">
					<label>DD</label>
				  </div>	
				 <div class="time_fields">
					<input id="yy_' . $this->id . '" name="' . $this->slug . '[date][yy]" maxlength="4"  type="text" value="' . $date['date']['yy'] . '">
					<label>yy</label>
				  </div>
				  <div class="time_fields">
					<input id="mm_' . $this->id . '" name="' . $this->slug . '[date][mm]" maxlength="2" type="text" value="' . $date['date']['mm'] . '">
					<label>MM</label>
				  </div>				  			  
				</div>';
            }
        } 
		else if ($this->field['date_type'] == "datepicker") {
            echo '<div class="time date_format_field">
				  <input id="' . $this->id . '" name="' . $this->slug . '[date][]" value="' . $date['date'][0] . '" type="text" ></div>';
        } else if ($this->field['date_type'] == "datedropdown") {
            echo '<div class="time date_format_field">
				 
					<select id="mm_' . $this->id . '" name="' . $this->slug . '[date][mm]">
					  <option value="">Month</option>';
            for ($a = 1; $a <= 12; $a++) {
                $sel = '';
                if ((int) $a == (int) $date['date']['mm']) {
                    $sel = 'selected="selected"';
                }
                echo '<option ' . $sel . ' value="' . $a . '">' . sprintf("%02s", $a) . '</option>';
            }
            echo '</select>
				 
				
					<select id="dd_' . $this->id . '" name="' . $this->slug . '[date][dd]">
					  <option value="">Day</option>';
            for ($a = 1; $a <= 31; $a++) {
                $sel = '';
                if ((int) $a == (int) $date['date']['dd']) {
                    $sel = 'selected="selected"';
                }
                echo '<option ' . $sel . ' value="' . $a . '">' . sprintf("%02s", $a) . '</option>';
            }
            echo '</select>
				
				  
					<select id="yy_' . $this->id . '" name="' . $this->slug . '[date][yy]">
					  <option value="">Year</option>';
            for ($a = 2099; $a >= 1900; $a--) {
                $sel = '';
                if ((int) $a == (int) $date['date']['yy']) {
                    $sel = 'selected="selected"';
                }
                echo '<option ' . $sel . ' value="' . $a . '">' . $a . '</option>';
            }
            echo '</select>
				 
				</div>';
        }
    }
	function addList()
	{
		$list = get_usermeta($this->user_id, $this->slug);		
		$width  = 90 /  $this->field['cols']; 
		
			for($a = 1 ,$c=0; $a <= $this->field['cols'] ; $a++,$c++)
			{
				
					echo '<div>';
					
					for($b = 1,$d=0 ; $b <= $this->field['cols'] ;$b++,$d++)
					{
						if(!is_array($list))
						$list[$c][$d] = "";
						
						echo '<input value="'.$list[$c][$d].'" style="width:'.$width.'%;margin-right:2px;" type="text" name="'.$this->slug.'['.$c.'][]" class="input_fields"> ';
					}
					
					echo '</div>';		
				
				
			}
		
		
	}
	function createFieldName($text)
    {
        return "pie_".$this->getMetaKey($text);
    }
    function createFieldID()
    {
        return "field_" . $this->field['id'];
    }
    function addLabel()
    {
        return '<label for="' . $this->id . '">' . $this->field['label'] . '</label>';
    }
    function printFields()
    {
        $update     = get_option('pie_register');
              
        switch ($this->type):
            case 'text':           
                $this->addTextField();
                break;
            case 'textarea':
                $this->addTextArea();
                break;
            case 'dropdown':
            case 'multiselect':
                $this->addDropdown();
                break;
            case 'number':
                $this->addNumberField();
                break;
            case 'radio':
            case 'checkbox':
                $this->addCheckRadio();
                break;
            case 'html':
                $this->addHTML();
                break;
            
            case 'time':
                $this->addTime();
                break;
            case 'upload':
                $this->addUpload();
                break;
            case 'address':
                $this->addAddress();
                break;
            case 'captcha':
                $this->addCaptcha();
                break;
            case 'phone':
                $this->addPhone();
                break;
            case 'date':
                $this->addDate();
                break;
            case 'list':
                $this->addList();
                break;
        endswitch;
    }
    function edit_user_profile($user)
    {
        if (sizeof($this->data) > 0) 
		{
            $this->user_id = $user->ID;			
            echo '<table class="form-table">';
           foreach ($this->data as $this->field) 
		   {
             	$this->slug = $this->createFieldName($this->field['type']."_".$this->field['id']);
                $this->type = $this->field['type'];
                $this->id   = $this->createFieldID();	   
			   
				//When to add label
				switch($this->type) :				
					case 'text' :
					case 'textarea':
					case 'dropdown':
					case 'multiselect':
					case 'number':
					case 'radio':
					case 'checkbox':													
					case 'time':				
					case 'upload':				
					case 'address':							
					case 'phone':				
					case 'date':				
					case 'list':	
					case "default" && $this->default_fields:						
					echo '<tr><th>'.$this->addLabel().'</th><td>';
					echo $this->printFields().'</td></tr>';
					break;	
											
				endswitch; 
			 }
           echo '</table>';
	
        }
    }
	
	function updateMyProfile($user_id) 
	{
     	if ( current_user_can('edit_user',$user_id) )
     	{
			$this->updateProfile($user_id); 
	 	}
 	}
    function validate_user_profile($errors, $update, $user)
    {
        foreach ($this->data as $this->field) {
            $this->slug         = $this->createFieldName($this->field['label']);
            $this->type         = $this->type;
            $this->id           = $this->createFieldID();
            $field_name         = $_POST[$this->slug];
            $required           = $this->field['required'];
            $rule               = $this->field['validation_rule'];
            $validation_message = (!empty($this->field['validation_message']) ? $this->field['validation_message'] : $this->field['label'] . " is required.");
            if ((!isset($field_name) || empty($field_name)) && $required) {
                $errors->add($this->slug, '<strong>'.__(ucwords('Error'),'piereg').'</strong>: ' . $validation_message);
            } else if ($rule == "number") {
                if (!is_numeric($field_name)) {
                    $errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.$this->field['label'] .__(' field must contain only numbers.','piereg' ));	
			    }
            } else if ($rule == "alphanumeric") {
                if (!preg_match("/^([a-z0-9])+$/i", $field_name)) {
                   	$errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.$this->field['label'] .__(' field may only contain alpha-numeric characters.','piereg' ));	
                }
            } else if ($rule == "email") {
                if (!filter_var($field_name, FILTER_VALIDATE_EMAIL)) {
                    $errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.$this->field['label'].__(' field must contain a valid email address.','piereg' ));	
                }
            } else if ($rule == "website") {
                if (!filter_var($field_name, FILTER_VALIDATE_URL)) {                   
					$errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.$this->field['label'] .__(' must be a valid URL.','piereg' ));	
                }
            }
        }
        if (sizeof($errors->errors) == 0) {
            $this->updateProfile($user->ID);
        }
        return $errors;
    }
    function updateProfile($user_id)
    {
        foreach ($this->data as $this->field) {
            
			//When to add label
				switch($this->field['type']) :				
					case 'text' :
					case 'textarea':
					case 'dropdown':
					case 'multiselect':
					case 'number':
					case 'radio':
					case 'checkbox':
					case 'html':								
					case 'time':				
					case 'upload':				
					case 'address':							
					case 'phone':				
					case 'date':				
					case 'list':					
					$slug       = $this->createFieldName($this->field['type']."_".$this->field['id']);
            		$field_name = $_POST[$slug];         
		    		update_user_meta($user_id,$slug, $field_name);				
					break;							
				endswitch; 	
			
        }	  
		
    }
}
