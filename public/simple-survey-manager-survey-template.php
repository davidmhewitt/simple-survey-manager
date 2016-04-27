<?php
    wp_register_script( 'jquery-validate-script', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js' ,array( 'jquery'));
    wp_enqueue_script( 'jquery-validate-script' );

    if( isset( $_POST['submitSurvey'] ) )
    {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-survey-manager-db-model.php';
        $survey_id = get_the_ID();
        $survey = SSM_Model_Surveys::get_by_wp_id($survey_id);
        SSM_Model_Responses::insert(
            array( 
                'survey_id' => $survey->survey_id, 
                'taken' => current_time( 'mysql' ), 
            ) 
        );
        $response_id = SSM_Model_Responses::insert_id();
        $i = 0;
        foreach($_POST['answer'] as $answer)
        {
            $data = 
                array(
                    'response_id' => $response_id,
                    'question_id' => $_POST['question_id'][$i], 
                );
            if(is_array($_POST['answer']))
            {
                $data['answer'] = json_encode($answer);
            } else {
                $data['answer'] = $answer;
            }
            $i = $i + 1;
            SSM_Model_Answers::insert($data);
        }
        wp_redirect(get_permalink(). "?completed=true");
    } 
?>

<?php
get_header(); ?>
<style type="text/css">
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
    }
</style>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
        <?php
            $survey_id = get_the_ID();
            if(post_password_required( $survey_id ))
            {
                echo get_the_password_form();
            } else { 
		    $survey_description = get_post_meta($survey_id, 'survey_description', true);
            
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-survey-manager-db-model.php';

            $survey = SSM_Model_Surveys::get_by_wp_id($survey_id);
            $questions = SSM_Model_Questions::get_all_for_survey_id($survey->survey_id);
		?>
        
        <div id="summary" style="color: red;"></div>
        <h1><?php single_post_title(); ?> </h1>
        <?php if(isset($_REQUEST['completed']))
        {
            echo "Thank you for completing the survey. You may now close this tab.";
        } else {
        ?>
        <?php echo $survey_description; ?>

        <form action="" id="contactForm" method="POST" enctype="multipart/form-data">
        <?php
            foreach($questions as $question)
            {
                $required_string = $question->required != '0' ? "&nbsp;<span style='color:red;'>*</span>" : "";
                echo '<p>';
                echo "<h2>" . $question->question_name . $required_string . "</h2>";
                createQuestionForm($question->question_type, $question->question_order, $question->answer_array, $question->required);
                echo '<input type="hidden" name="question_id['. $question->question_order .']" value="'. $question->question_id. '"/>'; 
                echo '</p>';
            }
        ?>
        <br/><br/>
        <input type="submit" name="submitSurvey"/>
        </form>
        <script>
            jQuery("#contactForm").validate({
                showErrors: function(errorMap, errorList) {
                    jQuery("#summary").html("Your form contains "
                        + this.numberOfInvalids()
                        + " errors, please ensure all required sections are filled in.");
                   window.scrollTo(0, 0);
                },
                focusInvalid: false,
                onfocusout: false,
                onclick: false,
                onkeyup: false,
            });
        </script>
        <?php
        }
        ?>
	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_footer(); ?>

<?php
            }
?>

<?php
    function createQuestionForm($type, $order, $answers, $required)
    {
        $required_string = $required != '0' ? "required" : "";
        switch ($type) {
            case 1:                
                echo "<input type=\"text\" name=\"answer[" . $order . "]\" ". $required_string. "/>";
                break;
            
            case 2:
                echo "<textarea name=\"answer[" . $order . "]\" ". $required_string. "></textarea>";
                break;
                
            case 3:
                $answers = json_decode($answers);
                $i = 0;
                foreach($answers as $answer)
                {
                    echo "<input type=\"radio\" name=\"answer[" . $order . "]\" value=\"". $i ."\" ". $required_string .">". $answer ."<br>";
                    $i++;              
                }
                break;
            case 4:
                $answers = json_decode($answers);
                $i = 0;
                foreach($answers as $answer)
                {
                    echo "<input type=\"checkbox\" name=\"answer[" . $order . "][]\" value=\"". $i ."\" ". $required_string. ">". $answer ."<br>";
                    $i++;              
                }
                break;
            case 5:
                $answers = json_decode($answers);
                $i = 0;
                echo "<select name=\"answer[" . $order . "]\">";
                foreach($answers as $answer)
                {
                    echo "<option value=\"". $i ."\" ". $required_string. ">". $answer ."</option>";
                    $i++;              
                }
                echo "</select>";
                break;
             case 6:
                $answers = json_decode($answers);
                echo '<div class="tableContainer">';
                echo '<div class="tableRow">';
                echo '<div class="tableRight">';
                echo '</div>';
                for($i = $answers->start_number; $i <= $answers->end_number; $i++)
                {
                    echo '<div class="tableRight">';
                    echo '<p>'. $i. '</p>';
                    echo '</div>';
                }
                echo '<div class="tableRight">';
                echo '</div>';
                echo '</div>';
                echo '<div class="tableRow">';
                echo '<div class="tableRight">';
                echo '<p>'. $answers->left_label . '</p>';
                echo '</div>';
                for($i = $answers->start_number; $i <= $answers->end_number; $i++)
                {
                    echo '<div class="tableRight">';
                    echo '<input type="radio" name="answer[' . $order . ']" value="'. $i .'" '. $required_string. '/>';
                    echo '</div>';
                }
                echo '<div class="tableRight">';
                echo '<p>'. $answers->right_label . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                break;
            default:
                break;
        }
    }
?>
