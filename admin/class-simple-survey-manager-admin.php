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
	
	public function add_results_link($actions, $post)
	{
		if ($post->post_type=='ssm_survey')
		{
			$actions['results'] = '<a href="edit.php?post_type=ssm_survey&page=results-shortcode-ref&post_id='.$post->ID.'" title="" rel="permalink">View Results</a>';
			unset($actions['inline hide-if-no-js']);
		}
		return $actions;
	}
	
	public function ajax_load_answers()
	{
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-survey-manager-db-model.php';
		$response_id = $_POST['response_id'];
		$answers = SSM_Model_Answers::get_all_for_response_id($response_id);
		echo json_encode($answers);
		wp_die();
	}
	
	public function ajax_load_question()
	{
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-survey-manager-db-model.php';
		$question_id = $_POST['question_id'];
		$question = SSM_Model_Questions::get($question_id);
		echo json_encode($question);
		wp_die();
	}
	
	public function register_custom_submenu_page()
	{
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-survey-manager-results-interface.php';
		$results_interface = new Simple_Survey_Manager_Results_Interface();
		
		add_submenu_page(
			'edit.php?post_type=ssm_survey',
			'Survey Results',
			'View Results',
			'edit_posts',
			'results-shortcode-ref',
			array($results_interface, 'render_interface')
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

	public function save_survey_hook($post_id, $post, $update)
	{
		if(wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) return;

        if ( ! current_user_can( 'edit_posts' ) ) return;

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-survey-manager-db-model.php';

        remove_action( 'save_post_ssm_survey', Array($this, 'save_survey_hook'));
        $survey_title = $_POST['survey_title'];
        wp_update_post( array( 'ID' => $post_id, 'post_title' => $survey_title ) );
        update_post_meta($post_id, 'survey_description', $_POST['survey_description']);
		add_action( 'save_post_ssm_survey', Array($this, 'save_survey_hook'));

		$current_user = wp_get_current_user();

		$data = 
			array(
				'survey_name' => $_POST['survey_title'], 
				'last_activity' => current_time( 'mysql' ), 
				'require_log_in' => 0, 
				'user' => $current_user->user_login,
				'survey_taken' => 0,
				'deleted' => 0,
				'wp_post_id' => $post_id,
			);

		if(SSM_Model_Surveys::get_by_wp_id($post_id) != null)
		{
			SSM_Model_Surveys::update($data, array('wp_post_id' => $post_id));
		} else {
			SSM_Model_Surveys::insert($data);
		}

    	$survey_id = SSM_Model_Surveys::get_by_wp_id($post_id)->survey_id;
    	SSM_Model_Questions::delete_all_for_survey_id($survey_id);

		if ( !isset($_POST['question'])) return;
		
		$i = 0;
        foreach($_POST['question'] as $question)
        {
			if(intval($_POST['question_type'][$i]) != 6)
			{
				SSM_Model_Questions::insert(
					array( 
						'survey_id' => $survey_id, 
						'question_name' => $question, 
						'question_order' => $i,
						'question_type' => $_POST['question_type'][$i],
						'deleted' => 0,
						'required' => isset($_POST['question_required'][$i]),
						'answer_array' => json_encode($_POST['given_answer'][$i]),
					) 
				);
			} else {
				SSM_Model_Questions::insert(
					array( 
						'survey_id' => $survey_id, 
						'question_name' => $question, 
						'question_order' => $i,
						'question_type' => $_POST['question_type'][$i],
						'deleted' => 0,
						'required' => isset($_POST['question_required'][$i]),
						'answer_array' => json_encode(
							array(
								'start_number' => $_POST['linear_start_select'][$i],
								'end_number' => $_POST['linear_end_select'][$i],
								'left_label' => $_POST['linear_left_label'][$i],
								'right_label' => $_POST['linear_right_label'][$i],
							)),
					) 
				);
			}

			$i = $i + 1;
        }

	}
}
