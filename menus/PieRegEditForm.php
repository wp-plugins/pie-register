<?php $button = get_option('piereg'); 
   $meta   = $this->getDefaultMeta();
?>
<script type="text/javascript" src="<?php echo plugins_url();?>/pie-register/js/phpjs.js"></script>
<script type="text/javascript" src="<?php echo plugins_url();?>/pie-register/js/drag.js"></script>
<script type="text/javascript" src="<?php echo plugins_url();?>/pie-register/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
var hintNum = 0;
jQuery(document).ready(function(e) {
   
    var displayhints = "<?php echo $button['display_hints']?>";
	
	if(displayhints=="1")
	{
		jQuery("#hint_"+hintNum).delay(500).fadeIn();	
		jQuery(".thanks").click(function() 
		{
			jQuery(this).parents(".fields_hint").delay(100).fadeOut();			
			hintNum++;
			jQuery("#hint_"+hintNum).delay(500).fadeIn();
			
		});	
	}
	else
	{
		jQuery(".fields_hint").remove();	
	}
});
var defaultMeta = Array();
<?php
foreach($meta as $key=>$value)
{
?>
	defaultMeta["<?php echo $key?>"] = '<?php echo mysql_escape_string($value)?>';	
<?php 
}
?>


</script>

<div class="right_section">
  <div class="pie_wrap">
    <h2>
      <?php _e("Form Editor : Registration Form",'piereg');?>
    </h2>
    <form method="post" id="formeditor">
      <input type="hidden" name="field[form][type]" value="form">
      <input type="hidden" name="field[form][meta]" value="0">
      <input type="submit" style="float: right;margin-top: 45px;margin-right: 85px;position:absolute;" class="button button-primary button-large" name="pie_form"  value="<?php _e("Save Changes",'piereg');?>">
      
        <a href="<?php echo get_bloginfo("url")?>?pr_preview=1" target="_blank" class="button button-primary button-large" name="pie_form"><?php _e("Preview",'piereg');?></a>
      <!--Form Settings-->
      
      <ul>
        <li class="fields">
          <div class="fields_options" id="field_form_title"> <a href="#" class="edit_btn"></a>
            <label>
              <?php echo $data['form']['label']?>
            </label>
            <br>
            <p id="paragraph_form">
              <?php echo stripslashes($data['form']['desc'])?>
            </p>
          </div>
          <div class="fields_main">
            <div class="advance_options_fields">
              <div class="advance_fields">
                <label>Label</label>
                <input id="form_title" value="<?php echo $data['form']['label']?>" type="text" name="field[form][label]" class="input_fields field_label">
              </div>
              <div class="advance_fields">
                <label for="form_desc">Description</label>
                <textarea onkeyup="changeParaText('form');" name="field[form][desc]" id="paragraph_textarea_form" rows="8" cols="16"><?php echo $data['form']['desc']?>
