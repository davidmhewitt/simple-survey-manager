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
class Simple_Survey_Manager_Admin_Interface {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function render_interface() {
		$survey_id = get_the_ID();
		$survey_description = get_post_meta($survey_id, 'survey_description', true);
		wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-survey-manager-db-model.php';

		$survey = SSM_Model_Surveys::get_by_wp_id($survey_id);
		$questions = SSM_Model_Questions::get_all_for_survey_id($survey->survey_id);
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
		<script>
			jQuery(document).ready(function() {
				var questions = <?php print json_encode($questions); ?>;
				jQuery.each(questions, function() {
					createQuestion(this['question_type'], this['question_name'], this['required'], JSON.parse(this['answer_array']));
				});
				jQuery('#question-card-container select').material_select();
			    jQuery('.question_type_select').on("change", function() {
			    	changeQuestionType(jQuery(this).parents('.card'), jQuery(this).val());
			    });
			    
			    jQuery("#add-question-button").click(function() {
			    	createQuestion(3);
					jQuery('html, body').animate({ 
					   scrollTop: jQuery(document).height()-jQuery(window).height()}, 
					   1400, 
					   "easeOutQuint"
					);
					updateQuestionNumbers();
				});

				resetAddMultipleChoiceHandler();
			});
			
			function generateLinearScale(container, start, end, leftHeading, rightHeading)
			{
				container.empty();
				if(typeof start === 'undefined') { start = 1; }
				if(typeof end === 'undefined') { end = 5; }
				if(typeof leftHeading === 'undefined') { leftHeading = ""; }
				if(typeof rightHeading === 'undefined') { rightHeading = ""; }
				
				var tableContainer = jQuery('<div/>', {
					'class':'tableContainer',
				});
				
				var topRow = jQuery('<div/>', {
					'class':'tableRow',
				}).appendTo(tableContainer);
				
				var bottomRow = jQuery('<div/>', {
					'class':'tableRow',
				}).appendTo(tableContainer);
				
				jQuery('<div/>', {
					'class':'tableRight',
				}).appendTo(topRow);
				
				for(var i = start; i <= end; i++)
				{
					var d = jQuery('<div/>', {
						'class':'tableRight',
					}).appendTo(topRow);
					jQuery('<p/>', {
						'text': i,
					}).appendTo(d);
				}
				
				jQuery('<div/>', {
					'class':'tableRight',
				}).appendTo(topRow);
				
				var leftDiv = jQuery('<div/>', {
					'class':'tableRight',
				}).appendTo(bottomRow);
				jQuery('<p/>', {
					'text': leftHeading,
				}).appendTo(leftDiv);
				
				for(var i = start; i <= end; i++)
				{
					var d = jQuery('<div/>', {
						'class':'tableRight',
					}).appendTo(bottomRow);
					jQuery('<input/>', {
						'type': 'radio',
						'name': 'linearRadio',
						'id': 'linearRadio_' + i,
						'disabled': 'disabled',
					}).appendTo(d);
					jQuery('<label/>', {
						'for': 'linearRadio_' + i,
						'text': '',
					}).appendTo(d);
				}
				
				var rightDiv = jQuery('<div/>', {
					'class':'tableRight',
				}).appendTo(bottomRow);
				jQuery('<p/>', {
					'text': rightHeading,
				}).appendTo(rightDiv);
				
				tableContainer.appendTo(container);
			}
			
			function resetLinearScaleHandlers()
			{
				jQuery('#linear_start_select').off('change');
				jQuery('#linear_start_select').on('change', function() {
					var answersDiv = jQuery(this).parents('.card-content').find('.answers');
					generateLinearScale(answersDiv, jQuery('#linear_start_select').val(), jQuery('#linear_end_select').val(), jQuery('#linear_left_label').val(), jQuery('#linear_right_label').val());
				});
				
				jQuery('#linear_end_select').off('change');
				jQuery('#linear_end_select').on('change', function() {
					var answersDiv = jQuery(this).parents('.card-content').find('.answers');
					generateLinearScale(answersDiv, jQuery('#linear_start_select').val(), jQuery('#linear_end_select').val(), jQuery('#linear_left_label').val(), jQuery('#linear_right_label').val());
				});
				
				jQuery('#linear_left_label').off('change');
				jQuery('#linear_left_label').on('change', function() {
					var answersDiv = jQuery(this).parents('.card-content').find('.answers');
					generateLinearScale(answersDiv, jQuery('#linear_start_select').val(), jQuery('#linear_end_select').val(), jQuery('#linear_left_label').val(), jQuery('#linear_right_label').val());
				});
				
				jQuery('#linear_right_label').off('change');
				jQuery('#linear_right_label').on('change', function() {
					var answersDiv = jQuery(this).parents('.card-content').find('.answers');
					generateLinearScale(answersDiv, jQuery('#linear_start_select').val(), jQuery('#linear_end_select').val(), jQuery('#linear_left_label').val(), jQuery('#linear_right_label').val());
				});
			}
			
