<?php
require_once('base.php');
class Registration_form extends Base
{
	var $id;
	var $name;
	var $field;	
	var $data;
	var $label_alignment;
	var $pages;
	
	function __construct()	
	{
		$this->data = $this->getCurrentFields();
		$this->label_alignment = $this->data['form']['label_alignment'];		
		$this->pages = 1;
		
	}
	function addFormData()
	{
		
		echo '<h2 id="pie_form_heading">'.$this->data['form']['label'].'</h2>';	
		echo '<p id="pie_form_desc" class="'.$this->addClass("").'" >'.$this->data['form']['desc'].'</p>';		
	
	}
	function addDefaultField()
	{
		$this->name = $this->field['field_name'];
		if($this->field['field_name']=="description")
		{
			echo '<textarea name="description" id="description" rows="5" cols="80">'.$this->getDefaultValue().'</textarea>';	
		}
		else
		{
			echo '<input id="'.$this->id.'" name="'.$this->field['field_name'].'" class="'.$this->addClass().'"  placeholder="'.$this->field['placeholder'].'" type="text" value="'.$this->getDefaultValue().'" />';	
		}	
	}
	function addTextField()
	{
		echo '<input id="'.$this->id.'" name="'.$this->name.'" class="'.$this->addClass().'"  '.$this->addValidation().'  placeholder="'.$this->field['placeholder'].'" type="text" value="'.$this->getDefaultValue().'" />';	
	}
	function addHiddenField()
	{
		echo '<input id="'.$this->id.'" name="'.$this->name.'"  type="hidden" value="'.$this->getDefaultValue().'" />';		
	}
	function addUsername()
	{
		echo '<input id="username" name="username" class="input_fields validate[required]" placeholder="'.$this->field['placeholder'].'" type="text" value="'.$this->getDefaultValue('username').'" data-errormessage-value-missing="'.$this->field['validation_message'].'"  />';	
		
	}
	function addPassword()
	{
		$style = "";
		if($this->label_alignment=="left")
			$style = 'class = "wdth-lft mrgn-lft"';
		
		
		echo '<input '; 
		
		if($this->field['show_meter']==1)
		{
			echo 'onkeyup="passwordStrength(this.value)" ';
		}
		
		echo 'id="'.$this->id.'" name="password" class="'.$this->addClass("input_fields",array("minSize[8]")).'" placeholder="'.$this->field['placeholder'].'" type="password" data-errormessage-value-missing="'.$this->field['validation_message'].'" data-errormessage-range-underflow="'.$this->field['validation_message'].'" data-errormessage-range-overflow="'.$this->field['validation_message'].'" />';
				
		
			$class = '';
			$fclass = '';
			
			$topclass = "";
			if($this->label_alignment=="top")
				$topclass = "label_top"; 
			
			echo '</div></li><li class="fields pageFields_'.$this->pages.' '.$topclass.'"><div class="fieldset"><label>Confirm Password</label><input id="confirm_password_'.$this->id.'" type="password" class="input_fields validate[required,equals['.$this->id.']]" placeholder="'.$this->field['placeholder'].'" />';	
			
				
			
			
	}	
	function addEmail()
	{
		echo '<input id="'.$this->id.'" name="e_mail" class="'.$this->addClass().'"  '.$this->addValidation().'  placeholder="'.$this->field['placeholder'].'" type="text" value="'.$this->getDefaultValue("e_mail").'" />';
		
		if(isset($this->field['confirm_email']))
		{
			$class = '';
			$fclass = '';
			
			$topclass = "";
			if($this->label_alignment=="top")
				$topclass = "label_top"; 	
		
			
			echo '</div></li><li class="fields pageFields_'.$this->pages.' '.$topclass .'"><div class="fieldset"><label>Confirm E-Mail</label><input  placeholder="'.$this->field['placeholder'].'" id="confirm_email_'.$this->id.'" type="text" class="input_fields validate[required,equals['.$this->id.']]">';
			
			
			
		}	
	}
	function addUpload()
	{
		echo '<input id="'.$this->id.'" name="'.$this->name.'" class="'.$this->addClass().'"  '.$this->addValidation().' type="file"  />';	
	}		
	function addTextArea()
	{		
		echo '<textarea id="'.$this->id.'" name="'.$this->name.'" rows="'.$this->field['rows'].'" cols="'.$this->field['cols'].'"  class="'.$this->addClass("").'"  placeholder="'.$this->field['placeholder'].'">'.$this->getDefaultValue().'</textarea>';		
	}
	function addName()
	{
				
			echo '<div class="fieldset"><label>First Name</label>';
			echo '<input value="'.$this->getDefaultValue('first_name').'" id="'.$this->id.'_firstname" name="first_name" class="'.$this->addClass().' input_fields" '.$this->addValidation().'  type="text"  />';				
			
			$topclass = "";
			if($this->label_alignment=="top")
				$topclass = "label_top"; 					
		
			echo '</div></li><li class="fields pageFields_'.$this->pages.' '.$topclass.'">';
				
			echo '<div class="fieldset"><label>Last Name</label>';
			echo '<input value="'.$this->getDefaultValue('last_name').'" id="'.$this->id.'_lastname" name="last_name" class="'.$this->addClass().' input_fields" '.$this->addValidation().'  type="text"  /></div>';		
		
	}
	function addTime()
	{
		
		$this->field['hours'] = TRUE;
		echo '<div class="time"><div class="time_fields"><input value="'.$this->getDefaultValue($this->name.'[hh]').'" maxlength="2" id="hh_'.$this->id.'" name="'.$this->name.'[hh]" type="text"  class="'.$this->addClass().'"  '.$this->addValidation().'><label>HH</label></div>';
		$this->field['hours'] = FALSE;
		
		$this->field['mins'] = TRUE;
		echo '<span class="colon">:</span><div class="time_fields"><input value="'.$this->getDefaultValue($this->name.'[mm]').'" maxlength="2" id="mm_'.$this->id.'" type="text" name="'.$this->name.'[mm]"  class="'.$this->addClass().'"  '.$this->addValidation().'><label>MM</label></div><div id="time_format_field_'.$this->id.'" class="time_fields"></div>';
		$this->field['mins'] = FALSE;
		
		if($this->field['time_type']=="12")
		{
			echo '<div class="time_fields"><select name="'.$this->name.'[time_format]" ><option value="am">AM</option><option value="pm">PM</option></select></div>';
		}
		
		echo '</div>';
	}	
	function addDropdown()
	{
		$multiple = "";
		$name = $this->name."[]";
		
		if($this->field['type']=="multiselect")
		{
			$multiple 	= 'multiple';			
		}		
		echo '<select '.$multiple.' id="'.$name.'" name="'.$name.'" class="'.$this->addClass("").'" '.$this->addValidation().'  >';
	
		if($this->field['list_type']=="country")
		{
			 $countries = get_option("pie_countries");			 
			echo $this->createDropdown($countries);			   	
		}
		else if($this->field['list_type']=="us_states")
		{
			 $us_states = get_option("pie_us_states");
			 $options 	= $this->createDropdown($us_states);				 
			  echo $options;						   	
		}
		else if($this->field['list_type'] == "can_states")
		{
			$can_states = get_option("pie_can_states");			
			echo $options 	= $this->createDropdown($can_states);					
		}
		else if(sizeof($this->field['value']) > 0)
			{	for($a = 0 ; $a < sizeof($this->field['value']) ; $a++)
				{
					$selected = '';
					if(is_array($this->field['selected']) && in_array($a,$this->field['selected']))
					{
						$selected = 'selected="selected"';	
					}				
					if($this->field['value'][$a] !="" && $this->field['display'][$a] != "")
					echo '<option '.$selected.' value="'.$this->field['value'][$a].'">'.$this->field['display'][$a].'</option>';	
				}		
			}
		echo '</select>';	
	}
	function addNumberField()
	{
		echo '<input id="'.$this->id.'" name="'.$this->name.'" class="'.$this->addClass().'"  '.$this->addValidation().'  placeholder="'.$this->field['placeholder'].'" type="number" value="'.$this->getDefaultValue().'"' ;
		
		if(!empty($this->field['min']))
		echo 'min="'.$this->field['min'].'"';
		
		if(!empty($this->field['max']))
		echo 'max="'.$this->field['max'].'"';
		
		echo '/>';	
	}
	function addPhone()
	{		
		echo '<input id="'.$this->id.'" class="'.$this->addClass().'"  '.$this->addValidation().' name="'.$this->name.'"  placeholder="'.$field['placeholder'].'" type="text" value="'.$field['default_value'].'" />';	
	}
	function addList()
	{
		$width  = 85 /  $this->field['cols']; 
		for($a = 1 ,$c=0; $a <= $this->field['rows'] ; $a++,$c++)
		{
			if($a==1)
			{
				echo '<div class="'.$this->id.'_'.$a.' pie_list">';
				
				for($b = 1 ; $b <= $this->field['cols'] ;$b++)
				{
					echo '<input style="width:'.$width.'%;margin-right:2px;" type="text" name="'.$this->name.'['.$c.'][]" class="input_fields"> ';
				}
				
				echo ' <img src="'.get_bloginfo('url').'/wp-content/plugins/pie-register/images/plus.png" onclick="addList('.$this->field['rows'].','.$this->field['id'].');" alt="add" /></div>';		
			}
			else
			{
				echo '<div style="display:none;" class="'.$this->id.'_'.$a.' pie_list">';
				for($b = 1 ; $b <= $this->field['cols'] ;$b++)
				{
					echo '<input style="width:'.$width.'%;margin-right:2px;" type="text" name="'.$this->name.'['.$c.'][]" class="input_fields">';
				}
				
					echo ' <img src="'.get_bloginfo('url').'/wp-content/plugins/pie-register/images/minus.gif" onclick="removeList('.$this->field['rows'].','.$this->field['id'].','.$a.');" alt="add" /></div>';
				
				
			}
		}
	}
	function addHTML()
	{
		echo $this->field['html'];
	}
	function addSectionBreak()
	{
		$class = "";
		
		if($this->label_alignment == "left")
		$class .= "wdth-lft ";
		
		$class .= "sectionBreak";
		
		echo '<div class="'.$class.'"></div>';	
	}
	function addCheckRadio()
	{
		if(sizeof($this->field['value']) > 0)
		{
			echo '<div class="radio_wrap">';
			for($a = 0 ; $a < sizeof($this->field['value']) ; $a++)
			{
				$checked = '';
				if(is_array($this->field['selected']) && in_array($a,$this->field['selected']))
				{
					$checked = 'checked="checked"';	
				}				
				
				//if(!empty($this->field['display'][$a]))
				{	
					
					echo "<label>";
					echo $this->field['display'][$a];	
					echo "</label>";
					echo '<input '.$checked.' value="'.$this->field['value'][$a].'" type="'.$this->field['type'].'" '.$multiple.' name="'.$this->name.'[]" class="'.$this->addClass("").' radio_fields" '.$this->addValidation().'  >';
					
					
				}
			}
			echo "</div>";		
		}				
	}
	function addAddress()
	{
		echo '<div class="address_main">';
		echo '<div class="address">
		  <input type="text" name="'.$this->name.'[address]" id="'.$this->id.'" class="'.$this->addClass().'"  '.$this->addValidation().'>
		  <label>Street Address</label>
		</div>';
		
		 if(!$this->field['hide_address2'])
		 {
		
			echo '<div class="address">
			  <input type="text" name="'.$this->name.'[address2]" id="address2_'.$this->id.'"  class="'.$this->addClass().'"  '.$this->addValidation().'>
			  <label>Address Line 2</label>
			</div>';
		 }
		
		echo '<div class="address">
		  <div class="address2">
			<input type="text" name="'.$this->name.'[city]" id="city_'.$this->id.'" class="'.$this->addClass().'"  '.$this->addValidation().'>
			<label>City</label>
		  </div>';
		
		
		 if(!$this->field['hide_state'])
		 {
			 	if($this->field['address_type'] == "International")
				{
					echo '<div class="address2"  >
					<input type="text" name="'.$this->name.'[state]" id="state_'.$this->id.'" class="'.$this->addClass().'"  '.$this->addValidation().'>
					<label>State / Province / Region</label>
				 	 </div>';		
				}
				else if($this->field['address_type'] == "United States")
				{
				  $us_states = get_option("pie_us_states");
				  $options 	= $this->createDropdown($us_states,$this->field['us_default_state']);	
				 
				  echo '<div class="address2"  >
					<select id="state_'.$this->id.'" name="'.$this->name.'[state]" class="'.$this->addClass("").'">
					 '.$options.' 
					</select>
					<label>State</label>
				  </div>';	
				}
				else if($this->field['address_type'] == "Canada")
				{
					
					$can_states = get_option("pie_can_states");
				  	$options 	= $this->createDropdown($can_states,$this->field['canada_default_state']);
					echo '<div class="address2">
						<select id="state_'.$this->id.'" class="'.$this->addClass("").'" name="'.$this->name.'[state]">
						  '.$options.'
						</select>
						<label>Province</label>
					  </div>';		
				}
		 }
		
		
		 
		 
		echo '</div>';
		
		echo '<div class="address">';	
		
		echo ' <div class="address2">
		<input id="zip_'.$this->id.'" name="'.$this->name.'[zip]" type="text" class="'.$this->addClass().'"  '.$this->addValidation().'>
		<label>Zip / Postal Code</label>
		 </div>';	 
		
		
		 if($this->field['address_type'] == "International")
		 {
			 $countries = get_option("pie_countries");			 
			 $options 	= $this->createDropdown($countries,$this->field['default_country']);  
			 echo '<div  class="address2" >
					<select id="country_'.$this->id.'" name="'.$this->name.'[country]" class="'.$this->addClass("").'"   '.$this->addValidation().'>
                    <option>Select Country</option>
					'. $options .'
					 </select>
					<label>Country</label>
		  		</div>';
		 }
		 
		 
		echo '</div>';
		echo '</div>';
	}	
	function addDate()
	{			
			
		
		if($this->field['date_type'] == "datefield")
		{
			
			if($this->field['date_format']=="mm/dd/yy")
			{
			
			echo '<div class="time date_format_field">
				  <div class="time_fields">
					<input id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" maxlength="2" type="text" class="'.$this->addClass("input_fields",array("custom[month]")).'" '.$this->addValidation().'>
					<label>MM</label>
				  </div>
				  <div class="time_fields">
					<input id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" maxlength="2"  type="text" class="'.$this->addClass("input_fields",array("custom[day]")).'" '.$this->addValidation().'>
					<label>DD</label>
				  </div>
				  <div class="time_fields">
					<input id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" maxlength="4"  type="text" class="'.$this->addClass("input_fields",array("custom[year]")).'" '.$this->addValidation().'>
					<label>YYYY</label>
				  </div>
				</div>';
			} 
			else if($this->field['date_format']=="yy/mm/dd" || $this->field['date_format']=="yy.mm.dd")
			{
				echo '<div class="time date_format_field">
				 <div class="time_fields">
					<input id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" maxlength="4"  type="text" class="'.$this->addClass("input_fields",array("custom[year]")).'">
					<label>YYYY</label>
				  </div>
				  <div class="time_fields">
					<input id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" maxlength="2" type="text" class="'.$this->addClass("input_fields",array("custom[month]")).'" '.$this->addValidation().'>
					<label>MM</label>
				  </div>
				  <div class="time_fields">
					<input id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" maxlength="2"  type="text" class="'.$this->addClass("input_fields",array("custom[day]")).'">
					<label>DD</label>
				  </div>				  
				</div>';	
			}
			else
			{
				echo '<div class="time date_format_field">
				 <div class="time_fields">
					<input id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" maxlength="2"  type="text" class="'.$this->addClass("input_fields",array("custom[day]")).'">
					<label>DD</label>
				  </div>	
				 <div class="time_fields">
					<input id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" maxlength="4"  type="text" class="'.$this->addClass("input_fields",array("custom[year]")).'">
					<label>YYYY</label>
				  </div>
				  <div class="time_fields">
					<input id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" maxlength="2" type="text" class="'.$this->addClass("input_fields",array("custom[month]")).'" '.$this->addValidation().'">
					<label>MM</label>
				  </div>				  			  
				</div>';	
			}
		}
		else if($this->field['date_type'] == "datepicker")
		{
		
						
				echo	'<div class="time date_format_field">
				  <input id="'.$this->id.'" name="'.$this->name.'[date][]" readonly="readonly" type="text" class="'.$this->addClass().' date_start" title="'.$this->field['date_format'].'">';
				  
				 echo '<input id="'.$this->id.'_format" type="hidden"  value="'.$this->field['date_format'].'">';
				 echo '<input id="'.$this->id.'_firstday" type="hidden"  value="'.$this->field['firstday'].'">';
				
				 echo '<input id="'.$this->id.'_startdate" type="hidden"  value="'.$this->field['startdate'].'">';
				  
				if($this->field['calendar_icon'] == "calendar")
				{
					 echo  '<img id="'.$this->id.'_icon" class="calendar_icon" src="'.get_bloginfo("url").'/wp-content/plugins/pie-register/images/calendar.png"  />'; 
				}
				else if($this->field['calendar_icon'] == "custom")
				{
					 echo  '<img id="'.$this->id.'_icon" class="calendar_icon" src="'.$this->field['calendar_icon_url'].'"  />'; 
				}
				  
				 echo '</div>';	
		}
		else if($this->field['date_type'] == "datedropdown")
		{
				
			if($this->field['date_format']=="mm/dd/yy")
			{
			
					echo '<div class="time date_format_field">
				  <div class="time_fields">
					<select id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" class="'.$this->addClass("").'">
					  <option value="">Month</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
					</select>
				  </div>
				  <div class="time_fields">
					<select id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" class="'.$this->addClass("").'">
					  <option value="">Day</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>
					</select>
				  </div>
				  <div class="time_fields">
					<select id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" class="'.$this->addClass("").'">
					  <option value="">Year</option>
					  <option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option>
					</select>
				  </div>
				</div>';
			}
			else if($this->field['date_format']=="yy/mm/dd" || $this->field['date_format']=="yy.mm.dd")
			{
					echo '<div class="time date_format_field">
					 <div class="time_fields">
					<select id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" class="'.$this->addClass("").'">
					  <option value="">Year</option>
					  <option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option>
					</select>
				  </div>
				  <div class="time_fields">
					<select id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" class="'.$this->addClass("").'">
					  <option value="">Month</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
					</select>
				  </div>
				  <div class="time_fields">
					<select id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" class="'.$this->addClass("").'">
					  <option value="">Day</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>
					</select>
				  </div>				 
				</div>';
			
			}
			else
			{
				echo '<div class="time date_format_field">
				
				  
				  <div class="time_fields">
					<select id="dd_'.$this->id.'" name="'.$this->name.'[date][dd]" class="'.$this->addClass("").'">
					  <option value="">Day</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>
					</select>
				  </div>	
				  <div class="time_fields">
					<select id="mm_'.$this->id.'" name="'.$this->name.'[date][mm]" class="'.$this->addClass("").'">
					  <option value="">Month</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
					</select>
				  </div>
				  	 <div class="time_fields">
					<select id="yy_'.$this->id.'" name="'.$this->name.'[date][yy]" class="'.$this->addClass("").'">
					  <option value="">Year</option>
					  <option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option>
					</select>
				  </div>			 
				</div>';	
			}			
		}	
	}
	function addInvitationField()
	{
			echo '<input id="'.$this->id.'" name="invitation" class="'.$this->addClass("input_fields",array("required")).'"  placeholder="'.$this->field['placeholder'].'" type="text" value="'.$this->getDefaultValue().'" />';		
	}	
		
