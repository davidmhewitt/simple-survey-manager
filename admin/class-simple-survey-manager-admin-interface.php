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
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/materialize.min.css'; ?>">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="<?php echo plugin_dir_url( __FILE__ ) . 'js/materialize.min.js'; ?>"></script>
		<script>
			jQuery(document).ready(function() {
			    jQuery('select').material_select();
			});
		</script>

		<div style="max-width: 20%; width: 20%; float: right; position: fixed; right: 10%;">
				<a class="waves-effect waves-light btn"><i class="material-icons left">add_circle</i>Add Question</a>
		</div>

    	<div class="card" style="max-width: 75%; width: 75%;">
     		<div class="card-content">
        		<form class="col s12">
          			<div class="row">
	        			<div class="input-field col s12">
	          				<input style="font-size: 20pt; line-height: 20pt;" placeholder="Untitled Survey" id="survey_title" type="text" class="validate">
	          				<label for="survey_title">Survey Title</label>
	        			</div>
	        			<div class="input-field col s12">
          					<textarea id="textarea1" class="materialize-textarea"></textarea>
	          				<label for="textarea1">Survey Description</label>
	        			</div>
        			</div>
	        	</form>
	      	</div>
        </div>
        <div class="card" style="max-width: 75%; width: 75%;">
     		<div class="card-content">
        		<form class="col s12">
          			<div class="row" style="margin-bottom: 0px;">
	        			<div class="input-field col s9">
	          				<input style="font-size: 12pt; line-height: 12pt;" placeholder="Question" id="survey_title" type="text" class="validate">
	        			</div>
	        			<div class="input-field col s3">
		        			<select>
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
	      			<div class="row">
	      				<div class="input-field col s11">
				    		<input placeholder="Option 1" id="first_name" type="text" class="validate">
				    	</div>
				    	<div class="input-field col s1" style="margin-top: 40px;">
				    		<i class="material-icons">clear</i>
				    	</div>
				    </div>
				    <div class="row">
				    	<a href="#">Add Option</a>
				    </div>
	        	</form>
	      	</div>
	      	<div class="card-action">
	      		<div class="row">
		      		<div class="col s1">
		              <i class="material-icons">delete</i>
		            </div>
		            <div class="col s2">
		              <div class="switch">
					    <label>
					      <input type="checkbox">
					      <span class="lever"></span>
					      Required
					    </label>
					  </div>
				  	</div>
			  	</div>
		    </div>
        </div>
        <div class="card" style="max-width: 75%; width: 75%;">
     		<div class="card-content">
        		<form class="col s12">
          			<div class="row" style="margin-bottom: 0px;">
	        			<div class="input-field col s9">
	          				<input style="font-size: 12pt; line-height: 12pt;" placeholder="Question" id="survey_title" type="text" class="validate">
	        			</div>
	        			<div class="input-field col s3">
		        			<select>
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
	        	</form>
	      	</div>
	      	<div class="card-action">
	      		<div class="row">
		      		<div class="col s1">
		              <i class="material-icons">delete</i>
		            </div>
		            <div class="col s2">
		              <div class="switch">
					    <label>
					      <input type="checkbox">
					      <span class="lever"></span>
					      Required
					    </label>
					  </div>
				  	</div>
			  	</div>
		    </div>
        </div>
        <div class="card" style="max-width: 75%; width: 75%;">
     		<div class="card-content">
        		<form class="col s12">
          			<div class="row" style="margin-bottom: 0px;">
	        			<div class="input-field col s9">
	          				<input style="font-size: 12pt; line-height: 12pt;" placeholder="Question" id="survey_title" type="text" class="validate">
	        			</div>
	        			<div class="input-field col s3">
		        			<select>
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
	        	</form>
	      	</div>
	      	<div class="card-action">
	      		<div class="row">
		      		<div class="col s1">
		              <i class="material-icons">delete</i>
		            </div>
		            <div class="col s2">
		              <div class="switch">
					    <label>
					      <input type="checkbox">
					      <span class="lever"></span>
					      Required
					    </label>
					  </div>
				  	</div>
			  	</div>
		    </div>
        </div>
		<?php
	}
}
