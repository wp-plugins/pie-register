<?php
$piereg = get_option( 'pie_register_2' );
?>
<style type="text/css">
.pieregister-admin .piereg-plugin-rate {background: none repeat scroll 0 0 rgb(255, 255, 255);box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05);float: right;margin: 0 10px 0 0;position: relative;width: 280px;}
.pieregister-admin .piereg-plugin-rate h3 {border-bottom: 1px solid rgb(238, 238, 238);font-size: 14px;line-height: 1.4;margin: 0;padding: 15px 12px 10px 15px;}
.pieregister-admin .piereg-plugin-rate h3 img {margin:-2px 10px 0px 0px;float:left;}
.pieregister-admin .piereg-plugin-rate .piereg_inner {padding:0px 20px;}
.pieregister-admin .piereg-plugin-rate .piereg_inner a{color:rgb(1, 45, 127); text-decoration:none;}
.pieregister-admin .piereg-plugin-rate .piereg_inner a:hover{color:rgb(255, 0, 0);}
.pieregister-admin .piereg-plugin-rate .piereg_inner .piereg_inner_message span{line-height:2;}
.pieregister-admin .piereg-plugin-rate .piereg_inner .piereg_created_by{margin:0px;}
.pieregister-admin .piereg-plugin-rate .piereg_inner .piereg_created_by span {margin: 0;padding-bottom: 10px;float:left;margin-top:10px;}
.pieregister-admin .piereg-plugin-rate .piereg_inner .piereg_created_by a img{width:145px;}
</style>

<script type="text/javascript">
var piereg = jQuery.noConflict();
piereg(document).ready(function(){
	piereg( document ).tooltip({
		track: true
	});
});
 
</script>

<div id="container" class="pieregister-admin">
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
            <div style="display:inline-block;">
			<table id="PR_table_Short_Code" cellspacing="0" cellpadding="10" >
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
            
            
            
		<div class="piereg-plugin-rate">
            <h3>
            <img src="<?php echo plugins_url("images/registerd.png",dirname(__FILE__));?>" alt=" " />
            <?php _e("Pie-Register","piereg"); ?></h3>
            <?php /*?><div class="piereg_inner">
                <h4><?php _e("Need Help?","piereg"); ?></h4>
                <p class="piereg_inner_message">
                <span><?php _e("If you have any query, feel free to post your Questions at our","piereg"); ?>
                <a href="http://pieregister.genetechsolutions.com/forum/" target="_blank"><?php _e("Hosted Forum","piereg"); ?></a>
                <?php _e("to get free support","piereg"); ?></span>
                </p>
            </div>
            <hr /><?php */?>
            <div class="piereg_inner">
                <h4><?php _e("Do you like this plugin?","piereg"); ?></h4>
                <p class="piereg_inner_message">
                <span><a href="http://wordpress.org/support/view/plugin-reviews/pie-register?filter=5" title="<?php _e("Rate it 5","piereg"); ?>" target="_blank">
                <?php _e("Rate it 5","piereg"); ?>
                </a> <?php _e("on WordPress.org","piereg"); ?></span><br />
                <span><?php _e("Blog about it & link to the","piereg"); ?>
                <a href="http://pieregister.genetechsolutions.com/" title="<?php _e("plugin page","piereg"); ?>" target="_blank"><?php _e("plugin page","piereg"); ?></a></span><br />
                <span><?php _e("Check out our","piereg"); ?> <a href="https://wordpress.org/plugins/pie-register/" target="_blank" title="<?php _e("WordPress plugins","piereg"); ?>" ><?php _e("WordPress plugins","piereg"); ?></a></span>
                </p>
            </div>
            <hr />
            <div class="piereg_inner" style="padding:5px 0px 5px 20px;">
                <p class="piereg_created_by">
                <span><?php _e("Developed by","piereg"); ?></span> &nbsp;&nbsp; <a href="http://www.genetechsolutions.com/" title="GenetechSolutions" target="_blank" ><img alt="GenetechSolutions" title="GenetechSolutions" src="http://www.genetechsolutions.com/images/sitelogo.png"></a>
                </p>
            </div>
        </div>
    </div>
    
    <div class="pieHelpTicket">
    	<div style="display:inline-block;">
            <h2><?php _e("Need Help?","piereg"); ?></h2>
            <!--<p class="pieHelpPara">
            <?php _e("Please use pie-register hosted forum for getting free support for V 2.0","piereg"); ?><br />
            <strong>
            <?php _e("Dear","piereg");?>&nbsp;<?php echo (!empty(wp_get_current_user()->data->display_name))?wp_get_current_user()->data->display_name : "User"; ?></strong>,<br />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?php _e("Please use our own hosted","piereg"); ?> <a href="http://pieregister.genetechsolutions.com/forum/" title="<?php _e("Forum","piereg"); ?>" target="_blank"><strong><?php _e("Forum","piereg"); ?></strong></a> <?php _e("to get free support","piereg"); ?>.<br />
            <br />
            <?php _e("Thanks","piereg"); ?><br />
            <?php _e("The","piereg"); ?> <a href="http://pieregister.genetechsolutions.com/" target="_blank" title="<?php _e("Pie-Register","piereg"); ?>"><strong><?php _e("Pie-Register","piereg"); ?></strong></a> <?php _e("Team","piereg"); ?><br />
            <!--<a href="https://wordpress.org/plugins/pie-register/" target="_blank">https://wordpress.org/plugins/pie-register</a>-->
            <!--</p>-->
            <p>
            <?php _e("If you have any query, feel free to post your Questions at our","piereg"); ?>
            <a href="http://pieregister.genetechsolutions.com/forum/" target="_blank"><?php _e("Hosted Forum","piereg"); ?></a>
            <?php _e("to get free support","piereg"); ?>
            </p>
        </div>        
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <?php /*?><form method="post" action="http://pieregister.genetechsolutions.com" target="_blank">
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
    </form><?php */?>
    
    <div>
    	<iframe scrolling="no" src="<?php echo "http://genetechsolutions.com/pie_register_help_contain/iframe.html"; ?>" style="width:100%; height:450px;"></iframe>
    </div>

  </div>
</div>





