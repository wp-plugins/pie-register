<style type="text/css">
.pie_reg_comment{text-align:center;}
.field_note{font-size:12px; color:#FF0000;}
.required{color:#FF0000}
.piereg_entry-content{height:auto;margin: 10px auto;max-width: 793px;width: 100%;}
ul#pie_register {padding: 0;list-style: none;/*float: left;*/width: 90%;margin: 0 5%;}
.fields {width: 100%;padding: 0% 0% 0px 0%;float: left;font-family: arial;color: #262626;font-size: 14px;position: relative;margin-top: 9px;margin-bottom: 8px;}
.fields .fieldset {padding: 1%;float: left;width: 90%;margin-left: -4%;}
.fields label {cursor:pointer;font-size: 14px;color: #848484;width: 100%;text-transform: capitalize;line-height: normal;width: 29%;float: left;word-break: break-word;}
.fields .input_fields {border-radius: 3px;border: 1px solid #d5d5d5;height: 20px;padding: 0px 2% 0px 2%;margin-top: 0px;margin-bottom: 0;width: 60%;display: inline-block;color: #848484;}
.msg_div{margin: 0 0 0px 0px !important;padding: 12px 20px; !important;border-width: 1px !important;border-style: solid !important;color:#ffffff !important;border-radius: 8px !important;}
.piereg_login_error {background:#d22828 !important;}
.piereg_message {background:#6a9644;}
.piereg_warning {background: #e98237;}
li{list-style:none;}
</style>
<div style="max-width:600px; margin:0 auto;">

<?php 	
  //If Registration contanis errors
global $wp_session;

			if(isset($errors->errors['login-error'][0]) > 0)
			{
				$message = $errors->errors['login-error'][0];						  	
			}
			else if (! empty($_GET['action']) )
        	{
          
            if ( 'loggedout' == $_GET['action'] )
                $message = __("You are now logged out.","piereg");
            elseif ( 'recovered' == $_GET['action'] )
                $message = __("Check your e-mail for the confirmation link.","piereg");
			elseif ( 'payment_cancel' == $_GET['action'] )
                $message = __("You have canelled your registration.","piereg");
			elseif ( 'payment_success' == $_GET['action'] )
                $success = __("Thank you for your registration. You will receieve your login credentials soon.","piereg");		
			elseif ( 'activate' == $_GET['action'] )
			{
				$unverified = get_users(array('meta_key'=> 'hash','meta_value' => $_GET['activation_key']));
				
				if(sizeof($unverified )==1)
				{
					$user_id	= $unverified[0]->ID;
					$user_login = $unverified[0]->user_login; 	
					if($user_login == $_GET['id'])
					{
						update_user_meta( $user_id, 'active', 1);
						$hash = "";
						update_user_meta( $user_id, 'hash', $hash );
						$success = __("Your account is now active","piereg");	
					}
					else
					{
						 $message = __("Invalid activation key.","piereg");	
					}
				}		
				
				 
			}
        }

		if ( !empty($message) )
			echo '<p class="msg_div piereg_login_error"> ' . apply_filters('piereg_messages', $message) . "</p>\n";
		if ( !empty($success) )
			echo '<p class="msg_div message">' . apply_filters('piereg_messages',__($success,"piereg")) . "</p>\n";
		
		if($_POST['success'] != "")
			echo '<p class="msg_div message">'.apply_filters('piereg_messages',__($_POST['success'],"piereg")).'</p>';
		if($_POST['error'] != "")
			echo '<p class="msg_div piereg_login_error">'.apply_filters('piereg_messages',__($_POST['error'],"piereg")).'</p>';	

   ?>

    <h2><?php _e("Renew Account","piereg") ?></h2>
    <div style="text-align:right;"><span class="field_note">* <?php _e("Required Field(s)","piereg") ?></span></div>
    <div id="piereg_login">
      <form method="post" action="" id="piereg_loginform" name="loginform">
      	<ul id="pie_register">
            <li class="fields">
                <div class="fieldset">
                    <label for="x_card_num"><?php _e("User Name","piereg") ?></label>
                    <input required="true" type="text" class="input_fields" id="user_name" name="user_name" autocomplete="off" value="" aria-required="true" aria-invalid="false">
                    <span class="required">*</span>
                </div>
            </li>
            <li class="fields">
                <div class="fieldset">
                    <label for="x_card_num"><?php _e("Password","piereg") ?></label>
                    <input required="true" type="password" class="input_fields" id="u_pass" name="u_pass" autocomplete="off" value="" aria-required="true" aria-invalid="false">
                    <span class="required">*</span>
                </div>
            </li>
			<?php
			do_action("add_select_payment_script");
			?>
			<li class="fields">
				<div class="fieldset">
					<label for="x_card_num"><?php _e("Select Payment","piereg") ?></label>
					<select id="select_payment_method" name="select_payment_method">
						<option value=""><?php _e("Select","piereg") ?></option>
						<?php do_action('Add_payment_option'); ?>
					</select><span class="required">*</span>
				</div>
			</li>
			<?php do_action("get_payment_content_area");
            ?>
            <p class="submit">
              <input type="submit" value="<?php _e("Renew Account","piereg") ?>" class="button button-primary button-large" id="pie_renew" name="pie_renew">
            </p>
      	</ul>   
      </form>
    </div>
</div>