			function resetAddMultipleChoiceHandler()
			{
				jQuery(".add_multiple_choice_answer").off('click');
				jQuery('.add_multiple_choice_answer').on('click', function() {
					addMultipleChoiceAnswer(jQuery(this));
					return false;
				});	
			}
			
			function addMultipleChoiceAnswer(element, text)
			{
				if(typeof text === 'undefined') { text = ""; }

				var answersDiv = element.parents('.card-content').find('.answers');
				var firstAnswer = answersDiv.find('.given_answer').val();
				if(firstAnswer != null && firstAnswer != "")
				{
					var givenAnswerClone = answersDiv.find('.row').first().clone();
					givenAnswerClone.find('.given_answer').val(text);
					answersDiv.append(givenAnswerClone);
				} else {
					answersDiv.find('.given_answer').val(text);
				}
				
				updateQuestionNumbers();
			}

			function createQuestion(type, question_text, required, given_answers)
			{
				if (typeof question_text === 'undefined') { question_text = ''; }
				if (typeof required === 'undefined') { required = '0'; }
				if (typeof given_answers === 'undefined') { given_answers = []; }

				var newQ = jQuery("#question-card-template").clone().attr("id", "");				
				newQ.appendTo('#question-card-container');
				changeQuestionType(newQ, type);
				newQ.find("#question").val(question_text);
				newQ.find("#question_required").prop("checked", required == '0' ? false : true);
				jQuery.each(given_answers, function() {
					addMultipleChoiceAnswer(newQ.find("#question"), this)
				});
				resetDeleteEventHandler();
				resetAddMultipleChoiceHandler();
			}

			function resetDeleteEventHandler()
			{
				jQuery(".question-delete-button").off('click');
				jQuery(".question-delete-button").on('click', function() {
			    	jQuery(this).parents('.card').slideUp("slow", function() {
		    			jQuery(this).remove();
		    			updateQuestionNumbers();
			    	});
			    });
			}

			function changeQuestionType(e, t)
			{
				jQuery('.question_type_select').off("change");
				e.find(".card-content").empty();
				jQuery("#question-type-"+t).clone().attr("id", "").appendTo(e.find(".card-content"));
				e.find(".card-content select").material_select();
				if(t === "6")
				{
					generateLinearScale(e.find('.answers'));
				}
				jQuery('.question_type_select').on("change", function() {
					changeQuestionType(jQuery(this).parents('.card'), jQuery(this).val());
			    });
			    updateQuestionNumbers();
				resetAddMultipleChoiceHandler();
				resetLinearScaleHandlers();
			}

			function updateQuestionNumbers()
			{
				jQuery("#question-card-container .card").each(function(index) {
					jQuery(this).find('#question').attr('name', 'question[' + index + ']');
					jQuery(this).find('.question_type_select').attr('name', 'question_type[' + index + ']');
					jQuery(this).find('#question_required').attr('name', 'question_required[' + index + ']');
					jQuery(this).find('.given_answer').each(function(answer_index) {
						jQuery(this).attr('name', 'given_answer[' + index + '][' + answer_index + ']');
					});
				});
			}

		</script>

		<div style="max-width: 20%; width: 20%; float: right; position: fixed; right: 10%;">
				<a id="add-question-button" class="waves-effect waves-light btn"><i class="material-icons left">add_circle</i>Add Question</a>
		</div>

