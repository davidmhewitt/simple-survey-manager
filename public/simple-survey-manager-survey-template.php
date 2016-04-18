<?php
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
            $survey_id = get_the_ID();
		    $survey_description = get_post_meta($survey_id, 'survey_description', true);
            
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-survey-manager-db-model.php';

            $survey = SSM_Model_Surveys::get_by_wp_id($survey_id);
            $questions = SSM_Model_Questions::get_all_for_survey_id($survey->survey_id);
		?>
        
        <h1><?php single_post_title(); ?> </h1>
        <?php echo $survey_description; ?>
        
        <?php
            foreach($questions as $question)
            {
                $required_string = $question->required != '0' ? "&nbsp;<span style='color:red;'>*</span>" : "";
                echo "<h2>" . $question->question_name . $required_string . "</h2>";
                //createQuestionForm
            }
        ?>

	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_footer(); ?>
