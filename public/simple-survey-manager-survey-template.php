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
                createQuestionForm($question->question_type, $question->question_order, $question->answer_array);
            }
        ?>

	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_footer(); ?>

<?php
    function createQuestionForm($type, $order, $answers)
    {
        switch ($type) {
            case 1:
                echo "<input type=\"text\" name=\"answer[" . $order . "]\"/>";
                break;
            
            case 2:
                echo "<textarea name=\"answer[" . $order . "]\"></textarea>";
                break;
                
            case 3:
                $answers = json_decode($answers);
                $i = 0;
                foreach($answers as $answer)
                {
                    echo "<input type=\"radio\" name=\"answer[" . $order . "]\" value=\"". $i ."\">". $answer ."<br>";
                    $i++;              
                }
                break;
            case 4:
                $answers = json_decode($answers);
                $i = 0;
                foreach($answers as $answer)
                {
                    echo "<input type=\"checkbox\" name=\"answer[" . $order . "][]\" value=\"". $i ."\">". $answer ."<br>";
                    $i++;              
                }
                break;
            case 5:
                $answers = json_decode($answers);
                $i = 0;
                echo "<select name=\"answer[" . $order . "]\">";
                foreach($answers as $answer)
                {
                    echo "<option value=\"". $i ."\">". $answer ."</option>";
                    $i++;              
                }
                echo "</select>";
                break;
            default:
                break;
        }
    }
?>
