<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.davidhewitt.tech/
 * @since             1.0.0
 * @package           Simple_Survey_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Survey Manager
 * Plugin URI:        http://www.davidhewitt.tech/ssm/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            David Hewitt
 * Author URI:        http://www.davidhewitt.tech/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-survey-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-simple-survey-manager-activator.php
 */
function activate_simple_survey_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-survey-manager-activator.php';
	Simple_Survey_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-simple-survey-manager-deactivator.php
 */
function deactivate_simple_survey_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-survey-manager-deactivator.php';
	Simple_Survey_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simple_survey_manager' );
register_deactivation_hook( __FILE__, 'deactivate_simple_survey_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simple-survey-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_simple_survey_manager() {

	$plugin = new Simple_Survey_Manager();
	$plugin->run();

}
run_simple_survey_manager();
