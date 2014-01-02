<?php
$piereg = get_option( 'pie_register' );


if( $_POST['notice'] ){
	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '.</strong></p></div>';
}

?>
<script type="text/javascript" src="<?=plugins_url();?>/pie-register/ckeditor/ckeditor.js"></script>

<div id="container">
  <div class="right_section">
    <div class="notifications">
      <h2><? _e("Notifications : Registration Form",'piereg');?></h2>
      <form method="post" action="">
        <ul>
          <li>
            <div class="fields">
              <h2><? _e("Notifications to Administrator",'piereg');?></h2>
              <input name="enable_admin_notifications" <?=($piereg['enable_admin_notifications']=="1")?'checked="checked"':''?> type="checkbox" class="checkbox" value="1" />
              <? _e("Enable email notification to administrators",'piereg');?>
              <p><? _e("Enter a message below to receive a notification email when users submit this form.",'piereg');?></p>
            </div>
          </li>
          <li>
            <div class="fields">
              <label><? _e("Send To Email*",'piereg');?></label>
              <input name="admin_sendto_email" value="<?=$piereg['admin_sendto_email']?>" type="text" class="input_fields" />
            </div>
          </li>
          <li>
            <div class="fields">
              <label><? _e("From Name",'piereg');?></label>
              <input name="admin_from_name" value="<?=$piereg['admin_from_name']?>" type="text" class="input_fields2" />
            </div>
          </li>
          <li>
            <div class="fields">
              <label><? _e("From Email",'piereg');?></label>
              <input name="admin_from_email" value="<?=$piereg['admin_from_email']?>" type="text" class="input_fields2" />
            </div>
          </li>
          <li>
            <div class="fields">
              <label><? _e("Reply To",'piereg');?></label>
              <input name="admin_to_email" value="<?=$piereg['admin_to_email']?>" type="text" class="input_fields2" />
            </div>
          </li>
          <li>
            <div class="fields">
              <label><? _e("BCC",'piereg');?></label>
              <input  name="admin_bcc_email" value="<?=$piereg['admin_bcc_email']?>" type="text" class="input_fields" />
            </div>
          </li>
          <li>
            <div class="fields">
              <label><? _e("Subject",'piereg');?></label>
              <input name="admin_subject_email" value="<?=$piereg['admin_subject_email']?>" type="text" class="input_fields" />
            </div>
          </li>
          <li>
            <div class="fields">
              <label><? _e("Message",'piereg');?></label>
            <p><strong>Replacement Keys:</strong> &nbsp; %user_login%  &nbsp; %user_pass% &nbsp; %user_email% &nbsp; %blogname% &nbsp; %siteurl%  &nbsp; %activationurl%  &nbsp; %firstname% &nbsp; %lastname%&nbsp; %user_url%&nbsp; %user_aim%&nbsp; %user_yim%&nbsp; %user_jabber%&nbsp; %user_biographical_nfo%  <? 
			   	
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
						case 'name' :
						case 'pagebreak' :
						case 'sectionbreak' :
						case 'hidden' :					
						continue 2;
						break;
						endswitch;						
											
						$meta_key	= "pie_".$pie_fields['type']."_".$pie_fields['id'];
						
						echo "&nbsp; %".$meta_key."% &nbsp;";
					}
				}
						
						
				?>
			   </p>
              <textarea name="admin_message_email" class="ckeditor"><?=$piereg['admin_message_email']?></textarea>
              <div class="clear"></div>
            </div>
          </li>
        </ul>
        <input name="action" value="pie_reg_update" type="hidden" />
        <input type="hidden" name="admin_email_notification_page" value="1" />
        <p class="submit"><input style="background: #464646;color: #ffffff;border: 0;cursor: pointer;padding: 5px 0px 5px 0px;margin-top: 15px;
min-width: 113px;" class="submit_btn" name="Submit" value="<?php _e('Save Changes','piereg');?>" type="submit" /></p>
      </form>
    </div>
  </div>
</div>
