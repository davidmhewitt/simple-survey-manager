<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.davidhewitt.tech/
 * @since      1.0.0
 *
 * @package    Simple_Survey_Manager
 * @subpackage Simple_Survey_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Survey_Manager
 * @subpackage Simple_Survey_Manager/admin
 * @author     David Hewitt <davidmhewitt@gmail.com>
 */
class Simple_Survey_Manager_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Survey_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Survey_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Survey_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Survey_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		
	}

	/**
	 * Register the custom post type for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function register_custom_post_type()
	{
		register_post_type( 'ssm_survey',
	    array(
	   	  'menu_icon' => 'dashicons-feedback',
	      'labels' => array(
	        'name' => __( 'Surveys' ),
	        'singular_name' => __( 'Survey' ),
            'add_new' => __( 'Create New'),
            'add_new_item' => __( 'Create New Survey'),
            'edit' => __( 'Edit'),
            'edit_item' => __( 'Edit Survey'),
            'new_item' => __( 'New Survey'),
            'view' => __( 'View'),
            'view_item' => __( 'View Survey'),
            'search_items' => __( 'Search Surveys'),
            'not_found' => __( 'No Surveys found'),
            'not_found_in_trash' => __( 'No Surveys found in Trash'),
            'parent' => __( 'Parent Survey')
	      ),
	      'public' => true,
	      'has_archive' => true,
	      'rewrite' => array('slug' => 'surveys'),
	      'supports' => false,
	    )
	  );
	}

	/**
	 * Register the custom survey editor for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function register_custom_meta_box()
	{
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-survey-manager-admin-interface.php';
		$admin_interface = new Simple_Survey_Manager_Admin_Interface();
		add_meta_box( 'ssm_survey_meta_box', __('Questions'), array($admin_interface, 'render_interface'), 'ssm_survey', 'normal', 'high' );
	}

	public function save_survey_hook($post_id)
	{
        if ( ! current_user_can( 'edit_posts' ) )
            return;

        remove_action( 'save_post_ssm_survey', Array($this, 'save_survey_hook'));
        $survey_title = $this->sanitized_text('survey_title');
        wp_update_post( array( 'ID' => $post_id, 'post_title' => $survey_title ) );
        update_post_meta($post_id, 'survey_description', $this->sanitized_text('survey_description'));
        add_action( 'save_post_ssm_survey', Array($this, 'save_survey_hook'));

	}

	private function sanitized_text ( $id ) {
        $data = '';
        if ( isset( $_POST[ $id ] ) ) {
            $data = $_POST[ $id ];
        }
        return sanitize_text_field( $data );
    }
}