</textarea>
              </div>
              <div class="advance_fields">
                <label>CSS Class Name</label>
                <input type="text" name="field[form][css]" value="<?php echo $data['form']['css']?>" class="input_fields">
              </div>
              <div class="advance_fields">
                <label>Label Alignment</label>
                <select class="swap_class" onchange="swapClass(this.value);" name="field[form][label_alignment]">
                  <option <?php if($data['form']['label_alignment']=='top') echo 'selected="selected"';?> value="top">Top</option>
                  <option <?php if($data['form']['label_alignment']=='left') echo 'selected="selected"';?> value="left">Left</option>
                </select>
              </div>
              
            </div>
          </div>
        </li>
      </ul>
      <!--Form Settings End-->
      <fieldset>
        <legend align="center"><?php echo _e("Drag Fields Here","piereg"); ?></legend>
        
      
       
       
        <div id="hint_1" class="fields_hint" style="right: -266px;top: 50%;"> <img src="../wp-content/plugins/pie-register/images/left_arrow.jpg" width="45" height="26" align="left">
          <div class="hint_content">
            <h4>Did You Know ?</h4>
            <span>You can sort fields vertically</span> <br>
            <input type="button" class="thanks" value="Yes Thanks !">
          </div>
        </div>
        
      
        
        <!--Form Fields-->
        <ul id="elements">
          <?php   
		 
		if(sizeof($data) >  0) 
		{
			$no = max(array_keys($data));			
			$field_values = array();
			$meta   = $this->getDefaultMeta();
			
			foreach($data as $field)
			{				
			
				//We don't need Form and Submit Button in sorting
				if($field['type']=="submit" || $field['type']=="" || $field['type']=="form" || ($field['type']=="invitation" && $button["enable_invitation_codes"]=="0"))
				{
					continue;
				}
					
					?>
          <li class="fields">
            <div id="holder_<?php echo $field['id']?>" class="fields_options fields_optionsbg">
            <?php
        
		if($field['type'] == "url" || $field['type'] == "aim" || $field['type'] == "yim" || $field['type'] == "jabber" || $field['type'] == "description") 
		{
			$field['type'] = "default";	
		}
		
		
		 //We can't edit default wordpress fields
		  if($field['type']!="default")
          { 
          		echo '<a href="javascript:;" class="edit_btn"></a>';
          } 
		  ?>
            <!--Adding Label-->
            <div class="label_position"  id="field_label_<?php echo $field['id']?>">
              <label><?php echo (empty($field['label']) ? ucfirst($field['type']):trim($field['label']))?></label>
            </div>
            <?php
           //We can't remove Username, password and email fields
		    if(!isset($field['remove']))					
				  	echo '<a href="javascript:;" rel="'.$field['id'].'" class="delete_btn">X</a>';                
			  	else
			  		echo '<input  name="field['.$field['id'].'][remove]" value="0" type="hidden" /> '; 
            ?>
            <input type="hidden" name="field[<?php echo $field['id']?>][id]" value="<?php echo $field['id']?>" id="id_<?php echo $field['id']?>">
            <input type="hidden" name="field[<?php echo $field['id']?>][type]" id="type_<?php echo $field['id']?>" value="<?php echo $field['type']?>" >
            <div class="fields_position" id="field_position_<?php echo $field['id']?>">
              <?php
					
										
					
					switch($field['type']) :
					case 'text' :
					case 'username' :
					case 'website' :
					case 'hidden' :
					case 'phone':
					$this->addTextField($field,$field['id']);
					break;
					case 'invitation' :					
					$this->addInvitationField($field,$field['id']);					
					break;
					case 'password' :
					$this->addPassword($field,$field['id']);
					break;
					case 'email' :
					$this->addEmail($field,$field['id']);
					break;
					case 'textarea':
					$this->addTextArea($field,$field['id']);
					break;
					case 'dropdown':
					case 'multiselect':
					$this->addDropdown($field,$field['id']);
					break;
					case 'number':
					$this->addNumberField($field,$field['id']);			
					break;
					case 'radio':
					case 'checkbox':
					$this->addCheckRadio($field,$field['id']);
					break;
					case 'html':
					$this->addHTML($field,$field['id']);
					break;
					case 'name':
					$this->addName($field,$field['id']);
					break;
					case 'time':
					$this->addTime($field,$field['id']);
					break;
					case 'upload':
					$this->addUpload($field,$field['id']);
					break;
					case 'address':
					$this->addAddress($field,$field['id']);
					break;
					case 'captcha':
					$this->addCaptcha($field,$field['id']);
					break;					
					case 'date':
					$this->addDate($field,$field['id']);
					break;
					case 'list':
					$this->addList($field,$field['id']);
					break;
					case 'sectionbreak':
					$this->addSectionBreak($field,$field['id']);
					break;
					case 'pagebreak':
					$this->addPageBreak($field,$field['id']);
					break;
					case 'default':
					$this->addDefaultField($field,$field['id']);
					break;
				endswitch;				
					
				$field_values[$field['id']] = serialize($this->cleantext($field,$field['id']));
				
				  echo "</div>";
				 			  
			 ?>
            </div>
            <?php 
			
			echo str_replace("%d%",$field['id'],$meta[$field['type']]);
		 
		  		
		  ?> </li>
          <?php 	
				
			}	
		}
		
		?>
          <script type="text/javascript"><?php
		foreach($field_values as $key=>$value)
		{
		?>
       		fillValues('<?php echo $value?>',<?php echo $key?>);
        <?php 
		}
		?>no = "<?php echo ($no + 1)?>";</script>
          <?php
	?>
        </ul>
      </fieldset>
      <ul id="submit_ul">
        <li class="fields">
          <div class="fields_options submit_field"> <a href="#" class="edit_btn"></a>
            <input id="reset_btn" disabled="disabled" name="fields[reset]" type="reset" class="submit_btn" value="<?php echo $data['submit']['reset_text']?>" />
            
            <input disabled="disabled" name="fields[submit]" type="submit" class="submit_btn" value="<?php echo $data['submit']['text']?>" />
            <input  name="field[submit][label]" value="Submit"  type="hidden" />
            <input  name="field[submit][type]" value="submit" type="hidden" />
            <input  name="field[submit][remove]" value="0" type="hidden" />
            <input  name="field[submit][meta]" value="0" type="hidden">
          </div>
          <div class="fields_main">
            <div class="advance_options_fields advance_options_submit">
              <div class="advance_fields">
                <label>Submit Button Text</label>
                <input type="text" class="input_fields" name="field[submit][text]" value="<?php echo $data['submit']['text']?>">
              </div>
              
              <div class="advance_fields">
                <label>Show Reset Button</label>
                <select onchange="showHideReset();" id="show_reset" class="swap_reset" name="field[submit][reset]">
                  <option <?php if($data['submit']['reset']=='0') echo 'selected="selected"';?> value="0">No</option>
                  <option <?php if($data['submit']['reset']=='1') echo 'selected="selected"';?> value="1">Yes</option>
                </select>
              </div>
              
              
               <div class="advance_fields">
                <label>Reset Button Text</label>
                <input type="text" class="input_fields" name="field[submit][reset_text]" value="<?php echo $data['submit']['reset_text']?>">
              </div>
              <div class="advance_fields">
                <label>Confirmation Message</label>
                <div class="radio_fields">
                  <input class="reg_success" type="radio" value="text" name="field[submit][confirmation]" <?php if($data['submit']['confirmation']=='text') echo 'checked="checked"';?>>
                  <label>Text</label>
                  <input class="reg_success" type="radio" value="page" name="field[submit][confirmation]" <?php if($data['submit']['confirmation']=='page') echo 'checked="checked"';?>>
                  <label>Page</label>
                  <input class="reg_success" type="radio" value="redirect" name="field[submit][confirmation]" <?php if($data['submit']['confirmation']=='redirect') echo 'checked="checked"';?>>
                  <label>Redirect</label>
                </div>
              </div>
              <div class="advance_fields submit_meta submit_meta_redirect">
                <label>Redirect URL</label>
                <input type="text" class="input_fields" name="field[submit][redirect_url]" value="<?php echo $data['submit']['redirect_url']?>">
              </div>
              <div class="advance_fields submit_meta submit_meta_page">
                <label>Select Page</label>
                <?php  $args =  array("name"=>"field[submit][page]","selected"=>$data['submit']['page']);         wp_dropdown_pages( $args ); ?>
              </div>
              <div class="advance_fields submit_meta submit_meta_text">
                <label>Registration Success Message</label>
                <textarea name="field[submit][message]" rows="8" cols="16"><?php echo $data['submit']['message']?>
