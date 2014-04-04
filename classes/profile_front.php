<?php
require_once('base.php');
class Profile_front extends Base
{
    var $field;
    var $user_id;
    var $slug;
    var $type;
    var $name;
    var $id;
    var $data;
	var $user;
	
    function __construct($user)
    {
        $this->data = $this->getCurrentFields();
		$this->user = $user;	
		$this->user_id = $user->ID;			
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
	function print_user_profile()
    {
        if (sizeof($this->data) > 0) 
		{
          	echo '<h1 id="piereg_pie_form_heading">'.__("Profile Page","piereg").'</h1>';
            echo '<a class="piereg_edit_profile_link" href="?page_id='.$_GET['page_id'].'&edit_user=1"></a>';
		    echo '<table border="0" cellpadding="0" cellspacing="0" class="pie_profile" id="pie_register">';
           foreach ($this->data as $this->field) 
		   {
             	$this->slug = $this->createFieldName($this->field['type']."_".$this->field['id']);
                $this->type = $this->field['type'];
                $this->id   = $this->createFieldID();	
				
				if($this->type=="default")
					 $this->slug   = $this->field['field_name'];
			   
			   if($this->field['show_in_profile']=="0")
			   {
			   		continue;
			   }
			  
				//When to add label
				switch($this->type) :				
					case 'password':
					case 'form':
					continue;
					break;
					
					
					case 'username' :
					echo '<tr><td class="fields fields2">'.$this->addLabel();
					echo '</td><td class="fields"><span>'.$this->user->data->user_login.'</span></td></tr>';
					break;
					
					case 'email' :
					echo '<tr><td class="fields fields2">'.$this->addLabel();
					echo '</td><td class="fields"><span>'.$this->user->data->user_email.'</span></td></tr>';
					break;
					
					case 'default' &&  $this->slug=="url":											
					echo '<tr><td class="fields fields2">'.$this->addLabel();
					echo '</td><td class="fields"><span>'.$this->user->data->user_url.'</span></td></tr>';
					break;
					
					
					case 'name':											
					$this->slug = "first_name";
					echo '<tr><td class="fields fields2"><label>First Name</label>';
					echo '</td><td class="fields"><span>'.$this->getValue().'</span></td></tr>';
					
					$this->slug = "last_name";
					echo '<tr><td class="fields fields2"><label>Last Name</label>';
					echo '</td><td class="fields"><span>'.$this->getValue().'</span></td></tr>';
					break;
										
					
					case 'profile_pic':
					echo '<tr><td class="fields fields2">'.$this->addLabel();
					$imgPath = (trim($this->getValue($this->type, $this->slug)) != "")? $this->getValue($this->type, $this->slug) : plugins_url("../images/userImage.png",__FILE__);
					echo '</td><td class="fields"><img src="'.$imgPath.'" style="max-width:150px;" /></td></tr>';
					break;
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
					case "default":
					echo '<tr><td class="fields fields2">'.$this->addLabel();
					echo '</td><td class="fields"><span>'.$this->getValue($this->type, $this->slug).'</span></td></tr>';
					break;	
									
				endswitch; 
			 }
           echo '</table>';
	
        }
    }
	function getValue()
	{
		$value = get_usermeta($this->user_id, $this->slug);
		if($this->type=="date")
		{
			if(is_array($value['date']))
			{
				if($this->field['date_format']=="datefield" || $this->field['date_format']=="datedropdown" )
				{
					$val = $this->field['date_format'];
					$val = str_replace("mm",$value['date']['mm'],$val);
					$val = str_replace("dd",$value['date']['dd'],$val);
					$val = str_replace("yy",$value['date']['yy'],$val);
					return 	$val;
				}
				else
				{
					return implode($this->field['date_format'][2],$value['date']);			
				}
				
				
			}
			return $value;			
		}
		else if($this->type=="time")
		{
			if(is_array($value))
			return implode(" : ",$value);	
			return $value;
		}
		else if($this->type=="list")
		{
			if(!is_array($value))
			return $value;			
			$list = "";
			
			for($a = 0 ; $a < sizeof($value) ; $a++)
			{
				$row  = "";
				for($b = 0 ; $b < sizeof($value[$a]) ; $b++)
				{
					$row 	.= $value[$a][$b];	
					$list 	.= $value[$a][$b]." ";
				}
				if(!empty($row))
				$list .= "<br />";
			}
			
			$value = $list ;	
		}
		else if($this->type=="multiselect")
		{
			$list = "<ol>";
			for($a = 0 ; $a < sizeof($value) ; $a++ )
			{
				$list .= "<li>".$value[$a]."</li>";	
			}	
			$list .= "</ol>";
			$value = $list;	
		}
		else if(is_array($value))
		{
			return implode(", ",$value);	
		}
		return $value;	
	}	
}