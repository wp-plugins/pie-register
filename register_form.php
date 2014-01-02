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
	
	jQuery('html, body').animate({
        scrollTop: jQuery("#progressbar").offset().top
    }, 100);
	
	
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