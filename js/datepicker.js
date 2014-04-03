jQuery(document).ready(function(e) {
 	
	jQuery("#pie_regiser_form").validationEngine();
	jQuery("#lostpasswordform").validationEngine();
	jQuery("#resetpassform").validationEngine();	
	jQuery("#loginform").validationEngine();
	
	jQuery('.date_start').datepicker({
        dateFormat : 'yy-mm-dd',
		changeMonth: true,
		changeYear: true
	});	
	
	jQuery('.date_start').each(function(index, element) {
       
	    var id = jQuery(this).attr("id");
		
		//Setting date Format
		var formatid = id + "_format";
		var format = jQuery("#"+formatid).val();
		jQuery( "#"+id ).datepicker( "option", "dateFormat", format );
		
		//First day of a week
		var formatid = id + "_firstday";
		var format = jQuery("#"+formatid).val();
		jQuery( "#"+id ).datepicker( "option", "firstDay", format );
		
		//Min date		
		var formatid = id + "_startdate";
		var format = jQuery("#"+formatid).val();
		jQuery( "#"+id ).datepicker( "option", "minDate", format );
		
		jQuery("#ui-datepicker-div").hide();
    });
	
	jQuery(".calendar_icon").on("click", function() {
    	var id = jQuery(this).attr("id");		
		id = id.replace("_icon","");		
		jQuery("#"+id).datepicker("show");
	});  
	jQuery(".pie_next").click(function () 
	{  
		var validate = jQuery("#pie_regiser_form").validationEngine('validate');

		if(validate)
		{
			var id 		= jQuery(this).attr("id");
			var pageNo 	= jQuery("#"+id+"_curr").val();		
			pieNextPage(pageNo);	
		}  
	}); 
	
	jQuery(".pie_prev").click(function () 
	   {  
		var id 		= jQuery(this).attr("id");
		var pageNo 	= jQuery("#"+id+"_curr").val();
		pieNextPage(pageNo);	
		  
	}); 
	
	jQuery("#comments,.entry-meta").hide();
	
});
function passwordStrength(password)
{
	var desc = new Array();
	desc[0] = "Very Weak";
	desc[1] = "Weak";
	desc[2] = "Better";
	desc[3] = "Medium";
	desc[4] = "Strong";
	desc[5] = "Strongest";

	var score   = 0;

	//if password bigger than 6 give 1 point
	if (password.length > 6) score++;

	//if password has both lower and uppercase characters give 1 point	
	if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;

	//if password has at least one number give 1 point
	if (password.match(/\d+/)) score++;

	//if password has at least one special caracther give 1 point
	if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;

	//if password bigger than 12 give another 1 point
	if (password.length > 12) score++;

	 document.getElementById("piereg_passwordDescription").innerHTML = desc[score];
	 document.getElementById("piereg_passwordStrength").className = "strength" + score;
}