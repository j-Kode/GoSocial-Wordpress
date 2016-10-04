<?php
/**
 * Template Name: Job Search Result
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>

<?php get_header(); ?>

<?php get_template_part( 'template-parts/form', 'job_search' ); ?>

<?php get_template_part( 'template-parts/listing', 'job_search' ); ?>

<?php get_footer(); ?>