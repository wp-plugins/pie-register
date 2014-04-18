var currHTML, endHtml, dragType,fieldMeta;
var no = 3;

function getStyle(type) {
	var meta = "";
	var html = jQuery('<div/>').addClass("fields_options fields_optionsbg").html('<a href="javascript:;" class="edit_btn"></a><div class="label_position"  id="field_label_' + no + '"><label>Untitled ' + type + '</label></div><a rel="' + no + '" href="javascript:;" class="delete_btn">X</a><input type="hidden" name="field[' + no + '][id]" value="' + no + '" id = "id_' + no + '"><div id="field_position_' + no + '" class="fields_position"></div>');
	
	if (type == "text") {
		html.find("#field_position_" + no).html('<input type="text" disabled="disabled"  id="field_' + no + '" class="input_fields">');
	} else if (type == "textarea") {
		html.find("#field_position_" + no).html('<textarea disabled="disabled" id="field_' + no + '" rows="5" cols="73"> </textarea>');
	} else if (type == "dropdown") {
		html.find("#field_position_" + no).html('<select disabled="disabled" id="field_' + no + '"><option></option></select>');
	} else if (type == "multiselect") {
		html.find("#field_position_" + no).html('<select disabled="disabled" id="field_' + no + '" multiple="multiple"><option></option></select>');
	} else if (type == "number") {
		html.find("#field_position_" + no).html('<input disabled="disabled" id="field_' + no + '" type="number" class="input_fields" />');
	} else if (type == "checkbox") {
		html.find("#field_position_" + no).html('<div class="radio_wrap"><label>My Label</label><input disabled="disabled" id="field_' + no + '" type="checkbox" class="radio_fields" /><label>My Label</label><input disabled="disabled" id="field_' + no + '" type="checkbox" class="radio_fields" /></div>');
	} else if (type == "hidden") {
		html.find("#field_position_" + no).html('<input type="text" disabled="disabled"  id="field_' + no + '" class="input_fields">');
	} else if (type == "radio") {
		html.find("#field_position_" + no).html('<div class="radio_wrap"><label>My Label</label><input disabled="disabled" id="field_' + no + '" type="radio" class="radio_fields" /><label>My Label</label><input disabled="disabled" id="field_' + no + '" type="radio" class="radio_fields" /></div>');
	} else if (type == "html") {
		html.find("#field_position_" + no).html('<div id="field_' + no + '" class="htmldiv">');
	} else if (type == "sectionbreak") {
		html.find("#field_position_" + no).html('<div style="width:100%;float:left;border: 1px solid #aaaaaa;margin-top:25px;"></div>');
	}
	if (type == "pagebreak") {
		html.find("#field_position_" + no).html('<img src="../wp-content/plugins/pie-register/images/pagebreak.png" />');
	} else if (type == "time") {
		html.find("#field_position_" + no).html('<div class="time"><div class="time_fields"><input disabled="disabled" type="text" class="input_fields"><label>HH</label></div><span class="colon">:</span><div class="time_fields"><input disabled="disabled" type="text" class="input_fields"><label>MM</label></div><div id="time_format_field_' + no + '" class="time_fields"><select disabled="disabled"><option>AM</option><option>PM</option></select></div></div>');
	} else if (type == "website") {
		html.find("#field_position_" + no).html('<input disabled="disabled" type="text" id="field_' + no + '" class="input_fields">');
	} else if (type == "name") {
		
		html.find("#field_label_" + no + " label").html("First Name");
		html.find("#field_position_" + no).html('<input disabled="disabled" type="text" class="input_fields">');
		html.find("#field_position_" + no).after('<div id="last_name_field" class="fields_position"> <input disabled="disabled" type="text" class="input_fields"></div>');
		html.find("#field_position_" + no).after('<div class="label_position"><label>Last Name</label></div>');
		html.find("#field_position_" + no).after('<input type="hidden" name="field[' + no + '][label]" id="label_' + no + '" value="First Name">');
	} else if (type == "captcha") {
		html.find("#field_position_" + no).html('<img id="captcha_img" src="../wp-content/plugins/pie-register/images/recatpcha.jpg" />');
	} else if (type == "upload" || type == "profile_pic") {
		html.find("#field_position_" + no).html('<input disabled="disabled" type="file" id="field_' + no + '" class="input_fields">');
	} else if (type == "address") {
		html.find("#field_position_" + no).html('<div id="address_fields" class="address">  <input type="text" class="input_fields">  <label>Street Address</label></div><div class="address" id="address_address2_' + no + '">  <input type="text" class="input_fields">  <label>Address Line 2</label></div><div class="address">  <div class="address2"><input type="text" class="input_fields"><label>City</label>  </div>  <div  class="address2 state_div_' + no + '" id="state_' + no + '"><input type="text" class="input_fields"><label>State / Province / Region</label>  </div>  <div  class="address2 state_div_' + no + '" id="state_us_' + no + '" style="display:none;"><select id="state_us_field_' + no + '"><option value="" selected="selected"></option><option value="Alabama">Alabama</option><option value="Alaska">Alaska</option><option value="Arizona">Arizona</option><option value="Arkansas">Arkansas</option><option value="California">California</option><option value="Colorado">Colorado</option><option value="Connecticut">Connecticut</option><option value="Delaware">Delaware</option><option value="District of Columbia">District of Columbia</option><option value="Florida">Florida</option><option value="Georgia">Georgia</option><option value="Hawaii">Hawaii</option><option value="Idaho">Idaho</option><option value="Illinois">Illinois</option><option value="Indiana">Indiana</option><option value="Iowa">Iowa</option><option value="Kansas">Kansas</option><option value="Kentucky">Kentucky</option><option value="Louisiana">Louisiana</option><option value="Maine">Maine</option><option value="Maryland">Maryland</option><option value="Massachusetts">Massachusetts</option><option value="Michigan">Michigan</option><option value="Minnesota">Minnesota</option><option value="Mississippi">Mississippi</option><option value="Missouri">Missouri</option><option value="Montana">Montana</option><option value="Nebraska">Nebraska</option><option value="Nevada">Nevada</option><option value="New Hampshire">New Hampshire</option><option value="New Jersey">New Jersey</option><option value="New Mexico">New Mexico</option><option value="New York">New York</option><option value="North Carolina">North Carolina</option><option value="North Dakota">North Dakota</option><option value="Ohio">Ohio</option><option value="Oklahoma">Oklahoma</option><option value="Oregon">Oregon</option><option value="Pennsylvania">Pennsylvania</option><option value="Rhode Island">Rhode Island</option><option value="South Carolina">South Carolina</option><option value="South Dakota">South Dakota</option><option value="Tennessee">Tennessee</option><option value="Texas">Texas</option><option value="Utah">Utah</option><option value="Vermont">Vermont</option><option value="Virginia">Virginia</option><option value="Washington">Washington</option><option value="West Virginia">West Virginia</option><option value="Wisconsin">Wisconsin</option><option value="Wyoming">Wyoming</option><option value="Armed Forces Americas">Armed Forces Americas</option><option value="Armed Forces Europe">Armed Forces Europe</option><option value="Armed Forces Pacific">Armed Forces Pacific</option></select><label>State</label>  </div>  <div class="address2 state_div_' + no + '" id="state_canada_' + no + '" style="display:none;"><select id="state_canada_field_' + no + '"><option value="" selected="selected"></option><option value="Alberta">Alberta</option><option value="British Columbia">British Columbia</option><option value="Manitoba">Manitoba</option><option value="New Brunswick">New Brunswick</option><option value="Newfoundland &amp; Labrador">Newfoundland &amp; Labrador</option><option value="Northwest Territories">Northwest Territories</option><option value="Nova Scotia">Nova Scotia</option><option value="Nunavut">Nunavut</option><option value="Ontario">Ontario</option><option value="Prince Edward Island">Prince Edward Island</option><option value="Quebec">Quebec</option><option value="Saskatchewan">Saskatchewan</option><option value="Yukon">Yukon</option></select></select><label>Province</label>  </div></div><div class="address">  <div class="address2"><input type="text" class="input_fields"><label>Zip / Postal Code</label>  </div>  <div id="address_country_' + no + '" class="address2"><select disabled="disabled"><option></option></select><label>Country</label>  </div></div>');
	} else if (type == "phone") {
		html.find("#field_position_" + no).html('<input type="text" id="field_' + no + '" class="input_fields">');
	} else if (type == "date") {
		html.find("#field_position_" + no).html('<div class="time date_format_field" id="datefield_' + no + '">  <div class="time_fields" id="mm_' + no + '">    <input disabled="disabled" type="text" class="input_fields">    <label>MM</label>  </div>  <div class="time_fields" id="dd_' + no + '">    <input disabled="disabled" type="text" class="input_fields">    <label>DD</label>  </div>  <div class="time_fields" id="yy_' + no + '">    <input disabled="disabled" type="text" class="input_fields">    <label>YY</label>  </div></div><div class="time date_format_field" id="datepicker_' + no + '"  style="display:none;">  <input type="text" class="input_fields">  <img src="../wp-content/plugins/pie-register/images/calendar.png" id="calendar_image_' + no + '" style="display:none;" /> </div><div class="time date_format_field" id="datedropdown_' + no + '"  style="display:none;">  <div class="time_fields" id="month_' + no + '"><select disabled="disabled">      <option>Month</option>    </select></div>    <div class="time_fields" id="day_' + no + '"><select disabled="disabled">      <option>Day</option>    </select>  </div>   <div class="time_fields" id="year_' + no + '"><select disabled="disabled">      <option>Year</option>    </select> </div></div>');
	} else if (type == "list") {
		html.find("#field_position_" + no).html('<input type="text" id="field_' + no + '" class="input_fields"><img src="../wp-content/plugins/pie-register/images/plus.png" />');
	} else if (type == "invitation") {
		html.find("#field_position_" + no).html('<input type="text" disabled="disabled"  id="invitation_field" class="input_fields">');
	} else if (type == "url" || type == "aim" || type == "yim" || type == "jabber" || type == "phone" || type == "description") {
		var label = jQuery('a[name="' + type + '"]').html();
		if (type == "description") {
			html.find("#field_position_" + no).html('<textarea disabled="disabled" id="default_' + type + '" rows="5" cols="73"> </textarea>');
		} else {
			html.find("#field_position_" + no).html('<input type="text" disabled="disabled"  id="default_' + type + '" class="input_fields">');
		}
		html.find("#field_label_" + no + " label").html(label);
		html.find(".edit_btn").remove();
		meta = '<input type="hidden" name="field[' + no + '][id]" value="' + no + '" id="id_' + no + '">';
		meta += '<input type="hidden" name="field[' + no + '][type]" value="default" id="type_' + no + '">';
		meta += '<input type="hidden" name="field[' + no + '][label]" value="' + label + '" id="label_' + no + '">';
		meta += '<input type="hidden" name="field[' + no + '][field_name]" value="' + type + '" id="label_' + no + '">';
	}
	return jQuery("<div/>").append(html.clone()).html() + meta;
}

