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
<title><?php _e("Form Preview", "pireg") ?></title>
<link type="text/css" rel="stylesheet" href="<?=get_bloginfo('url')?>/wp-content/plugins/pie-register/css/front.css"  />
<script type='text/javascript' src='<?=plugins_url("js/jquery.js",__FILE__)?>'></script>

<script type='text/javascript' src='<?=plugins_url("js/validation.js",__FILE__)?>'></script>
<script type='text/javascript' src='<?=plugins_url("js/jquery.validationEngine-en.js",__FILE__)?>'></script>
<script type='text/javascript' src='<?=plugins_url("js/jquery-ui.js",__FILE__)?>'></script>
<script type='text/javascript' src='<?=plugins_url("js/datepicker.js",__FILE__)?>'></script>

</head>
<body class="preview_page">
<div class="main_wrapper">
<?
//Printing Success Message
if($_POST['success'] != "")
	echo '<p class="message">'.$_POST['success'].'</p>';
if(sizeof($errors->errors) > 0)
{
	foreach($errors->errors as $err)
	{
		$error .= $err[0] . "<br />";	
	}
	echo '<p class="login_error">'.$error.'</p>';
}
	
$form->addFormData();


if($form->countPageBreaks() > 1)
{
	
	wp_enqueue_script("jquery-ui",plugins_url('pie-register/js/jquery-ui.js'),array(),false,true );	
		
?>
<div class="form_wrapper">
<div id="progressbar"></div>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
  jQuery(document).ready(function(e) {
  	 jQuery( "#progressbar" ).progressbar({
      value:  1 /<?=$form->countPageBreaks()?> * 100
    });  
});
 
 </script>
<?	
}
?>
<form enctype="multipart/form-data" id="pie_regiser_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
  <ul id="pie_register">
    <?
	$form->printFields();
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
<? 
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
function pieNextPage(pageNo)
{
	pieHideFields();	
	var elms = document.getElementsByClassName('pageFields_'+pageNo);
	for(a = 0 ; a < elms.length ; a++)
	{
		elms[a].style.display = "";	
	} 
	
	
 jQuery( "#progressbar" ).progressbar( "option", {
          value: pageNo /<?=$form->pages?> * 100
        }); 
	 	
}
function pieHideFields()
{
	var elms = document.getElementsByClassName('fields');
	for(a = 0 ; a < elms.length ; a++)
	{
		elms[a].style.display = "none";	
	}
}

<? } ?>
</script>
</div>
</body>
</html>