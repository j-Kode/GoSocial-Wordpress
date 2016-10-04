<?php
/**
 * Job Board shortcode generator functions.
 * All custom shortcode defined by theme in this file
 *
 * @package WordPress
 * @subpackage Job_Board
 * @since Job Board 1.0
 */
?><?php

/**
 * Don't auto-p wrap shortcodes that stand alone
 *
 * Ensures that shortcodes are not wrapped in <<p>>...<</p>>.
 *
 * @since 2.9.0
 *
 * @param string $content The content.
 * @return string The filtered content.
 */
function jb_shortcode_unautop( $content ) {
	global $shortcode_tags;

	if ( empty( $shortcode_tags ) || !is_array( $shortcode_tags ) ) {
		return $content;
	}

	$tagregexp = join( '|', array_map( 'preg_quote', array_keys( $shortcode_tags ) ) );

	$pattern =
		  '/'
		. '<p>'                              // Opening paragraph
		. '\\s*+'                            // Optional leading whitespace
		. '('                                // 1: The shortcode
		.     '\\['                          // Opening bracket
		.     "($tagregexp)"                 // 2: Shortcode name
		.     '(?![\\w-])'                   // Not followed by word character or hyphen
		                                     // Unroll the loop: Inside the opening shortcode tag
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
		.     ')*?'
		.     '(?:'
		.         '\\/\\]'                   // Self closing tag and closing bracket
		.     '|'
		.         '\\]'                      // Closing bracket
		.         '(?:'                      // Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		.             '[^\\[]*+'             // Not an opening bracket
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		.                 '[^\\[]*+'         // Not an opening bracket
		.             ')*+'
		.             '\\[\\/\\2\\]'         // Closing shortcode tag
		.         ')?'
		.     ')'
		. ')'
		. '\\s*+'                            // optional trailing whitespace
		. '<\\/p>'                           // closing paragraph
		. '/s';

	return preg_replace( $pattern, '$1', $content );
}

remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 99);
add_filter( 'the_content', 'jb_shortcode_unautop',100 );


/*----------------------------------------------------*/
/*	Row Shortcode	*/
/*----------------------------------------------------*/
if( !function_exists( 'jobboard_sc_row' ) ){
	function jobboard_sc_row( $atts, $content = null ){
		return '<div class="row">'.do_shortcode($content).'</div>';
	}
	add_shortcode( 'jb_row', 'jobboard_sc_row' );
}

/*----------------------------------------------------*/
/*	Column Shortcode	*/
/*----------------------------------------------------*/
if( !function_exists( 'jobbard_sc_column' ) ){
	function jobbard_sc_column( $atts, $content = null ){
		$a = shortcode_atts( array(
			'grid' => '1',
			'offset' => '0',
		), $atts );
		
		return '<div class="col-sm-'.$a['grid'].' col-sm-offset-'.$a['offset'].'"><p>'.do_shortcode($content).'</p></div>';
	}
	add_shortcode( 'jb_column', 'jobbard_sc_column' );
}

/*----------------------------------------------------*/
/*	Title Shortcode	*/
/*----------------------------------------------------*/
if( !function_exists( 'jobboard_sc_title' ) ){
	function jobboard_sc_title( $atts, $content = null ){
		$a = shortcode_atts( array (
			'size' => 'normal',
			'content' => null,
		), $atts );
		return '<h3 class="sc-title '.$a['size'].'">'.$a['content'].'</h3>';
	}
	add_shortcode( 'jb_title', 'jobboard_sc_title' );
}

/*----------------------------------------------------*/
/*	Unordered List Shortcode	*/
/*----------------------------------------------------*/
if( !function_exists( 'jobboard_sc_ul' ) ){
	function jobboard_sc_ul( $atts, $content = null){
		$a = shortcode_atts( array(
			'icon' => 'fa-circle',
			'color' => '',
			'content' => array(),
		), $atts);
		$output = '';
		$content = explode( ';', $a['content'] );
		$output .= '<ul class="sc-ul"> ';
		foreach($content as $item ){
			$output .= '<li>';
			$output .= '<i style="color:'.$a['color'].'" class="fa fa-fw '.$a['icon'].'"></i> ';
			$output .= $item;
			$output .= '</li>';
		}
		$output .= '</ul>';
		
		return $output;
	}
	add_shortcode( 'jb_ul', 'jobboard_sc_ul' );
}

