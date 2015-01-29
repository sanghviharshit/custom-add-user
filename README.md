# WP-Custom-New-User
[WordPress] Allows adding users without sending an email confirmation to new users. Also adds custom text below add user form.

When a user tries to add another user to a site, an invitation gets sent to the invited user. When that invitation expires, the invited user cannot be added to a site for a time period as their username is on hold, which creates a lot of frustration for both - the user trying to add people to their site and users trying to access said site.

This plugin removes the invitation (activation link) and automatically adds the user to the site. It also send out a notification to the user indicating that they have been added to that site with a link to access it. 

It also overrides WordPress's default restrictions for Username and enforces site admins to add users with username as email id in configurable list of domains (e.g. gmail.com, nyu.edu, etc). Only Super admins can bypass this restrictions.


## Installation

The Plugin can be installed in one of two ways both of which are documented below. 

The options are:

### Copying a Directory

1. Copy the `custom-user-new` directory into your `wp-content/plugins` directory.
2. In the WordPress Network Admin dashboard, navigate to the *Plugins* page
3. Locate the menu item that reads “Custom New User Page"
4. Click on *Network Activate.*

### Installing a zip file

1. Create a zip of `custom-user-new` directory.
2. In the WordPress dashboard, navigate to the *Plugins* -> *Add New* page.
3. Click Upload Plugin.
4. Upload the zip file and click Install Now.
5. Click on *Network Activate.*


# Credits
[Neel Shah](shah.neel@nyu.edu) for inspiring me to write wordpress plugin.

## Documentation

Please visit, [Documentation](http://sanghviharshit.github.io/WP-Custom-New-User/doc)
