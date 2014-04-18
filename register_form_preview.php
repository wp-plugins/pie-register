<?php

//For backwards compatibility, load wordpress if it hasn't been loaded yet
//Will be used if this file is being called directly
if(!class_exists("PieRegister")){
    for ( $i = 0; $i < $depth = 10; $i++ ) {
        $wp_root_path = str_repeat( '../', $i );

        if ( file_exists("{$wp_root_path}wp-load.php" ) ) {
            require_once("{$wp_root_path}wp-load.php");
            require_once("{$wp_root_path}wp-admin/includes/admin.php");
            break;
        }
    }

    //redirect to the login page if user is not authenticated
    auth_redirect();
}



?>
<html>
<head>
<title><?php _e("Form Preview", "piereg") ?></title>
<link type="text/css" rel="stylesheet" href="<?php echo get_bloginfo('url')?>/wp-content/plugins/pie-register/css/front.css"  />
<script type='text/javascript' src='<?php echo plugins_url("js/jquery.js",__FILE__)?>'></script>

<script type='text/javascript' src='<?php echo plugins_url("js/validation.js",__FILE__)?>'></script>
<script type='text/javascript' src='<?php echo plugins_url("js/jquery.validationEngine-en.js",__FILE__)?>'></script>
<script type='text/javascript' src='<?php echo plugins_url("js/jquery-ui.js",__FILE__)?>'></script>
<script type='text/javascript' src='<?php echo plugins_url("js/datepicker.js",__FILE__)?>'></script>

</head>
<body class="piereg_preview_page">
<div class="piereg_main_wrapper">
<?php
//Printing Success Message
if($_POST['success'] != "")
	echo '<p class="piereg_message">'.apply_filters('piereg_messages',__($_POST['success'],"piereg")).'</p>';
if(sizeof($errors->errors) > 0)
{
	foreach($errors->errors as $err)
	{
		$error .= $err[0] . "<br />";	
	}
	echo '<p class="piereg_login_error">'.apply_filters('piereg_messages',__($error,"piereg")).'</p>';
}
	
$form->addFormData();


if($form->countPageBreaks() > 1){
		
?>
<div class="pieregformWrapper">
<div class="piereg_progressbar"></div>
<?php
echo PieRegister::piereg_ProgressBarScripts($form->countPageBreaks());
}
?>
<form enctype="multipart/form-data" id="pie_regiser_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
  <ul id="pie_register">
    <?php
	echo $form->printFields();
?>
  </ul>
</form>
</div>
<script type="text/javascript">
wp_custom_login_remove_element('wp-admin-css');
wp_custom_login_remove_element('colors-fresh-css');

function wp_custom_login_remove_element(id) 
{
	if(!document.getElementById(id))
	return false;
	var element = document.getElementById(id);
	element.parentNode.removeChild(element);
}
<?php 
if($form->pages > 1)
{
?>
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


<?php } ?>
</script>
</div>
</body>
</html>