	function createFieldName($text)
	{
		return $this->getMetaKey($text);			
	}
	function createFieldID()
	{
		return "field_".$this->field['id'];	
	}
	function getDefaultValue($name="")
	{
		if($name != "")
		{
			$this->name = $name;	
		}
		if(isset($_POST[$this->name]))
		{
			return $_POST[$this->name];	
		}
		return $this->field['default_value'];	
	}
	function addDesc()
	{
		if(!empty($this->field['desc']))
		{
			return '<span class="desc">'.$this->field['desc'].'</span>';
		}
	}
	function addLabel()
	{
		if($this->field['type']=="name" && $this->field['name_format']=="normal")
		{
			return "";
		}		
					
		$topclass = "";
		if($this->label_alignment=="top")
			$topclass = "label_top";
	
		return '<label class="'.$topclass .'" for="'.$this->name.'">'.$this->field['label'].'</label>';		
	}
	function addClass($default = "input_fields",$val = array())
	{
		$class = $default." ".$this->field['css'];
		
		
		if($this->field['required'])
		{
			$val[] = "required";		
		}
		
		
		if($this->field['validation_rule']=="number"  || $this->field['type']=="number")
		{
			$val[] = "custom[number]";		
		}
		else if($this->field['validation_rule']=="alphanumeric")
		{
			$val[] = "custom[alphanumeric]";		
		}
		else if($this->field['validation_rule']=="email" || $this->field['type']=="email")
		{
			$val[] = "custom[email]";		
		}
		else if($this->field['validation_rule']=="website" || $this->field['type']=="website")
		{
			$val[] = "custom[url]";		
		}		
		else if($this->field['validation_rule']=="standard" || $this->field['phone_format']=="standard")
		{
			$val[] = "custom[phone_standard]";		
		}
		else if($this->field['validation_rule']=="international" || $this->field['phone_format']=="international")
		{
			$val[] = "custom[phone_international]";		
		}
		else if($this->field['type']=="time")
		{
			
			$val[] = "custom[number]";	
			$val[] = "minSize[2]";
			$val[] = "maxSize[2]";
			$val[] = "min[0]";
			
			if($this->field['hours']==TRUE)
			{
				if($this->field['time_type']=="12")
				{
					$val[] = "max[12]";
				}
				else
				{
					$val[] = "max[23]";	
				}
			}
			else if($this->field['mins']==TRUE)
			{
				$val[] = "max[59]";	
			}
				
		}
		else if($this->field['type']=="upload" && explode(",",$this->field['file_types']) > 0)
		{
			if(!empty($this->field['file_types']))
			{
				$val[] = "funcCall[checkExtensions]";	
				$val[] = "ext[".str_replace(",","|",$this->field['file_types'])."]";			
			}
		}
		
		if(sizeof($val) > 0)
		{
			$val = " validate[".implode(",",$val)."]";
			$class .= $val;	
		}
		
		return $class;	
	}
	function addValidation()
	{
				
		
		if($this->field['required'] && !empty($this->field['validation_message']))
		{
			$val[] = 'data-errormessage-value-missing="'.$this->field['validation_message'].'"';
		}
		
		
		if($this->field['validation_rule']=="number" || $this->field['type']=="number" || $this->field['validation_rule']=="alphanumeric" || $this->field['validation_rule']=="email" || $this->field['type']=="email" || $this->field['validation_rule']=="website" || $this->field['type']=="website" || $this->field['type']=="phone" || $this->field['type']=="date")
		{
			$val[] = 'data-errormessage-custom-error="'.$this->field['validation_message'].'"';		
		}		
		else if($this->field['type']=="time")
		{
			$val[] = 'data-errormessage-custom-error="'.$this->field['validation_message'].'"';		
			$val[] = 'data-errormessage-range-underflow="'.$this->field['validation_message'].'"';	
			$val[] = 'data-errormessage-range-overflow="'.$this->field['validation_message'].'"';
		}
		
		
		if(sizeof($val) > 0)
		{
			return implode(" ",$val);			
		}
		
		
	}
	
