<div class="piereg_entry-content entry-content">
<div id="piereg_login">

<?php 
  //If Registration contanis errors
global $wp_session,$errors;

			if(isset($errors->errors['login-error'][0]) > 0)
			{
				$message = __($errors->errors['login-error'][0],"piereg");						  	
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
						$success = "Your account is now active";	
					}
					else
					{
						 $message = __("Invalid activation key","piereg");	
					}
				}		
				
				 
			}
        }
		if(trim($wp_session['message']) != "" )
		{
			echo '<p class="piereg_login_error"> ' . apply_filters('piereg_messages',__($wp_session['message'],"piereg")) . "</p>";
			$wp_session['message'] = "";
		}
		if ( !empty($message) )
			echo '<p class="piereg_login_error"> ' . apply_filters('piereg_messages', $message) . "</p>\n";
		if ( !empty($success) )
			echo '<p class="piereg_message">' . apply_filters('piereg_messages',$success) . "</p>\n";
		
		if($_POST['success'] != "")
			echo '<p class="piereg_message">'.apply_filters('piereg_messages',__($_POST['success'],"piereg")).'</p>';
		if($_POST['error'] != "")
			echo '<p class="piereg_login_error">'.apply_filters('piereg_messages',__($_POST['error'],"piereg")).'</p>';	
		
   ?>


  <form method="post" action="" id="piereg_loginform" name="loginform">
    <p>
      <label for="user_login"><?php _e("Username","piereg");?> </label>
      <input placeholder="Username" type="text" size="20" value="" class="input validate[required]" id="user_login" name="log">
    </p>
    <p>
      <label for="user_pass"><?php _e("Password","piereg");?> </label>
      <input placeholder="Password" type="password" size="20" value="" class="input validate[required]" id="user_pass" name="pwd">
    </p>
   <?php if(!is_page()) { ?>
    <p class="forgetmenot">
      <label for="rememberme">
        <input type="checkbox" value="forever" id="rememberme" name="rememberme">
        <?php _e("Remember Me","piereg");?></label>
    </p>
    <?php } ?>
    <p class="submit">
      <input type="submit" value="Log In" class="button button-primary button-large" id="wp-submit" name="wp-submit">
      <input type="hidden" value="<?php echo  admin_url()?>" name="redirect_to">
      <input type="hidden" value="1" name="testcookie">
    </p>
   <?php if(!is_page() ) { ?>
    <p id="nav"> <a href="<?php echo wp_registration_url();?>"><?php _e("Register","piereg") ?></a> <a style="cursor:default;text-decoration:none;" href="javascript:;"> | </a> <a title="Password Lost and Found" href="<?php echo wp_lostpassword_url(site_url());?>">Lost your password?</a> </p>
    <?php } ?>
    
    <?php if($pagenow == 'wp-login.php'  ) { ?>
    <p id="backtoblog"><a title="Are you lost?" href="<?php bloginfo("url"); ?>">&larr;<?php echo __(" Back to ".get_bloginfo("name"),"piereg"); ?></a></p>
    <?php } ?>
    
  </form>
  <?php
  
  do_action("check_enable_social_site_method");
  ?>
</div>
</div>