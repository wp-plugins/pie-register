=== Pie Register ===
Contributors: genetech

Tags: register, registration, password, invitation, custom login, ajax login, custom registration, enhance login, enhance profile, custom  logo screen, user registration
Requires at least: 3.5
Tested up to: 3.6.1
Stable tag: 1.31.2

Enhance default Registration form, Custom logo, Password field, Invitation codes, Paypal, Captcha validation, Email verification, user moderation & more.

== Description ==
# Welcome to Pie-Register by Genetech Solutions.


So you want to have users register and login to your site before they interact with it? You need user registration and you don't have to build it from scratch. With Pie-Register you can use your logo and color scheme to brand your registration pages to your liking.  Use this plugin to quickly add custom user registration to your WordPress based blog or site.


Want your customers to pay for the service you provide? The plugin allows you to charge the users when they register with PayPal integration.


And there is more, you can:

* __Send Invitation Codes__
* __Use CAPTCHA__
* __Email Validation__
* __User Profile Moderation__
* __User Defined Fields__


Use the free Pie-Register plugin to easily add features like these to your service; they help you go above and beyond your competitors. There are more features to come in the weeks ahead.


See the plugin features section for complete details.


# Features:


Pie-Register has great features which you can’t find in any other free plugin.


**Custom Logo and Color Scheme** Use your own logo and color scheme and get your brand in the spotlight.

**Password Field** Hate those forgettable auto-generated passwords? Allow your users to set their own passwords during registration.  The plugin includes that sweet Password Strength Meter.

**Invitation Codes** Is your blog super exclusive? If so, you better require an invite to join your high end crew. Setup multiple codes and track where your new users are coming from with the optional Invitation Tracking Dashboard Widget.

**Disclaimers** Worried about legal liabilities? Setup a general disclaimer, license agreement and/or privacy policy for new users to agree to during registration.

**CAPTCHA Validation** If you don’t want those spam bots registering use CAPTCHA protection. The plugin includes a simple Captcha easy enough for real humans to read as well as the ability to add a re-CAPTCHA.

**Email Validation** Ensure your users are registering with valid email accounts by forcing them to click a validation link that’s sent out with their registration email.  Email validation initially sets the username to a random generated string (something like: ‘unverified__h439herld3′). The user can’t login until they click on that validation link sent in the email. This will put their real username in place allowing them to login. 

**Unverified registrations** have a defined grace period. They are automatically deleted after a specified period of time so you don’t get clogged up with those fakes. (Manage under Users > Unverified Users)

**User Moderation** Want absolute control? Check out every new user yourself and hand pick who can stay and who gets the boot before they are able to login to your site. (Manage under Users > Unverified Users)

**Profile Fields** Have new users fill out there entire profile or just some fields. Make fields optional or required as needed.

**User Defined Fields** Add your own defined fields to the registration page for users to fill out. Includes ability to add date, selection, checkbox, radio and text area fields!

**Duplicate Email Registration** Got multiple users using the same email address? Easily solve this predicament without forcing them to sign up with unneeded email accounts.  This is also useful for administrators to create another account with one email address.

**Customized Admin & User Registration Email** Tired of the same old emails when someone new registers? Spice it up with your own From/Reply-To address, customized subject and customize the entire message! You can even disable those tiresome Admin notifications for new registrations.

**Membership Fee** Charge your users for membership via Pie-Register’s built-in PayPal integration.

== Installation ==
Just follow the simple steps:

1. Upload the `pie-register` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set the options in the Settings Panel


== LOCALIZATION ==
* Currently This feature is not available. We are working on it to improve.

== Support ==
* Please visit http://www.genetechsolutions.com/pie-register.html in order to get support regarding this plugin!

== CHANGELOG ==
### v1.32 September 23 2013
* Stripslashes issue resolved
* function mb_string has change to htmlentities with utf8_decode functions
* wpdb_prepare replaced with sanitize function
* Empty URL field is now allowed on After Sign in redirect.
* Custom Fields are now removeable.
* Grace Period issue resolved.

