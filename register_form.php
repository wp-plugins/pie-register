<?php



function outputRegForm($fromwidget=false){

			$form 		= new Registration_form();

			$success 	= '' ;

			$error 		= '' ;

			$option 	= get_option( 'pie_register_2' );

$registration_from_fields = '<div class="pieregformWrapper pieregWrapper"><style type="text/css">

.field_note{font-size:12px; color:#FF0000;}

.required{color:#FF0000}

</style>';

$registration_from_fields .= '<div id="show_pie_register_error_js" class="piereg_entry-content"></div>';



/*//Printing Success Message

if($_POST['success'] != "")

	$registration_from_fields .= '<p class="piereg_message">'.apply_filters('piereg_messages',__($_POST['success'],"piereg")).'</p>';

if($_POST['error'] != "")

	$registration_from_fields .= '<p class="piereg_login_error">'.apply_filters('piereg_messages',__($_POST['error'],"piereg")).'</p>';

if(sizeof($errors->errors) > 0)

{

	foreach($errors->errors as $err)

	{

		$error .= $err[0] . "<br />";	

	}

	$registration_from_fields .= '<p class="piereg_login_error">'.apply_filters('piereg_messages',__($error,"piereg")).'</p>';

}

	*/

	

$registration_from_fields .= $form->addFormData();









$registration_from_fields .= '<div id="pie_register_reg_form">';



$registration_from_fields .= '<form enctype="multipart/form-data" id="pie_regiser_form" method="post" action="'.$_SERVER['REQUEST_URI'].'">';

if($form->countPageBreaks() > 1){

	$registration_from_fields .= '<div class="piereg_progressbar"></div>';

}

$registration_from_fields .= '<ul id="pie_register">';



	   //ob_start();

	   $output = $form->printFields($fromwidget);

	   $registration_from_fields .= $output;

		/*$registration_from_fields .= '<li class="pieloading_status">';

		$registration_from_fields .= __('Please wait..','piereg');

		$registration_from_fields .='</li>';*/

	   //return $registration_from_fields;

	   //ob_end_flush();

$registration_from_fields .= '</ul>	';

$registration_from_fields .= '</form>';



if($form->pages > 1)

{

	$registration_from_fields.= <<<EOL

	<script type="text/javascript">

	pieHideFields();

if(window.location.hash) 

{

	var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character 

	var elms = document.getElementsByClassName('pageFields_'+hash);

	for(a = 0 ; a < elms.length ; a++)

	{

		elms[a].style.display = "";	

	}   

} 

else 

{

    var elms = document.getElementsByClassName('pageFields_1');

	for(a = 0 ; a < elms.length ; a++)

	{

		elms[a].style.display = "";	

	}   

}





</script>

EOL;



 }

 if($form->countPageBreaks() > 1){

	$registration_from_fields .= PieRegister::piereg_ProgressBarScripts($form->countPageBreaks());

}

 $registration_from_fields.='</div></div>';

return $registration_from_fields;

}