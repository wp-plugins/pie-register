<?php

$piereg = get_option( 'pie_register' );

$piereg_custom = get_option( 'pie_register_custom' );

if( $_POST['notice'] ){

	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '.</strong></p></div>';

}

?>

<h2><?php _e('Payment Gateway Settings', 'piereg');?></h2>

<div id="pie-register">

<form method="post" action="" enctype="multipart/form-data">

	<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg-update-options'); ?>

	<p class="submit"><input name="Submit" value="<?php _e('Save Changes','piereg');?>" type="submit" /></p>

	<div class="label"><?php _e('Enter Your Paypal hosted button ID.', 'piereg');?></div>

    <div class="input"><input type="text" name="piereg_paypal_butt_id" id="paypal_butt_id" style="width:150px;" value="<?php echo $piereg['paypal_butt_id'];?>" /></div>

	<?php /*?><div class="label"><?php _e('Paypal PDT Token', 'piereg');?></div>

	<div class="input"><input type="text" name="piereg_paypal_pdt" id="paypal_pdt" style="width:300px;" value="<?php echo $piereg['paypal_pdt'];?>" /></div><?php */?>
    
    <div class="label"><?php _e('Paypal Mode', 'piereg');?></div>

    <div class="input">
    	<select name="piereg_paypal_sandbox" id="paypal_sandbox">
			<option value="no" <?php if($piereg['paypal_sandbox'] == "no") echo 'selected="selected"';?>>Live</option>
            <option value="yes" <?php if($piereg['paypal_sandbox'] == "yes") echo 'selected="selected"';?>>Sandbox</option>
        </select>
            
    </div>
<div style="width:1px;height:20px;"></div>
	<div class="infos">
    	<p><strong><?php _e('Please follow the steps below to create and set the required Options.', 'piereg');?></strong></p>
		<ol>
        	<li>Login to your <a href="https://www.paypal.com/">Paypal account</a>.</li>
            <li>Go to Merchant Services and Click on <a href="https://www.paypal.com/ae/cgi-bin/webscr?cmd=_web-tools">Buy Now</a> button.</li>
            <li>Give Your Button a Name. i.e: Website Access fee and Set Price.</li>
            <li>Click on Step3: Customize advance features (optional) Tab, select "Add advanced variables" checbox and add the following snippet:
            	<p><strong><?php _e('rm=2', 'piereg');?></strong></p>
				<p><strong><?php _e('notify_url='.wp_login_url().'?action=ipn', 'piereg');?></strong></p>
                <p><strong><?php _e('cancel_return='.wp_login_url().'?action=payment_cancel', 'piereg');?></strong></p>
                <p><strong><?php _e('return='.wp_login_url().'?action=payment_success', 'piereg');?></strong></p>
            </li>
            <li>Click Create button, On the next page, you will see the generated button code snippet like the following:
            <xmp style="cursor:text;overflow:scroll;width:500px;white-space:pre-line;">
            	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="XXXXXXXXXX">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>

            </xmp>
            </li>
            <li>Copy the snippet into any text editor and extract and put the hosted_button_id value (XXXXXXXXXX) into the Above Field.</li>
            <li>Save Changes, You're done!</li>
        </ul>
		
	</div>

	<input name="action" value="pie_reg_update" type="hidden" />

	<input type="hidden" name="payment_gateway_page" value="1" />

	<p class="submit"><input name="Submit" value="<?php _e('Save Changes','piereg');?>" type="submit" /></p>

</form>

</div>

