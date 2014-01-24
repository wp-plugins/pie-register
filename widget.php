<?php
require_once('classes/registration_form.php');
class Pie_Register_Widget extends WP_Widget 
{
	function __construct() 
	{
		parent::__construct(
			'pie_widget', // Base ID
			__('Pie Register - Registration Form', 'pie_register'), // Name
			array( 'description' => __( 'Registration Form', 'pie_register' ), ) // Args
		);		
		
	}
	
	public function widget( $args, $instance ) 
	{
		global $errors;		
		$form 		= new Registration_form();
		$success 	= '' ;
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		
		$this->forms_styles();				
	
		include("register_form.php");
		echo $args['after_widget'];		
	}
	function forms_styles()
	{
		wp_register_style( 'prefix-style', plugins_url('css/front.css', __FILE__) );
		wp_enqueue_style( 'prefix-style' );	
		wp_enqueue_script( 'jquery' );	
		wp_enqueue_script('jquery-ui-datepicker');	
		wp_enqueue_script("validation",plugins_url('js/validation.js', __FILE__) );
		wp_enqueue_script("validation-lang",plugins_url('js/jquery.validationEngine-en.js', __FILE__) ,array(),false,true);		
		wp_register_style( 'validation', plugins_url('css/validation.css', __FILE__) );
		wp_enqueue_style( 'validation' );
		wp_enqueue_script("datepicker",plugins_url('pie-register/js/datepicker.js') );	
		add_action("wp_head",array($this,"addUrl"));
	}
	
}
class Pie_Login_Widget extends WP_Widget 
{/**
	 * Register widget with WordPress.
	 */
	function __construct() 
	{
		parent::__construct(
			'pie_login_widget', // Base ID
			__('Pie Register - Login Form', 'pie_login'), // Name
			array( 'description' => __( 'Login Form', 'pie_login' ), ) // Args
		);		
		
	}
	public function widget( $args, $instance ) 
	{
		echo $args['before_widget'];
		global $errors;
		wp_register_style( 'prefix-style', plugins_url('css/front.css', __FILE__) );
		wp_enqueue_style( 'prefix-style' );	
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script('jquery-ui-datepicker');	
		wp_enqueue_script("validation",plugins_url('js/validation.js', __FILE__) );
		wp_enqueue_script("validation-lang",plugins_url('js/jquery.validationEngine-en.js', __FILE__) );		
		wp_register_style( 'validation', plugins_url('css/validation.css', __FILE__) );
		wp_enqueue_style( 'validation' );
		wp_enqueue_script("datepicker",plugins_url('pie-register/js/datepicker.js') );
	
		
		if ( !is_user_logged_in() ) 
		{
			echo '<h4 class="widget-title widgettitle">Member Login</h4>';
			include("login_form.php");
		}
		else
		{
			$current_user = wp_get_current_user();
			echo '<h4 class="widget-title widgettitle">Member Login</h4>';
			echo '<div class="logged-In"><img src="'.plugin_dir_url(__FILE__).'images/userImage.png"/>';
			echo '<div class="member_div"><h4><a href="javascript:;">' . $current_user->user_login . '</a></h4>';
			echo '<a href="'.wp_logout_url( ).'" class="logout-link" title="Logout">Logout</a></div></div>';	
		}
		echo $args['after_widget'];
			
	}
	
}
class Pie_Forgot_Widget extends WP_Widget 
{
	function __construct() 
	{
		parent::__construct(
			'pie_forgot_widget', // Base ID
			__('Pie Register - Forgot Password Form', 'pie_forgot'), // Name
			array( 'description' => __( 'Forgot Password Form', 'pie_forgot' ), ) // Args
		);	
	}
	public function widget( $args, $instance ) 
	{
		echo $args['before_widget'];
		global $errors;
		wp_register_style( 'prefix-style', plugins_url('css/front.css', __FILE__) );
		wp_enqueue_style( 'prefix-style' );	
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script('jquery-ui-datepicker');	
		wp_enqueue_script("validation",plugins_url('js/validation.js', __FILE__) );
		wp_enqueue_script("validation-lang",plugins_url('js/jquery.validationEngine-en.js', __FILE__) );		
		wp_register_style( 'validation', plugins_url('css/validation.css', __FILE__) );
		wp_enqueue_style( 'validation' );
		wp_enqueue_script("datepicker",plugins_url('pie-register/js/datepicker.js') );	
		include("forgot_password.php");	
		echo $args['after_widget'];
			
	}
	
}