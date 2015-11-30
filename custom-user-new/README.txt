Allows adding users without sending an email confirmation to new users. Also adds custom text below add user form. And overrides username restrictions.
Donate link: https://github.com/sanghviharshit/
Tags: custom, user, new, existing, add, promote
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



== Description ==

Allows adding users without sending an email confirmation to new users. Also adds custom text below add user form.

When a user tries to add another user to a site, an invitation gets sent to the invited user. When that invitation expires, the invited user cannot be added to a site for a time period as their username is on hold, which creates a lot of frustration for both - the user trying to add people to their site and users trying to access said site.

This plugin removes the invitation (activation link) and automatically adds the user to the site. It also send out a notification to the user indicating that they have been added to that site with a link to access it. 

It also overrides WordPress's default restrictions for Username and enforces site admins to add users with username as email id in configured domains (e.g. gmail.com, nyu.edu, etc). Only Super admins can bypass this restrictions.


== Installation ==

= From your WordPress dashboard =
> Not yet available on WordPress.org
1. Visit 'Plugins > Add New'
2. Search for 'Custom New User'
3. Network Activate 'Custom New User' from your Plugins page.

= From WordPress.org =
> Not yet available on WordPress.org
1. Download 'custom-user-new'.
2. Upload the 'custom-user-new' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Network Activate *Custom User New* from your Plugins page.

= Installing a zip file =

1. Create a zip of `custom-user-new` directory.
2. In the WordPress dashboard, navigate to the *Plugins* -> *Add New* page.
3. Click Upload Plugin.
4. Upload the zip file and click Install Now.
5. Click on *Network Activate.*

= Copying a Directory =

1. Copy the `custom-user-new` directory into your `wp-content/plugins` directory.
2. In the WordPress Network Admin dashboard, navigate to the *Plugins* page
3. Locate the menu item that reads “Custom New User Page"
4. Click on *Network Activate.*

== Frequently Asked Questions ==

= How can I contribute? =

Make Pull requests to github repository.

== Screenshots ==

Not yet available.

== Changelog ==

= 1.0.0 =
* Merged Add new and existing user form.

= 0.1.6 =
* Updated validate user function.

= 0.1.5 =
* Cleaned up obsolete code.

= 0.1.4 =
* Add existing user without confirmation.

= 0.1.3 =
* Configurable error message.

= 0.1.2 =
* Restricts usernames as email address in configurable list of domains.

= 0.1.1 =
* Minimized custom code.

= 0.1.0 =
* Network Settigns page.

= 0.0.1 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
* Merge Add new and existing user form.

= 0.1.4 =
* Add existing user without confirmation.

= 0.1.3 =
* Configurable error message.

= 0.1.2 =
* To restrict usernames as email address in configurable list of domains.

= 0.1.1 =
* To make it more reliable against future core updates.

= 0.1.0 =
* Upgrade to display network settings page.