=== Pie Register ===
Contributors: genetech

Tags: register, registration, password, invitation, custom login, custom registration, enhance login, enhance profile, custom  logo screen, user registration, custom profile page
Requires at least: 3.5
Tested up to: 3.9.1
Stable tag: 2.0.9

Custom Registration form, Custom Login Pages, Custom Profile Page, Invitation codes, Paypal, Email verification, user moderation & more.
== Description ==
**Welcome to Pie-Register by Genetech Solutions.**

So you want to have users register and login to your site before they interact with it? You need user registration and you don't have to build it from scratch. With Pie-Register you can use your logo and color scheme to brand your registration pages to your liking.  Use this plugin to quickly add custom user registration to your WordPress based blog or site.

Want your customers to pay for the service you provide? The plugin allows you to charge the users when they register with PayPal integration.

And there is more, you can:

*   **Customised Registration Form**
*   **Charge Users for Registration**
*   **Enable Email Verification or Admin Moderation**
*   **Create Custom Profile Pages**
*   **Use Shortcodes and Widgets**

Use the free Pie-Register plugin to easily add features like these to your service; they help you go above and beyond your competitors.

See the plugin features section for complete details.

**Features Highlight:**

Pie-Register has great features which you can’t find in any other free plugin.

1.   **New and Improved User Interface**
2.   **Custom Login/Registration Pages**
3.   **Invitation Codes**
4.   **Shortcodes**
5.   **CAPTCHA Validation**
6.   **Email Verification**
7.   **Admin Moderation**
8.   **Widgets**
9.   **Profile Pages**
10.   **Customized Admin & User Registration Email**
11.   **Paypal**


== Installation ==
Just follow the simple steps:

1.   **Upload the `pie-register` directory to the `/wp-content/plugins/` directory**
2.   **Activate the plugin through the 'Plugins' menu in WordPress**

