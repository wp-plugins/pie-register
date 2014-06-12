<?php

$piereg = get_option( 'pie_register_2' );

?>

<div id="container">

  <div class="right_section">

    <div class="settings" style="padding-bottom:0px;">

      <h2><?php _e("Help",'piereg') ?></h2>

    </div>

    

    <p class="pieHelpPara">
    <div style="clear:both;">
	<?php _e("Welcome to the Pie-Register’s Customer Support Page. Many of your installation and setup related queries are answered in our FAQ’s, Documentation and Forums sections listed below. It is suggested that before you submit a support ticket, please review the mentioned sections for a clear and better understanding of Pie-Register. This will reduce the Support Volume for a timely execution of the Support Process","piereg"); ?>
	</div>
<br /><br />

    <?php _e("If you still have any query, feel free to contact us by submitting a support ticket form on the right","piereg"); ?></p>

    <div class="pieHelpMenuButtonContaner">

        <ul class="pieHelpMenuButton">

            <li><a href="http://pieregister.genetechsolutions.com/faqs/" target="_blank_pieHelp_1"><?php _e("Browse Frequently Asked Questions","piereg"); ?></a></li>

            <li><a href="http://pieregister.genetechsolutions.com/get-support/" target="_blank_pieHelp_2"><?php _e("Pie-Register v2.0 Beta Problems","piereg"); ?></a></li>

            <li><a href="http://pieregister.genetechsolutions.com/forum/" target="_blank_pieHelp_3"><?php _e("Go To Forums","piereg"); ?></a></li>

            <li><a href="http://pieregister.genetechsolutions.com/using-pie-register/" target="_blank_pieHelp_4"><?php _e("Review Documentation","piereg"); ?></a></li>

            <li><a href="http://pieregister.genetechsolutions.com/getting-started/" target="_blank_pieHelp_5"><?php _e("Getting Started","piereg"); ?></a></li>

            <li><a href="http://pieregister.genetechsolutions.com/setting-up-pie-register/" target="_blank_pieHelp_6"><?php _e("Setting up Pie-Register","piereg"); ?></a></li>

            <li><a href="http://pieregister.genetechsolutions.com/getting-started/" target="_blank_pieHelp_7"><?php _e("Installation Problems","piereg"); ?></a></li>

            <li><a href="http://pieregister.genetechsolutions.com/using-pie-register/" target="_blank_pieHelp_8"><?php _e("Using Pie-Register","piereg"); ?></a></li>

            <li><a href="http://pieregister.genetechsolutions.com/forums/forum/news-announcements/" target="_blank_pieHelp_9"><?php _e("News and Announcements","piereg"); ?></a></li>

        </ul>

    </div>

    

    <div class="pieHelpTicket">

    	<style type="text/css">

		.PR_short_code_input,.PR_short_code_input:hover,.PR_short_code_input:focus,.PR_short_code_input:active{

			background-color:transparent !important;border:none !important;font-weight:bold;width:240px; box-shadow:none !important;

		}

		table#PR_table_Short_Code tr:nth-child(1){

			background: none repeat scroll 0 0 rgb(73, 73, 73);

			color: rgb(255, 255, 255);

			font-size: 15px;

			text-align: center;

		}

		</style>

    	<h2><?php _e("Embedding Forms/Shortcodes","piereg"); ?></h2>

		<p class="pieHelpPara">

			<?php _e("Now, you can easily embed your Login, Registration, Forgot Password forms and Profile pages anywhere inside a post, page or a custom post type or even into the widgets through the use of following shortcodes","piereg"); ?></p>

			<table id="PR_table_Short_Code" cellspacing="0" cellpadding="10">

				<tr>

					<td><strong><?php _e("Usage","piereg"); ?></strong></td>

					<td><strong><?php _e("Short Code","piereg"); ?></strong></td>

				</tr>

				<tr>

					<td><label for="F_L_F_U"><?php _e("For login form use","piereg"); ?> : </label></td>

					<td>

                    <input type="text" id="F_L_F_U" value="[pie_register_login]" readonly="readonly" class="PR_short_code_input" onkeypress="this.select();" onfocus="this.select();" /></td>

				</tr>

				<tr>

					<td><label for="F_R_F_U"><?php _e("For Registration form use","piereg"); ?> : </label></td>

					<td>

                    <input type="text" id="F_R_F_U" value="[pie_register_form]" readonly="readonly" class="PR_short_code_input" onkeypress="this.select();" onfocus="this.select();" /></td>

				</tr>

				<tr>

					<td><label for="F_F_P_F_U"><?php _e("For forgot password form use","piereg"); ?> : </label></td>

					<td>

                    <input type="text" id="F_F_P_F_U" value="[pie_register_forgot_password]" readonly="readonly" class="PR_short_code_input" onkeypress="this.select();" onfocus="this.select();" /></td>

				</tr>

				<tr>

					<td><label for="F_P_P_U"><?php _e("For profile page use","piereg"); ?> : </label></td>

					<td>

                    <input type="text" id="F_P_P_U" value="[pie_register_profile]" readonly="readonly" class="PR_short_code_input" onkeypress="this.select();" onfocus="this.select();" /></td>

				</tr>

				<tr>

					<td></td>

					<td></td>

				</tr>

			</table>

            

    </div>

    

    <!-- <form method="post" action="http://192.168.14.2/hadi/Pie_register_Blog/" target="_blank"> -->

    <form method="post" action="http://pieregister.genetechsolutions.com" target="_blank">

    	<?php global $current_user;

    	get_currentuserinfo(); ?>



    	<input type="hidden" name="input_8" value="<?php echo $_SERVER['REMOTE_ADDR'] ?>" />

    	<input type="hidden" name="gform_submit" value="6" />

    	<input type="hidden" name="processFromPR" value="1" />

    	<input type="hidden" name="is_submit_6" value="1" />

    	<input type="hidden" name="state_2" value="YToyOntpOjA7czo2OiJhOjA6e30iO2k6MTtzOjMyOiJkYjk5MDFjYjFkODExZWE4NGNiODkxNGZiOTI2NGFmMyI7fQ==" />

    	<input type="hidden" name="gform_field_values" value="" />

    	<input type="hidden" name="gform_target_page_number_6" value="0" />

    	<input type="hidden" name="gform_source_page_number_6" value="1" />

    	<input type="hidden" name="gform_unique_id" value="<?php echo time(); ?>" />

    	<input type="hidden" name="input_1" value="<?php bloginfo("url"); ?>" />

    	<input type="hidden" name="input_2" value="<?php echo get_bloginfo('name'); ?>" />

    	<input type="hidden" name="input_9" value="<?php echo date("Y-m-d"); ?>" />

        <div class="pieHelpTicket">

            <h2><?php _e("Create a Support Ticket","piereg"); ?></h2>

            <ul class="pieHelpTicketHelp">

                <li>

                    <label for="pieHelpName"><?php _e("Name","piereg"); ?></label>

                    <input id="pieHelpName" required="required" name="input_4" type="text" value="<?php echo $current_user->user_firstname ?>" />

                </li>

                <li>

                    <label for="pieHelpEmail"><?php _e("E-mail","piereg"); ?></label>

                    <input id="pieHelpEmail" type="email" required="required" name="input_10" value="<?php echo $current_user->data->user_email; ?>" />

                </li>

            </ul>

            

            <div class="pieHelpTicket_input_fileds">

                <label for="pieHelpLicenseKey"><?php _e("License Key","piereg"); ?></label>

                <input id="pieHelpLicenseKey" type="text" name="input_6" value="<?php echo $piereg['support_license']; ?>" 

                <?php echo (isset($piereg['support_license']) and trim($piereg['support_license']) != "" )? 'readonly="readonly"' : "" ?> />

            </div>

            <div class="pieHelpTicket_input_fileds">

                <label for="pieHelpComments"><?php _e("Comments","piereg"); ?></label>

                <textarea id="pieHelpComments" name="input_7" required="required" ></textarea>

            </div>

            

            <div class="pieHelpTicket_input_fileds">

                <input type="submit" value="<?php _e("Submit","piereg"); ?>" name="submit_support"  />

            </div>

        </div>

    </form>

    

    <div>

    	<iframe scrolling="no" src="<?php echo "http://pieregister.genetech.co/pie_register_help_contain/iframe.html"; ?>" style="width:100%; height:450px;"></iframe>

    </div>



  </div>

</div>











