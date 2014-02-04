<script type="text/javascript">
jQuery(document).ready(function(e) {
  jQuery(".selectall").change(function () {
		if(jQuery(this).attr("checked")=="checked")
		{
			jQuery(".meta_key").attr("checked","checked")
		}
		else
		{
			jQuery(".meta_key").removeAttr("checked");		
		}	  
	
 	}); 
	
	jQuery(".meta_key").change(function () {
		
		if (jQuery('.meta_key:checked').length == jQuery('.meta_key').length) {
      		jQuery(".selectall").attr("checked","checked");
    	} 
		else
		{
			jQuery(".selectall").removeAttr("checked");		
		} 
	
 	});
	
	
    jQuery('#date_start,#date_end').datepicker({
        dateFormat : 'yy-mm-dd',	
		 maxDate: "M D"
    });
	
	
	jQuery("#start_icon").on("click", function() {
    	jQuery("#date_start").datepicker("show");
	});
	
	jQuery("#end_icon").on("click", function() {
    	jQuery("#date_end").datepicker("show");
	});
	
	jQuery("#export").on("submit", function() {
    	if(jQuery('.meta_key:checked').length < 1)
		{
			alert("Please select at least one field to export.");
			return false;
		}
	});
	
});
</script>

<div class="notifications">
  <h2>
    <?php  _e("Export/Import User Entries",'piereg') ?>
  </h2>
  <div class="export">
    <?php
   if(!empty( $_POST['error_message'] ))
	echo '<p class="error">' . $_POST['error_message']  . "</p>";
	
	 if(!empty( $_POST['success_message'] ))
	echo '<p class="success">' . $_POST['success_message']  . "</p>";
	?>
    <form method="post" action="" id="export">
      <ul>
        <li>
          <div class="fields">
            <h2>
              <?php  _e("Export",'piereg'); ?>
            </h2>
            <p><?php  _e("Now you can export all users with custom fields with a particular date range in a CSV file! Simply select your fields and select your Date Range. The Date Range feature is optional which means that if you do not select a date range then all entries will be exported. Click on the Download Export Files to complete the operation.",'piereg'); ?> </p>
          </div>
        </li>
        <li>
          <div class="fields select_checkbox">
            <h2>
              <?php _e("Select Fields",'piereg'); ?>
            </h2>
            <input id="field_selectall" type="checkbox" class="checkbox selectall" />
            <label for="field_selectall">Select All</label>
            <input id="field_user_login" type="checkbox" class="checkbox meta_key" name="pie_fields_csv[user_login]" value="Username"  />
            <label for="field_user_login">Username</label>
            <input id="field_first_name" type="checkbox" class="checkbox meta_key" name="pie_meta_csv[first_name]" value="First Name" />
            <label for="field_first_name">First Name</label>
            <input id="field_last_name" type="checkbox" class="checkbox meta_key" name="pie_meta_csv[last_name]" value="Last Name" />
            <label for="field_last_name">Last Name</label>
            <input id="field_nickname" type="checkbox" class="checkbox meta_key" name="pie_meta_csv[nickname]" value="Nickname" />
            <label for="field_nickname">Nickname</label>
            <input id="field_display_name" type="checkbox" class="checkbox meta_key" name="pie_fields_csv[display_name]" value="Display name" />
            <label for="field_display_name">Display name</label>
            <input id="field_user_email" type="checkbox" class="checkbox meta_key" name="pie_fields_csv[user_email]" value="E-mail" />
            <label for="field_user_email">E-mail</label>
            <input id="field_user_url" type="checkbox" class="checkbox meta_key" name="pie_fields_csv[user_url]" value="Website" />
            <label for="field_user_url">Website</label>
            <input id="field_aim" type="checkbox" class="checkbox meta_key" name="pie_meta_csv[aim]" value="AIM" />
            <label for="field_aim">AIM</label>
            <input id="field_yim" type="checkbox" class="checkbox meta_key" name="pie_meta_csv[yim]" value="Yahoo IM" />
            <label for="field_yim">Yahoo IM</label>
            <input id="field_jabber" type="checkbox" class="checkbox meta_key" name="pie_meta_csv[jabber]" value="Jabber / Google Talk" />
            <label for="field_jabber">Jabber / Google Talk</label>
            <input id="field_description" type="checkbox" class="checkbox meta_key" name="pie_meta_csv[description]" value="Biographical Info" />
            <label for="field_description">Biographical Info</label>
            <?php
			  
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
					case 'captcha' :	
					case 'name' :			
					continue 2;
					break;
					endswitch;
					
								
					$field_id 	= $pie_fields['id'];
					$label 		= $pie_fields['label'];
					$meta_key	= "pie_".$pie_fields['type']."_".$pie_fields['id'];
						
						
				?>
            <input id="field_<?php echo $field_id?>" type="checkbox" class="checkbox meta_key" name="pie_meta_csv[<?php echo $meta_key?>]" value="<?php echo $pie_fields_csv[$field_id]['label']?>" />
            <label for="field_<?php echo $field_id?>" >
              <?php echo $label?>
            </label>
            <?php 
					}
				}
				
			  ?>
          </div>
        </li>
        <li>
          <div class="fields date">
            <h2>Select User Registration Date Range</h2>
            <div class="start_date">
              <label for="field_">Start</label>
              <input id="date_start" name="date_start" type="text" class="input_fields" />
              <img id="start_icon" src="../wp-content/plugins/pie-register/images/calendar_img.jpg" width="22" height="22" alt="calendar" class="calendar_img" /></div>
            <div class="end_date">
              <label for="field_">End</label>
              <input id="date_end" name="date_end" type="text" class="input_fields" />
              <img id="end_icon" src="../wp-content/plugins/pie-register/images/calendar.png" width="22" height="22" alt="calendar" class="calendar_img" /></div>
            Date Range is optional, if no date range is selected all entries will be exported.
            <div class="clear"></div>
            <input type="submit" class="submit_btn" value="Download Export Files" />
          </div>
        </li>
      </ul>
    </form>
  </div>
  <div class="import">
    <form method="post" action="" enctype="multipart/form-data">
      <ul>
        <li>
          <div class="fields">
            <h2>
              <?php _e("Import",'piereg'); ?>
            </h2>
            <p>
              <?php _e("Select the  CSV file you would like to import. When you click the import button below, Pie Register will import the users. Please see the example of CSV file before the import operartion.",'piereg'); ?>
            </p>
          </div>
        </li>
        <li>
          <div class="fields">
            <h2>Select File</h2>
            <input name="csvfile" type="file" class="input_fields" />
            <span style="float:left"><?php echo sprintf( __( 'You may want to see', 'pie-register').'<a target="_blank" href="%s">'.__('the example of the CSV file', 'pie-register').'</a>.' , plugin_dir_url(__FILE__).'examples/example.csv'); ?></span>
            <div class="clear"></div>
            <input type="submit" class="submit_btn submit_btn2" value="Import" />
          </div>
        </li>
      </ul>
    </form>
  </div>
</div>
