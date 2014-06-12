var piereg = jQuery.noConflict();

/*function check_pass_strength ( ) {

		var pass = piereg('#pass1').val();

		var pass2 = piereg('#pass2').val();

		var user = piereg('#user_login').val();

		// get the result as an object, i'm tired of typing it

		var res = piereg('#pass-strength-result');

		var strength = passwordStrength(pass, user, pass2);

		piereg(res).removeClass('short bad good strong mismatch');

		 if ( strength == 1 ) {

			// this catches 'Too short' and the off chance anything else comes along

			piereg(res).addClass('short');

			piereg(res).html( pwsL10n.short );

		}

		else if ( strength == 2 ) {

			piereg(res).addClass('bad');

			piereg(res).html( pwsL10n.bad );

		}

		else if ( strength == 3 ) {

			piereg(res).addClass('good');

			piereg(res).html( pwsL10n.good );

		}

		else if ( strength == 4 ) {

			piereg(res).addClass('strong');

			piereg(res).html( pwsL10n.strong );

		}

		else if ( strength == 5 ) {

			piereg(res).addClass('mismatch');

			piereg(res).html( pwsL10n.mismatch );

		}

		else {

			// this catches 'Too short' and the off chance anything else comes along

			piereg(res).addClass('short');

			piereg(res).html( pwsL10n.short );

		}

	}

	piereg(function($) { 

		$('#pass1').keyup( check_pass_strength );

		$('#pass2').keyup( check_pass_strength )

		$('.color-palette').click(function(){$(this).siblings('input[name=admin_color]').attr('checked', 'checked')});

	} );*/