### v1.31 July 13 2013
* Backward Compatibility Issues fixed.
* Plugin Conflict Issue fixed.
* Input has been sanitized.
* UTF-8 characters are now supported by necessary text fields.
* reCaptcha Library has been upgraded.


### v1.30 July 11 2013
#### New Features:

* You can now specify the landing page on successful login.
* Added Logo URL box for Login pages.
* Added 'Reset to defaults' Button on Settings Page.
* Replaced the static path for admin, includes and plugin folders with WP relative Path Functions
* Replaced the WordPress Link and ALT text from login and registration page logo with site URL and NAME
* Invitation code will now appear on the user profile page.

#### Bugs Fixed:

* Fixed Paypal issues and upgraded the IPN.
* Fixed password strength meter error.
* Fixed invitation code widget and auto delete problem.
* Fixed re-Captcha size issue on registration form.
* Fixed some security issues.

### v1.2.91 January 01 2013
* Fixed PHP Warning: Missing argument 2 for wpdb::prepare()

### v1.2.9a March 30 2012
* Fixed Custom Fields Reseting problem
* Fixed Invitation Code update problem


### v1.2.9 March 9 2012
* Fixed Array problem on text fields.
* Compatible upto wp 3.3.*

### v1.2.8 beta October 31 2011
* Invitation Code Section has been extended
* Custom Email Notification on Admin/Email verification
* deprecated Functions has been replaced
* No need to place the "Session" Variable in the wp-login.php file, anymore
* and much much more..
* Warning! This is the beta Version of the Pie-Register, Do not upgrade unless you've upgraded Wordpress to 3.2* Version.

### v1.2.7 October 4 2011
* New ScreenShots Added

### v1.2.6 October 4 2011
* Fixed Multiple Invitation Saving Problem

=v1.2.5 October 3 2011=
*Fixed custom meta fields "Saving" problem by the admin. (Fixed by Julian Warren with Thanks)
*Added New Layout Pie-Register Menu built!

### v1.2.4 September 21 2011
*Fixed the "Backslash" problem on the admin Setting Page.

### v1.2.3 August 8 2011
*Highlighted the Code to put on the wp-login.php at the plugin page.

### v1.2.2 February 20 2011

* Fixed Settings Override.
* Compatible to Wp 3.0.5


### v1.2.1 January 20 2011

* Fixed Password strength meter.
* Compatible to Wp 3.0.4


=v1.2.0 June 25 2010=

* Fixed Image uploads errors.
* First step to Compatiblity to Wp 3.0

### v1.1.9a April 07 2010

* Fixed Error after registration.

### v1.1.9 April 06 2010

* Fixed Login box after verification.
* Fixed diplaying invitation codes to the user dashboard.

### v1.1.8 March 03 2010

* Fixed Payment link.
* Fixed session errors/header errors.
* And much much more..

### v1.1.7 Febraury 04 2010

* Fixed Resend verification email.
* Fixed resend payment link.

### v1.1.6 February 03 2010

* Fixed Security updates for Paypal resend code.
* Add Phone/Mobile number Field.

### v1.1.5 February 02 2010

* Keep away Unverified/Un-paid users to get logged in.
* Fixed Logo display and form fields.
* Fixed short tags enabled.
* Fixed Date fields.
* Changed Paypal Buy now to One -Time Subscription fee Button.

### v1.1.3** January 08 2010

* Fixed User Registeration after Paypal Return.


### v1.1.2** January 07 2010

* Fixed temp user id and email verification.

### v1.1.1** January 06 2010

* Added Paypal Return and thank you URL.

### v1.0.1** January 04 2010

* Fixed Password meter.
* Added Paypal as shopping Cart


== Screenshots ==

1. Registration Page
2. Pie Register Settings
3. Invitation Tracking Dashboard Widget
4. Unverified User Management