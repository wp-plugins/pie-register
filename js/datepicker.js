var piereg = jQuery.noConflict();

function pieNextPage(elem)

{

	//pieHideFields();

	piereg(elem).closest('.pieregformWrapper').find('form .fields').css('display','none');

	var id 		= piereg(elem).attr("id");

	//var pageNo 	= piereg("#"+id+"_curr").val();

	var pageNo = piereg(elem).closest('form').find("#"+id+"_curr").val();

	var totalPages = piereg(elem).closest('form').find('.piereg_regform_total_pages').val();

	//var pageNo = piereg("#"+id+"_curr").val();

	//var elms = document.getElementsByClassName('pageFields_'+pageNo);

	piereg(elem).closest('form').find('.pageFields_'+pageNo).css('display','block');/*

	for(a = 0 ; a < elms.length ; a++)

	{

		elms[a].style.display = "";	

	} */

	

	/*piereg('html, body').animate({

        scrollTop: piereg(".piereg_progressbar").offset().top

    }, 0);*/

	//alert("pageno"+pageNo+" Total Pages:"+totalPages);

	piereg(elem).closest('.pieregformWrapper').find(".piereg_progressbar" ).progressbar( "option", {

		

          value: pageNo / totalPages * 100

        }); 

	 	

}

function pieHideFields()

{

	/*var elms = document.getElementsByClassName('fields');

	for(a = 0 ; a < elms.length ; a++)

	{

		elms[a].style.display = "none";	

	}*/

	piereg('.pieregformWrapper .fields').css('display','none');

}

piereg(document).ready(function(e) {

 	

	piereg(".pieregformWrapper form").validationEngine();

	piereg("#lostpasswordform").validationEngine();

	piereg("#resetpassform").validationEngine();	

	piereg("#loginform").validationEngine();

	

	piereg('.date_start').datepicker({

        dateFormat : 'yy-mm-dd',

		changeMonth: true,

		changeYear: true

	});	

	

	piereg('.date_start').each(function(index, element) {

       

	    var id = piereg(this).attr("id");

		

		//Setting date Format

		var formatid = id + "_format";

		var format = piereg("#"+formatid).val();

		piereg( "#"+id ).datepicker( "option", "dateFormat", format );

		

		//First day of a week

		var formatid = id + "_firstday";

		var format = piereg("#"+formatid).val();

		piereg( "#"+id ).datepicker( "option", "firstDay", format );

		

		//Min date		

		var formatid = id + "_startdate";

		var format = piereg("#"+formatid).val();

		piereg( "#"+id ).datepicker( "option", "minDate", format );

		

		piereg("#ui-datepicker-div").hide();

    });

	

	piereg(".calendar_icon").on("click", function() {

    	var id = piereg(this).attr("id");		

		id = id.replace("_icon","");		

		piereg("#"+id).datepicker("show");

	});  

	piereg(".pie_next").click(function () 

	{  

		var validate = piereg(this).closest('.pieregformWrapper').find('form').validationEngine('validate')

		//var validate = piereg("#pie_regiser_form").validationEngine('validate');



		if(validate)

		{

			//var id 		= piereg(this).attr("id");

			//var pageNo 	= piereg("#"+id+"_curr").val();		

			//pieNextPage(pageNo);

			pieNextPage(this);	

		}  

	}); 

	

	piereg(".pie_prev").click(function () 

	   {  

		/*var id 		= piereg(this).attr("id");

		var pageNo 	= piereg("#"+id+"_curr").val();

		pieNextPage(pageNo);*/

		pieNextPage(this);

		  

	}); 

	

	//piereg("#comments,.entry-meta").hide();

	

});

/*function passwordStrength(password)

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

}*/






/**************************************************************************************/


 piereg(document).ready(function(){
      if(piereg("#pie_widget-2 #piereg_passwordStrength").length > 0){
            piereg("#pie_widget-2 #password_2").bind("keyup", function(){
				var pass1 = piereg("#pie_widget-2 #password_2").val();
				var pass2 = "";
				if(piereg("#pie_widget-2 #confirm_password_password_2").val().trim() != ""){
					pass2 = piereg("#pie_widget-2 #confirm_password_password_2").val();
				}
				
				var username = "";
				if(piereg("#pie_widget-2 #username").val() != ""){
					username = piereg("#pie_widget-2 #username").val();
				}
				
				var strength = passwordStrength(pass1,username,pass2);
				widget_updateStrength(strength,pass1,pass2);
            });
            piereg("#pie_widget-2 #confirm_password_password_2").bind("keyup", function(){
				var pass1 = piereg("#pie_widget-2 #password_2").val();
				var pass2 = piereg("#pie_widget-2 #confirm_password_password_2").val();
				var username = "";
				if(piereg("#pie_widget-2 #username").val().trim() != "")
					username = piereg("#pie_widget-2 #username").val();
				
				var strength = passwordStrength(pass1, username, pass2);
				
				widget_updateStrength(strength,pass1,pass2);
            });
        }
    });