</textarea>
              </div>
            </div>
          </div>
        </li>
      </ul>
      <?php
	 
		if(!(empty($button['paypal_butt_id'])) && $button['enable_paypal']==1)
	 	{
			?>
      <script type="text/javascript">jQuery("#submit_ul").hide();</script>
      <ul id="paypal_button">
        <li class="fields">
          <div class="fields_options submit_field"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" /></div>
          <!--<input  name="field[submit][type]" value="paypal" type="hidden" />--> 
        </li>
      </ul>
      <?php 
		}
	  ?>
      <input type="submit" style="float: right;margin-right:85px;" class="button button-primary button-large" name="pie_form"  value="Save Changes">
    </form>
  </div>
  <div class="right_menu">
   
         <div id="hint_0" style="top: 135px;margin-left: -271px;position: fixed;float:right;" class="fields_hint"> <img src="../wp-content/plugins/pie-register/images/right_arrow.jpg" width="45" height="26" align="right">
          <div class="hint_content">
            <h4>Did You Know ?</h4>
            <span>You can Drag n Drop fields.</span> <br>
            <input type="button" class="thanks" value="Yes Thanks !">
          </div>
        </div> 
    <ul>
      <li id="default_fields"><a class="right_menu_heading" href="javascript:;">Default Fields</a>
        <ul class="controls picker">
          <li class="standard_name"><a name="name" class="default" href="javascript:;">Name</a></li>
          <li class="standard_website"><a name="url" class="default" href="javascript:;">Website</a></li>
          <li class="standard_aim"><a name="aim" class="default" href="javascript:;">AIM</a></li>
          <li class="standard_yahoo"><a name="yim" class="default" href="javascript:;">Yahoo IM</a></li>
          <li class="standard_google"><a name="jabber" class="default" href="javascript:;">Jabber / Google Talk</a></li>
          <li class="standard_about"><a name="description" class="default" href="javascript:;">About Yourself</a></li>
        </ul>
      </li>
      <li id="standard_fields"><a class="right_menu_heading" href="javascript:;">Standard Fields</a>
        <ul class="controls picker">
          <li class="standard_text"><a name="text" href="javascript:;">Text Field</a></li>
          <li class="standard_textarea"><a name="textarea" href="javascript:;">Text Area</a></li>
          <li class="standard_dropdown"><a name="dropdown" href="javascript:;">Drop Down</a></li>
          <li class="standard_multiselect"><a name="multiselect" href="javascript:;">Multi Select</a></li>
          <li class="standard_numbers"><a name="number" href="javascript:;">Numbers</a></li>
          <li class="standard_checkbox"><a name="checkbox" href="javascript:;">Checkbox</a></li>
          <li class="standard_radio"><a name="radio" href="javascript:;">Radio Buttons</a></li>
          <li class="standard_hidden"><a name="hidden" href="javascript:;">Hidden</a></li>
          <li class="standard_html"><a name="html" href="javascript:;">HTML</a></li>
          <li class="standard_selection"><a name="sectionbreak" href="javascript:;">Section Break</a></li>
          <li class="standard_pagebreak"><a name="pagebreak" href="javascript:;">Page Break</a></li>
        </ul>
      </li>
      <li id="advanced_fields"><a class="right_menu_heading" href="javascript:;">Advanced Fields</a>
        <ul class="controls picker">
          <li class="standard_time"><a name="time" href="javascript:;">Time</a></li>
          <li class="standard_address"><a name="address" class="default" href="javascript:;">Address</a></li>
          <li class="standard_captcha"><a name="captcha" class="default" href="javascript:;">Captcha</a></li>
          <li class="standard_date"><a name="date" href="javascript:;">Date</a></li>
          <li class="standard_phone"><a name="phone" href="javascript:;">Phone</a></li>
          <li class="standard_upload"><a name="upload" href="javascript:;">Upload</a></li>
          <li class="standard_list"><a name="list" href="javascript:;">List</a></li>
          <?php if($button['enable_invitation_codes']==1) { ?> 
          <li class="standard_invitation"><a name="invitation" class="default" href="javascript:;">Invitation Code</a></li>
          <?php } ?>
        </ul>
      </li>
    </ul>
  </div>
</div>
