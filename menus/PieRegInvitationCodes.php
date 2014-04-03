<?php
//$piereg['invitation_code_usage']

if( $_POST['notice'] ){
	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '.</strong></p></div>';
}
?>
<script type="text/javascript">
function confirmDel(id)
{
	var conf = window.confirm("Are you sure?");
	if(conf)
	{
		document.getElementById("invi_del_id").value = id;
		document.getElementById("del_form").submit();
	}
}
function changeStatus(id)
{
	document.getElementById("status_id").value = id;
	document.getElementById("status_form").submit();	
}
</script>
<form method="post" action="" id="del_form">
  <input type="hidden" id="invi_del_id" name="invi_del_id" value="0" />
</form>
<form method="post" action="" id="status_form">
  <input type="hidden" id="status_id" name="status_id" value="0" />
</form>
<div id="container">
  <div class="right_section">
    <div class="invitation">
      <h2><?php  _e("Invitation Codes",'piereg'); ?></h2>
      <form method="post" action="">
        <ul>
          <li>
            <div class="fields">
              <h2>Guideline</h2>
              <p><?php  _e("Protect your privacy. If you want your blog to be exclusive, enable Invitation Codes and keep track of your users.",'piereg'); ?></p>
            </div>
          </li>
          <li>
            <div class="fields">
              <label><?php _e("Enable Invitation Codes","piereg");?></label>
              <div class="radio_fields">
                <input id="enable_invitation_codes_yes" type="radio" value="1" name="enable_invitation_codes" <?php echo ($piereg['enable_invitation_codes']=="1")?'checked="checked"':''?> />
                <label for="enable_invitation_codes_yes"><?php _e("Yes","piereg");?></label>
                <input id="enable_invitation_codes_no" type="radio" value="0" name="enable_invitation_codes" <?php echo ($piereg['enable_invitation_codes']=="0")?'checked="checked"':''?> />
                <label for="enable_invitation_codes_no"><?php _e("No","piereg");?></label>
              </div>
              <span class="quotation"><?php _e("Set this to Yes if you want users to register only by your defined invitaion codes. You will have to add invitation code field in the form editor.","piereg");?></span> </div>
          </li>
          <li>
            <div class="fields">
              <h3><?php _e("Insert Code","piereg");?></h3>
              <textarea id="piereg_codepass" name="piereg_codepass"></textarea>
              <span class="note"><strong><?php _e("Note","piereg");?>:</strong> <?php _e("Each Code will be on a Separate Line.","piereg");?></span> </div>
          </li>
          <li>
            <div class="fields">
              <h3><?php _e("Usage","piereg");?></h3>
              <input style="float:left;" value=""  type="text" name="invitation_code_usage" class="input_fields2" />
               <span style="float:left;clear:both;" class="note"><?php _e("Number of time a particular code can be used for registration.","piereg");?></span> 
            </div>
          </li>
          <li>
            <p class="submit">
              <input name="Submit" style="background: #464646;color: #ffffff;border: 0;cursor: pointer;padding: 5px 0px 5px 0px;margin-top: 15px;
min-width: 113px;float:right;" value="<?php _e('Add Code','piereg');?>" type="submit" />
            </p>
          </li>
        </ul>
      </form>
<style type="text/css">
.widefat th, .widefat th a{ color:#fefefe !important;font-weight:normal !important; text-shadow:none !important;}
.widefat th{background:#64727C !important;}
</style>
      <?php
	  include_once(dirname(__FILE__)."/invitaion_code_pagination.php");
	  new B5F_WP_Table();
	  ?>
      
    </div>
  </div>
</div>