function getOptions(no, optionType) {
	var html = jQuery('<div class="advance_fields  sel_options_' + no + '"/>');
	html.append('<label for="display_' + no + '">Display Value</label>');
	html.append('<input type="text" name="field[' + no + '][display][]" id="display_' + no + '" class="input_fields character_fields select_option_display" />');
	html.append('<label for="value_' + no + '">Value</label>');
	html.append('<input type="text" name="field[' + no + '][value][]" id="value_' + no + '" class="input_fields character_fields select_option_value" />');
	html.append('<label>Checked</label>');
	html.append('<input type="' + optionType + '" value="0" id="check_' + no + '" name = "field[' + no + '][selected][]" class="select_option_checked">');
	html.append('<a style="color:white" href="javascript:;" onclick="addOptions(' + no + ',\'' + optionType + '\',jQuery(this));">+</a><a style="color:white;font-size: 13px;margin-left: 2px;" href="javascript:;" onclick="jQuery(this).parent().remove();">x</a></div>');
	jQuery("#field_" + no).append("<option></option>");
	return html;
}

function getEditor() {
	var html = jQuery('<div class="advance_fields"/>');
	html.append('<textarea rows="8" id="htmlbox_' + no + '" class="ckeditor" name="field[' + no + '][html]" cols="16"></textarea>');
	return html;
}

