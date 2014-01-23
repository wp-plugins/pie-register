function check_pass_strength ( ) {
		var pass = jQuery('#pass1').val();
		var pass2 = jQuery('#pass2').val();
		var user = jQuery('#user_login').val();
		// get the result as an object, i'm tired of typing it
		var res = jQuery('#pass-strength-result');
		var strength = passwordStrength(pass, user, pass2);
		jQuery(res).removeClass('short bad good strong mismatch');
		 if ( strength == 1 ) {
			// this catches 'Too short' and the off chance anything else comes along
			jQuery(res).addClass('short');
			jQuery(res).html( pwsL10n.short );
		}
		else if ( strength == 2 ) {
			jQuery(res).addClass('bad');
			jQuery(res).html( pwsL10n.bad );
		}
		else if ( strength == 3 ) {
			jQuery(res).addClass('good');
			jQuery(res).html( pwsL10n.good );
		}
		else if ( strength == 4 ) {
			jQuery(res).addClass('strong');
			jQuery(res).html( pwsL10n.strong );
		}
		else if ( strength == 5 ) {
			jQuery(res).addClass('mismatch');
			jQuery(res).html( pwsL10n.mismatch );
		}
		else {
			// this catches 'Too short' and the off chance anything else comes along
			jQuery(res).addClass('short');
			jQuery(res).html( pwsL10n.short );
		}
	}
	jQuery(function($) { 
		$('#pass1').keyup( check_pass_strength );
		$('#pass2').keyup( check_pass_strength )
		$('.color-palette').click(function(){$(this).siblings('input[name=admin_color]').attr('checked', 'checked')});
	} );