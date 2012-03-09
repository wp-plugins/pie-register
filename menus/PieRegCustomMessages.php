<?php
$piereg = get_option( 'pie_register' );

if( $_POST['notice'] ){
	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '.</strong></p></div>';
}
?>
<h2><?php _e('Payment Gateway Settings', 'piereg');?></h2>
<div id="pie-register">
<form method="post" action="" enctype="multipart/form-data">
	<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg-update-options'); ?>
	<p class="submit"><input name="Submit" value="<?php _e('Save Changes','piereg');?>" type="submit" /></p>
	<div class="label"><?php _e('Edit Message # 1', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_1" id="_admin_message_1" style="width:500px;" value="<?php echo $piereg['_admin_message_1'];?>" /></div>
<div class="label"><?php _e('Edit Message # 2', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_2" id="_admin_message_2" style="width:500px;" value="<?php echo $piereg['_admin_message_2'];?>" /></div>
<div class="label"><?php _e('Edit Message # 3', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_3" id="_admin_message_3" style="width:500px;" value="<?php echo $piereg['_admin_message_3'];?>" /></div>
<div class="label"><?php _e('Edit Message # 4', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_4" id="_admin_message_4" style="width:500px;" value="<?php echo $piereg['_admin_message_4'];?>" /></div>
<div class="label"><?php _e('Edit Message # 5', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_5" id="_admin_message_5" style="width:500px;" value="<?php echo $piereg['_admin_message_5'];?>" /></div>
<div class="label"><?php _e('Edit Message # 6', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_6" id="_admin_message_6" style="width:500px;" value="<?php echo $piereg['_admin_message_6'];?>" /></div>
<div class="label"><?php _e('Edit Message # 7', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_7" id="_admin_message_7" style="width:500px;" value="<?php echo $piereg['_admin_message_7'];?>" /></div>
<div class="label"><?php _e('Edit Message # 8', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_8" id="_admin_message_8" style="width:500px;" value="<?php echo $piereg['_admin_message_8'];?>" /></div>
<div class="label"><?php _e('Edit Message # 9', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_9" id="_admin_message_9" style="width:500px;" value="<?php echo $piereg['_admin_message_9'];?>" /></div>
<div class="label"><?php _e('Edit Message # 10', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_10" id="_admin_message_10" style="width:500px;" value="<?php echo $piereg['_admin_message_10'];?>" /></div>
<div class="label"><?php _e('Edit Message # 12', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_12" id="_admin_message_12" style="width:500px;" value="<?php echo $piereg['_admin_message_12'];?>" /></div>
<div class="label"><?php _e('Edit Message # 13', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_13" id="_admin_message_13" style="width:500px;" value="<?php echo $piereg['_admin_message_13'];?>" /></div>
<div class="label"><?php _e('Edit Message # 14', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_14" id="_admin_message_14" style="width:500px;" value="<?php echo $piereg['_admin_message_14'];?>" /></div>
<div class="label"><?php _e('Edit Message # 15', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_15" id="_admin_message_15" style="width:500px;" value="<?php echo $piereg['_admin_message_15'];?>" /></div>
<div class="label"><?php _e('Edit Message # 16', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_16" id="_admin_message_16" style="width:500px;" value="<?php echo $piereg['_admin_message_16'];?>" /></div>
<div class="label"><?php _e('Edit Message # 17', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_17" id="_admin_message_17" style="width:500px;" value="<?php echo $piereg['_admin_message_17'];?>" /></div>
<div class="label"><?php _e('Edit Message # 18', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_18" id="_admin_message_18" style="width:500px;" value="<?php echo $piereg['_admin_message_18'];?>" /></div>
<div class="label"><?php _e('Edit Message # 19', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_19" id="_admin_message_19" style="width:500px;" value="<?php echo $piereg['_admin_message_19'];?>" /></div>
<div class="label"><?php _e('Edit Message # 20', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_20" id="_admin_message_20" style="width:500px;" value="<?php echo $piereg['_admin_message_20'];?>" /></div>
<div class="label"><?php _e('Edit Message # 21', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_21" id="_admin_message_21" style="width:500px;" value="<?php echo $piereg['_admin_message_21'];?>" /></div>
<div class="label"><?php _e('Edit Message # 22', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_22" id="_admin_message_22" style="width:500px;" value="<?php echo $piereg['_admin_message_22'];?>" /></div>
<div class="label"><?php _e('Edit Message # 23', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_23" id="_admin_message_23" style="width:500px;" value="<?php echo $piereg['_admin_message_23'];?>" /></div>
<div class="label"><?php _e('Edit Message # 24', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_24" id="_admin_message_24" style="width:500px;" value="<?php echo $piereg['_admin_message_24'];?>" /></div>
<div class="label"><?php _e('Edit Message # 25', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_25" id="_admin_message_25" style="width:500px;" value="<?php echo $piereg['_admin_message_25'];?>" /></div>
<div class="label"><?php _e('Edit Message # 26', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_26" id="_admin_message_26" style="width:500px;" value="<?php echo $piereg['_admin_message_26'];?>" /></div>
<div class="label"><?php _e('Edit Message # 27', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_27" id="_admin_message_27" style="width:500px;" value="<?php echo $piereg['_admin_message_27'];?>" /></div>
<div class="label"><?php _e('Edit Message # 28', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_28" id="_admin_message_28" style="width:500px;" value="<?php echo $piereg['_admin_message_28'];?>" /></div>
<div class="label"><?php _e('Edit Message # 29', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_29" id="_admin_message_29" style="width:500px;" value="<?php echo $piereg['_admin_message_29'];?>" /></div>
<div class="label"><?php _e('Edit Message # 30', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_30" id="_admin_message_30" style="width:500px;" value="<?php echo $piereg['_admin_message_30'];?>" /></div>
<div class="label"><?php _e('Edit Message # 31', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_31" id="_admin_message_31" style="width:500px;" value="<?php echo $piereg['_admin_message_31'];?>" /></div>
<div class="label"><?php _e('Edit Message # 32', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_32" id="_admin_message_32" style="width:500px;" value="<?php echo $piereg['_admin_message_32'];?>" /></div>
<div class="label"><?php _e('Edit Message # 33', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_33" id="_admin_message_33" style="width:500px;" value="<?php echo $piereg['_admin_message_33'];?>" /></div>
<div class="label"><?php _e('Edit Message # 34', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_34" id="_admin_message_34" style="width:500px;" value="<?php echo $piereg['_admin_message_34'];?>" /></div>
<div class="label"><?php _e('Edit Message # 35', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_35" id="_admin_message_35" style="width:500px;" value="<?php echo $piereg['_admin_message_35'];?>" /></div>
<div class="label"><?php _e('Edit Message # 36', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_36" id="_admin_message_36" style="width:500px;" value="<?php echo $piereg['_admin_message_36'];?>" /></div>
<div class="label"><?php _e('Edit Message # 37', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_37" id="_admin_message_37" style="width:500px;" value="<?php echo $piereg['_admin_message_37'];?>" /></div>
<div class="label"><?php _e('Edit Message # 38', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_38" id="_admin_message_38" style="width:500px;" value="<?php echo $piereg['_admin_message_38'];?>" /></div>
<div class="label"><?php _e('Edit Message # 39', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_39" id="_admin_message_39" style="width:500px;" value="<?php echo $piereg['_admin_message_39'];?>" /></div>
<div class="label"><?php _e('Edit Message # 40', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_40" id="_admin_message_40" style="width:500px;" value="<?php echo $piereg['_admin_message_40'];?>" /></div>
<div class="label"><?php _e('Edit Message # 41', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_41" id="_admin_message_41" style="width:500px;" value="<?php echo $piereg['_admin_message_41'];?>" /></div>
<div class="label"><?php _e('Edit Message # 42', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_42" id="_admin_message_42" style="width:500px;" value="<?php echo $piereg['_admin_message_42'];?>" /></div>
<div class="label"><?php _e('Edit Message # 43', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_43" id="_admin_message_43" style="width:500px;" value="<?php echo $piereg['_admin_message_43'];?>" /></div>
<div class="label"><?php _e('Edit Message # 44', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_44" id="_admin_message_44" style="width:500px;" value="<?php echo $piereg['_admin_message_44'];?>" /></div> 
<div class="label"><?php _e('Edit Message # 45', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_45" id="_admin_message_45" style="width:500px;" value="<?php echo $piereg['_admin_message_45'];?>" /></div>
<div class="label"><?php _e('Edit Message # 46', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_46" id="_admin_message_46" style="width:500px;" value="<?php echo $piereg['_admin_message_46'];?>" /></div>
<div class="label"><?php _e('Edit Message # 47', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_47" id="_admin_message_47" style="width:500px;" value="<?php echo $piereg['_admin_message_47'];?>" /></div>
<div class="label"><?php _e('Edit Message # 48', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_48" id="_admin_message_48" style="width:500px;" value="<?php echo $piereg['_admin_message_48'];?>" /></div> 
<div class="label"><?php _e('Edit Message # 49', 'piereg');?></div><div class="input"><input type="text" name="piereg__admin_message_49" id="_admin_message_49" style="width:500px;" value="<?php echo $piereg['_admin_message_49'];?>" /></div>
	
	<input name="action" value="pie_reg_update" type="hidden" />
	<input type="hidden" name="customised_messages_page" value="1" />
	<p class="submit"><input name="Submit" value="<?php _e('Save Changes','piereg');?>" type="submit" /></p>
</form>
</div>