	function addCaptcha()
	{
		 $settings  	=  get_option("pie_register");
		 $publickey		= $settings['captcha_publc'] ;
		 
		 if($publickey)
		 {
		 	require_once('wp-content/plugins/pie-register/recaptchalib.php');		
 			 echo recaptcha_get_html($publickey);
		 }
	}
	function addSubmit()
	{
		if($this->pages > 1)
		{
			echo '<input class="pie_prev" name="pie_prev" id="pie_prev_'.$this->pages.'" type="button" value="Previous" />';
			echo '<input id="pie_prev_'.$this->pages.'_curr" name="page_no" type="hidden" value="'.($this->pages-1).'" />';						
		}
		$check_payment = get_option("pie_register")	;
		if($check_payment["enable_paypal"]==1 && !(empty($check_payment['paypal_butt_id'])))
		{
			$this->addPaypal();	
		}
		else
		{
			echo '<input name="pie_submit" type="submit" value="'.$this->field['text'].'" />';	
		}
		
		if($this->field['reset']==1)
		{
			echo '<input name="pie_reset" type="reset" value="'.$this->field['reset_text'].'" />';		
		}
	}
	function addPaypal()
	{
		echo '<input name="pie_submit" value="paypal" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" />';	
	}
	function addPagebreak()
	{
		if($this->pages > 1)
		{
			echo '<input id="pie_prev_'.$this->pages.'_curr" name="page_no" type="hidden" value="'.($this->pages-1).'" />';		
			
			if($this->field['prev_button']=="text")
			{
				echo '<input class="pie_prev" name="pie_prev" id="pie_prev_'.$this->pages.'" type="button" value="'.$this->field['prev_button_text'].'" />';	
			}
			else if($this->field['prev_button']=="url")
			{
				echo '<img class="pie_prev" name="pie_prev" id="pie_prev_'.$this->pages.'" src="'.$this->field['prev_button_url'].'"  />';		
			}
		}
		
		echo '<input id="pie_next_'.$this->pages.'_curr" name="page_no" type="hidden" value="'.($this->pages+1).'" />';	
		if($this->field['next_button']=="text")
		{
			echo '<input class="pie_next" name="pie_next" id="pie_next_'.$this->pages.'" type="button" value="'.$this->field['next_button_text'].'" />';			
		}
		else if($this->field['next_button']=="url")
		{
			echo '<img style="cursor:pointer;" src="'.$this->field['next_button_url'].'" class="pie_next" name="pie_next" id="pie_next_'.$this->pages.'" />';	
		}
		
		
			
	}
	function printFields()
	{
		$update = get_option( 'pie_register' );	
		wp_enqueue_script( 'jquery' );
		if($update['outputcss']==1)//Output Form CSS
		{
			wp_register_style( 'prefix-style', $this->pluginURL("css/front.css") );
			wp_enqueue_style( 'prefix-style' );	
		}		
		
		foreach($this->data as $this->field)
		{
			
			if ($this->field['type']=="")
			{
				continue;
			}
			
			if($this->field['type']=="invitation" && $update["enable_invitation_codes"]=="0")
			{
				continue;	
			}
			
			
			$this->name 	= $this->createFieldName($this->field['type']."_".$this->field['id']);
			$this->id 		= $this->name;
			
			//We don't need to print li for hidden field
			if ($this->field['type'] == "hidden")
			{
				$this->addHiddenField();
				continue;
			}
			
			$topclass = "";
			if($this->label_alignment=="top")
				$topclass = "label_top"; 
			
			echo '<li class="fields pageFields_'.$this->pages.' '.$topclass.'">';
			
			//When to add label
			switch($this->field['type']) :				
				case 'text' :								
				case 'website' :							
				case 'username' :
				case 'password':			
				case 'email' :
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
				case 'captcha':				
				case 'phone':				
				case 'date':				
				case 'list':								
				case 'sectionbreak':				
				case 'default':
				case 'invitation':			
				echo '<div class="fieldset">'.$this->addLabel();								
				break;							
			endswitch;
			
		
			
			if($this->field['type'] == "pagebreak")
			{
				$this->addPagebreak();	
				$this->pages++;			
			} 
			
			
			
			
			//Printting Field
			switch($this->field['type']) :				
				case 'text' :								
				case 'website' :
				$this->addTextField();
				break;				
				case 'username' :
				$this->addUsername();
				break;
				case 'password' :
				$this->addPassword();
				break;
				case 'email' :
				$this->addEmail();
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
				case 'name':
				$this->addName();
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
				case 'submit':
				$this->addSubmit();
				break;				
				case 'sectionbreak':
				$this->addSectionBreak();
				break;	
				case 'default':
				$this->addDefaultField();
				break;
				case 'invitation':
				$this->addInvitationField();
				break;							
			endswitch;
			
			
			
				switch($this->field['type']) :				
				case 'text' :								
				case 'website' :							
				case 'username' :
				case 'password':			
				case 'email' :
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
				case 'captcha':				
				case 'phone':				
				case 'date':				
				case 'list':		
				case 'default':
				case 'invitation':				
				echo $this->addDesc();
				echo '</div>';					
				break;							
			endswitch;
					
			echo '</li>';
			
			if($this->field['type'] == "password" && $this->field['show_meter']==1)
			{		
				echo '<li class="fields pageFields_'.$this->pages.'">';
				echo "<div id='password_meter' class='fieldset' ".$style.">";
				echo '<label id="passwordDescription">Password not entered</label>
				<div id="passwordStrength" class="strength0">&nbsp;</div>';
				echo "</div>";
				echo '</li>';
			}
		}		
	}
	function validateRegistration($errors)
	{
		if(!is_wp_error($errors))
		$errors = new WP_Error();
		$piereg 	= get_option( 'pie_register' );
		
		
		global $wpdb;
		if ( empty( $_POST['username'] ) )
		{
			$errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.__(' Invalid Username','piereg' ));		
		}
		else if ( username_exists( $_POST['username'] ) )
		{
			$errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.__(' Username already exists','piereg' ));		
		}		
		
		if ( empty( $_POST['e_mail'] ) || !filter_var($_POST['e_mail'],FILTER_VALIDATE_EMAIL) )
		{
			$errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.__(' Invalid E-mail address','piereg' ));		
		}
		else if ( email_exists( $_POST['e_mail'] ) )
		{
			$errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.__(' E-mail address already exists','piereg' ));
					
		}
			
		 foreach($this->data as $field)
		 {
			$slug 				= $this->createFieldName($field['type']."_".$field['id']);			
			if($field['type']=="username" || $field['type']=="email"  || $field['type']=="password")
			{
				  $slug  = $this->createFieldName($field['label']);	
			}			
			
			$field_name			= $_POST[$slug];			
			$required 			= $field['required'];
			$rule				= $field['validation_rule'];
			$validation_message	= (!empty($field['validation_message']) ? $field['validation_message'] : $field['label'] ." is required.");
			
			//Handling File Field
			if($field['type']=="upload")
			{
				$field_name			= $_FILES[$slug];
				
				if(!empty($field['file_types']))
				$file_types			= explode(",",$field['file_types']);
				
				$ext 				= pathinfo($_FILES[$slug]['name'], PATHINFO_EXTENSION);	
				$upload_dir = wp_upload_dir();
				
				
				if((is_array($file_types) && in_array($ext,$file_types)) || empty($field['file_types']))
				{
					if(!move_uploaded_file($field_name['tmp_name'],$upload_dir['path']."/".$_FILES[$slug]['name']) && $required)
					$errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.__(' Fail to upload file.','piereg' ));
					else
						$_POST[$slug] = $upload_dir['url']."/".$_FILES[$slug]['name'];	
					
				}
				else if($required)
				{
					$errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.__(' Invalid File Type.','piereg' ));		
				}				
				
			} 
			else if($field['type']=="invitation"  && $piereg["enable_invitation_codes"]=="1")
			{
				
				
				$code = $_POST['invitation'];
				
				$codetable	= $this->codeTable();				
				$codes = $wpdb->get_results( "SELECT * FROM $codetable where name = '$code' and status = 1");
				foreach($codes as $c)
				{
					$times_used = $c->count;
					$usage 		= $c->usage;	
				}
			
				
				if(count($codes) != 1)
				{
					$errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.__(' Invalid Invitation Code.','piereg' ));		
						
				}
				elseif($times_used >= $usage)
				{
					$errors->add( $slug , '<strong>'.__(ucwords('Error'),'piereg').'</strong>: '.__(' Invitation Code has expired.','piereg' ));					
				}		
			}
			else if($field['type']=="captcha")
			{
				$settings  		=  get_option("pie_register");
		 		$privatekey		= $settings['captcha_private'] ;
				require_once('wp-content/plugins/pie-register/recaptchalib.php');	
				
				$resp = recaptcha_check_answer ($privatekey,

												$_SERVER["REMOTE_ADDR"],
												$_POST["recaptcha_challenge_field"],
												$_POST["recaptcha_response_field"]);
				
				if (!$resp->is_valid) {				 
				  $errors->add('recaptcha_mismatch',"<strong>". __(ucwords("Error"),"piereg").":</strong>:". __("Invalid Security Code ", 'piereg'));				 
				}	
			
			}
			else if($field['type']=="name")
			{
				$field_name	= $_POST["first_name"];	
			}
			
			
			if( (!isset($field_name) || empty($field_name)) && $required)
			{
				$errors->add( $slug , "<strong>". __(ucwords("Error"),"piereg").":</strong> " .$validation_message );				
			}
			else if($rule=="number")
			{
				if(!is_numeric($field_name))
				{
					$errors->add( $slug , "<strong>". __(ucwords("Error"),"piereg").":</strong> ".$field['label'] ." field must contain only numbers." );		
				}	
			}
			else if($rule=="alphanumeric")
			{
				if(! preg_match("/^([a-z0-9])+$/i", $field_name))
				{
					$errors->add( $slug ,"<strong>". __(ucwords("Error"),"piereg").":</strong> ".$field['label'] ." field may only contain alpha-numeric characters." );		
				}	
			}	
			else if($rule=="email")
			{
				if(!filter_var($field_name,FILTER_VALIDATE_EMAIL))
				{
					$errors->add( $slug ,"<strong>". __(ucwords("Error"),"piereg").":</strong> ".$field['label'] ." field must contain a valid email address." );		
				}	
			}	
			else if($rule=="website")
			{
				if(!filter_var($field_name,FILTER_VALIDATE_URL))
				{
					$errors->add( $slug ,"<strong>". __(ucwords("Error"),"piereg").":</strong> ".$field['label'] ." must be a valid URL." );		
				}	
			}				 
		 }			
		return $errors;
	}
	function addUser($user_id)
	{
		global $wpdb;
		foreach($this->data as $field)
		{
			//Some form fields which we can't save like paypal, submit,formdata
			if(!isset($field['meta']))
			{
				if($field['type']=="default" && $field['field_name'] != 'url')
				{
					$slug 				= $field['field_name'];				
					$value				= $_POST[$slug];
					update_user_meta($user_id, $slug, $value);	
				}
				else if($field['type']=="invitation")
				{
					$prefix		= $wpdb->prefix."pieregister_";
					$codetable	= $prefix."code";				
					$codes 		= $wpdb->query( "update $codetable set count = count + 1 where name = '".$_POST['invitation']."' and status = 1");
					
					update_user_meta($user_id, "invite_code", $_POST['invitation']);			
				}
				else if($field['type']=="name")
				{
					$slug 				= "first_name";				
					$value				= $_POST[$slug];
					update_user_meta($user_id, $slug, $value);	
					
					$slug 				= "last_name";				
					$value				= $_POST[$slug];
					update_user_meta($user_id, $slug, $value);	
				}
				else
				{
					$slug 				= $this->createFieldName($field['type']."_".$field['id']);				
					$field_name			= $_POST[$slug];
					update_user_meta($user_id, "pie_".$slug, $field_name);
				}
			}
		}		
	} 
	function countPageBreaks()
	{
		$pages = 1;
		if(count($this->data) > 0):
			foreach($this->data as $field)
			{
				if($field['type']=="pagebreak")
					$pages++;	
			}
		endif;
		return $pages ;
	}					
}
