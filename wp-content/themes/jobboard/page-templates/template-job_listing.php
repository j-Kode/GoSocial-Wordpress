<?php
/**
 * Template Name: Job Listing
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 *
 */
?>

<?php get_header(); ?>

<?php get_template_part( 'template-parts/form', 'job_search' ); ?>

<?php get_template_part( 'template-parts/listing', 'job_listing' ); ?>

<?php get_template_part( 'template-parts/listing', 'top_job' ); ?>

<?php get_footer(); ?>