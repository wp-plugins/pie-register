<?php
$piereg = get_option( 'pie_register' );
$piereg_custom = get_option( 'pie_register_custom' );
if( $_POST['notice'] ){
	echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '.</strong></p></div>';
}
?>

<div id="container">
  <div class="right_section">
    <div class="settings">
      <h2>
        <?php _e('Payment Gateway Settings', 'piereg');?>
      </h2>
      <div id="pie-register">
        <form method="post" action="" enctype="multipart/form-data">
        <?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'piereg-update-options'); ?>
        <h3>Paypal Information</h3>
        <div class="fields">
          <label>Enable Paypal</label>
          <div class="radio_fields" style="margin-left:85px;">
            <input id="enable_paypal_yes" type="radio" value="1" name="enable_paypal" <?php echo ($piereg['enable_paypal']=="1")?'checked="checked"':''?> />
            <label for="enable_paypal_yes">Yes</label>
            <input id="enable_paypal_no" type="radio" value="0" name="enable_paypal" <?php echo ($piereg['enable_paypal']=="0")?'checked="checked"':''?> />
            <label for="enable_paypal_no">No</label>
          </div>
        </div>
        <div class="fields">
          <label>
            <?php _e('Enter Your Paypal hosted button ID.', 'piereg');?>
          </label>
          <input type="text" name="piereg_paypal_butt_id" class="input_fields" id="paypal_butt_id" style="width:184px; margin-left:20px;" value="<?php echo $piereg['paypal_butt_id'];?>" />
        </div>
        <?php /*?><div class="label"><?php _e('Paypal PDT Token', 'piereg');?></div>

	<div class="input"><input type="text" name="piereg_paypal_pdt" id="paypal_pdt" style="width:300px;" value="<?php echo $piereg['paypal_pdt'];?>" /></div><?php */?>
        <div class="fields">
          <label style="min-width:256px;">
            <?php _e('Paypal Mode', 'piereg');?>
          </label>
          <select name="piereg_paypal_sandbox" id="paypal_sandbox">
            <option value="no" <?php if($piereg['paypal_sandbox'] == "no") echo 'selected="selected"';?>>Live</option>
            <option value="yes" <?php if($piereg['paypal_sandbox'] == "yes") echo 'selected="selected"';?>>Sandbox</option>
          </select>
          <div class="fields">
            <input name="Submit" style="margin:0;" class="submit_btn" value="<?php _e('Save Changes','piereg');?>" type="submit" />
          </div>
        </div>
        <h3>Steps</h3>
        <div style="width:1px;height:20px;"></div>
        <div class="fields">
        <p><strong>
          <?php _e('Please follow the steps below to create and set the required Options.', 'piereg');?>
          </strong></p>
        <ol>
        <li>Login to your <a href="https://www.paypal.com/">Paypal account</a>.</li>
        <li>Go to Merchant Services and Click on <a href="https://www.paypal.com/ae/cgi-bin/webscr?cmd=_web-tools">Buy Now</a> button.</li>
        <li>Give Your Button a Name. i.e: Website Access fee and Set Price.</li>
        <li>Click on Step3: Customize advance features (optional) Tab, select "Add advanced variables" checbox and add the following snippet:
          <ul>
            <li><strong>
              <?php _e('rm=2', 'piereg');?>
              </strong></li>
            <li><strong>
              <?php _e('notify_url='.trailingslashit(get_bloginfo("url")).'?action=ipn_success', 'piereg');?>
              </strong></li>
            <li><strong>
              <?php _e('cancel_return='.trailingslashit(get_bloginfo("url")).'?action=payment_cancel', 'piereg');?>
              </strong></li>
            <li><strong>
              <?php _e('return='.trailingslashit(get_bloginfo("url")).'?action=payment_success', 'piereg');?>
              </strong></li>
          </ul>
        </li>
        <li>Click Create button, On the next page, you will see the generated button code snippet like the following:
          <xmp style="cursor:text;width:100%;white-space:pre-line; margin:0;">
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
        </ol>
        <input name="action" value="pie_reg_update" type="hidden" />
        <input type="hidden" name="payment_gateway_page" value="1" />
        <div class="fields">
          <input name="Submit" class="submit_btn" value="<?php _e('Save Changes','piereg');?>" type="submit" />
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