Please follow our plugin [documentation](http://pieregister.genetechsolutions.com/documentation/ "Pie-Register Documentation") on how to Create Registration Form, Login , Forgot Password and Profile Pages using Shortcodes and Widgets.



== Support Forum ==
Please visit our [Website Forum](http://pieregister.genetechsolutions.com "Pie-Register Forum") in order to get support regarding this plugin!

== CHANGELOG ==
### 2.0.9
* Replace all Static URI calls with proper WP URI Functions
* Added ability to redirect Users to an External URLs on Logout
* Changed Password Strength Meter wth Wordpress
* Fixed Several jQuery/Javascript issues and conlicts with several themes and plugins
* Fixed Invitation Code table upgrading issue (Removed DB Delta)
* Made Default Fields labels editable both at Form Editor and Translation files
* Fixed Several Spelling Mistakes
* Tested with different Plugins and themes

### 2.0.8
* Added Paypal IPN verification
* Added Logout URL with after logout page redirection via Settings page (i.e: http://example.com/?piereg_logout_url=true)
* Updated Email Templates
* Fixed Headers Problems on Email Templates
* Updated Replacement keys on Email templates
* Remove user Password variable from Templates
* Fixed All Email templates Notifications
* Fixed Invitation Code Validation when set not Required
* Fixed After login Redirection issues
* Updated Text Editor
* Fixed Post Header hidden problems.
* Added User IP variable on Email Templates
* Compaitbility checks with many other plugins and much more.

### 2.0.7
* Made License key requirement optional and removed Popup of Un-Registered Version.

### 2.0.6
* Upload missing file `invitation_code_pagination.php` into the package.
* Fixed File Upload issues.

### 2.0.5
* Added Invitation Code Dashboard Widget
* Added Return and Reply Path on All Emails sent via Pie-Register
* Removed Option to Modify Avatar
* Stopped PR pages if already exist.
* Added option to Override WP Profile Page
* Added Option to Override WP Login/Registration/Forgot Password Pages.
* Enhanced Invitation Code Module
* Fixed Shortcodes related issues.
* Display Error when CURL or FOPEN library not installed on Server.
* Updated User Role Function.
* Fixed Upgrading issues.
* Fixed Email sending issues.
* Fixed CSS issues.
* Added option to import version 1.0 Email templates.
* Fixed lot of minor issues and much more..

### 2.0.4
* Added DB Version
* Fixed Upgrading to version 2.0 issues from older version

### 2.0.3
* Disable Pie-Register to modify avatars
* Made Custom CSS and Tracking Code appears across the site

### 2.0.2
* Major Release
* First Stable version of 2.0 family
* Fixed a lot of bugs in version 2.0.1

### v2.0.1
* Fixed layout issues
* Fixed Jquery Conflicts
* Fixed profile Edit page
* Fixed Redirect issues
* Added WP User Roles on Registration
* Added Profile Picture Option
* Added Registration Date on Email templates
* Added help page
* Updated Get Free License Option
* and much more

### v2.0.beta
* A totally new look sporting an attractive New Interface
* Revamped with a lot of New Features.
* Built in intuitive Form Editor
* Added Shortcodes.
* and much much more..
* Warning: This is Beta Release - NOT recommended to upgrade/install on Production sites, it still in beta, suggestions and bugs reporting are welcome

### v1.31.3
* zxcvbn lib is added
* Password Strength meter fix and compatible for future versions.
* UTF character bug fixed
* Login Enqueue function added.

### v1.31.2
* Stripslashes issue resolved
* function mb_string has change to htmlentities with utf8_decode functions
* wpdb_prepare replaced with sanitize function
* Empty URL field is now allowed on After Sign in redirect.
* Custom Fields are now removeable.
* Grace Period issue resolved.

### v1.31
* Backward Compatibility Issues fixed.
* Plugin Conflict Issue fixed.
* Input has been sanitized.
* UTF-8 characters are now supported by necessary text fields.
* reCaptcha Library has been upgraded.


### v1.30
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

### v1.2.91
* Fixed PHP Warning: Missing argument 2 for wpdb::prepare()

### v1.2.9a
* Fixed Custom Fields Reseting problem
* Fixed Invitation Code update problem


### v1.2.9
* Fixed Array problem on text fields.
* Compatible upto wp 3.3.*

### v1.2.8
* Invitation Code Section has been extended
* Custom Email Notification on Admin/Email verification
* deprecated Functions has been replaced
* No need to place the "Session" Variable in the wp-login.php file, anymore
* and much much more..
* Warning! This is the beta Version of the Pie-Register, Do not upgrade unless you've upgraded Wordpress to 3.2* Version.

### v1.2.7
* New ScreenShots Added

### v1.2.6 October 4 2011
* Fixed Multiple Invitation Saving Problem

=v1.2.5
*Fixed custom meta fields "Saving" problem by the admin. (Fixed by Julian Warren with Thanks)
*Added New Layout Pie-Register Menu built!

### v1.2.4
*Fixed the "Backslash" problem on the admin Setting Page.

### v1.2.3
*Highlighted the Code to put on the wp-login.php at the plugin page.

### v1.2.2

* Fixed Settings Override.
* Compatible to Wp 3.0.5


### v1.2.1

* Fixed Password strength meter.
* Compatible to Wp 3.0.4


=v1.2.0

* Fixed Image uploads errors.
* First step to Compatiblity to Wp 3.0

### v1.1.9a

* Fixed Error after registration.

### v1.1.9

* Fixed Login box after verification.
* Fixed diplaying invitation codes to the user dashboard.

### v1.1.8

* Fixed Payment link.
* Fixed session errors/header errors.
* And much much more..

### v1.1.7

* Fixed Resend verification email.
* Fixed resend payment link.

### v1.1.6

* Fixed Security updates for Paypal resend code.
* Add Phone/Mobile number Field.

### v1.1.5

* Keep away Unverified/Un-paid users to get logged in.
* Fixed Logo display and form fields.
* Fixed short tags enabled.
* Fixed Date fields.
* Changed Paypal Buy now to One -Time Subscription fee Button.

### v1.1.3**

* Fixed User Registeration after Paypal Return.


### v1.1.2**

* Fixed temp user id and email verification.

### v1.1.1**

* Added Paypal Return and thank you URL.

### v1.0.1**

* Fixed Password meter.
* Added Paypal as shopping Cart


== Screenshots ==

1. Pie-Register Form Editor
2. Pie-Register Invitation Code
3. Pie Register User Notifications Settings
4. Pie-Register Admin Notification Settings
5. Pie-Register Genereal Settings Page
6. Pie-Register User Import/Export
7. Pie-Register Paypal Settings Page
