<?php

/**
 * Handles the survey editor interface
 *
 * @link       http://www.davidhewitt.tech/
 * @since      1.0.0
 *
 * @package    Simple_Survey_Manager
 * @subpackage Simple_Survey_Manager/admin
 */

/**
 * Handles the survey editor interface
 *
 * This class defines all code necessary to render the survey editor interface and write the
 * results to the database
 *
 * @since      1.0.0
 * @package    Simple_Survey_Manager
 * @subpackage Simple_Survey_Manager/includes
 * @author     David Hewitt <davidmhewitt@gmail.com>
 */
class Simple_Survey_Manager_Results_Interface {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function render_interface() {
		$survey_id = $_REQUEST['post_id'];

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-survey-manager-db-model.php';

		$survey = SSM_Model_Surveys::get_by_wp_id($survey_id);
		$responses = SSM_Model_Responses::get_all_for_survey_id($survey->survey_id);
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/materialize.min.css'; ?>">		
		<style type="text/css">
			@font-face {
				font-family: 'Material Icons';
				font-style: normal;
				font-weight: 400;
				src: local('Material Icons'), local('MaterialIcons-Regular'), url(<?php echo plugin_dir_url( __FILE__ ) . 'font/material-design-icons/MaterialIcons-Regular.woff2'; ?>) format('woff2');
			}

			.material-icons {
				font-family: 'Material Icons';
				font-weight: normal;
				font-style: normal;
				font-size: 24px;
				line-height: 1;
				letter-spacing: normal;
				text-transform: none;
				display: inline-block;
				white-space: nowrap;
				word-wrap: normal;
				direction: ltr;
				-webkit-font-feature-settings: 'liga';
				-webkit-font-smoothing: antialiased;
			}
			.tableContainer {
				display: table;
				align: center;
				width: 80%;
				margin: 0 auto; 
			}
			.tableRow  {
				display: table-row;
			}
			.tableLeft, .tableRight, .tableMiddle {
				display: table-cell;
			}
			.tableLeft p, .tableRight p, .tableMiddle p {
				margin: 1px 1px;
				padding-left: 8px;
			}
		</style>
		<script src="<?php echo plugin_dir_url( __FILE__ ) . 'js/materialize.min.js'; ?>"></script>

    	<div class="card" style="max-width: 75%; width: 75%;">
     		<div class="card-content">
        		<div class="col s12">
          			<div class="row">
	        			<?php print_r($responses) ?>
        			</div>
	        	</div>
	      	</div>
        </div>
		<?php
	}
}
