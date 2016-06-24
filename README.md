# Custom Add User
This **WordPress plugin** provides completely customized experience for adding users with a **single form** for adding brand `new users` (single site or multisite installs) or `existing users` (in multisite). The users can be added without them requiring `account activation` (which is useful in private multisites where authentication is handled by third party systems such as Shibboleth/ LDAP). It also allows you to add `custom instructions` for adding users to your site.

## Use cases
In a private multisite installation, you might often face two **_issues_** -

1. If user authentication is handled by external authentication systems such as `LDAP` or `Shibboleth`, you don't want the new user to be '`invited`' to join your site or one of the sites in your network. Instead, you would like to send them a `welcome email` skipping the invitation part which requires them to click the link to activate their account. Since user authentication is not handled by WordPress, it doesn't really make sense for WordPress to validate user activation.

2. When a user (site admin) tries to add another user to a site, an invitation gets sent to the invited user. When that invitation `expires`, the invited user cannot be added to a site for a time period as their username is on hold, which creates a lot of frustration for both - the site admin trying to add people to their site and users trying to access the site after the invitation has expired. Also until the invited user has accepted the invitation, no other site admins can add the same user to a different site in the multisite network. 

3. When a site admin adds a user to a site, the new user is also added to the `network users` table. If any site admin tries to add a user to other sites, they have to _know_ if the user already exists in any of the other sites in the network, for which they might not even have _access_ because only **super admins** can see the list of all users in the network. If the user already exists in the network and they try to add this user using the `'Add New User'` form instead of `'Add Existing User'` form, they will get an **error** saying the user already exists and vice versa. This leads to a lot of confusion among site admins as they have no idea if the user they are trying to add is a brand new or an existing user in the multisite installation.

This plugin addresses all the above issues by -

1. `Skipping` the invitation (activation link) email and automatically adding the user to the site instantly. A welcome email is sent as soon as site admin adds the user. (You can customize the text of `Welcome email` from network settings screen.)

2. `Hiding` the 'Add existing User' form, so that site admins only see one form for adding users.

3. `Handling` the 'brand new user' vs 'existing user' issue in the back-end after site admin submits the form. If the user doesn't exist, the plugin will create a new user and add it to the site. If the user already exists, it will add the user to the site.


## Installation

#### From your WordPress dashboard
1. Visit 'Plugins > Add New'
2. Search for 'Custom Add User'
3. **Activate** or **Network Activate** (multisite) 'Custom Add User' from your Plugins page.

#### From WordPress.org
1. Download 'custom-add-new'.
2. Upload the 'custom-user-new' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. **Activate** or **Network Activate** (multisite) 'Custom Add User' from your Plugins page.

#### Installing a zip file
1. Create a zip of `custom-user-new` directory.
2. In the WordPress dashboard, navigate to the *Plugins* -> *Add New* page.
3. Click Upload Plugin.
4. Upload the zip file and click Install Now.
3. **Activate** or **Network Activate** (multisite) 'Custom Add User' from your Plugins page.

#### Copying a Directory
1. Copy the `custom-user-new` directory into your `wp-content/plugins` directory.
2. In the WordPress Network Admin dashboard, navigate to the *Plugins* page
3. **Activate** or **Network Activate** (multisite) 'Custom Add User' from your Plugins page.


