<?		
  //If Registration contanis errors
			
			if(isset($errors->errors['login-error'][0]) > 0)
			{
				$message = $errors->errors['login-error'][0];						  	
			}
			else if (! empty($_GET['action']) )
        	{
          
            if ( 'loggedout' == $_GET['action'] )
                $message = "You are now logged out.";
            elseif ( 'recovered' == $_GET['action'] )
                $message = "Check your e-mail for the confirmation link.";
			elseif ( 'payment_cancel' == $_GET['action'] )
                $message = "You have canelled your registration.";
			elseif ( 'payment_success' == $_GET['action'] )
                $success = "Thank you for your registration. You will receieve your login credentials soon.";		
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
						 $message = "Invalid activation key.";	
					}
				}		
				
				 
			}
        }

		if ( !empty($message) )
			echo '<p class="login_error">' . apply_filters('login_messages', $message) . "</p>\n";
		if ( !empty($success) )
			echo '<p class="message">' . $success . "</p>\n";	
		
   ?>

<div id="login">
  <form method="post" action="" id="loginform" name="loginform">
    <p>
      <label for="user_login">Username </label>
      <input type="text" size="20" value="" class="input validate[required]" id="user_login" name="log">
    </p>
    <p>
      <label for="user_pass">Password </label>
      <input type="password" size="20" value="" class="input validate[required]" id="user_pass" name="pwd">
    </p>
   <? if(!is_page()) { ?>
    <p class="forgetmenot">
      <label for="rememberme">
        <input type="checkbox" value="forever" id="rememberme" name="rememberme">
        Remember Me</label>
    </p>
    <? } ?>
    <p class="submit">
      <input type="submit" value="Log In" class="button button-primary button-large" id="wp-submit" name="wp-submit">
      <input type="hidden" value="<?= admin_url()?>" name="redirect_to">
      <input type="hidden" value="1" name="testcookie">
    </p>
   <? if(!is_page()) { ?>
    <p id="nav"> <a href="<?  echo site_url('/wp-login.php?action=register');?>">Register</a> | <a title="Password Lost and Found" href="<?  echo site_url('/wp-login.php?action=lostpassword');?>">Lost your password?</a> </p>
    <p id="backtoblog"><a title="Are you lost?" href="<? bloginfo("url"); ?>">← Back to Pie Register</a></p>
    <? } ?>
    
  </form>
</div>