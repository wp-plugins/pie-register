<?php
$piereg 				= get_option( 'pie_register_2' );
$pie_user_email_types 	= get_option( 'pie_user_email_types' );

if( $_POST['notice'] ){
	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '.</strong></p></div>';
}
$replacement_fields = "";			   	
$fields = maybe_unserialize(get_option("pie_fields"));
if(sizeof($fields ) > 0)
{
	
	foreach($fields as $pie_fields)	
	{
		switch($pie_fields['type']) :
		case 'default' :
		case 'form' :					
		case 'submit' :
		case 'username' :
		case 'email' :
		case 'password' :
		case 'upload' :
		case 'profile_pic' :
		case 'name' :
		case 'pagebreak' :
		case 'sectionbreak' :
		case 'hidden' :					
		continue 2;
		break;
		endswitch;						
							
		$meta_key	= "pie_".$pie_fields['type']."_".$pie_fields['id'];
		
		$replacement_fields .= "&nbsp; %".$meta_key."% &nbsp;";
	}
}
?> 

<script type="text/javascript" src="<?php echo plugins_url("../ckeditor/ckeditor.js",__FILE__);?>"></script>
<script type="text/javascript">
jQuery(document).ready(function(e) {
	var types =  document.getElementsByName("user_email_type");
	
	for(a = 0 ; a < types.length ; a++ )
	{
		var val = document.getElementsByName("user_email_type")[a].value;
		jQuery("."+val).hide();
	}
	
	jQuery('input[name="user_email_type"]').click(function(e) {
		
		for(a = 0 ; a < types.length ; a++ )
		{
			var val = document.getElementsByName("user_email_type")[a].value;
			jQuery("."+val).hide();
		}
		
		var val = jQuery(this).val();
		jQuery("."+val).show();
	});
	jQuery('input[name="user_email_type"]').eq(0).trigger("click"); 
	<?php if(isset($_POST['user_email_type']))
	{
	?>jQuery('input[value="<?php echo $_POST['user_email_type']?>"]').eq(0).trigger("click"); <?php 
	} 
	?>
	
	   
});
</script>
<div id="container">
  <div class="right_section">
    <div class="notifications">
       <h2><?php _e("Notifications : Registration Form",'piereg') ?></h2>
      <form method="post" action="">
      <p class="submit">
          <input name="Submit" style="background: #464646;color: #ffffff;border: 0;cursor: pointer;padding: 5px 0px 5px 0px;margin-top: 15px;
min-width: 113px;float:right;" value="<?php _e('Save Changes','piereg');?>" type="submit" />
        </p>
        <ul>
          <li>
            <div class="fields">
               <h2><?php _e("Notifications to Users",'piereg') ?></h2>
              
              <p><?php _e("Enter a message below to receive a notification email when users submit this form.",'piereg') ?></p>        
              
            </div>
          </li>
          <li>
            <div class="fields">
              <label><?php _e("Messsage Type",'piereg') ?></label>
              <?php foreach ($pie_user_email_types as $val=>$type) { ?>
              <input id="user_email_type_<?php echo $val?>" name="user_email_type" value="<?php echo $val?>" type="radio" />
              <label style="float:none;" class="pie_msg_type" for="user_email_type_<?php echo $val?>"><?php echo $type?></label>
              &nbsp;&nbsp;
              <?php } ?>
            </div>
            <?php foreach ($pie_user_email_types as $val=>$type) { ?>
         <!-- <li class="<?php echo $val?>">
            <div class="fields">
              <label><?php _e("Send To Email*",'piereg') ?></label>
              <input name="user_sendto_email_<?php echo $val?>" value="<?php echo $piereg['user_sendto_email_'.$val]?>" type="text" class="input_fields" />
            </div>
          </li>-->
          <li class="<?php echo $val?>">
            <div class="fields">
              <label><?php _e("From Name",'piereg') ?></label>
              <input name="user_from_name_<?php echo $val?>" value="<?php echo $piereg['user_from_name_'.$val]?>" type="text" class="input_fields2" />
            </div>
          </li>
          <li class="<?php echo $val?>">
            <div class="fields">
              <label><?php _e("From Email",'piereg') ?></label>
              <input name="user_from_email_<?php echo $val?>" value="<?php echo $piereg['user_from_email_'.$val]?>" type="text" class="input_fields2" />
            </div>
          </li>
          <li class="<?php echo $val?>">
            <div class="fields">
              <label><?php _e("Reply To",'piereg') ?></label>
              <input name="user_to_email_<?php echo $val?>" value="<?php echo $piereg['user_to_email_'.$val]?>" type="text" class="input_fields2" />
            </div>
          </li>
         <!-- <li class="<?php echo $val?>">
            <div class="fields">
              <label><?php _e("BCC",'piereg') ?></label>
              <input  name="user_bcc_email_<?php echo $val?>" value="<?php echo $piereg['user_bcc_email_'.$val]?>" type="text" class="input_fields" />
            </div>
          </li>-->
          <li class="<?php echo $val?>">
            <div class="fields">
              <label><?php _e("Subject",'piereg') ?></label>
              <input name="user_subject_email_<?php echo $val?>" value="<?php echo $piereg['user_subject_email_'.$val]?>" type="text" class="input_fields" />
            </div>
          </li>
          <li class="<?php echo $val?>">
            <div class="fields">
              <label><?php _e("Message",'piereg') ?></label>    
              <p><strong><?php _e("Replacement Keys","piereg");?>:</strong> &nbsp; %user_login%  &nbsp; %user_pass% &nbsp; %user_email% &nbsp; %blogname% &nbsp; %siteurl%  &nbsp; %activationurl%  &nbsp; %firstname% &nbsp; %lastname%&nbsp; %forgot_pass_link%&nbsp; %user_url%&nbsp; %user_aim%&nbsp; %user_yim%&nbsp; %user_jabber%&nbsp; %user_biographical_nfo% &nbsp;  %all_field% &nbsp; %user_registration_date% %&nbsp; %reset_password_url%
               <?php echo $replacement_fields?>          
               </p>         
              <textarea name="user_message_email_<?php echo $val?>" class="ckeditor"><?php echo $piereg['user_message_email_'.$val]?></textarea>
              <div class="clear"></div>
            </div>
          </li>
          <?php } ?>
        </ul>
        <input name="action" value="pie_reg_update" type="hidden" />
        <input type="hidden" name="user_email_notification_page" value="1" />
        <p class="submit">
          <input name="Submit" style="background: #464646;color: #ffffff;border: 0;cursor: pointer;padding: 5px 0px 5px 0px;margin-top: 15px;
min-width: 113px;float:right;" value="<?php _e('Save Changes','piereg');?>" type="submit" />
        </p>
      </form>
      
<?php 
		$old_ver_options = get_option("pie_register");
		if($old_ver_options['adminvmsg'] != "" || $old_ver_options['emailvmsg'] != "" || $old_ver_options['msg'] )
		{
?>
            <div class="fields">
                <form method="post">
                    <label><?php _e("Click here to import version 1.x email template","piereg"); ?></label>                
                    <p class="submit"><input name="import_email_template_from_version_1" style="background: #464646;color: #ffffff;border: 0;cursor: pointer;padding: 5px;margin-top: 15px;" value=" <?php _e('Import email template','piereg');?> " type="submit" /></p>
                    <input type="hidden" name="old_version_emport" value="yes" />
                </form>
            </div>
<?php
		}
		unset($old_ver_options);
?>
      
      
    </div>
  </div>
</div>
