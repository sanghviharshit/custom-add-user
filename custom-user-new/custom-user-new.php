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
 * Version: 0.1.5
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
        add_action( 'network_admin_menu', array( $this, 'network_admin_menu' ) );
        add_action( 'user_new_form', array( $this , 'custom_content_below_add_user_form' ) );
        add_action( 'admin_action_createuser', array( $this , 'custom_createuser' ) );
        add_action( 'admin_action_adduser', array( $this , 'custom_adduser' ) );

        
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
     * Add network admin menu
     *
     * @access public
     * @return void
     */
    function network_admin_menu() {
        add_submenu_page( 'settings.php', 'Add User Instructions', 'Add User Instructions', 'manage_network', 'custom-user-new-settings', array( $this, 'output_network_settings_page' ) );
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
    * Adds Custom text on add user page below add user form.
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