function changeParaText(id) {
	var textarea = jQuery("#paragraph_textarea_" + id).val();
	jQuery("#paragraph_" + id).html(textarea);
}
//This will add options for select or multiselect
function addOptions(id, type, elem) {
	var html = jQuery("<div/>").append(getOptions(id, type).clone()).html();
	if (!elem) {
		jQuery(".sel_options_" + id).last().after(html);
	} else {
		jQuery(elem).parent().after(html);
	}
}
//Function to check numeric value
function isNumeric(val) {
	var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
	if (!numberRegex.test(val) || val < 1) {
		return false;
	}
}

function changeDropdown(elm) {
	var id = elm.attr("id");
	id = id.replace("display_", "");
	id = id.replace("value_", "");
	id = id.replace("check_", "");
	jQuery("#field_" + id).html("");
	jQuery('.sel_options_' + id).each(function (a, b) {
		var html = jQuery('.sel_options_' + id).eq(a).find("input.select_option_display").val();
		var val = jQuery('.sel_options_' + id).eq(a).find("input.select_option_value").val();
		jQuery('.sel_options_' + id).eq(a).find("input.select_option_checked").val(a);
		var option = jQuery("<option/>").attr("value", val).html(html);
		if (jQuery('.sel_options_' + id).eq(a).find("input.select_option_checked").is(':checked')) {
			option.attr('selected', 'selected');
		}
		option.appendTo("#field_" + id);
	});
}

