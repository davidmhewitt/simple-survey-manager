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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-survey-manager-db-model.php';
		
		$survey_id = $_REQUEST['post_id'];		

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
		<style media="print">
			#adminmenumain { display:none }
			#wpcontent{ margin-left:0; 
				float:none; 
				width:auto }
			#wpfooter { display: none }
			.card { max-width:100% !important; width: 100% !important; }
			h5 { font-size: 125%; background: #e0e0e0; }
		</style>
		<script src="<?php echo plugin_dir_url( __FILE__ ) . 'js/materialize.min.js'; ?>"></script>
		<script>
			jQuery(document).ready(function() {
				var results = <?php echo json_encode($responses); ?>;
				var current_page = window.location.hash.slice(1) | 1;
				jQuery('#current_page').html(current_page);
				var total_pages = <?php echo count($responses); ?>;
				
				populateData();
				
				jQuery('#total_pages').html('of ' + total_pages + ' responses');
				jQuery('#page_left_arrow').click(function() {
					if(current_page > 1)
					{
						current_page--;
						updatePageArrows();
						jQuery('#current_page').html(current_page);
						populateData();
						window.location.hash = current_page;
					}
					return false;
				});
				jQuery('#page_right_arrow').click(function() {
					if(current_page < total_pages)
					{
						current_page++;
						updatePageArrows();
						jQuery('#current_page').html(current_page);
						populateData();
						window.location.hash = current_page;
					}
					return false;
				});
				
				function updatePageArrows()
				{
					if(current_page == total_pages) {
						jQuery('#page_right_arrow').addClass('disabled');
					}							
					else {
						jQuery('#page_right_arrow').removeClass('disabled');
					}
					if(current_page == 1) {
						jQuery('#page_left_arrow').addClass('disabled');
					}							
					else {
						jQuery('#page_left_arrow').removeClass('disabled');
					}
				}
				
				
				function populateData()
				{
					result = results[current_page - 1];
					
					jQuery("#response_data").empty();
					var dateDisplay = jQuery('<span/>', {
							'text': 'Response at: ' + result.taken,
						});
					jQuery("#response_data").append(dateDisplay);
				
					
					var data = {
						'action': 'ssm_load_answers',
						'response_id': result.response_id
					};

					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
					jQuery.post(ajaxurl, data, function(response) {
						var answers = JSON.parse(response);
										
						jQuery.each(answers, function(i, answer) {
							
							var data = {
								'action': 'ssm_load_question',
								'question_id': answer.question_id
							};
							
							jQuery.post(ajaxurl, data, function(response) {
								if(answer.response_id != results[current_page -1].response_id)
									return;
									
								var question = JSON.parse(response);
														
								var d = jQuery('<p/>', {
									'id': question.question_order,
									'style': 'margin-bottom: 25px;'
								});
								var newQ = jQuery('<h5/>', {
									'text': question.question_name,
									'style': 'background: #e0e0e0;',
								}).appendTo(d);
								var given_answers = JSON.parse(question.answer_array);
								var answerString = ""
								if(Array.isArray(given_answers))
								{
									var checkboxes = JSON.parse(answer.answer);
									if(Array.isArray(checkboxes))
									{
										jQuery.each(checkboxes, function(i, checkbox) {
											var index = checkbox;
											answerString += given_answers[index] + ", ";
										});
									} else {
										var index = parseInt(answer.answer.replace('"', ''));
										answerString = given_answers[index];
									}									
								} else {
									answerString = answer.answer;
								}
								answerString = answerString.replace(/</g, "&lt;")
								answerString = answerString.replace(/\\r\\n/g, "<br />");
								answerString = answerString.replace(/\\\\\\"/g, "&quot;");
								answerString = answerString.replace(/\\\//g, "/");
								if(answerString[0] == '"' && answerString[answerString.length - 1] == '"')
									answerString = answerString.slice(1, -1);
								console.log(answerString);
								var newA = jQuery('<span/>', {
									'html': answerString,
								}).appendTo(d);
								jQuery("#response_data").append(d);
								
								jQuery("#response_data > p").sort(function(a, b) {
									return parseInt(a.id) - parseInt(b.id);
								}).each(function() {
									var elem = jQuery(this);
									elem.remove();
									jQuery(elem).appendTo("#response_data");
								});
							});
						});
					});
				}
			});
		</script>

    	<div class="card" style="max-width: 75%; width: 75%;">
     		<div class="card-content">
        		<div class="col s12">
          			<div class="row">
	        			<ul class="pagination right">
							<li class="disabled" id="page_left_arrow"><a href="#"><i class="material-icons">chevron_left</i></a></li>
							<li class="waves-effect" id="current_page">1</li>
							<li id="page_right_arrow"><a href="#"><i class="material-icons">chevron_right</i></a></li>
							<li id="total_pages"></li>
							<li id="print" style="cursor:pointer;" onclick="window.print();"><i class="material-icons">print</i></li>
						</ul>
        			</div>
					<h4><?php echo $survey->survey_name; ?></h4>
					<div class="row">
	        			<div id="response_data">
						</div>
        			</div>
	        	</div>
	      	</div>
        </div>
		<?php
	}
}