/*----------------------------------------------------*/
/*	Drop Cap Shortcode	*/
/*----------------------------------------------------*/
if( !function_exists( 'jobboard_sc_dropcap' ) ){
	function jobboard_sc_dropcap( $atts, $content = null){
		$a = shortcode_atts( array(
			'style' => 'normal',
			'color' => '#1abc9c',
			'content' => 'S',
		), $atts );
		$output = '';
		
		if( $a['style'] == 'normal' ){
			$output .= '<span class="sc-dropcap '.$a['style'].'" style="color:'.$a['color'].'">';
		}else{
			$output .= '<span class="sc-dropcap '.$a['style'].'" style="background:'.$a['color'].'">';
		}
		$output .= $a['content'];
		$output .= '</span>';
		
		return $output;
	}
	add_shortcode( 'jb_dropcap', 'jobboard_sc_dropcap' );
}

/*----------------------------------------------------*/
/*	Button Shortcode	*/
/*----------------------------------------------------*/
if( !function_exists( 'jobboard_sc_button' ) ){
	function jobboard_sc_button( $atts, $content = null){
		$a = shortcode_atts( array(
			'size' => 'medium',
			'style' => 'grey',
			'text' => __( 'Button', 'jobboard' ),
			'url' => '#',
			'new_tab' => 'no'
		), $atts );
		
		if($a['new_tab'] == 'yes'){
			$target = '_blank';
		}else{
			$target = '_self';
		}
		
		$output = '';
		
		$output .= '<a href="'.$a['url'].'" target="'.$target.'" class="btn sc-button '.$a['size'].' '.$a['style'].'">';
		$output .= $a['text'];
		$output .= '</a>';
		return $output;
	}
	add_shortcode( 'jb_button', 'jobboard_sc_button' );
}

/*----------------------------------------------------*/
/*	Alert Shortcode	*/
/*----------------------------------------------------*/
if( !function_exists( 'jobboard_sc_alert' ) ){
	function jobboard_sc_alert( $atts, $content = null){
		$a = shortcode_atts( array(
			'style' => 'info',
		), $atts );
		
		return '<div class="alert alert-dismissible jb-alert '.$a['style'].'" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span><span class="sr-only">Close</span></button>
				'.$content.'
		</div>';
	}
	add_shortcode( 'jb_alert', 'jobboard_sc_alert' );
}

/*----------------------------------------------------*/
/*	Accordion Shortcode	*/
/*----------------------------------------------------*/
if( !function_exists( 'jobboard_sc_accordion' ) ){
	function jobboard_sc_accordion( $atts, $content = null ){
		$a = shortcode_atts( array(
			'title' => __( 'Accordion Title', 'jobboard' ),
			'open' => true,
		), $atts );
		$output = '';
		
		$accordion_id = jobboard_seo_url($a['title']);
		
		$in = '';
		if( $a['open'] == 'true' ){
			$in = 'in';
		}
		
		$output .= '<div class="jb-accordion-wrapper">';
		
		$output .= '<div class="jb-accordion-title">';
		$output .= $a['title'].'<button type="button" class="jb-accordion-button" data-toggle="collapse" data-target="#'.$accordion_id.'"><i class="fa"></i></button>';
		$output .= '</div><!-- /.accordion-title --> ';
		
		$output .= '<div id="'.$accordion_id.'" class="jb-accordion-content collapse '.$in.'">';
		$output .= '<p>'.do_shortcode($content).'</p>';
		$output .= '</div><!-- /.collapse -->';
		
		$output .= '</div>';
		
		return $output;
	}
	add_shortcode( 'jb_accordion', 'jobboard_sc_accordion' );
}

/*----------------------------------------------------*/
/*	Blockquote Shortcode	*/
/*----------------------------------------------------*/
if( !function_exists( 'jobboard_sc_blockquote' ) ){
	function jobboard_sc_blockquote( $atts, $content = null){
		return '<blockquote class="sc-blockqoute">"'.$content.'"</blockquote>';
	}
	add_shortcode( 'jb_blockquote', 'jobboard_sc_blockquote' );
}