function widget_updateStrength(strength,pass1,pass2){
    //var status = new Array('short', 'bad', 'good', 'strong', 'mismatch');
	//piereg_pass_v_week,piereg_pass_week,piereg_pass_medium,piereg_pass_strong
    var status = new Array('piereg_pass','piereg_pass_v_week', 'piereg_pass_week', 'piereg_pass_medium', 'piereg_pass_strong', 'piereg_pass_v_week');
    var dom = piereg("#pie_widget-2 #piereg_passwordStrength");




	if(pass1 == "" && pass2 == ""){
		dom.removeClass().addClass(status[0]).text(piereg_pass_str_meter_string[0]);
		return false;
	}
	
    switch(strength){
    case 1:
      dom.removeClass().addClass(status[1]).text(piereg_pass_str_meter_string[1]);
      break;
    case 2:
      dom.removeClass().addClass(status[2]).text(piereg_pass_str_meter_string[2]);
      break;
    case 3:
      dom.removeClass().addClass(status[3]).text(piereg_pass_str_meter_string[3]);
      break;
    case 4:
     dom.removeClass().addClass(status[4]).text(piereg_pass_str_meter_string[4]);
      break;
    case 5:
      dom.removeClass().addClass(status[5]).text(piereg_pass_str_meter_string[5]);
      break;
    default:
      dom.removeClass().addClass(status[1]).text(piereg_pass_str_meter_string[1]);
      break;
    }
}










 piereg(document).ready(function(){
      if(piereg("#piereg_passwordStrength").length > 0){
            piereg("#password_2").bind("keyup", function(){
				var pass1 = piereg("#password_2").val();
				var pass2 = "";
				if(piereg("#confirm_password_password_2").val().trim() != ""){
					pass2 = piereg("#confirm_password_password_2").val();
				}
				
				var username = "";
				if(piereg("#username").val() != ""){
					username = piereg("#username").val();
				}
				var strength = passwordStrength(pass1,username,pass2);
				updateStrength(strength,pass1,pass2,"");
				
				
            });
            piereg("#confirm_password_password_2").bind("keyup", function(){
				var pass1 = piereg("#password_2").val();
				var pass2 = piereg("#confirm_password_password_2").val();
				var username = "";
				if(piereg("#username").val().trim() != "")
					username = piereg("#username").val();
				
				var strength = passwordStrength(pass1, username, pass2);
				updateStrength(strength,pass1,pass2,"");
            });
        }
    });

function updateStrength(strength,pass1,pass2,widje){
    //var status = new Array('short', 'bad', 'good', 'strong', 'mismatch');
	//piereg_pass_v_week,piereg_pass_week,piereg_pass_medium,piereg_pass_strong
	
    var status = new Array('piereg_pass','piereg_pass_v_week', 'piereg_pass_week', 'piereg_pass_medium', 'piereg_pass_strong', 'piereg_pass_v_week');
    var dom = piereg("#piereg_passwordStrength");//piereg_pass
	
	
	
				
	if(pass1 == "" && pass2 == ""){
		dom.removeClass().addClass(status[0]).text(piereg_pass_str_meter_string[0]);
		return false;
	}
	
    switch(strength){
    case 1:
      dom.removeClass().addClass(status[1]).text(piereg_pass_str_meter_string[1]);
      break;
    case 2:
      dom.removeClass().addClass(status[2]).text(piereg_pass_str_meter_string[2]);
      break;
    case 3:
      dom.removeClass().addClass(status[3]).text(piereg_pass_str_meter_string[3]);
      break;
    case 4:
     dom.removeClass().addClass(status[4]).text(piereg_pass_str_meter_string[4]);
      break;
    case 5:
      dom.removeClass().addClass(status[5]).text(piereg_pass_str_meter_string[5]);
      break;
    default:
      dom.removeClass().addClass(status[1]).text(piereg_pass_str_meter_string[1]);
      break;
    }
}

