function set_add_del_code(){

	jQuery('.remove_code').show();

	jQuery('.add_code').hide();

	jQuery('.add_code:last').show();

	jQuery(".code_block:only-child > .remove_code").hide();

}

function selremcode(clickety){

	jQuery(clickety).parent().remove(); 

	set_add_del_code(); 

	return false;

}

function seladdcode(clickety){

	jQuery('.code_block:last').after(

    	jQuery('.code_block:last').clone());

	jQuery('.code_block:last input').attr('value', '');



	set_add_del_code(); 

	return false;

}

function set_add_del(){

	jQuery('.remove_row').show();

	jQuery('.add_row').hide();

	jQuery('.add_row:last').show();

	jQuery(".row_block:only-child > .remove_row").hide();

}

function selrem(clickety){

	jQuery(clickety).parent().parent().remove(); 

	set_add_del(); 

	return false;

}

function seladd(clickety){

	jQuery('.row_block:last').after(

    	jQuery('.row_block:last').clone());

	jQuery('.row_block:last input.custom').attr('value', '');

	jQuery('.row_block:last input.extraops').attr('value', '');

	var custom = jQuery('.row_block:last input.custom').attr('name');

	var reg = jQuery('.row_block:last input.reg').attr('name');

	var profile = jQuery('.row_block:last input.profile').attr('name');

	var req = jQuery('.row_block:last input.required').attr('name');

	var fieldtype = jQuery('.row_block:last select.fieldtype').attr('name');

	var extraops = jQuery('.row_block:last input.extraops').attr('name');

	var c_split = custom.split("[");

	var r_split = reg.split("[");

	var p_split = profile.split("[");

	var q_split = req.split("[");

	var f_split = fieldtype.split("[");

	var e_split = extraops.split("[");

	var split2 = c_split[1].split("]");

	var index = parseInt(split2[0]) + 1;

	var c_name = c_split[0] + '[' + index + ']';

	var r_name = r_split[0] + '[' + index + ']';

	var p_name = p_split[0] + '[' + index + ']';

	var q_name = q_split[0] + '[' + index + ']';

	var f_name = f_split[0] + '[' + index + ']';

	var e_name = e_split[0] + '[' + index + ']';

	jQuery('.row_block:last input.custom').attr('name', c_name);

	jQuery('.row_block:last input.reg').attr('name', r_name);

	jQuery('.row_block:last input.profile').attr('name', p_name);

	jQuery('.row_block:last input.required').attr('name', q_name);

	jQuery('.row_block:last select.fieldtype').attr('name', f_name);

	jQuery('.row_block:last input.extraops').attr('name', e_name);

	set_add_del(); 

	return false;

}

function toggleVerificationType(first_type,second_type){
	if(jQuery(second_type).is(":checked")){
		jQuery(second_type).attr("checked",false);
	}
}
