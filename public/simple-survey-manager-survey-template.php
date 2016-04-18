<?php
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
            $survey_id = get_the_ID();
		    $survey_description = get_post_meta($survey_id, 'survey_description', true);
		?>
        
        <h1><?php single_post_title(); ?> </h1>
        <?php echo $survey_description; ?>

	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_footer(); ?>