function checkEvents(elm, target) {
	if (elm.checked) {
		jQuery("#" + target).hide();
		jQuery("." + target).hide();
	} else {
		jQuery("#" + target).show();
		jQuery("." + target).show();
	}
}
var pie_scroll_counter1 = false,pie_scroll_counter2 = false,pie_scroll_counter3 = false;
function add_scroll_dragable_area(id) // for jQuery scroll in right side accordion
{
	jQuery(id).mCustomScrollbar({
		scrollButtons:{
			enable:true
		}
	});
}
function bindButtons() {
	//Adding Functionalities to right menu
	jQuery(".right_menu_heading").live("click", function (e) {
		if (jQuery(this).parent().find("ul").is(':visible')) {
			jQuery(this).parent().find("ul").slideUp();
			return;
		}
		jQuery("ul.picker").slideUp();
		jQuery(this).parent().find("ul").slideDown().promise().done(function(){
			 var id = jQuery(this).attr("id");
			 if(pie_scroll_counter1 == false && id == "content_1")
			 {
				 add_scroll_dragable_area("ul#content_1");
				 pie_scroll_counter1 = true;
			 }
			 else 
			 if(pie_scroll_counter2 == false && id == "content_2")
			 {
				 add_scroll_dragable_area("ul#content_2");
				 pie_scroll_counter2 = true;
			 }
			 else 
			 if(pie_scroll_counter3 == false && id == "content_3")
			 {
				 add_scroll_dragable_area("ul#content_3");
				 pie_scroll_counter3 = true;
			 }
		 });
		
		e.preventDefault();
	});
	//Adding Functionalities to Edit buttons
	jQuery(".edit_btn").live("click", function (e) {
		jQuery(this).parents(".fields").find(".fields_main").toggle();
		e.preventDefault();
	});
	//Adding Functionalities to delete (X) buttons
	jQuery(".delete_btn").live("click", function () {
		var delId = jQuery(this).attr("rel");
		var delType = jQuery("#type_" + delId).val();
		var field = jQuery("input[name='field[" + delId + "][field_name]']").val();
		jQuery(this).parents("li").fadeOut(function () {
			jQuery(this).remove();
			if (delType == "default" || delType == "name" || delType == "address" || delType == "captcha" || delType == "invitation") {
				jQuery("ul.controls li a[name='" + field + "']").parent().show();
				jQuery("ul.controls li a[name='" + delType + "']").parent().show();
			}
		});
	});
	//Change Label ehile editing label field
	jQuery(".field_label").live("keyup", function () {
		var id = jQuery(this).attr("id");
		var val = jQuery(this).val();
		if (val != "") {
			jQuery('#field_' + id + ' label').html(jQuery(this).val());
		}
	});
	//Change Field length
	jQuery(".field_length").live("keyup", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("length_", "");
		var val = jQuery(this).val();
		if (val != "") {
			jQuery('#field_' + id).attr("maxlength", val);
		} else {
			jQuery('#field_' + id).removeAttr("maxlength");
		}
	});
	//Change Field default value
	jQuery(".field_default_value").live("keyup", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("default_value_", "");
		var val = jQuery(this).val();
		jQuery('#field_' + id).val(val);
	});
	//Change Field placeholder
	jQuery(".field_placeholder").live("keyup", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("placeholder_", "");
		var val = jQuery(this).val();
		jQuery('#field_' + id).attr("placeholder", val);
	});
	//Change Field rows
	jQuery(".field_rows").live("keyup", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("rows_", "");
		var val = jQuery(this).val();
		jQuery('textarea#field_' + id).attr("rows", val);
	});
	//Change Field Cols
	jQuery(".field_cols").live("keyup", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("cols_", "");
		var val = jQuery(this).val();
		jQuery('textarea#field_' + id).attr("cols", val);
	});
	//Next Button
	jQuery(".next_button").live("change", function () {
		if (jQuery(this).attr('checked')) {
			var val = jQuery(this).val();
			var id = jQuery(this).attr("id");
			id = id.replace("next_button_", "");
			id = id.replace("_" + val, "");
			if (val == "text") {
				jQuery("#next_button_url_container_" + id).hide();
				jQuery("#next_button_text_container_" + id).show();
			} else if (val == "url") {
				jQuery("#next_button_url_container_" + id).show();
				jQuery("#next_button_text_container_" + id).hide();
			}
		}
	});
	//Previous Button
	jQuery(".prev_button").live("change", function () {
		if (jQuery(this).attr('checked')) {
			var val = jQuery(this).val();
			var id = jQuery(this).attr("id");
			id = id.replace("prev_button_", "");
			id = id.replace("_" + val, "");
			if (val == "text") {
				jQuery("#prev_button_url_container_" + id).hide();
				jQuery("#prev_button_text_container_" + id).show();
			} else if (val == "url") {
				jQuery("#prev_button_url_container_" + id).show();
				jQuery("#prev_button_text_container_" + id).hide();
			}
		}
	});
	//Calendar Icon
	jQuery(".calendar_icon").live("change", function () {
		if (jQuery(this).attr('checked')) {
			var val = jQuery(this).val();
			var id = jQuery(this).attr("id");
			id = id.replace("calendar_icon_", "");
			id = id.replace("_" + val, "");
			if (val == "none") {
				jQuery("#icon_url_container_" + id).hide();
				jQuery("#calendar_image_" + id).hide();
			} else if (val == "calendar") {
				jQuery("#icon_url_container_" + id).hide();
				jQuery("#calendar_image_" + id).show();
			} else if (val == "custom") {
				jQuery("#icon_url_container_" + id).show();
				jQuery("#calendar_image_" + id).hide();
			}
		}
	});
	jQuery("select.date_type").live("change", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("date_type_", "");
		var val = jQuery(this).val();
		jQuery("#datefield_" + id).hide();
		jQuery("#datepicker_" + id).hide();
		jQuery("#datedropdown_" + id).hide();
		jQuery("#icon_div_" + id).hide();
		if (val == "datefield") {
			jQuery("#datefield_" + id).show();
		} else if (val == "datepicker") {
			jQuery("#datepicker_" + id).show();
			jQuery("#icon_div_" + id).show();
		} else if (val == "datedropdown") {
			jQuery("#datedropdown_" + id).show();
		}
	});
	//Change Date Format
	jQuery("select.date_format").live("change", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("date_format_", "");
		var val = jQuery(this).val();
		if (val.charAt(0) == "m" && val.charAt(val.length - 1) == "y") {
			jQuery("#dd_" + id).insertBefore(jQuery("#yy_" + id));
			jQuery("#mm_" + id).insertBefore(jQuery("#dd_" + id));
			jQuery("#day_" + id).insertBefore(jQuery("#year_" + id));
			jQuery("#month_" + id).insertBefore(jQuery("#day_" + id));
		} else if (val.charAt(0) == "d" && val.charAt(val.length - 1) == "y") {
			jQuery("#mm_" + id).insertBefore(jQuery("#yy_" + id));
			jQuery("#dd_" + id).insertBefore(jQuery("#mm_" + id));
			jQuery("#month_" + id).insertBefore(jQuery("#year_" + id));
			jQuery("#day_" + id).insertBefore(jQuery("#month_" + id));
		} else if (val.charAt(0) == "y" && val.charAt(val.length - 1) == "d") {
			jQuery("#mm_" + id).insertBefore(jQuery("#dd_" + id));
			jQuery("#yy_" + id).insertBefore(jQuery("#mm_" + id));
			jQuery("#month_" + id).insertBefore(jQuery("#day_" + id));
			jQuery("#year_" + id).insertBefore(jQuery("#month_" + id));
		}
	});
	//Change Time Format
	jQuery("select.time_format").live("change", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("time_type_", "");
		var val = jQuery(this).val();
		if (val == "12")
			jQuery("#time_format_field_" + id).show();
		else if (val == "24")
			jQuery("#time_format_field_" + id).hide();
	});
	//Change Address Type
	jQuery("select.address_type").live("change", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("address_type_", "");
		var val = jQuery(this).val();
		if (val == "International") {
			jQuery("#default_country_div_" + id).show();
			jQuery("#address_country_" + id).show();
			jQuery("#state_" + id).show();
			jQuery("#state_us_" + id).hide();
			jQuery("#state_canada_" + id).hide();
			jQuery("#default_state_div_" + id).hide();
		} else if (val == "United States") {
			jQuery("#default_country_div_" + id).hide();
			jQuery("#address_country_" + id).hide();
			jQuery("#state_" + id).hide();
			jQuery("#state_us_" + id).show();
			jQuery("#state_canada_" + id).hide();
			jQuery("#default_state_div_" + id).show();
			jQuery(".can_states_" + id).hide();
			jQuery(".us_states_" + id).show();
		} else if (val == "Canada") {
			jQuery("#default_country_div_" + id).hide();
			jQuery("#address_country_" + id).hide();
			jQuery("#state_" + id).hide();
			jQuery("#state_us_" + id).hide();
			jQuery("#state_canada_" + id).show();
			jQuery("#default_state_div_" + id).show();
			jQuery(".can_states_" + id).show();
			jQuery(".us_states_" + id).hide();
		}
		if (document.getElementById("hide_state_" + id).checked) {
			jQuery(".state_div_" + id).hide();
			jQuery("#default_state_div_" + id).hide();
		}
	});
	//Change Default Country
	jQuery("select.default_country").live("change", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("default_country_", "");
		var val = jQuery(this).val();
		jQuery("#address_country_" + id + " select").html('<option>' + val + '<option>');
	});
	//Change Name Format 
	jQuery("select.name_format").live("change", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("name_format", "");
		var val = jQuery(this).val();
		if (val == "normal") {
			jQuery("#field_label" + id + " label").hide();
			jQuery('#first_name_field input').appendTo('#first_name_field');
			jQuery('#last_name_field input').appendTo('#last_name_field');
		} else if (val == "extended") {
			jQuery("#field_label" + id + " label").show();
			jQuery('#first_name_field label').appendTo('#first_name_field');
			jQuery('#last_name_field label').appendTo('#last_name_field');
		}
	});
	//Adding option Display Value
	jQuery("input.select_option_display,input.select_option_value").live("keyup", function () {
		changeDropdown(jQuery(this));
	});
	jQuery("input.select_option_checked").live("click", function () {
		changeDropdown(jQuery(this));
	});
	jQuery(".paypal").live("click", function () {
		jQuery("#paypal_button").remove();
		jQuery("#submit_ul").hide();
		jQuery("#submit_ul").after('<ul id="paypal_button"><li class="fields"><div class="fields_options submit_field"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" /></div> <input  name="field[submit][type]" value="paypal" type="hidden" /></li></ul>');
	});
	jQuery(".submit_button").live("click", function () {
		jQuery("#paypal_button").remove();
		jQuery("#submit_ul").show();
	});
	jQuery("ul.controls li a.default").live("click", function () {
		jQuery(this).parent().hide();
	});
	jQuery("ul.controls li a.default").each(function () {
		var type = jQuery(this).attr("name");
		if (document.getElementById("default_" + type))
			jQuery(this).parent().hide();
	});
	//Allow only numeric on Length, Rows, Cols, Length Field 
	jQuery(".numeric").live("keydown", function (event) {
		if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
			// Allow: Ctrl+A
			(event.keyCode == 65 && event.ctrlKey === true) ||
			// Allow: home, end, left, right
			(event.keyCode >= 35 && event.keyCode <= 39)) {
			// let it happen, don't do anything
			return;
		} else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
				event.preventDefault();
			}
		}
	});
	//Submit Button Properties Icon
	jQuery(".reg_success").live("change", function () {
		jQuery(".submit_meta").hide();
		jQuery(".reg_success").each(function (index, element) {
			if (jQuery(this).attr('checked')) {
				var val = jQuery(this).val();
				jQuery(".submit_meta_" + val).show();
			}
		});
	});
	//default state
	jQuery(".default_state").live("change", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("can_states_", "");
		id = id.replace("us_states_", "");
		var val = jQuery(this).val();
		jQuery("#state_canada_field_" + id).html('<option>' + val + '</option>');
		jQuery("#state_us_field_" + id).html('<option>' + val + '</option>');
	});
	jQuery(".hide_state").live("change", function () {
		var id = jQuery(this).attr("id");
		id = id.replace("hide_state_", "");
		var val = jQuery(this).val();
		if (jQuery(this).attr('checked')) {
			jQuery(".state_div_" + id).hide();
			jQuery("#default_state_div_" + id).hide();
		} else {
			var val = jQuery("#address_type_" + id).val();
			if (val == "International") {
				jQuery("#default_country_div_" + id).show();
				jQuery("#address_country_" + id).show();
				jQuery("#state_" + id).show();
				jQuery("#state_us_" + id).hide();
				jQuery("#state_canada_" + id).hide();
				jQuery("#default_state_div_" + id).hide();
			} else if (val == "United States") {
				jQuery("#default_country_div_" + id).hide();
				jQuery("#address_country_" + id).hide();
				jQuery("#state_" + id).hide();
				jQuery("#state_us_" + id).show();
				jQuery("#state_canada_" + id).hide();
				jQuery("#default_state_div_" + id).show();
				jQuery(".can_states_" + id).hide();
				jQuery(".us_states_" + id).show();
			} else if (val == "Canada") {
				jQuery("#default_country_div_" + id).hide();
				jQuery("#address_country_" + id).hide();
				jQuery("#state_" + id).hide();
				jQuery("#state_us_" + id).hide();
				jQuery("#state_canada_" + id).show();
				jQuery("#default_state_div_" + id).show();
				jQuery(".can_states_" + id).show();
				jQuery(".us_states_" + id).hide();
			}
		}
	});
	jQuery("#confirm_email").live("change", function () {
		if (jQuery(this).attr('checked')) {
			jQuery("#confirm_email_label_1").show();
			jQuery("#confirm_email_field_1").show();
		} else {
			jQuery("#confirm_email_label_1").hide();
			jQuery("#confirm_email_field_1").hide();
		}
	});
	jQuery(".right_menu_heading").parent().find("ul").slideUp();
}
jQuery(document).ready(function () {
	//Hiding Advanced Options By default	
	jQuery(".fields_main").hide();
	//Click functions on control
	jQuery(".controls li a").click(function () {
		dragType = jQuery(this).attr("name");
		jQuery(this).parent().addClass("fields");
		
		
		var response = defaultMeta[dragType];
		endHtml		 = getStyle(dragType);
		if(response)
		{
			
			response = response.split("%d%").join(no);			
			endHtml 	+= response;
			endHtml		+= '<input value = "'+dragType+'" type="hidden"  name="field['+no+'][type]" id="type_'+no+'">';
		
		}
		if (endHtml) 
		{
			endHtml = '<li class="fields">' + endHtml + '</li>';
			jQuery("#elements").append(endHtml);
			if (dragType == "html") {
				CKEDITOR.replace("htmlbox_" + no, {});
			}
			jQuery(".swap_class").trigger("change");
			no++;
			dragType = "";
			endHtml = "";
		}
		
		
		
	});
	jQuery("form#formeditor").submit(function (e) {
		jQuery(".field_label").each(function (index, element) {
			if (jQuery.trim(jQuery(this).val()) == '') {
				var id = jQuery(this).attr("id");
				id = id.replace("label_", "");
				var type = jQuery("#type_" + id).val();
				if(type != "html" )
				jQuery(this).val(type);
			}
		});
		
		jQuery(".greaterzero").each(function (index, element) 
		{
			if (jQuery.trim(jQuery(this).val()) == '' || jQuery.trim(jQuery(this).val()) < 1 || jQuery.trim(jQuery(this).val()) == "0") {
				
				jQuery(this).val("1");
			}	
		});
		
		
	});
	//This will handle sorting and after drop changes		
	jQuery("#elements").droppable({
		// accept: ".controls li p",
		drop: function (event, ui) {
			ui.draggable.addClass("fields");
			if (endHtml) {			
			
			endHtml += fieldMeta;	
			fieldMeta = "";
			ui.draggable.html(endHtml);				
				
				
				no++;
				jQuery(".swap_class").trigger("change")
				if (dragType == "invitation" || dragType == "name" || dragType == "address" || dragType == "captcha" || dragType == "aim" || dragType == "yim" || dragType == "jabber" || dragType == "description" || dragType == "url") {
					jQuery('ul.controls li a[name="' + dragType + '"]').parent().hide();
				}
				dragType = "";
				endHtml = "";
				
			}
		}
	}).sortable({
		cursor: 'crosshair',
		placeholder: "sort-state-highlight",
		start: function (event, ui) {
			for (var i in CKEDITOR.instances) {
				CKEDITOR.instances[i].destroy();
			}
		},
		stop: function (event, ui) {
			jQuery('.ckeditor').each(function () {
				var id = jQuery(this).attr("id");
				CKEDITOR.replace(id, {});
			});
			dragType = "";
			endHtml = "";
		}
	});
	//Selecting and Dragging control from the list
	jQuery(".controls li").draggable({
		connectToSortable: "#elements",
		helper: "clone",
		revert: "invalid"
	});
	
	//This will change the html of a control while dragging
	jQuery(".controls li").on("dragstart", function (event, ui) {
		ui.helper.attr("class", "");
		fieldMeta	= "";
		dragType = ui.helper.find("a").attr("name");
		//currHTML = ui.helper.html();
		endHtml = getStyle(dragType);
		
		if (dragType == "url" || dragType == "aim" || dragType == "yim" || dragType == "jabber"  || dragType == "description")
		{
			
		}
		else
		{
			fieldMeta 	  = defaultMeta[dragType];		
			fieldMeta	  = fieldMeta.split("%d%").join(no);	
		}
		
		
		fieldMeta	  += '<input value = "'+dragType+'" type="hidden"  name="field['+no+'][type]" id="type_'+no+'">';		
		
		console.log(fieldMeta);
		
		if (endHtml) {
			ui.helper.addClass("fields");
		}
	});
	
	
	
	//This will change the html of a control while dragging
	jQuery(".controls li").on("drag", function (event, ui) {
		ui.helper.attr("class", "");	
		endHtml = getStyle(dragType);		
		if (endHtml) {
			ui.helper.addClass("fields");
		}
	});
	bindButtons();
	showHideReset();
});
jQuery(window).load(function () {
	jQuery("select.date_type").trigger("change");
	jQuery("select.date_format").trigger("change");
	jQuery(".calendar_icon").trigger("change");
	jQuery(".swap_class").trigger("change");
	jQuery(".submit_meta").hide();
	jQuery(".reg_success").trigger("change");
	jQuery("select.address_type").trigger("change");
	jQuery(".next_button").trigger("change");
	jQuery(".prev_button").trigger("change");
	//jQuery(".name_format").trigger("change");
});

