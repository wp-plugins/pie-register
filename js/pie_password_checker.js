var piereg = jQuery.noConflict();
/********************************	Widgets Password Strangth Meater	*********************************/
piereg(document).ready(function(){
      if(piereg(".widget #piereg_passwordStrength_widget").length > 0){
		piereg(".widget #password_2_widget").bind("keyup", function(){
			var pass1 = piereg(".widget #password_2_widget").val();
			var pass2 = "";
			if(piereg(".widget #confirm_password_password_2_widget").val().trim() != ""){
				pass2 = piereg(".widget #confirm_password_password_2_widget").val();
			}
			
			var username = "";
			if(piereg(".widget #username_widget").val() != ""){
				username = piereg(".widget #username_widget").val();
			}
			var strength = passwordStrength(pass1,username,pass2);
			widget_updateStrength(strength,pass1,pass2);
		});
		piereg(".widget #confirm_password_password_2_widget").bind("keyup", function(){
			var pass1 = piereg(".widget #password_2_widget").val();
			var pass2 = piereg(".widget #confirm_password_password_2_widget").val();
			var username = "";
			if(piereg(".widget #username_widget").val().trim() != ""){
				username = piereg(".widget #username_widget").val();
			}
			var strength = passwordStrength(pass1, username, pass2);
			
			widget_updateStrength(strength,pass1,pass2);
		});
	}
});
function widget_updateStrength(strength,pass1,pass2){
    var status = new Array('piereg_pass','piereg_pass_v_week', 'piereg_pass_week', 'piereg_pass_medium', 'piereg_pass_strong', 'piereg_pass_v_week');
    var dom = piereg(".widget #piereg_passwordStrength_widget");
	
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

/*
	*	Add Restrict Password Strength meater since 2.0.13
*/

piereg(document).ready(function(){
	piereg("#pie_widget_regiser_form").on("submit",function(){
		
		var pass1 = "";
		if(piereg(".widget #password_2_widget").val().trim() != "")
		{
			pass1 = piereg(".widget #password_2_widget").val();
		}
		
		var pass2 = "";
		if(piereg(".widget #confirm_password_password_2_widget").val().trim() != "")
		{
			pass2 = piereg(".widget #confirm_password_password_2_widget").val();
		}
		
		var username = "";
		if(piereg(".widget #username_widget").val().trim() != "")
		{
			username = piereg(".widget #username_widget").val();
		}
		
		var strength = passwordStrength(pass1,username,pass2);
		//if(strength != 4)
		if(password_strength_meter_widget == 0 || password_strength_meter_widget == 1)
		{
			return true;
		}
		if(strength < password_strength_meter_widget)
		{
			piereg(this).find("#password_2_widget").closest(".fieldset").addClass("error");
			var restrict_strength_message = piereg(this).find("#password_strength_message").html();
			piereg(this).find("#password_2").closest(".fieldset").append('<div class="legend_txt"><span class="legend error">'+restrict_strength_message+'</span></div>');
			return false;
		}
	});
});

/********************************	Page Password Strangth Meater	*********************************/


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
    var status = new Array('piereg_pass','piereg_pass_v_week', 'piereg_pass_week', 'piereg_pass_medium', 'piereg_pass_strong', 'piereg_pass_v_week');
    var dom = piereg("#piereg_passwordStrength");
	
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

/*
	*	Add Restrict Password Strength meater since 2.0.13
*/
piereg(document).ready(function(){
	piereg("#pie_regiser_form").on("submit",function(){
		
		var pass1 = "";
		if(piereg("#password_2").val().trim() != "")
		{
			pass1 = piereg("#password_2").val();
		}
		
		var pass2 = "";
		if(piereg("#confirm_password_password_2").val().trim() != "")
		{
			pass2 = piereg("#confirm_password_password_2").val();
		}
		
		var username = "";
		if(piereg("#username").val().trim() != "")
		{
			username = piereg("#username").val();
		}
		
		var strength = passwordStrength(pass1,username,pass2);
		/*if(strength != 4)*/
		if(password_strength_meter == 0 || password_strength_meter == 1)
		{
			return true;
		}
		if(strength < password_strength_meter)
		{
			piereg(this).find("#password_2").closest(".fieldset").addClass("error");
			var restrict_strength_message = piereg(this).find("#password_strength_message").html();
			piereg(this).find("#password_2").closest(".fieldset").append('<div class="legend_txt"><span class="legend error">'+restrict_strength_message+'</span></div>');
			return false;
		}
	});
});


// Declare jQuery Object to $.
$ = jQuery;
