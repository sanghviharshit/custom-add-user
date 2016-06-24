<?php
/**
 * Custom Add User Page.
 *
 * @link http://about.me/harshit
 * @package Custom-Add-User
 * @author Harshit Sanghvi {@link http://github.com/sanghviharshit}
 * @license GNU General Public License (Version 3 - GPLv3) {@link http://www.gnu.org/licenses/gpl-3.0.html}
 *
 * @wordpress-plugin
 * Plugin Name: Custom Add User
 * Description: Completely customized add user page with single form for adding brand new users or existing users in multisite without sending them email notification.
 * Plugin URI: https://github.com/sanghviharshit/custom-add-user
 * Author: Harshit Sanghvi <sanghvi.harshit@gmail.com>
 * Author URI:        http://sanghviharshit.com
 * License: GPL3
 * Version: 2.0.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


define( 'CAU_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CAU_PAGE_SLUG', 'custom-add-user.php');


class Custom_Add_User {

    /** @var string $text_domain The text domain of the plugin */
    var $text_domain = 'cau_trans';
    /** @var string $plugin_name The plugin name */
    var $plugin_name = 'custom_add_user';
    /** @var string $plugin_dir The plugin directory path */
    var $plugin_dir;
    /** @var string $plugin_url The plugin directory URL */
    var $plugin_url;
    /** @var string $plugin_basename The plugin base name */
    var $plugin_basename;
    /** @var string $options_name The plugin options string */
    var $options_name = 'custom_add_user_options';
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
        add_action( 'init', array( $this, 'init' ), 1 );
        add_action( 'init', array( $this, 'init_vars' ), 1 );
    }

    /**
     * Initiate variables.
     *
     * @return void
     */
    function init_vars() {
        global $wpdb;
        
        /* Set plugin directory path */
        $this->plugin_dir = CAU_PLUGIN_DIR;
        /* Set plugin directory URL */
        $this->plugin_url = plugin_dir_url(__FILE__);
        /* Set plugin basename */
        $this->plugin_basename = plugin_basename( __FILE__ );

        $this->settings = $this->get_options();
        $this->network_settings = $this->get_options(null, 'network');

        // Makes sure the plugin is defined before trying to use it
        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }
         
        $this->current_settings = is_plugin_active_for_network( $this->plugin_basename )  ? $this->network_settings : $this->settings;
        
    }


    /**
     * Initiate plugin.
     *
     * @return void
     */
    function init() {
        
        /* Handle form from settings page */
        add_action( 'admin_init', array( $this, 'handle_page_requests' ) );
        /* Load Custom CSS only on user-new.php */
        add_action( 'load-user-new.php', array($this, 'load_css' ) );
        /* Create Admin menu */
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        /* Create Network Admin menu */
        add_action( 'network_admin_menu', array( $this, 'network_admin_menu' ) );
        /* Display instructions for adding users */
        add_action( 'user_new_form', array( $this , 'custom_content_below_add_user_form' ) );
        /* Handle 'createuser' action https://developer.wordpress.org/reference/hooks/admin_action__requestaction/ */
        add_action( 'admin_action_createuser', array( $this , 'custom_createuser' ) );
        /* Handle 'adduser' action https://developer.wordpress.org/reference/hooks/admin_action__requestaction/ */
        add_action( 'admin_action_adduser', array( $this , 'custom_adduser' ) );
        
    }

    /**
     * Add CSS
     * 
     * @return void
     */
    function load_css($hook) {
       wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'includes/custom-add-user.css', array(), $this->version, 'all' ); 
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
     * Add Custom Add User options page.
     * 
     * @return void
     */
    function admin_menu() {
        add_submenu_page( 'options-general.php', 'Custom Add User Settings', 'Custom Add User', 'manage_options', 'custom-add-user-settings', array( &$this, 'output_site_settings_page' ) );
    }

    /**
     * Add network admin menu
     *
     * @access public
     * @return void
     */
    function network_admin_menu() {
        if (is_plugin_active_for_network( $this->plugin_basename ) ) {
            add_submenu_page( 'settings.php', 'Custom Add User Settings', 'Custom Add User', 'manage_network', 'custom-add-user-settings', array( $this, 'output_network_settings_page' ) );    
        } 
    }

    /**
     * Network settings page for Custom New User
     *
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
        require_once( $this->plugin_dir . "includes/custom-add-user-settings.php" );
    }


    /**
    * Adds Custom text on add user page below add user form.
    *
    * @access public
    */
    public function custom_content_below_add_user_form($type) {
        /*
            $type can be ‘add-existing-user’ (Multisite), and ‘add-new-user’ (single site and network admin).
            https://developer.wordpress.org/reference/hooks/user_new_form/
        */
        $cau_instructions = '';
        if ($type == 'add-new-user' && !empty($this->current_settings['cau_settings']['cau_instructions_content'])) {
            $cau_instructions = stripslashes($this->current_settings['cau_settings']['cau_instructions_content']);
        }
        echo $cau_instructions;
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
            
            /* Check if user already exists in the network */
            $user_details = null;
            $user_email = wp_unslash( $_REQUEST['email'] );
            if ( false !== strpos( $user_email, '@' ) ) {
                $user_details = get_user_by( 'email', $user_email );
            } else {
                if ( is_super_admin() ) {
                    $user_details = get_user_by( 'login', $user_email );
                } else {
                    wp_redirect( add_query_arg( array('update' => 'enter_email'), 'user-new.php' ) );
                    die();
                }
            }

            if ( !$user_details ) {
                // Adding a new user to this site
                $new_user_email = wp_unslash( $_REQUEST['email'] );
                $user_details = wpmu_validate_user_signup( $_REQUEST['user_login'], $new_user_email );
                if ( is_wp_error( $user_details[ 'errors' ] ) && !empty( $user_details[ 'errors' ]->errors ) ) {
                    $add_user_errors = $user_details[ 'errors' ];
                } else {
                
                    $new_user_login = apply_filters( 'pre_user_login', sanitize_user( wp_unslash( $_REQUEST['user_login'] ), true ) );
                    
                    add_filter( 'wpmu_signup_user_notification', '__return_false' ); // Disable confirmation email
                    /* Not Disabling the welcome email */
                    //add_filter( 'wpmu_welcome_user_notification', '__return_false' ); // Disable welcome email
                    wpmu_signup_user( $new_user_login, $new_user_email, array( 'add_to_blog' => $wpdb->blogid, 'new_role' => $_REQUEST[ 'role' ] ) );
                    $key = $wpdb->get_var( $wpdb->prepare( "SELECT activation_key FROM {$wpdb->signups} WHERE user_login = %s AND user_email = %s", $new_user_login, $_REQUEST[ 'email' ] ) );
                    wpmu_activate_signup( $key );
                    $redirect = add_query_arg( array('update' => 'addnoconfirmation'), 'user-new.php' );
                    wp_redirect( $redirect );
                    die();
                }
            } else {
                //Add existing user to the blog.
                $new_user_email = $user_details->user_email;
                $redirect = 'user-new.php';
                $username = $user_details->user_login;
                $user_id = $user_details->ID;
                add_existing_user_to_blog( array( 'user_id' => $user_id, 'role' => $_REQUEST[ 'role' ] ) );
                $redirect = add_query_arg( array('update' => 'addnoconfirmation'), 'user-new.php' );
                wp_redirect( $redirect );
                die();
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
        $user_email = wp_unslash( $_REQUEST['email'] );
        if ( false !== strpos($user_email, '@') ) {
            $user_details = get_user_by('email', $user_email);
        } else {
            if ( is_super_admin() ) {
                $user_details = get_user_by('login', $user_email);
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
        add_existing_user_to_blog( array( 'user_id' => $user_id, 'role' => $_REQUEST[ 'role' ] ) );
        $redirect = add_query_arg( array('update' => 'addnoconfirmation'), 'user-new.php' );
        wp_redirect( $redirect );
        die();
    }


    /**
     * Update Custom Add User plugin settings into DB.
     *
     * @return void
     */
    function handle_page_requests() {
        if ( isset( $_POST['submit'] ) ) {
            if ( wp_verify_nonce( $_POST['_wpnonce'], 'cau_submit_settings_network' ) ) {
            //save network settings
                $this->save_options( array('cau_settings' => $_POST), 'network' );

                wp_redirect( add_query_arg( array( 'page' => 'custom-add-user-settings', 'dmsg' => urlencode( __( 'Changes were saved!', $this->text_domain ) ) ), 'settings.php' ) );
                exit;
            }
            elseif ( wp_verify_nonce( $_POST['_wpnonce'], 'cau_submit_settings' ) ) {
            //save settings

                $this->save_options( array('cau_settings' => $_POST) );

                wp_redirect( add_query_arg( array( 'page' => 'custom-add-user-settings', 'dmsg' => urlencode( __( 'Changes were saved!', $this->text_domain ) ) ), 'options-general.php' ) );
                exit;
            }
        }
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

global $custom_add_user;
$custom_add_user = new Custom_Add_User();