function fillValues(data, num) //Function to fill meta data
{
	var field = unserialize(data);
	var mt = "field[" + num + "][type]";
	var maintype = jQuery('*[name="' + mt + '"]').val();
	for (index in field) {
		var fieldname = "field[" + num + "][" + index + "]";
		var fieldvalue = field[index];
		var fieldtag = jQuery('*[name="' + fieldname + '"]').prop('tagName');
		var fieldtype = jQuery('*[name="' + fieldname + '"]').prop('type');
		if (fieldtag == "SELECT" || fieldtag == "TEXTAREA" || (fieldtag == "INPUT" && (fieldtype == "text" || fieldtype == "hidden"))) //For textfields , select textareas
		{
			jQuery('*[name="' + fieldname + '"]').val(fieldvalue);
		} else if (fieldtag == "INPUT" && (fieldtype == "radio" || fieldtype == "checkbox")) //For Radio && checkbox
		{
			if (fieldvalue != "") {
				jQuery('*[name="' + fieldname + '"][value="' + fieldvalue + '"]').attr('checked', 'checked');
			} else {
				jQuery('*[name="' + fieldname + '"]').attr('checked', 'checked');
			}
		} else if ((maintype == "dropdown" || maintype == "multiselect" || maintype == "radio" || maintype == "checkbox") && index == "display") {
			if (maintype == "dropdown" || maintype == "radio")
				var subtype = "radio";
			else if (maintype == "multiselect" || maintype == "checkbox")
				var subtype = "checkbox";
			for (a = 1; a < field["display"].length; a++) {
				addOptions(num, subtype);
			}
			for (a = 0; a < field["display"].length; a++) {
				var fieldname = "field[" + num + "][value][]";
				jQuery('input[name="' + fieldname + '"]').eq(a).val(field['value'][a]);
				var fieldname = "field[" + num + "][display][]";
				jQuery('input[name="' + fieldname + '"]').eq(a).val(field['display'][a]);
				var fieldname = "field[" + num + "][selected][]";
				jQuery('input[name="' + fieldname + '"]').eq(a).val(a);
			}
			if (field["selected"]) {
				//If Item is selected
				var fieldname = "field[" + num + "][selected][]";
				fieldname = jQuery('input[name="' + fieldname + '"]');
				for (a = 0; a < fieldname.length; a++) {
					for (b = 0; b < field['selected'].length; b++) {
						if (a == field['selected'][b]) {
							fieldname.eq(a).attr("checked", "checked");
						}
					}
				}
			}
		}
	}
}

function createckeditor() {
	jQuery(".ckeditor").ckeditor();
}

function swapClass(val) {
	if (val == "top") {
		jQuery(".fields_position").addClass("fields_position_top");
		jQuery(".fields_position_top").removeClass("fields_position");
		jQuery(".label_position").addClass("label_position_top");
		jQuery(".label_position_top").removeClass("label_position");
	} else if (val == "left") {
		jQuery(".fields_position_top").addClass("fields_position");
		jQuery(".fields_position").removeClass("fields_position_top");
		jQuery(".label_position_top").addClass("label_position");
		jQuery(".label_position").removeClass("label_position_top");
	}
}
function showHideReset()
{
	var val = jQuery("#show_reset").val();
	var elm = jQuery("#reset_btn");
	if(val == 0 )
		elm.hide();
	else
		elm.show();	
}