    	<div class="card" style="max-width: 75%; width: 75%;">
     		<div class="card-content">
        		<div class="col s12">
          			<div class="row">
	        			<div class="input-field col s12">
	          				<input style="font-size: 20pt; line-height: 20pt;" placeholder="Untitled Survey" id="survey_title" name="survey_title" type="text" class="validate" value="<?php echo get_the_title(); ?>">
	          				<label for="survey_title">Survey Title</label>
	        			</div>
	        			<div class="input-field col s12">
          					<textarea id="survey_description" class="materialize-textarea" name="survey_description"><?php echo $survey_description; ?></textarea>
	          				<label for="survey_description">Survey Description</label>
	        			</div>
        			</div>
	        	</div>
	      	</div>
        </div>
        <div id="question-card-container">
        </div>
        <div id="question-template-container" style="display: none;">
	        <div class="card" id="question-card-template" style="max-width: 75%; width: 75%;">
	     		<div class="card-content">
		      	</div>
		      	<div class="card-action">
		      		<div class="row">
			      		<div class="col s1">
			              <span class="question-delete-button" style="cursor: pointer;"><i class="material-icons">delete</i></span>
			            </div>
			            <div class="col s2">
			              <div class="switch">
						    <label>
						      <input type="checkbox" id="question_required">
						      <span class="lever"></span>
						      Required
						    </label>
						  </div>
					  	</div>
				  	</div>
			    </div>
	        </div>
	        <div class="col s12" id="question-type-3">
      			<div class="row" style="margin-bottom: 0px;">
        			<div class="input-field col s9">
          				<input style="font-size: 12pt; line-height: 12pt;" placeholder="Question" id="question" type="text" class="validate">
        			</div>
        			<div class="input-field col s3">
	        			<select class="question_type_select">
					      <option value="1">Short Answer</option>
					      <option value="2">Paragraph</option>
					      <option value="3" selected>Multiple Choice</option>
					      <option value="4">Checkboxes</option>
					      <option value="5">Dropdown</option>
					      <option value="6">Linear Scale</option>
					      <option value="7">Date</option>
					      <option value="8">Time</option>
					    </select>
        			</div>
      			</div>
      			<div class="answers">
	      			<div class="row" style="margin-bottom: 0px;">
	      				<div class="input-field col s11" style="margin-top: 0px;">
				    		<input placeholder="Option 1" type="text" class="given_answer validate">
				    	</div>
				    	<div class="input-field col s1" style="margin-top: 20px;">
				    		<i class="material-icons">clear</i>
				    	</div>
				    </div>
				</div>
			    <div class="row">
			    	<a href="#" class="add_multiple_choice_answer">Add Option</a>
			    </div>
        	</div>
        	<div class="col s12" id="question-type-1">
      			<div class="row" style="margin-bottom: 0px;">
        			<div class="input-field col s9">
          				<input style="font-size: 12pt; line-height: 12pt;" placeholder="Question" id="question" type="text" class="validate">
        			</div>
        			<div class="input-field col s3">
	        			<select class="question_type_select">
					      <option value="1" selected>Short Answer</option>
					      <option value="2">Paragraph</option>
					      <option value="3">Multiple Choice</option>
					      <option value="4">Checkboxes</option>
					      <option value="5">Dropdown</option>
					      <option value="6">Linear Scale</option>
					      <option value="7">Date</option>
					      <option value="8">Time</option>
					    </select>
        			</div>
      			</div>
      			<div class="row">
      				<div class="input-field col s6">
			    		<input placeholder="Short Answer Text" id="first_name" type="text" class="validate" disabled="disabled">
			    	</div>
			    </div>
        	</div>
        	<div class="col s12" id="question-type-2">
      			<div class="row" style="margin-bottom: 0px;">
        			<div class="input-field col s9">
          				<input style="font-size: 12pt; line-height: 12pt;" placeholder="Question" id="question" type="text" class="validate">
        			</div>
        			<div class="input-field col s3">
	        			<select class="question_type_select">
					      <option value="1">Short Answer</option>
					      <option value="2" selected>Paragraph</option>
					      <option value="3">Multiple Choice</option>
					      <option value="4">Checkboxes</option>
					      <option value="5">Dropdown</option>
					      <option value="6">Linear Scale</option>
					      <option value="7">Date</option>
					      <option value="8">Time</option>
					    </select>
        			</div>
      			</div>
      			<div class="row">
					<div class="input-field col s12">
      					<textarea id="long-answer-textarea" placeholder="Long Answer Text" class="materialize-textarea" disabled="disabled"></textarea>
        			</div>
			    </div>
        	</div>
			<div class="col s12" id="question-type-4">
      			<div class="row" style="margin-bottom: 0px;">
        			<div class="input-field col s9">
          				<input style="font-size: 12pt; line-height: 12pt;" placeholder="Question" id="question" type="text" class="validate">
        			</div>
        			<div class="input-field col s3">
	        			<select class="question_type_select">
					      <option value="1">Short Answer</option>
					      <option value="2">Paragraph</option>
					      <option value="3">Multiple Choice</option>
					      <option value="4" selected>Checkboxes</option>
					      <option value="5">Dropdown</option>
					      <option value="6">Linear Scale</option>
					      <option value="7">Date</option>
					      <option value="8">Time</option>
					    </select>
        			</div>
      			</div>
      			<div class="answers">
	      			<div class="row" style="margin-bottom: 0px;">
	      				<div class="input-field col s11" style="margin-top: 0px;">
				    		<input placeholder="Option 1" type="text" class="given_answer validate">
				    	</div>
				    	<div class="input-field col s1" style="margin-top: 20px;">
				    		<i class="material-icons">clear</i>
				    	</div>
				    </div>
				</div>
			    <div class="row">
			    	<a href="#" class="add_multiple_choice_answer">Add Option</a>
			    </div>
        	</div>
			<div class="col s12" id="question-type-5">
      			<div class="row" style="margin-bottom: 0px;">
        			<div class="input-field col s9">
          				<input style="font-size: 12pt; line-height: 12pt;" placeholder="Question" id="question" type="text" class="validate">
        			</div>
        			<div class="input-field col s3">
	        			<select class="question_type_select">
					      <option value="1">Short Answer</option>
					      <option value="2">Paragraph</option>
					      <option value="3">Multiple Choice</option>
					      <option value="4">Checkboxes</option>
					      <option value="5" selected>Dropdown</option>
					      <option value="6">Linear Scale</option>
					      <option value="7">Date</option>
					      <option value="8">Time</option>
					    </select>
        			</div>
      			</div>
      			<div class="answers">
	      			<div class="row" style="margin-bottom: 0px;">
	      				<div class="input-field col s11" style="margin-top: 0px;">
				    		<input placeholder="Option 1" type="text" class="given_answer validate">
				    	</div>
				    	<div class="input-field col s1" style="margin-top: 20px;">
				    		<i class="material-icons">clear</i>
				    	</div>
				    </div>
				</div>
			    <div class="row">
			    	<a href="#" class="add_multiple_choice_answer">Add Option</a>
			    </div>
        	</div>
			<div class="col s12" id="question-type-6">
      			<div class="row">
        			<div class="input-field col s9">
          				<input style="font-size: 12pt; line-height: 12pt;" placeholder="Question" id="question" type="text" class="validate">
        			</div>
        			<div class="input-field col s3">
	        			<select class="question_type_select">
					      <option value="1">Short Answer</option>
					      <option value="2">Paragraph</option>
					      <option value="3">Multiple Choice</option>
					      <option value="4">Checkboxes</option>
					      <option value="5">Dropdown</option>
					      <option value="6" selected>Linear Scale</option>
					      <option value="7">Date</option>
					      <option value="8">Time</option>
					    </select>
        			</div>
      			</div>
      			<div class="answers">
				</div>
				<div class="row">
					<div class="input-field col s2">
						<select id="linear_start_select">
							<option value="0">0</option>
							<option value="1" selected>1</option>
						</select>
						<label>from</label>
					</div>					
					<div class="input-field col s2">						
						<select id="linear_end_select">
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5" selected>5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
						</select>
						<label>to</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s3">
						<input id="linear_left_label" type="text" class="validate">
						<label for="linear_left_label">Left Label</label>
					</div>
					<div class="input-field col s3">
						<input id="linear_right_label" type="text" class="validate">
						<label for="linear_right_label">Right Label</label>
					</div>
				</div>
			</div>
	    </div>
		<?php
	}
}
