<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * and defines a function that starts the plugin.
 *
 * @link http://about.me/harshit
 * @package Custom-User-New
 * @author Harshit Sanghvi {@link http://github.com/sanghviharshit}
 * @license GNU General Public License (Version 2 - GPLv2) {@link http://www.gnu.org/licenses/gpl-2.0.html}
 *
 * @wordpress-plugin
 * Plugin Name: Custom New User Page
 * Description: Allows adding users without sending an email confirmation to new users. Also adds custom text below add user form.
 * Plugin URI: http://github.com/sanghviharshit
 * Author: Harshit Sanghvi <sanghvi.harshit@gmail.com>
 * Author URI:        http://about.me/harshit
 * License: GPL2
 * Version: 0.1.4
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


define( 'CUN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CUN_PAGE_SLUG', 'custom-user-new.php');


class Custom_User_New {

    /** @var string $text_domain The text domain of the plugin */
    var $text_domain = 'cun_trans';
    /** @var string $plugin_dir The plugin directory path */
    var $plugin_dir;
    /** @var string $plugin_url The plugin directory URL */
    var $plugin_url;
    /** @var string $domain The plugin domain */
    var $domain;
    /** @var string $options_name The plugin options string */
    var $options_name = 'custom_new_user_options';
    /** @var array $settings The plugin site options */
    var $settings;
    /** @var array $settings The plugin network options */
    var $network_settings;
    /** @var array $settings The plugin network or site options depending on localization in admin page */
    var $current_settings;


    /**
     * Constructor.
     */
    function __construct() {
        
        $this->init_vars();
        $this->init();
    }

    /**
     * Initiate plugin.
     *
     * @return void
     */
    function init() {
        add_action( 'admin_init', array( $this, 'handle_page_requests' ) );
        //add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'network_admin_menu', array( $this, 'network_admin_menu' ) );
        add_action( 'user_new_form', array( $this , 'custom_content_below_add_user_form' ) );
        add_action( 'admin_action_createuser', array( $this , 'custom_createuser' ) );
        add_action( 'admin_action_adduser', array( $this , 'custom_adduser' ) );

        add_filter( 'wpmu_validate_user_signup', array($this, 'validate_username'));
    }

    /**
     * Initiate variables.
     *
     * @return void
     */
    function init_vars() {
        global $wpdb;
        
        if ( isset( $wpdb->site) )
            $this->domain = $wpdb->get_var( "SELECT domain FROM {$wpdb->site}" );

        $this->settings = $this->get_options();
        $this->network_settings = $this->get_options(null, 'network');
        $this->current_settings = is_network_admin() ? $this->network_settings : $this->settings;
        
        /* Set plugin directory path */
        $this->plugin_dir = CUN_PLUGIN_DIR;
        /* Set plugin directory URL */
        $this->plugin_url = plugin_dir_url(__FILE__);

    }

    /**
     * Add CSS
     * @todo not yet used.
     * @return void
     */
    function admin_enqueue_scripts($hook) {
        // Including CSS file
    }

    /**
     * Loads the language file from the "languages" directory.
     *
     * @return void
     */
    function load_plugin_textdomain() {
        load_plugin_textdomain( $this->text_domain, null, dirname( plugin_basename( __FILE__ ) ) . '/includes/languages/' );
    }

    /**
     * Add Google Analytics options page.
     * 
     * @return void
     */
    function admin_menu() {
        global $menu;
        global $submenu;

        //unset($submenu['users.php'][10]);

        /** 
        * @todo remove, not used
        */

        if ( current_user_can('create_users') )
            $submenu['users.php'][10] = array(_x('Add New', 'user'), 'create_users', 'admin.php?page='.CUN_PAGE_SLUG);
        else
            $submenu['users.php'][10] = array(_x('Add New', 'user'), 'promote_users', 'admin.php?page='.CUN_PAGE_SLUG);


        add_submenu_page( 
                null, 
                'Add New User',
                'Add New User',
                'promote_users', 
                CUN_PAGE_SLUG,
                array( &$this, 'output_user_new_page' ) );

    }

    /**
     * Add network admin menu
     *
     * @access public
     * @return void
     */
    function network_admin_menu() {
        add_submenu_page( 'settings.php', 'Custom New User', 'Custom New User', 'manage_network', 'custom-user-new-settings', array( $this, 'output_network_settings_page' ) );
    }

    /**
     * Network settings page for Custom New User
     *
     * @access public
     * @return void
     */
    function output_network_settings_page() {
        $this->output_site_settings_page( 'network' );
    }

    /**
     * Admin options page output
     *
     * @return void
     */
    function output_site_settings_page( $network = '' ) {
        require_once( $this->plugin_dir . "includes/custom-user-new-settings.php" );
    }

    /**
     * Network add user page
     *
     * @access public
     * @return void
     */
    function output_network_user_new_page() {
        /* Get Network settings */
        $this->output_user_new_page( 'network' );
    }

    /**
     * Admin Users->add user page output
     *
     * @return void
     */
    function output_user_new_page( $network = '' ) {
        //require_once( $this->plugin_dir . "includes/custom-user-new.php" );
    }

    /**
    * Adds Custom text on add users page below add user form.
    *
    * @access public
    */
    public function custom_content_below_add_user_form() {
        if (!empty($this->network_settings['cun_settings']['cun_instructions_content'])) {
            $cun_instructions = stripslashes($this->network_settings['cun_settings']['cun_instructions_content']);
        }
        else {
            $cun_instructions = '';
        }
        echo $cun_instructions;
    } 


    /**
    * Creates user without email confirmation.
    *
    * @access public
    */
    public function custom_createuser() {
        global $wpdb;
        check_admin_referer( 'create-user', '_wpnonce_create-user' );

        if ( ! current_user_can('create_users') )
            wp_die(__('Cheatin&#8217; uh?'));

        if ( ! is_multisite() ) {
            $user_id = edit_user();

            if ( is_wp_error( $user_id ) ) {
                $add_user_errors = $user_id;
            } else {
                if ( current_user_can( 'list_users' ) )
                    $redirect = 'users.php?update=add&id=' . $user_id;
                else
                    $redirect = add_query_arg( 'update', 'add', 'user-new.php' );
                wp_redirect( $redirect );
                die();
            }
        } else {
            // Adding a new user to this site
            $user_details = wpmu_validate_user_signup( $_REQUEST[ 'user_login' ], $_REQUEST[ 'email' ] );
            if ( is_wp_error( $user_details[ 'errors' ] ) && !empty( $user_details[ 'errors' ]->errors ) ) {
                $add_user_errors = $user_details[ 'errors' ];
            } else {
				/**
				 * Filter the user_login, also known as the username, before it is added to the site.
				 *
				 * @since 2.0.3
				 *
				 * @param string $user_login The sanitized username.
				 */
                $new_user_login = apply_filters( 'pre_user_login', sanitize_user( wp_unslash( $_REQUEST['user_login'] ), true ) );
                
                add_filter( 'wpmu_signup_user_notification', '__return_false' ); // Disable confirmation email

                wpmu_signup_user( $new_user_login, $_REQUEST[ 'email' ], array( 'add_to_blog' => $wpdb->blogid, 'new_role' => $_REQUEST[ 'role' ] ) );
                $key = $wpdb->get_var( $wpdb->prepare( "SELECT activation_key FROM {$wpdb->signups} WHERE user_login = %s AND user_email = %s", $new_user_login, $_REQUEST[ 'email' ] ) );
                wpmu_activate_signup( $key );
                $redirect = add_query_arg( array('update' => 'addnoconfirmation'), 'user-new.php' );
                wp_redirect( $redirect );
                exit();
			}
    	}
    }

    /**
    * Adds existing user without email confirmation.
    *
    * @access public
    */
    public function custom_adduser() {

        global $wpdb;
        check_admin_referer( 'add-user', '_wpnonce_add-user' );

        $user_details = null;
        if ( false !== strpos($_REQUEST[ 'email' ], '@') ) {
            $user_details = get_user_by('email', $_REQUEST[ 'email' ]);
        } else {
            if ( is_super_admin() ) {
                $user_details = get_user_by('login', $_REQUEST[ 'email' ]);
            } else {
                wp_redirect( add_query_arg( array('update' => 'enter_email'), 'user-new.php' ) );
                die();
            }
        }

        if ( !$user_details ) {
            wp_redirect( add_query_arg( array('update' => 'does_not_exist'), 'user-new.php' ) );
            die();
        }

        if ( ! current_user_can('promote_user', $user_details->ID) )
            wp_die(__('Cheatin&#8217; uh?'));

        // Adding an existing user to this blog
        $new_user_email = $user_details->user_email;
        $redirect = 'user-new.php';
        $username = $user_details->user_login;
        $user_id = $user_details->ID;
        if ( ( $username != null && !is_super_admin( $user_id ) ) && ( array_key_exists($blog_id, get_blogs_of_user($user_id)) ) ) {
            $redirect = add_query_arg( array('update' => 'addexisting'), 'user-new.php' );
        } else {
            add_existing_user_to_blog( array( 'user_id' => $user_id, 'role' => $_REQUEST[ 'role' ] ) );
            $redirect = add_query_arg( array('update' => 'addnoconfirmation'), 'user-new.php' );
        }
        wp_redirect( $redirect );
        die();
    }


	/**
     * Check if email address is from allowed domains.
     *
     * @return bool
     */
    private function allowed_email_domain($email) {
    	if(!is_email($email)) {
    		return false;
		}
		$allowed_domains_db = stripslashes($this->network_settings['cun_settings']['cun_settings_username_domain']);
		$allowed_domains_db = strtolower(str_replace(' ', '', $allowed_domains_db));

		//Domains not configured, allow all
		if(empty($allowed_domains_db)) {
			return true;
		}
		$allowed_domains = explode(",", $allowed_domains_db);
		
		$domain_in_email = strtolower(array_pop(explode('@', $email)));
		
		if ( ! in_array($domain_in_email, $allowed_domains)) {
			return false;
		}
		return true;

    }
    /**
     * Update Custom New User plugin settings into DB.
     *
     * @return void
     */
    function handle_page_requests() {
        if ( isset( $_POST['submit'] ) ) {

            if ( wp_verify_nonce( $_POST['_wpnonce'], 'cun_submit_settings_network' ) ) {
            //save network settings
                $this->save_options( array('cun_settings' => $_POST), 'network' );

                wp_redirect( add_query_arg( array( 'page' => 'custom-user-new-settings', 'dmsg' => urlencode( __( 'Changes were saved!', $this->text_domain ) ) ), 'settings.php' ) );
                exit;
            }
            elseif ( wp_verify_nonce( $_POST['_wpnonce'], 'cun_submit_settings' ) ) {
            //save settings

                $this->save_options( array('cun_settings' => $_POST) );

                wp_redirect( add_query_arg( array( 'page' => 'custom-user-new-settings', 'dmsg' => urlencode( __( 'Changes were saved!', $this->text_domain ) ) ), 'options-general.php' ) );
                exit;
            }
        }
    }


	/*
	 * Override errors generated by WordPress due to network username
	 * restrictions that are sort of insane.
	 */
	function validate_username($result) {

		$username = $result['user_name'];

		// Copy any error messages that have not been overridden
		$new_errors = new WP_Error();

		if(is_wp_error( $result[ 'errors' ] ) && !empty( $result[ 'errors' ]->errors )) {
			$errors = $result['errors'];
			$codes = $errors->get_error_codes();

			foreach ($codes as $code) {
				$messages = $errors->get_error_messages($code);

				if ($code == 'user_name') {
					foreach ($messages as $message) {
						if ($message == __('Only lowercase letters (a-z) and numbers are allowed.')) {
							if (is_email($username)) {
								if(!is_super_admin() && !$this->allowed_email_domain($_REQUEST[ 'user_login' ])) {
									$allowed_domains_db = stripslashes($this->network_settings['cun_settings']['cun_settings_username_domain']);
									$allowed_domains_db = strtolower(str_replace(' ', '', $allowed_domains_db));
									$allowed_domains_db = str_replace(',', '/', $allowed_domains_db);

									$error_message = stripslashes($this->network_settings['cun_settings']['cun_settings_username_error']);
									if(empty($error_message)) {
										$message = 'Sorry, Username must be ' .$allowed_domains_db. ' email address.';	
									}
									else {
										$message = $error_message;
									}
									$new_errors->add($code, $message);
								}
							}
							else {
								$new_errors->add($code, $message);
							}
						}
						else {
							// Restore other username errors
							$new_errors->add($code, $message);
						}
					}
				}
				else {
					// Restore any other errors
					foreach ($messages as $message) {
						$new_errors->add($code, $message);
					}
				}
			}
		} else if (!is_super_admin() && !$this->allowed_email_domain($_REQUEST[ 'user_login' ])) {
			$allowed_domains_db = stripslashes($this->network_settings['cun_settings']['cun_settings_username_domain']);
			$allowed_domains_db = strtolower(str_replace(' ', '', $allowed_domains_db));
			$allowed_domains_db = str_replace(',', '/', $allowed_domains_db);

			$error_message = stripslashes($this->network_settings['cun_settings']['cun_settings_username_error']);
			if(empty($error_message)) {
				$message = 'Sorry, Username must be ' .$allowed_domains_db. ' email address.';	
			}
			else {
				$message = $error_message;
			}

			$new_errors->add($code, $message);

		} else {
			return $result;
		}

		$result['errors'] = $new_errors;

		return $result;
	}

	/*
	 * Email addresses can contain characters not allowed in the strict set,
	 * such as '+'.
	 */
	function sanitize_username($username, $raw_username, $strict) {
		if (is_email($raw_username)) {
			$username = $raw_username;
		}

		return $username;
	}



    /**
     * Save plugin options.
     *
     * @param  array $params The $_POST array
     * @return void
     */
    function save_options( $params, $network = ''  ) {
        /* Remove unwanted parameters */
        unset( $params['_wpnonce'], $params['_wp_http_referer'], $params['submit'] );
        /* Update options by merging the old ones */

        if ( '' == $network )
            $options = get_option( $this->options_name );
        else
            $options = get_site_option( $this->options_name );

        if(!is_array($options))
            $options = array();

        $options = array_merge( $options, $params );

        if ( '' == $network )
            update_option( $this->options_name, $options );
        else
            update_site_option( $this->options_name, $options );
    }

    /**
     * Get plugin options.
     *
     * @param  string|NULL $key The key for that plugin option.
     * @return array $options Plugin options or empty array if no options are found
     */
    function get_options( $key = null, $network = '' ) {

        if ( '' == $network )
            $options = get_option( $this->options_name );
        else
            $options = get_site_option( $this->options_name );

        /* Check if specific plugin option is requested and return it */
        if ( isset( $key ) && array_key_exists( $key, $options ) )
            return $options[$key];
        else
            return $options;
    }

}

global $custom_user_new;
$custom_user_new = new Custom_User_New();



