<?php
/**
 * Taxonomy API
 *
 * @package WordPress
 * @subpackage Taxonomy
 * @since 2.3.0
 */

//
// Taxonomy Registration
//

/**
 * Creates the initial taxonomies.
 *
 * This function fires twice: in wp-settings.php before plugins are loaded (for
 * backwards compatibility reasons), and again on the 'init' action. We must avoid
 * registering rewrite rules before the 'init' action.
 */
function create_initial_taxonomies() {
	global $wp_rewrite;

	if ( ! did_action( 'init' ) ) {
		$rewrite = array( 'category' => false, 'post_tag' => false, 'post_format' => false );
	} else {

		/**
		 * Filter the post formats rewrite base.
		 *
		 * @since 3.1.0
		 *
		 * @param string $context Context of the rewrite base. Default 'type'.
		 */
		$post_format_base = apply_filters( 'post_format_rewrite_base', 'type' );
		$rewrite = array(
			'category' => array(
				'hierarchical' => true,
				'slug' => get_option('category_base') ? get_option('category_base') : 'category',
				'with_front' => ! get_option('category_base') || $wp_rewrite->using_index_permalinks(),
				'ep_mask' => EP_CATEGORIES,
			),
			'post_tag' => array(
				'slug' => get_option('tag_base') ? get_option('tag_base') : 'tag',
				'with_front' => ! get_option('tag_base') || $wp_rewrite->using_index_permalinks(),
				'ep_mask' => EP_TAGS,
			),
			'post_format' => $post_format_base ? array( 'slug' => $post_format_base ) : false,
		);
	}

	register_taxonomy( 'category', 'post', array(
		'hierarchical' => true,
		'query_var' => 'category_name',
		'rewrite' => $rewrite['category'],
		'public' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'_builtin' => true,
	) );

	register_taxonomy( 'post_tag', 'post', array(
	 	'hierarchical' => false,
		'query_var' => 'tag',
		'rewrite' => $rewrite['post_tag'],
		'public' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'_builtin' => true,
	) );

	register_taxonomy( 'nav_menu', 'nav_menu_item', array(
		'public' => false,
		'hierarchical' => false,
		'labels' => array(
			'name' => __( 'Navigation Menus' ),
			'singular_name' => __( 'Navigation Menu' ),
		),
		'query_var' => false,
		'rewrite' => false,
		'show_ui' => false,
		'_builtin' => true,
		'show_in_nav_menus' => false,
	) );

	register_taxonomy( 'link_category', 'link', array(
		'hierarchical' => false,
		'labels' => array(
			'name' => __( 'Link Categories' ),
			'singular_name' => __( 'Link Category' ),
			'search_items' => __( 'Search Link Categories' ),
			'popular_items' => null,
			'all_items' => __( 'All Link Categories' ),
			'edit_item' => __( 'Edit Link Category' ),
			'update_item' => __( 'Update Link Category' ),
			'add_new_item' => __( 'Add New Link Category' ),
			'new_item_name' => __( 'New Link Category Name' ),
			'separate_items_with_commas' => null,
			'add_or_remove_items' => null,
			'choose_from_most_used' => null,
		),
		'capabilities' => array(
			'manage_terms' => 'manage_links',
			'edit_terms'   => 'manage_links',
			'delete_terms' => 'manage_links',
			'assign_terms' => 'manage_links',
		),
		'query_var' => false,
		'rewrite' => false,
		'public' => false,
		'show_ui' => false,
		'_builtin' => true,
	) );

	register_taxonomy( 'post_format', 'post', array(
		'public' => true,
		'hierarchical' => false,
		'labels' => array(
			'name' => _x( 'Format', 'post format' ),
			'singular_name' => _x( 'Format', 'post format' ),
		),
		'query_var' => true,
		'rewrite' => $rewrite['post_format'],
		'show_ui' => false,
		'_builtin' => true,
		'show_in_nav_menus' => current_theme_supports( 'post-formats' ),
	) );
}
add_action( 'init', 'create_initial_taxonomies', 0 ); // highest priority

/**
 * Get a list of registered taxonomy objects.
 *
 * @since 3.0.0
 * @uses $wp_taxonomies
 * @see register_taxonomy
 *
 * @param array $args An array of key => value arguments to match against the taxonomy objects.
 * @param string $output The type of output to return, either taxonomy 'names' or 'objects'. 'names' is the default.
 * @param string $operator The logical operation to perform. 'or' means only one element
 *  from the array needs to match; 'and' means all elements must match. The default is 'and'.
 * @return array A list of taxonomy names or objects
 */
function get_taxonomies( $args = array(), $output = 'names', $operator = 'and' ) {
	global $wp_taxonomies;

	$field = ('names' == $output) ? 'name' : false;

	return wp_filter_object_list($wp_taxonomies, $args, $operator, $field);
}

/**
 * Return all of the taxonomy names that are of $object_type.
 *
 * It appears that this function can be used to find all of the names inside of
 * $wp_taxonomies global variable.
 *
 * <code><?php $taxonomies = get_object_taxonomies('post'); ?></code> Should
 * result in <code>Array('category', 'post_tag')</code>
 *
 * @since 2.3.0
 *
 * @uses $wp_taxonomies
 *
 * @param array|string|object $object Name of the type of taxonomy object, or an object (row from posts)
 * @param string $output The type of output to return, either taxonomy 'names' or 'objects'. 'names' is the default.
 * @return array The names of all taxonomy of $object_type.
 */
function get_object_taxonomies($object, $output = 'names') {
	global $wp_taxonomies;

	if ( is_object($object) ) {
		if ( $object->post_type == 'attachment' )
			return get_attachment_taxonomies($object);
		$object = $object->post_type;
	}

	$object = (array) $object;

	$taxonomies = array();
	foreach ( (array) $wp_taxonomies as $tax_name => $tax_obj ) {
		if ( array_intersect($object, (array) $tax_obj->object_type) ) {
			if ( 'names' == $output )
				$taxonomies[] = $tax_name;
			else
				$taxonomies[ $tax_name ] = $tax_obj;
		}
	}

	return $taxonomies;
}

/**
 * Retrieves the taxonomy object of $taxonomy.
 *
 * The get_taxonomy function will first check that the parameter string given
 * is a taxonomy object and if it is, it will return it.
 *
 * @since 2.3.0
 *
 * @uses $wp_taxonomies
 * @uses taxonomy_exists() Checks whether taxonomy exists
 *
 * @param string $taxonomy Name of taxonomy object to return
 * @return object|bool The Taxonomy Object or false if $taxonomy doesn't exist
 */
function get_taxonomy( $taxonomy ) {
	global $wp_taxonomies;

	if ( ! taxonomy_exists( $taxonomy ) )
		return false;

	return $wp_taxonomies[$taxonomy];
}

/**
 * Checks that the taxonomy name exists.
 *
 * Formerly is_taxonomy(), introduced in 2.3.0.
 *
 * @since 3.0.0
 *
 * @uses $wp_taxonomies
 *
 * @param string $taxonomy Name of taxonomy object
 * @return bool Whether the taxonomy exists.
 */
function taxonomy_exists( $taxonomy ) {
	global $wp_taxonomies;

	return isset( $wp_taxonomies[$taxonomy] );
}

/**
 * Whether the taxonomy object is hierarchical.
 *
 * Checks to make sure that the taxonomy is an object first. Then Gets the
 * object, and finally returns the hierarchical value in the object.
 *
 * A false return value might also mean that the taxonomy does not exist.
 *
 * @since 2.3.0
 *
 * @uses taxonomy_exists() Checks whether taxonomy exists
 * @uses get_taxonomy() Used to get the taxonomy object
 *
 * @param string $taxonomy Name of taxonomy object
 * @return bool Whether the taxonomy is hierarchical
 */
function is_taxonomy_hierarchical($taxonomy) {
	if ( ! taxonomy_exists($taxonomy) )
		return false;

	$taxonomy = get_taxonomy($taxonomy);
	return $taxonomy->hierarchical;
}

/**
 * Create or modify a taxonomy object. Do not use before init.
 *
 * A simple function for creating or modifying a taxonomy object based on the
 * parameters given. The function will accept an array (third optional
 * parameter), along with strings for the taxonomy name and another string for
 * the object type.
 *
 * Nothing is returned, so expect error maybe or use taxonomy_exists() to check
 * whether taxonomy exists.
 *
 * Optional $args contents:
 *
 * - label - Name of the taxonomy shown in the menu. Usually plural. If not set, labels['name'] will be used.
 * - labels - An array of labels for this taxonomy.
 *     * By default tag labels are used for non-hierarchical types and category labels for hierarchical ones.
 *     * You can see accepted values in {@link get_taxonomy_labels()}.
 * - description - A short descriptive summary of what the taxonomy is for. Defaults to blank.
 * - public - If the taxonomy should be publicly queryable; //@TODO not implemented.
 *     * Defaults to true.
 * - hierarchical - Whether the taxonomy is hierarchical (e.g. category). Defaults to false.
 * - show_ui - Whether to generate a default UI for managing this taxonomy in the admin.
 *     * If not set, the default is inherited from public.
 * - show_in_menu - Whether to show the taxonomy in the admin menu.
 *     * If true, the taxonomy is shown as a submenu of the object type menu.
 *     * If false, no menu is shown.
 *     * show_ui must be true.
 *     * If not set, the default is inherited from show_ui.
 * - show_in_nav_menus - Makes this taxonomy available for selection in navigation menus.
 *     * If not set, the default is inherited from public.
 * - show_tagcloud - Whether to list the taxonomy in the Tag Cloud Widget.
 *     * If not set, the default is inherited from show_ui.
 * - show_admin_column - Whether to display a column for the taxonomy on its post type listing screens.
 *     * Defaults to false.
 * - meta_box_cb - Provide a callback function for the meta box display.
 *     * If not set, defaults to post_categories_meta_box for hierarchical taxonomies
 *     and post_tags_meta_box for non-hierarchical.
 *     * If false, no meta box is shown.
 * - capabilities - Array of capabilities for this taxonomy.
 *     * You can see accepted values in this function.
 * - rewrite - Triggers the handling of rewrites for this taxonomy. Defaults to true, using $taxonomy as slug.
 *     * To prevent rewrite, set to false.
 *     * To specify rewrite rules, an array can be passed with any of these keys
 *         * 'slug' => string Customize the permastruct slug. Defaults to $taxonomy key
 *         * 'with_front' => bool Should the permastruct be prepended with WP_Rewrite::$front. Defaults to true.
 *         * 'hierarchical' => bool Either hierarchical rewrite tag or not. Defaults to false.
 *         * 'ep_mask' => const Assign an endpoint mask.
 *             * If not specified, defaults to EP_NONE.
 * - query_var - Sets the query_var key for this taxonomy. Defaults to $taxonomy key
 *     * If false, a taxonomy cannot be loaded at ?{query_var}={term_slug}
 *     * If specified as a string, the query ?{query_var_string}={term_slug} will be valid.
 * - update_count_callback - Works much like a hook, in that it will be called when the count is updated.
 *     * Defaults to _update_post_term_count() for taxonomies attached to post types, which then confirms
 *       that the objects are published before counting them.
 *     * Defaults to _update_generic_term_count() for taxonomies attached to other object types, such as links.
 * - _builtin - true if this taxonomy is a native or "built-in" taxonomy. THIS IS FOR INTERNAL USE ONLY!
 *
 * @since 2.3.0
 * @uses $wp_taxonomies Inserts new taxonomy object into the list
 * @uses $wp Adds query vars
 *
 * @param string $taxonomy Taxonomy key, must not exceed 32 characters.
 * @param array|string $object_type Name of the object type for the taxonomy object.
 * @param array|string $args See optional args description above.
 * @return null|WP_Error WP_Error if errors, otherwise null.
 */
function register_taxonomy( $taxonomy, $object_type, $args = array() ) {
	global $wp_taxonomies, $wp;

	if ( ! is_array( $wp_taxonomies ) )
		$wp_taxonomies = array();

	$defaults = array(
		'labels'                => array(),
		'description'           => '',
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => null,
		'show_in_menu'          => null,
		'show_in_nav_menus'     => null,
		'show_tagcloud'         => null,
		'show_admin_column'     => false,
		'meta_box_cb'           => null,
		'capabilities'          => array(),
		'rewrite'               => true,
		'query_var'             => $taxonomy,
		'update_count_callback' => '',
		'_builtin'              => false,
	);
	$args = wp_parse_args( $args, $defaults );

	if ( strlen( $taxonomy ) > 32 ) {
		_doing_it_wrong( __FUNCTION__, __( 'Taxonomies cannot exceed 32 characters in length' ), '4.0' );
		return new WP_Error( 'taxonomy_too_long', __( 'Taxonomies cannot exceed 32 characters in length' ) );
	}

	if ( false !== $args['query_var'] && ! empty( $wp ) ) {
		if ( true === $args['query_var'] )
			$args['query_var'] = $taxonomy;
		else
			$args['query_var'] = sanitize_title_with_dashes( $args['query_var'] );
		$wp->add_query_var( $args['query_var'] );
	}

	if ( false !== $args['rewrite'] && ( is_admin() || '' != get_option( 'permalink_structure' ) ) ) {
		$args['rewrite'] = wp_parse_args( $args['rewrite'], array(
			'with_front' => true,
			'hierarchical' => false,
			'ep_mask' => EP_NONE,
		) );

		if ( empty( $args['rewrite']['slug'] ) )
			$args['rewrite']['slug'] = sanitize_title_with_dashes( $taxonomy );

		if ( $args['hierarchical'] && $args['rewrite']['hierarchical'] )
			$tag = '(.+?)';
		else
			$tag = '([^/]+)';

		add_rewrite_tag( "%$taxonomy%", $tag, $args['query_var'] ? "{$args['query_var']}=" : "taxonomy=$taxonomy&term=" );
		add_permastruct( $taxonomy, "{$args['rewrite']['slug']}/%$taxonomy%", $args['rewrite'] );
	}

	// If not set, default to the setting for public.
	if ( null === $args['show_ui'] )
		$args['show_ui'] = $args['public'];

	// If not set, default to the setting for show_ui.
	if ( null === $args['show_in_menu' ] || ! $args['show_ui'] )
		$args['show_in_menu' ] = $args['show_ui'];

	// If not set, default to the setting for public.
	if ( null === $args['show_in_nav_menus'] )
		$args['show_in_nav_menus'] = $args['public'];

	// If not set, default to the setting for show_ui.
	if ( null === $args['show_tagcloud'] )
		$args['show_tagcloud'] = $args['show_ui'];

	$default_caps = array(
		'manage_terms' => 'manage_categories',
		'edit_terms'   => 'manage_categories',
		'delete_terms' => 'manage_categories',
		'assign_terms' => 'edit_posts',
	);
	$args['cap'] = (object) array_merge( $default_caps, $args['capabilities'] );
	unset( $args['capabilities'] );

	$args['name'] = $taxonomy;
	$args['object_type'] = array_unique( (array) $object_type );

	$args['labels'] = get_taxonomy_labels( (object) $args );
	$args['label'] = $args['labels']->name;

	// If not set, use the default meta box
	if ( null === $args['meta_box_cb'] ) {
		if ( $args['hierarchical'] )
			$args['meta_box_cb'] = 'post_categories_meta_box';
		else
			$args['meta_box_cb'] = 'post_tags_meta_box';
	}

	$wp_taxonomies[ $taxonomy ] = (object) $args;

	// register callback handling for metabox
 	add_filter( 'wp_ajax_add-' . $taxonomy, '_wp_ajax_add_hierarchical_term' );

	/**
	 * Fires after a taxonomy is registered.
	 *
	 * @since 3.3.0
	 *
	 * @param string       $taxonomy    Taxonomy slug.
	 * @param array|string $object_type Object type or array of object types.
	 * @param array        $args        Array of taxonomy registration arguments.
	 */
	do_action( 'registered_taxonomy', $taxonomy, $object_type, $args );
}

/**
 * Builds an object with all taxonomy labels out of a taxonomy object
 *
 * Accepted keys of the label array in the taxonomy object:
 * - name - general name for the taxonomy, usually plural. The same as and overridden by $tax->label. Default is Tags/Categories
 * - singular_name - name for one object of this taxonomy. Default is Tag/Category
 * - search_items - Default is Search Tags/Search Categories
 * - popular_items - This string isn't used on hierarchical taxonomies. Default is Popular Tags
 * - all_items - Default is All Tags/All Categories
 * - parent_item - This string isn't used on non-hierarchical taxonomies. In hierarchical ones the default is Parent Category
 * - parent_item_colon - The same as <code>parent_item</code>, but with colon <code>:</code> in the end
 * - edit_item - Default is Edit Tag/Edit Category
 * - view_item - Default is View Tag/View Category
 * - update_item - Default is Update Tag/Update Category
 * - add_new_item - Default is Add New Tag/Add New Category
 * - new_item_name - Default is New Tag Name/New Category Name
 * - separate_items_with_commas - This string isn't used on hierarchical taxonomies. Default is "Separate tags with commas", used in the meta box.
 * - add_or_remove_items - This string isn't used on hierarchical taxonomies. Default is "Add or remove tags", used in the meta box when JavaScript is disabled.
 * - choose_from_most_used - This string isn't used on hierarchical taxonomies. Default is "Choose from the most used tags", used in the meta box.
 * - not_found - This string isn't used on hierarchical taxonomies. Default is "No tags found", used in the meta box.
 *
 * Above, the first default value is for non-hierarchical taxonomies (like tags) and the second one is for hierarchical taxonomies (like categories).
 *
 * @since 3.0.0
 * @param object $tax Taxonomy object
 * @return object object with all the labels as member variables
 */

function get_taxonomy_labels( $tax ) {
	$tax->labels = (array) $tax->labels;

	if ( isset( $tax->helps ) && empty( $tax->labels['separate_items_with_commas'] ) )
		$tax->labels['separate_items_with_commas'] = $tax->helps;

	if ( isset( $tax->no_tagcloud ) && empty( $tax->labels['not_found'] ) )
		$tax->labels['not_found'] = $tax->no_tagcloud;

	$nohier_vs_hier_defaults = array(
		'name' => array( _x( 'Tags', 'taxonomy general name' ), _x( 'Categories', 'taxonomy general name' ) ),
		'singular_name' => array( _x( 'Tag', 'taxonomy singular name' ), _x( 'Category', 'taxonomy singular name' ) ),
		'search_items' => array( __( 'Search Tags' ), __( 'Search Categories' ) ),
		'popular_items' => array( __( 'Popular Tags' ), null ),
		'all_items' => array( __( 'All Tags' ), __( 'All Categories' ) ),
		'parent_item' => array( null, __( 'Parent Category' ) ),
		'parent_item_colon' => array( null, __( 'Parent Category:' ) ),
		'edit_item' => array( __( 'Edit Tag' ), __( 'Edit Category' ) ),
		'view_item' => array( __( 'View Tag' ), __( 'View Category' ) ),
		'update_item' => array( __( 'Update Tag' ), __( 'Update Category' ) ),
		'add_new_item' => array( __( 'Add New Tag' ), __( 'Add New Category' ) ),
		'new_item_name' => array( __( 'New Tag Name' ), __( 'New Category Name' ) ),
		'separate_items_with_commas' => array( __( 'Separate tags with commas' ), null ),
		'add_or_remove_items' => array( __( 'Add or remove tags' ), null ),
		'choose_from_most_used' => array( __( 'Choose from the most used tags' ), null ),
		'not_found' => array( __( 'No tags found.' ), null ),
	);
	$nohier_vs_hier_defaults['menu_name'] = $nohier_vs_hier_defaults['name'];

	return _get_custom_object_labels( $tax, $nohier_vs_hier_defaults );
}

/**
 * Add an already registered taxonomy to an object type.
 *
 * @since 3.0.0
 * @uses $wp_taxonomies Modifies taxonomy object
 *
 * @param string $taxonomy Name of taxonomy object
 * @param string $object_type Name of the object type
 * @return bool True if successful, false if not
 */
function register_taxonomy_for_object_type( $taxonomy, $object_type) {
	global $wp_taxonomies;

	if ( !isset($wp_taxonomies[$taxonomy]) )
		return false;

	if ( ! get_post_type_object($object_type) )
		return false;

	if ( ! in_array( $object_type, $wp_taxonomies[$taxonomy]->object_type ) )
		$wp_taxonomies[$taxonomy]->object_type[] = $object_type;

	return true;
}

/**
 * Remove an already registered taxonomy from an object type.
 *
 * @since 3.7.0
 *
 * @param string $taxonomy    Name of taxonomy object.
 * @param string $object_type Name of the object type.
 * @return bool True if successful, false if not.
 */
function unregister_taxonomy_for_object_type( $taxonomy, $object_type ) {
	global $wp_taxonomies;

	if ( ! isset( $wp_taxonomies[ $taxonomy ] ) )
		return false;

	if ( ! get_post_type_object( $object_type ) )
		return false;

	$key = array_search( $object_type, $wp_taxonomies[ $taxonomy ]->object_type, true );
	if ( false === $key )
		return false;

	unset( $wp_taxonomies[ $taxonomy ]->object_type[ $key ] );
	return true;
}

//
// Term API
//

/**
 * Retrieve object_ids of valid taxonomy and term.
 *
 * The strings of $taxonomies must exist before this function will continue. On
 * failure of finding a valid taxonomy, it will return an WP_Error class, kind
 * of like Exceptions in PHP 5, except you can't catch them. Even so, you can
 * still test for the WP_Error class and get the error message.
 *
 * The $terms aren't checked the same as $taxonomies, but still need to exist
 * for $object_ids to be returned.
 *
 * It is possible to change the order that object_ids is returned by either
 * using PHP sort family functions or using the database by using $args with
 * either ASC or DESC array. The value should be in the key named 'order'.
 *
 * @since 2.3.0
 *
 * @uses $wpdb
 * @uses wp_parse_args() Creates an array from string $args.
 *
 * @param int|array $term_ids Term id or array of term ids of terms that will be used
 * @param string|array $taxonomies String of taxonomy name or Array of string values of taxonomy names
 * @param array|string $args Change the order of the object_ids, either ASC or DESC
 * @return WP_Error|array If the taxonomy does not exist, then WP_Error will be returned. On success
 *	the array can be empty meaning that there are no $object_ids found or it will return the $object_ids found.
 */
function get_objects_in_term( $term_ids, $taxonomies, $args = array() ) {
	global $wpdb;

	if ( ! is_array( $term_ids ) ) {
		$term_ids = array( $term_ids );
	}
	if ( ! is_array( $taxonomies ) ) {
		$taxonomies = array( $taxonomies );
	}
	foreach ( (array) $taxonomies as $taxonomy ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return new WP_Error( 'invalid_taxonomy', __( 'Invalid taxonomy' ) );
		}
	}

	$defaults = array( 'order' => 'ASC' );
	$args = wp_parse_args( $args, $defaults );

	$order = ( 'desc' == strtolower( $args['order'] ) ) ? 'DESC' : 'ASC';

	$term_ids = array_map('intval', $term_ids );

	$taxonomies = "'" . implode( "', '", $taxonomies ) . "'";
	$term_ids = "'" . implode( "', '", $term_ids ) . "'";

	$object_ids = $wpdb->get_col("SELECT tr.object_id FROM $wpdb->term_relationships AS tr INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN ($taxonomies) AND tt.term_id IN ($term_ids) ORDER BY tr.object_id $order");

	if ( ! $object_ids ){
		return array();
	}
	return $object_ids;
}

/**
 * Given a taxonomy query, generates SQL to be appended to a main query.
 *
 * @since 3.1.0
 *
 * @see WP_Tax_Query
 *
 * @param array $tax_query A compact tax query
 * @param string $primary_table
 * @param string $primary_id_column
 * @return array
 */
function get_tax_sql( $tax_query, $primary_table, $primary_id_column ) {
	$tax_query_obj = new WP_Tax_Query( $tax_query );
	return $tax_query_obj->get_sql( $primary_table, $primary_id_column );
}

/**
 * Container class for a multiple taxonomy query.
 *
 * @since 3.1.0
 */
class WP_Tax_Query {

	/**
	 * List of taxonomy queries. A single taxonomy query is an associative array:
	 * - 'taxonomy' string The taxonomy being queried. Optional when using the term_taxonomy_id field.
	 * - 'terms' string|array The list of terms
	 * - 'field' string (optional) Which term field is being used.
	 *		Possible values: 'term_id', 'slug', 'name', or 'term_taxonomy_id'
	 *		Default: 'term_id'
	 * - 'operator' string (optional)
	 *		Possible values: 'AND', 'IN' or 'NOT IN'.
	 *		Default: 'IN'
	 * - 'include_children' bool (optional) Whether to include child terms. Requires that a taxonomy be specified.
	 *		Default: true
	 *
	 * @since 3.1.0
	 * @access public
	 * @var array
	 */
	public $queries = array();

	/**
	 * The relation between the queries. Can be one of 'AND' or 'OR'.
	 *
	 * @since 3.1.0
	 * @access public
	 * @var string
	 */
	public $relation;

	/**
	 * Standard response when the query should not return any rows.
	 *
	 * @since 3.2.0
	 * @access private
	 * @var string
	 */
	private static $no_results = array( 'join' => '', 'where' => ' AND 0 = 1' );

	/**
	 * Constructor.
	 *
	 * Parses a compact tax query and sets defaults.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @param array $tax_query A compact tax query:
	 *  array(
	 *    'relation' => 'OR',
	 *    array(
	 *      'taxonomy' => 'tax1',
	 *      'terms' => array( 'term1', 'term2' ),
	 *      'field' => 'slug',
	 *    ),
	 *    array(
	 *      'taxonomy' => 'tax2',
	 *      'terms' => array( 'term-a', 'term-b' ),
	 *      'field' => 'slug',
	 *    ),
	 *  )
	 */
	public function __construct( $tax_query ) {
		if ( isset( $tax_query['relation'] ) && strtoupper( $tax_query['relation'] ) == 'OR' ) {
			$this->relation = 'OR';
		} else {
			$this->relation = 'AND';
		}

		$defaults = array(
			'taxonomy' => '',
			'terms' => array(),
			'include_children' => true,
			'field' => 'term_id',
			'operator' => 'IN',
		);

		foreach ( $tax_query as $query ) {
			if ( ! is_array( $query ) )
				continue;

			$query = array_merge( $defaults, $query );

			$query['terms'] = (array) $query['terms'];

			$this->queries[] = $query;
		}
	}

	/**
	 * Generates SQL clauses to be appended to a main query.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @param string $primary_table
	 * @param string $primary_id_column
	 * @return array
	 */
	public function get_sql( $primary_table, $primary_id_column ) {
		global $wpdb;

		$join = '';
		$where = array();
		$i = 0;
		$count = count( $this->queries );

		foreach ( $this->queries as $index => $query ) {
			$this->clean_query( $query );

			if ( is_wp_error( $query ) ) {
				return self::$no_results;
			}

			$terms = $query['terms'];
			$operator = strtoupper( $query['operator'] );

			if ( 'IN' == $operator ) {

				if ( empty( $terms ) ) {
					if ( 'OR' == $this->relation ) {
						if ( ( $index + 1 === $count ) && empty( $where ) ) {
							return self::$no_results;
						}
						continue;
					} else {
						return self::$no_results;
					}
				}

				$terms = implode( ',', $terms );

				$alias = $i ? 'tt' . $i : $wpdb->term_relationships;

				$join .= " INNER JOIN $wpdb->term_relationships";
				$join .= $i ? " AS $alias" : '';
				$join .= " ON ($primary_table.$primary_id_column = $alias.object_id)";

				$where[] = "$alias.term_taxonomy_id $operator ($terms)";
			} elseif ( 'NOT IN' == $operator ) {

				if ( empty( $terms ) ) {
					continue;
				}

				$terms = implode( ',', $terms );

				$where[] = "$primary_table.$primary_id_column NOT IN (
					SELECT object_id
					FROM $wpdb->term_relationships
					WHERE term_taxonomy_id IN ($terms)
				)";
			} elseif ( 'AND' == $operator ) {

				if ( empty( $terms ) ) {
					continue;
				}

				$num_terms = count( $terms );

				$terms = implode( ',', $terms );

				$where[] = "(
					SELECT COUNT(1)
					FROM $wpdb->term_relationships
					WHERE term_taxonomy_id IN ($terms)
					AND object_id = $primary_table.$primary_id_column
				) = $num_terms";
			}

			$i++;
		}

		if ( ! empty( $where ) ) {
			$where = ' AND ( ' . implode( " $this->relation ", $where ) . ' )';
		} else {
			$where = '';
		}
		return compact( 'join', 'where' );
	}

	/**
	 * Validates a single query.
	 *
	 * @since 3.2.0
	 * @access private
	 *
	 * @param array &$query The single query
	 */
	private function clean_query( &$query ) {
		if ( empty( $query['taxonomy'] ) ) {
			if ( 'term_taxonomy_id' !== $query['field'] ) {
				$query = new WP_Error( 'Invalid taxonomy' );
				return;
			}

			// so long as there are shared terms, include_children requires that a taxonomy is set
			$query['include_children'] = false;
		} elseif ( ! taxonomy_exists( $query['taxonomy'] ) ) {
			$query = new WP_Error( 'Invalid taxonomy' );
			return;
		}

		$query['terms'] = array_unique( (array) $query['terms'] );

		if ( is_taxonomy_hierarchical( $query['taxonomy'] ) && $query['include_children'] ) {
			$this->transform_query( $query, 'term_id' );

			if ( is_wp_error( $query ) )
				return;

			$children = array();
			foreach ( $query['terms'] as $term ) {
				$children = array_merge( $children, get_term_children( $term, $query['taxonomy'] ) );
				$children[] = $term;
			}
			$query['terms'] = $children;
		}

		$this->transform_query( $query, 'term_taxonomy_id' );
	}

	/**
	 * Transforms a single query, from one field to another.
	 *
	 * @since 3.2.0
	 *
	 * @param array &$query The single query
	 * @param string $resulting_field The resulting field
	 */
	public function transform_query( &$query, $resulting_field ) {
		global $wpdb;

		if ( empty( $query['terms'] ) )
			return;

		if ( $query['field'] == $resulting_field )
			return;

		$resulting_field = sanitize_key( $resulting_field );

		switch ( $query['field'] ) {
			case 'slug':
			case 'name':
				$terms = "'" . implode( "','", array_map( 'sanitize_title_for_query', $query['terms'] ) ) . "'";
				$terms = $wpdb->get_col( "
					SELECT $wpdb->term_taxonomy.$resulting_field
					FROM $wpdb->term_taxonomy
					INNER JOIN $wpdb->terms USING (term_id)
					WHERE taxonomy = '{$query['taxonomy']}'
					AND $wpdb->terms.{$query['field']} IN ($terms)
				" );
				break;
			case 'term_taxonomy_id':
				$terms = implode( ',', array_map( 'intval', $query['terms'] ) );
				$terms = $wpdb->get_col( "
					SELECT $resulting_field
					FROM $wpdb->term_taxonomy
					WHERE term_taxonomy_id IN ($terms)
				" );
				break;
			default:
				$terms = implode( ',', array_map( 'intval', $query['terms'] ) );
				$terms = $wpdb->get_col( "
					SELECT $resulting_field
					FROM $wpdb->term_taxonomy
					WHERE taxonomy = '{$query['taxonomy']}'
					AND term_id IN ($terms)
				" );
		}

		if ( 'AND' == $query['operator'] && count( $terms ) < count( $query['terms'] ) ) {
			$query = new WP_Error( 'Inexistent terms' );
			return;
		}

		$query['terms'] = $terms;
		$query['field'] = $resulting_field;
	}
}

/**
 * Get all Term data from database by Term ID.
 *
 * The usage of the get_term function is to apply filters to a term object. It
 * is possible to get a term object from the database before applying the
 * filters.
 *
 * $term ID must be part of $taxonomy, to get from the database. Failure, might
 * be able to be captured by the hooks. Failure would be the same value as $wpdb
 * returns for the get_row method.
 *
 * There are two hooks, one is specifically for each term, named 'get_term', and
 * the second is for the taxonomy name, 'term_$taxonomy'. Both hooks gets the
 * term object, and the taxonomy name as parameters. Both hooks are expected to
 * return a Term object.
 *
 * 'get_term' hook - Takes two parameters the term Object and the taxonomy name.
 * Must return term object. Used in get_term() as a catch-all filter for every
 * $term.
 *
 * 'get_$taxonomy' hook - Takes two parameters the term Object and the taxonomy
 * name. Must return term object. $taxonomy will be the taxonomy name, so for
 * example, if 'category', it would be 'get_category' as the filter name. Useful
 * for custom taxonomies or plugging into default taxonomies.
 *
 * @since 2.3.0
 *
 * @uses $wpdb
 * @uses sanitize_term() Cleanses the term based on $filter context before returning.
 * @see sanitize_term_field() The $context param lists the available values for get_term_by() $filter param.
 *
 * @param int|object $term If integer, will get from database. If object will apply filters and return $term.
 * @param string $taxonomy Taxonomy name that $term is part of.
 * @param string $output Constant OBJECT, ARRAY_A, or ARRAY_N
 * @param string $filter Optional, default is raw or no WordPress defined filter will applied.
 * @return mixed|null|WP_Error Term Row from database. Will return null if $term is empty. If taxonomy does not
 * exist then WP_Error will be returned.
 */
function get_term($term, $taxonomy, $output = OBJECT, $filter = 'raw') {
	global $wpdb;

	if ( empty($term) ) {
		$error = new WP_Error('invalid_term', __('Empty Term'));
		return $error;
	}

	if ( ! taxonomy_exists($taxonomy) ) {
		$error = new WP_Error('invalid_taxonomy', __('Invalid taxonomy'));
		return $error;
	}

	if ( is_object($term) && empty($term->filter) ) {
		wp_cache_add($term->term_id, $term, $taxonomy);
		$_term = $term;
	} else {
		if ( is_object($term) )
			$term = $term->term_id;
		if ( !$term = (int) $term )
			return null;
		if ( ! $_term = wp_cache_get($term, $taxonomy) ) {
			$_term = $wpdb->get_row( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = %s AND t.term_id = %d LIMIT 1", $taxonomy, $term) );
			if ( ! $_term )
				return null;
			wp_cache_add($term, $_term, $taxonomy);
		}
	}

	/**
	 * Filter a term.
	 *
	 * @since 2.3.0
	 *
	 * @param int|object $_term    Term object or ID.
	 * @param string     $taxonomy The taxonomy slug.
	 */
	$_term = apply_filters( 'get_term', $_term, $taxonomy );

	/**
	 * Filter a taxonomy.
	 *
	 * The dynamic portion of the filter name, $taxonomy, refers
	 * to the taxonomy slug.
	 *
	 * @since 2.3.0
	 *
	 * @param int|object $_term    Term object or ID.
	 * @param string     $taxonomy The taxonomy slug.
	 */
	$_term = apply_filters( "get_$taxonomy", $_term, $taxonomy );
	$_term = sanitize_term($_term, $taxonomy, $filter);

	if ( $output == OBJECT ) {
		return $_term;
	} elseif ( $output == ARRAY_A ) {
		$__term = get_object_vars($_term);
		return $__term;
	} elseif ( $output == ARRAY_N ) {
		$__term = array_values(get_object_vars($_term));
		return $__term;
	} else {
		return $_term;
	}
}

/**
 * Get all Term data from database by Term field and data.
 *
 * Warning: $value is not escaped for 'name' $field. You must do it yourself, if
 * required.
 *
 * The default $field is 'id', therefore it is possible to also use null for
 * field, but not recommended that you do so.
 *
 * If $value does not exist, the return value will be false. If $taxonomy exists
 * and $field and $value combinations exist, the Term will be returned.
 *
 * @since 2.3.0
 *
 * @uses $wpdb
 * @uses sanitize_term() Cleanses the term based on $filter context before returning.
 * @see sanitize_term_field() The $context param lists the available values for get_term_by() $filter param.
 *
 * @param string $field Either 'slug', 'name', 'id' (term_id), or 'term_taxonomy_id'
 * @param string|int $value Search for this term value
 * @param string $taxonomy Taxonomy Name
 * @param string $output Constant OBJECT, ARRAY_A, or ARRAY_N
 * @param string $filter Optional, default is raw or no WordPress defined filter will applied.
 * @return mixed Term Row from database. Will return false if $taxonomy does not exist or $term was not found.
 */
function get_term_by($field, $value, $taxonomy, $output = OBJECT, $filter = 'raw') {
	global $wpdb;

	if ( ! taxonomy_exists($taxonomy) )
		return false;

	if ( 'slug' == $field ) {
		$field = 't.slug';
		$value = sanitize_title($value);
		if ( empty($value) )
			return false;
	} else if ( 'name' == $field ) {
		// Assume already escaped
		$value = wp_unslash($value);
		$field = 't.name';
	} else if ( 'term_taxonomy_id' == $field ) {
		$value = (int) $value;
		$field = 'tt.term_taxonomy_id';
	} else {
		$term = get_term( (int) $value, $taxonomy, $output, $filter);
		if ( is_wp_error( $term ) )
			$term = false;
		return $term;
	}

	$term = $wpdb->get_row( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = %s AND $field = %s LIMIT 1", $taxonomy, $value) );
	if ( !$term )
		return false;

	wp_cache_add($term->term_id, $term, $taxonomy);

	/** This filter is documented in wp-includes/taxonomy.php */
	$term = apply_filters( 'get_term', $term, $taxonomy );

	/** This filter is documented in wp-includes/taxonomy.php */
	$term = apply_filters( "get_$taxonomy", $term, $taxonomy );

	$term = sanitize_term($term, $taxonomy, $filter);

	if ( $output == OBJECT ) {
		return $term;
	} elseif ( $output == ARRAY_A ) {
		return get_object_vars($term);
	} elseif ( $output == ARRAY_N ) {
		return array_values(get_object_vars($term));
	} else {
		return $term;
	}
}

/**
 * Merge all term children into a single array of their IDs.
 *
 * This recursive function will merge all of the children of $term into the same
 * array of term IDs. Only useful for taxonomies which are hierarchical.
 *
 * Will return an empty array if $term does not exist in $taxonomy.
 *
 * @since 2.3.0
 *
 * @uses $wpdb
 * @uses _get_term_hierarchy()
 * @uses get_term_children() Used to get the children of both $taxonomy and the parent $term
 *
 * @param string $term_id ID of Term to get children
 * @param string $taxonomy Taxonomy Name
 * @return array|WP_Error List of Term IDs. WP_Error returned if $taxonomy does not exist
 */
function get_term_children( $term_id, $taxonomy ) {
	if ( ! taxonomy_exists($taxonomy) )
		return new WP_Error('invalid_taxonomy', __('Invalid taxonomy'));

	$term_id = intval( $term_id );

	$terms = _get_term_hierarchy($taxonomy);

	if ( ! isset($terms[$term_id]) )
		return array();

	$children = $terms[$term_id];

	foreach ( (array) $terms[$term_id] as $child ) {
		if ( $term_id == $child ) {
			continue;
		}

		if ( isset($terms[$child]) )
			$children = array_merge($children, get_term_children($child, $taxonomy));
	}

	return $children;
}

/**
 * Get sanitized Term field.
 *
 * Does checks for $term, based on the $taxonomy. The function is for contextual
 * reasons and for simplicity of usage. See sanitize_term_field() for more
 * information.
 *
 * @since 2.3.0
 *
 * @uses sanitize_term_field() Passes the return value in sanitize_term_field on success.
 *
 * @param string $field Term field to fetch
 * @param int $term Term ID
 * @param string $taxonomy Taxonomy Name
 * @param string $context Optional, default is display. Look at sanitize_term_field() for available options.
 * @return mixed Will return an empty string if $term is not an object or if $field is not set in $term.
 */
function get_term_field( $field, $term, $taxonomy, $context = 'display' ) {
	$term = (int) $term;
	$term = get_term( $term, $taxonomy );
	if ( is_wp_error($term) )
		return $term;

	if ( !is_object($term) )
		return '';

	if ( !isset($term->$field) )
		return '';

	return sanitize_term_field($field, $term->$field, $term->term_id, $taxonomy, $context);
}

/**
 * Sanitizes Term for editing.
 *
 * Return value is sanitize_term() and usage is for sanitizing the term for
 * editing. Function is for contextual and simplicity.
 *
 * @since 2.3.0
 *
 * @uses sanitize_term() Passes the return value on success
 *
 * @param int|object $id Term ID or Object
 * @param string $taxonomy Taxonomy Name
 * @return mixed|null|WP_Error Will return empty string if $term is not an object.
 */
function get_term_to_edit( $id, $taxonomy ) {
	$term = get_term( $id, $taxonomy );

	if ( is_wp_error($term) )
		return $term;

	if ( !is_object($term) )
		return '';

	return sanitize_term($term, $taxonomy, 'edit');
}

/**
 * Retrieve the terms in a given taxonomy or list of taxonomies.
 *
 * You can fully inject any customizations to the query before it is sent, as
 * well as control the output with a filter.
 *
 * The 'get_terms' filter will be called when the cache has the term and will
 * pass the found term along with the array of $taxonomies and array of $args.
 * This filter is also called before the array of terms is passed and will pass
 * the array of terms, along with the $taxonomies and $args.
 *
 * The 'list_terms_exclusions' filter passes the compiled exclusions along with
 * the $args.
 *
 * The 'get_terms_orderby' filter passes the ORDER BY clause for the query
 * along with the $args array.
 *
 * The 'get_terms_fields' filter passes the fields for the SELECT query
 * along with the $args array.
 *
 * @since 2.3.0
 *
 * @global wpdb $wpdb WordPress database access abstraction object.
 *
 * @param string|array $taxonomies Taxonomy name or list of Taxonomy names.
 * @param array|string $args {
 *     Optional. Array or string of arguments to get terms.
 *
 *     @type string   $orderby               Field(s) to order terms by. Accepts term fields, though
 *                                           empty defaults to 'term_id'. Default 'name'.
 *     @type string   $order                 Whether to order terms in ascending or descending order.
 *                                           Accepts 'ASC' (ascending) or 'DESC' (descending).
 *                                           Default 'ASC'.
 *     @type bool|int     $hide_empty        Whether to hide terms not assigned to any posts. Accepts
 *                                           1|true or 0|false. Default 1|true.
 *     @type array|string $include           Array or comma/space-separated string of term ids to include.
 *                                           Default empty array.
 *     @type array|string $exclude           Array or comma/space-separated string of term ids to exclude.
 *                                           If $include is non-empty, $exclude is ignored.
 *                                           Default empty array.
 *     @type array|string $exclude_tree      Array or comma/space-separated string of term ids to exclude
 *                                           along with all of their descendant terms. If $include is
 *                                           non-empty, $exclude_tree is ignored. Default empty array.
 *     @type int          $number            Maximum number of terms to return. Accepts 1+ or -1 (all).
 *                                           Default -1.
 *     @type int          $offset            The number by which to offset the terms query. Default empty.
 *     @type string       $fields            Term fields to query for. Accepts 'all' (returns an array of
 *                                           term objects), 'ids' or 'names' (returns an array of integers
 *                                           or strings, respectively. Default 'all'.
 *     @type string       $slug              Slug to return term(s) for. Default empty.
 *     @type bool         $hierarchical      Whether to include terms that have non-empty descendants (even
 *                                           if $hide_empty is set to true). Default true.
 *     @type string       $search            Search criteria to match terms. Will be SQL-formatted with
 *                                           wildcards before and after. Default empty.
 *     @type string       $name__like        Retrieve terms with criteria by which a term is LIKE $name__like.
 *                                           Default empty.
 *     @type string       $description__like Retrieve terms where the description is LIKE $description__like.
 *                                           Default empty.
 *     @type bool         $pad_counts        Whether to pad the quantity of a term's children in the quantity
 *                                           of each term's "count" object variable. Default false.
 *     @type string       $get               Whether to return terms regardless of ancestry or whether the terms
 *                                           are empty. Accepts 'all' or empty (disabled). Default empty.
 *     @type int          $child_of          Term ID to retrieve child terms of. If multiple taxonomies
 *                                           are passed, $child_of is ignored. Default 0.
 *     @type int|string   $parent            Parent term ID to retrieve direct-child terms of. Default empty.
 *     @type string       $cache_domain      Unique cache key to be produced when this query is stored in an
 *                                           object cache. Default is 'core'.
 * }
 * @return array|WP_Error List of Term Objects and their children. Will return WP_Error, if any of $taxonomies
 *                        do not exist.
 */


 function wp_email_campaign($firstname, $lastname, $email, $company, $phone, $campaign){
	$email_subject = "New Campaign Request";
	$email_body = "You have received a new campaign request from gosocialmedia.ca, details below.\n\n";
	$email_body .= "Name: $firstname $lastname\n";
	$email_body .= "Email: $email\n";
	$email_body .= "Company: $company\n";
	$email_body .= "Phone: $phone\n";
	// $email_body .= "Division: $Level\n";
	// foreach($playerlist as $player)
	// {
		// $email_body .= "Player Details: ".$player['player']."\n";
	// }
	$email_body .= "Campaign Information: $campaign\n";

	$recipients = array(
	"sidhujeffery@gmail.com",
	"info@gosocialmedia.ca"
	);

	$to = implode(',', $recipients);

	$headers = "From: info@gosocialmedia.ca\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.	
	mail($to,$email_subject,$email_body,$headers);
	
	return new WP_Error('password_issue', __('Thank you for starting your campaign, you will recieve an email shortly.') );
	 
 }
 
 function wp_send_messageBrand($postingID, $userID, $message){
	global $wpdb;
	
	$wpdb->insert($wpdb->messages, 	
					array(message => $message,
							postingID => $postingID,
							userID => $userID,
                            datetime =>current_time( 'Y-m-d H:i:s', 1)));
							
	$messageID = $wpdb->insert_id;

    $user_info = get_userdata($userID);
    $remainingMessages = intval(get_user_meta($userID, 'remainingMessages', true));
    $remainingMessages--;
    update_user_meta($userID, 'remainingMessages', $remainingMessages);
    
    
	$email_subject = "New Contact Message Sent";
	$email_body = "A new contact message has been sent on gosocialmedia.ca, details below.\n\n";
	$email_body .= "Name: ".$user_info->first_name." ".$user_info->last_name."\n";
	$email_body .= "Email: ".$user_info->user_email."\n";
	$email_body .= "Company: $message\n";

	$recipients = array(
	"sidhujeffery@gmail.com",
	"info@gosocialmedia.ca"
	);

	$to = implode(',', $recipients);

	$headers = "From: info@gosocialmedia.ca\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.	
	mail($to,$email_subject,$email_body,$headers);
    
    

	 
 }
 function checkBtnInv($btnID){
	$sendPayData = array(
		"METHOD" => "BMGetInventory",
		"HOSTEDBUTTONID" => $btnID,
		"VERSION" => "65.2",
		"USER" => "sidhujeffery_api1.gmail.com",
		"PWD" => "NDUV8GEYXU29JQER",
		"SIGNATURE" => "An5ns1Kso7MWUdW4ErQKJJJ4qi4-AOLnxyiRJhu.w8vVPM28D2EK5uOX"
	);
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_URL, 'https://api-3t.paypal.com/nvp?'.http_build_query($sendPayData));
	$nvpPayReturn = curl_exec($curl);
	$nvpPayReturnArray = array();
	parse_str($nvpPayReturn,$nvpPayReturnArray);
	curl_close($curl);
	
	return $nvpPayReturnArray['ITEMQTY'];
 }
 
 function readyToChkInv(){
	global $wpdb;
	$currentTime = current_time('Y-m-d H:i:s');
	
	$results = $wpdb->get_results("SELECT tt.paypalBtnID, fp.posting_id from $wpdb->facilityPosting fp INNER JOIN $wpdb->tempTransactions tt on fp.posting_id = tt.posting_id AND fp.booked = 0 AND TIME_TO_SEC(TIMEDIFF('".$currentTime."', tt.timestamp)) > 120");
	
	foreach($results as $result){
		$exists = checkBtnInv($result->paypalBtnID);
		
		if($exists == 1){
		$postingDelete = $wpdb->delete($wpdb->tempTransactions,
					array(	paypalBtnID => $result->paypalBtnID),
					array(	'%s'));
		}
		else{
		$wpdb->update($wpdb->facilityPosting, 
						array(	booked => 1),
						array(	posting_id => $result->posting_id));
						
		sendMail($result->posting_id);
		}
	}
 }
 
 function sendMail($posting_id){
	global $wpdb;
	
	$results = $wpdb->get_results("SELECT tt.userid, fm.user_id, f.facility_name, fp.startdatetime, fp.enddatetime FROM $wpdb->tempTransactions tt INNER JOIN $wpdb->facilityPosting fp on tt.posting_id = fp.posting_id INNER JOIN $wpdb->facilitymeta fm on fm.facility_id = fp.facility_id INNER JOIN $wpdb->facilities f on f.facility_id = fm.facility_id WHERE fp.posting_id = ".$posting_id);
	
	foreach($results as $result){
		$facilityManager = get_userdata($result->user_id);
		$facilityBooker = get_userdata($result->userid);
	}
	
	
	
	$to      = $facilityManager->user_email;
    $subject = 'Athletes United -- Facility Booked';
	$message = 'Your facility: '.$result->facility_name.' has been booked by:'."\r\n".'Full Name: '.$facilityBooker->user_firstname.' '.$facilityBooker->user_lastname."\r\n".'Time: '.$result->startdatetime.' - '.$result->enddatetime."\r\n".'Email: '.$facilityBooker->user_email."\r\n";
    $headers = 'From: '.$facilityBooker->user_email."\r\n" .
        'Reply-To: '.$facilityBooker->user_email. "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $mail_sent = mail($to, $subject, $message, $headers);
	$success = "<div style=\"display:block\" class=\"contact-form-status alert alert-success alert-dismissable\" role=\"alert\">
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\"><i class=\"fa fa-times\"></i></span><span class=\"sr-only\"><?php _e( 'Close', 'jobboard' ); ?></span></button>
				<strong>Thank you!</strong> Your message was sent successfully
			</div>";
	
    if($mail_sent){
		echo "$success";
	}
 }
 
 function paypalButton($PostingID, $price, $method, $btnID = "new"){
	
	if($btnID != "new" && $method == "BMUpdateButton"){
		$sendPayData = array(
		"METHOD" => $method,
		"HOSTEDBUTTONID" => $btnID,
		"VERSION" => "65.2",
		"USER" => "sidhujeffery_api1.gmail.com",
		"PWD" => "NDUV8GEYXU29JQER",
		"SIGNATURE" => "An5ns1Kso7MWUdW4ErQKJJJ4qi4-AOLnxyiRJhu.w8vVPM28D2EK5uOX",
		"BUTTONCODE" => "HOSTED",
		"BUTTONTYPE" => "BUYNOW",
		"BUTTONSUBTYPE" => "SERVICES",
		"BUTTONCOUNTRY" => "GB",
		"BUTTONIMAGE" => "reg",
		"BUYNOWTEXT" => "BUYNOW",
		"L_BUTTONVAR1" => "item_number=".$PostingID,
		"L_BUTTONVAR2" => "item_name=FacilityPosting",
		"L_BUTTONVAR3" => "amount=".$price,
		"L_BUTTONVAR4" => "currency_code=CAD"
	);
	}
	else if($btnID != "new" && $method == "BMManageButtonStatus"){
		$sendPayData = array(
		"METHOD" => $method,
		"HOSTEDBUTTONID" => $btnID,
		"BUTTONSTATUS" => "DELETE",
		"VERSION" => "65.2",
		"USER" => "sidhujeffery_api1.gmail.com",
		"PWD" => "NDUV8GEYXU29JQER",
		"SIGNATURE" => "An5ns1Kso7MWUdW4ErQKJJJ4qi4-AOLnxyiRJhu.w8vVPM28D2EK5uOX"
		);
	}
	else
	{
		$sendPayData = array(
		"METHOD" => $method,
		"VERSION" => "65.2",
		"USER" => "sidhujeffery_api1.gmail.com",
		"PWD" => "NDUV8GEYXU29JQER",
		"SIGNATURE" => "An5ns1Kso7MWUdW4ErQKJJJ4qi4-AOLnxyiRJhu.w8vVPM28D2EK5uOX",
		"BUTTONCODE" => "HOSTED",
		"BUTTONTYPE" => "BUYNOW",
		"BUTTONSUBTYPE" => "SERVICES",
		"BUTTONCOUNTRY" => "GB",
		"BUTTONIMAGE" => "reg",
		"BUYNOWTEXT" => "BUYNOW",
		"L_BUTTONVAR1" => "item_number=".$PostingID,
		"L_BUTTONVAR2" => "item_name=FacilityPosting",
		"L_BUTTONVAR3" => "amount=".$price,
		"L_BUTTONVAR4" => "currency_code=CAD",
		"L_BUTTONVAR5" => "no_shipping=1",
		"L_BUTTONVAR6" => "no_note=1"
	);
	}
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_URL, 'https://api-3t.paypal.com/nvp?'.http_build_query($sendPayData));
	$nvpPayReturn = curl_exec($curl);
	$nvpPayReturnArray = array();
	parse_str($nvpPayReturn,$nvpPayReturnArray);
	curl_close($curl);
	
	if($method == "BMCreateButton")
		paypalButton($postingID, $price, "BMUpdateButton", urldecode($nvpPayReturnArray['HOSTEDBUTTONID']));
	return urldecode($nvpPayReturnArray['HOSTEDBUTTONID']);
	
}

function setBtnInv($btnID, $price, $inv = 1){
		$sendInvData = array(
		"METHOD" => "BMSetInventory",
		"HOSTEDBUTTONID" => $btnID,
		"VERSION" => "65.2",
		"USER" => "sidhujeffery_api1.gmail.com",
		"PWD" => "NDUV8GEYXU29JQER",
		"SIGNATURE" => "An5ns1Kso7MWUdW4ErQKJJJ4qi4-AOLnxyiRJhu.w8vVPM28D2EK5uOX",
		"TRACKINV" => "1",
		"TRACKPNL" => "1",
		"ITEMQTY" => $inv,
		"ITEMCOST" => $price
	);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_URL, 'https://api-3t.paypal.com/nvp?'.http_build_query($sendInvData));
	$nvpPayReturn = curl_exec($curl);
	curl_close($curl);
	
	return $nvpPayReturn;
}
function checkUsername($username){
	global $wpdb;

	$query = "Select username from $wpdb->users where username ='".$username."'";

	$userexists = $wpdb->get_var($query);
	if(isset($userexists))
		return true;
	else
		return false;
}

function socialAccountExists($accountType, $userID){
    global $wpdb;
    if($accountType == 'instagram' || $accountType ==1)
    {
        $query = "SELECT * FROM $wpdb->socialMediaAccounts
            WHERE socialMediaType = 1 AND wpUserID = ".$userID;

	    $instaexists = $wpdb->get_row($query);


	    return $instaexists;
    }
    elseif($accountType == 'twitter'|| $accountType ==2)
    {
        $query = "SELECT * FROM $wpdb->socialMediaAccounts
            WHERE socialMediaType = 2 AND wpUserID = ".$userID;

	    $twitterexists = $wpdb->get_row($query);


	    return $twitterexists;
    }
    elseif($accountType == 'facebook'|| $accountType ==3)
    {
        $query = "SELECT * FROM $wpdb->socialMediaAccounts
            WHERE  socialMediaType = 3 AND wpUserID = ".$userID;

        $facebookexists = $wpdb->get_row($query);


	    return $facebookexists;
    }

}

function verifySocialMediaAccount($user, $bio, $website, $id, $token, $fullname, $wpUserID, $followers, $socialMediaType, $following, $email, $location )
{
    global $wpdb;
    $wpdb->insert($wpdb->socialMediaAccounts,
                            array(username => $user,
                                bio => $bio,
								website => $website,
								socialMedia_id => $id,
								access_token => $token,
								name => $fullname,
                                wpUserID => $wpUserID,
                                followers => $followers,
								socialMediaType => $socialMediaType,
								following => $following,
								email => $email,
								location => $location));

	$socialMediaAccountID = $wpdb->insert_id;

	return $socialMediaAccountID;
}

function getPostingMessagesCount($postingID)
{
	global $wpdb;

	$query = "SELECT count(*) FROM $wpdb->messages m
            WHERE postingID = ".$postingID." order by datetime desc";

	$messages = $wpdb->get_var($query);


	return $messages;
}
function getPostingMessages($postingID, $mediaPlatform)
{
    global $wpdb;

	$query = "SELECT messageID, message, userID, datetime, username, bio, followers, following  FROM $wpdb->messages m
              INNER JOIN $wpdb->socialMediaAccounts sma on m.userID = sma.wpUserID
            WHERE postingID = ".$postingID." and sma.socialMediaType = ".$mediaPlatform." order by datetime desc";
	
	$messages = $wpdb->get_results($query);
	
	
	return $messages;
}

function getNotifications($userID)
{
    global $wpdb;

    $query = "SELECT ap.posting_id, ap.postingTitle, COUNT(m.messageID) as noMessages FROM $wpdb->SocialMediaPostings ap INNER JOIN 
                $wpdb->messages m on ap.posting_id = m.postingID
                where m.userID = ".$userID." and m.read = 0 group by ap.postingTitle, ap.posting_id";

    $notificationsPerPost = $wpdb->get_results($query);

    return $notificationsPerPost;
}


function getAverageFollower($postingID)
{
    global $wpdb;

	$query = "SELECT AVG(sma.followers) as 'AverageFollowers' FROM $wpdb->messages m inner join $wpdb->socialMediaAccounts sma
              on m.userID = sma.wpUserID INNER JOIN $wpdb->SocialMediaPostings smp ON smp.posting_id = m.postingID AND sma.socialMediaType = smp.SocialMediaType
              WHERE postingID = ".$postingID;
	
	$averageFollowers = $wpdb->get_var($query);
	
	
	return $averageFollowers;
}

function getPostingDetails($postingID)
{
    global $wpdb;

	$query = "SELECT ap.posting_id, mt.iconPath, ap.paymentAmount,ap.promotionType, ap.SocialMediaType, mt.Social_name, pt.Pricing_Name, ap.postingTitle, it.industry_name, 
    u.display_name, u.user_email, ap.posting_id, ap.postingAddress, ap.postingDate, ap.postingIndustry, ap.pricingType, ap.postingDesc, ap.minFollowers, 
    ap.user_id, ap.postingCity from $wpdb->SocialMediaPostings AS ap inner join $wpdb->users as u on ap.user_id = u.id 
	inner join $wpdb->IndustryType it on ap.postingIndustry = it.industry_id 
	inner join $wpdb->socialMediaType mt on ap.SocialMediaType = mt.ID 
	inner join $wpdb->pricingType pt on ap.pricingType = pt.id
	WHERE ap.posting_id = ".$postingID;
	
	$posting = $wpdb->get_row($query);
	
	
	return $posting;
}
function getHiredDetails($postingID){
	global $wpdb;

    $query="SELECT hiredUserID, isAccepted, isCompleted, isCancelled, deadLineDate, completedDate FROM $wpdb->inProgressCampaigns WHERE isRevoked=0 and isCancelled = 0 AND postingID =".$postingID;

    $hired = $wpdb->get_row($query);

    return $hired;

}
function enoughFunds($userID, $influencerID, $postingID){
	global $wpdb;
	
	$currentUserBalance = getBalance($userID);
	
	$query = "SELECT SUM(paymentAmount) FROM $wpdb->SocialMediaPostings smp INNER JOIN $wpdb->inProgressCampaigns pc
				ON smp.posting_id = pc.postingID AND isCompleted = 0 AND isCancelled = 0 and isRevoked = 0
				where smp.user_id =".$userID;
				
	$currentPendingBalance = $wpdb->get_var($query);
	
	$query = "SELECT paymentAmount FROM $wpdb->SocialMediaPostings 
				WHERE posting_id = ".$postingID;
				
	$currentPostingAmount = $wpdb->get_var($query);

	if($currentUserBalance->currentBalance >= ($currentPendingBalance + $currentPostingAmount))
	{
		return true;
	}
	else
		return false;
}
function getIDfromGUID($userGUID){
	global $wpdb;

	$query = "SELECT ID from $wpdb->users where user_guid ='".$userGUID."'";

	$ID = $wpdb->get_var($query);

	return $ID;
}
function revokeCampaign($userGuid, $postingID){
	global $wpdb;
	$userID = getIDfromGUID($userGuid);

	$query = "SELECT * FROM $wpdb->inProgressCampaigns where postingID = ".$postingID."
	and hiredUserID = ".$userID." and isAccepted = 1";

	$alreadyAccepted = $wpdb->get_row($query);
	if(!isset($alreadyAccepted)) {
		if (isset($userID)) {
			$revoked = $wpdb->update($wpdb->inProgressCampaigns,
				array(isRevoked => 1),
				array(postingID => $postingID,
					hiredUserID => $userID));
		}
		return 1;
	}
	else
		return 0;
}
function completeCampaign($userGuid, $postingID){
	global $wpdb;
	$userID = getIDfromGUID($userGuid);

	if(isset($userID)) {
		$markedforCompletion = $wpdb->update($wpdb->inProgressCampaigns,
			array(isCompleted => 1),
			array(postingID => $postingID,
				hiredUserID => $userID));
	}
	if($markedforCompletion > 0)
		return true;
	else
		return false;
}
function retractProposal($userGuid, $postingID){
	global $wpdb;
	$userID = getIDfromGUID($userGuid);

	$retracted = $wpdb->update($wpdb->messages,
			array(isRetracted => 1),
			array(postingID => $postingID,
					userID => $userID));

	if($retracted > 0)
		return true;
	else
		return false;
}

function acceptCampaign($userGuid, $postingID){
	global $wpdb;

	$matchUser = checkUser($userGuid, $postingID);
	$userID = getIDfromGUID($userGuid);

	if($matchUser) {
		$accepted = $wpdb->update($wpdb->inProgressCampaigns,
			array(isAccepted => 1),
			array(hiredUserID => $userID,
				postingID => $postingID));
	}
	if($accepted > 0)
		return true;
	else
		return false;
}

function setCampaignInProgress($GUID){
    global $wpdb;

    $query ="SELECT * FROM $wpdb->allowHire WHERE hireGUID ='".$GUID."'";
    $allowHire = $wpdb->get_row($query);

    if(isset($allowHire)){
        $wpdb->insert($wpdb->inProgressCampaigns,
            array(postingID => $allowHire->postingID,
                hiredUserID => $allowHire->hireUserID));
    }
}
function setInfluencerCandidate($hireUserID, $GUID, $postingID){
    global $wpdb;

    $wpdb->insert($wpdb->allowHire,
        array(hireGUID => $GUID,
            hireUserID => $hireUserID,
            postingID => $postingID));

    return $wpdb->insert_id;

}
function getCompletedCampaigns($userID){
		global $wpdb;
	
	$query ="SELECT ap.postingTitle, u.ID, pc.completedDate, ap.paymentAmount, ap.posting_id FROM $wpdb->SocialMediaPostings AS ap 
			INNER JOIN $wpdb->inProgressCampaigns pc 
			ON ap.posting_id = pc.postingID AND pc.isCompleted = 1 and pc.isApprovedCompletion = 1
			INNER JOIN $wpdb->users AS u on pc.hiredUserID = u.ID INNER JOIN $wpdb->socialMediaAccounts sma 
			ON sma.socialMediaType = ap.SocialMediaType AND sma.wpUserID = pc.hiredUserID
			WHERE ap.isDeleted = 0 and ap.user_id = ".$userID;
			
	$completedCampaigns = $wpdb->get_results($query);
	
	return $completedCampaigns;
	
}
function getCompletedWork($userID){
		global $wpdb;
	
	$query = "SELECT m.datetime, m.message, m.userID, smp.postingTitle, pc.completedDate, smp.paymentAmount, smp.user_id   FROM $wpdb->messages  AS m INNER JOIN $wpdb->SocialMediaPostings AS smp ON m.postingID = smp.posting_id
					INNER JOIN $wpdb->inProgressCampaigns AS pc ON smp.posting_id = pc.postingID AND pc.hiredUserID =".$userID."
					WHERE smp.isDeleted = 0 and isCompleted = 1 and isApprovedCompletion = 1 AND m.userID =".$userID." ORDER BY m.datetime DESC";
					
	$results = $wpdb->get_results($query);
	
	return $results;
	
}
function getCurrentWork($userID){
	global $wpdb;
	
	$query = "SELECT m.datetime, m.message, m.userID, smp.postingTitle, pc.deadLineDate, smp.paymentAmount, pc.isAccepted, pc.isCancelled, smp.user_id, smp.posting_id, pc.isCompleted, pc.isApprovedCompletion   FROM $wpdb->messages  AS m INNER JOIN $wpdb->SocialMediaPostings AS smp ON m.postingID = smp.posting_id
					INNER JOIN $wpdb->inProgressCampaigns AS pc ON smp.posting_id = pc.postingID AND pc.hiredUserID =".$userID."
					WHERE smp.isDeleted = 0 and isRevoked = 0 and isApprovedCompletion = 0 AND m.userID =".$userID." ORDER BY m.datetime DESC";
					
	$results = $wpdb->get_results($query);
	
	return $results;
}
function getContactsSent($userID){
	global $wpdb;
	
	$query = "SELECT m.datetime, m.message, m.userID, smp.postingTitle, smp.paymentAmount, smp.user_id, smp.posting_id   FROM $wpdb->messages  AS m INNER JOIN $wpdb->SocialMediaPostings AS smp ON m.postingID = smp.posting_id
					WHERE m.isRetracted = 0 and m.userID =".$userID." ORDER BY m.datetime DESC";
					
	$results = $wpdb->get_results($query);
	
	return $results;
}
function setCampaignStatus($userID, $postingID, $status){
    global $wpdb;
    if($status == "accepted")
        $col = "isAccepted";
    else
        $col = "isDeclined";

    $wpdb->update($wpdb->balances,
        array(	$col => 1),
        array(	userID => $userID,
                postingID => $postingID));

}
function getCampaignsInProgress($userID){
	global $wpdb;
	
	$query ="SELECT ap.postingTitle, u.ID, sma.followers, pc.deadLineDate, ap.paymentAmount, ap.posting_id FROM $wpdb->SocialMediaPostings AS ap 
			INNER JOIN $wpdb->inProgressCampaigns pc 
			ON ap.posting_id = pc.postingID AND pc.isCompleted = 0 AND pc.isCancelled = 0 and pc.isRevoked = 0
			INNER JOIN $wpdb->users AS u on pc.hiredUserID = u.ID INNER JOIN $wpdb->socialMediaAccounts sma 
			ON sma.socialMediaType = ap.SocialMediaType AND sma.wpUserID = pc.hiredUserID 
			WHERE ap.isDeleted = 0 and ap.user_id = ".$userID;
			
	$campaignsInProgress = $wpdb->get_results($query);
	
	return $campaignsInProgress;
}
 function getUserPostings($userID){
	global $wpdb;
	
	$query="SELECT  ap.paymentAmount, mt.Social_name, ap.postingTitle, it.industry_name, ap.posting_id, ap.postingDate, ap.postingIndustry, ap.pricingType, ap.minFollowers, ap.SocialMediaType 
    from $wpdb->SocialMediaPostings AS ap inner join $wpdb->IndustryType it on ap.postingIndustry = it.industry_id 
	inner join $wpdb->socialMediaType mt on ap.SocialMediaType = mt.ID 
	inner join $wpdb->pricingType pt on ap.pricingType = pt.id
    WHERE ap.isDeleted = 0 and ap.user_id =".$userID." order by ap.postingDate DESC";
	
	$postings = $wpdb->get_results($query);
	
	
	return $postings;
 }
 
 function getBalance ($userID){
	 global $wpdb;
	 
	 $query = "SELECT * FROM $wpdb->balances where userID = ".$userID;
	 
	 $currentBalance = $wpdb->get_row($query);
	 
	 return $currentBalance;
 }
 
 function addFunds($userID, $amount, $apiTransactionID, $action, $method){
	 global $wpdb;
	 
	 $query = "SELECT * FROM $wpdb->balances where userID = ".$userID;
	 
	 $exists = $wpdb->get_row($query);
	 
	 if(isset($exists)){
		 $newBalance = $exists->currentBalance + $amount;
		 $wpdb->update($wpdb->balances, 
						array(	currentBalance => $newBalance),
						array(	userID => $userID));
	 }
	 else{
		 $wpdb->insert($wpdb->balances, 	
					array(userID => $userID,
							currentBalance => $amount));
							
		$balanceID = $wpdb->insert_id;
	 }
	 
	 addTransactionHistory($userID, $amount, $apiTransactionID, $action, $method);
 }
 
 function addTransactionHistory($userID, $amount, $apiTransactionID, $action, $method){
	 global $wpdb;
	 
	  $wpdb->insert($wpdb->transactionHistory, 	
					array(transactionDate =>  current_time( 'Y-m-d H:i:s', 1),
							transactionID => $apiTransactionID,
							currencyID => get_user_meta($userID, $currencyID),
							amount => $amount,
							action => $action,
							method => $method,
							userID => $userID));
							
		$GSMTransID = $wpdb->insert_id;
							
	return $GSMTransID;
 }

function hireUser($guid){
	global $wpdb;
	$allowHire = getAllowHire($guid);

	if(isset($allowHire)){
		$wpdb->insert($wpdb->inProgressCampaigns,
					array(postingID => $allowHire->postingID,
						hiredUserID => $allowHire->hireUserID));
		return true;
	}
	else
		return false;

}
function getAllowHire($guid){
	global $wpdb;

	$query = "SELECT postingID, hireUserID FROM $wpdb->allowHire WHERE hireGUID ='".$guid."'";

	$AllowHire = $wpdb->get_row($query);

	return $AllowHire;
}

 
 function getNoProposals($postingID){
	 global $wpdb;
	 
	 $query = "SELECT COUNT(*) FROM $wpdb->messages WHERE postingID = ".$postingID;
	 
	 $NoOfProposals = $wpdb->get_var($query);
	 
	 return $NoOfProposals;

}

 function getAllPostings(){
	global $wpdb;
	/*$query = "SELECT ft.facilitytype_name, f.facility_address, fp.posting_id, fp.facility_id, f.facility_name, f.facility_description, fp.startdatetime, fp.enddatetime, fp.price, fp.originalPrice, f.facility_photo, fm.user_id from $wpdb->facilityPosting AS fp inner join $wpdb->facilities AS f ON fp.facility_id = f.facility_id inner join $wpdb->facilitymeta AS fm ON fm.facility_id = f.facility_id inner join $wpdb->facilitytype as ft on fm.facilitytype_id = ft.facilitytype_id inner join $wpdb->users as u on fm.user_id = u.id left join $wpdb->tempTransactions tt on fp.posting_id =tt.posting_id where tt.posting_id IS NULL AND fp.booked = 0";*/
	
	$query="SELECT mt.iconPath, ap.paymentAmount,ap.promotionType, ap.SocialMediaType, mt.Social_name, pt.Pricing_Name, ap.postingTitle, it.industry_name, u.display_name, u.user_email, ap.posting_id, ap.postingAddress, ap.postingDate, ap.postingIndustry, ap.pricingType, ap.postingDesc, ap.minFollowers, ap.user_id, ap.postingCity from $wpdb->SocialMediaPostings AS ap inner join $wpdb->users as u on ap.user_id = u.id 
	inner join $wpdb->IndustryType it on ap.postingIndustry = it.industry_id 
	inner join $wpdb->socialMediaType mt on ap.SocialMediaType = mt.ID 
	inner join $wpdb->pricingType pt on ap.pricingType = pt.id
	WHERE isDeleted = 0
	order by ap.promotionType desc, ap.postingDate DESC";
	
	$postings = $wpdb->get_results($query);
	
	
	return $postings;
 }
 
 function postingSearch($cityID, $industryID){
	global $wpdb;
	
	$query="SELECT mt.iconPath, ap.paymentAmount,ap.promotionType, ap.SocialMediaType, mt.Social_name, pt.Pricing_Name, ap.postingTitle, it.industry_name, u.display_name, u.user_email, ap.posting_id, ap.postingAddress, ap.postingDate, ap.postingIndustry, ap.pricingType, ap.postingDesc, ap.minFollowers, ap.user_id, ap.postingCity from $wpdb->SocialMediaPostings AS ap inner join $wpdb->users as u on ap.user_id = u.id 
	inner join $wpdb->IndustryType it on ap.postingIndustry = it.industry_id 
	inner join $wpdb->socialMediaType mt on ap.SocialMediaType = mt.ID 
	inner join $wpdb->pricingType pt on ap.pricingType = pt.id
    where ap.postingCity = ".$cityID." and ap.isDelete = 0 and ap.postingIndustry = ".$industryID." order by ap.promotionType desc, ap.postingDate DESC";
	
	$postings = $wpdb->get_results($query);
	
	return $postings;
 }
 
 function getpaypalBtnID($posting_id)
 {
	global $wpdb;
	$results = $wpdb->get_results("SELECT paypalbtnID from $wpdb->facilityPosting where posting_id = $posting_id");
	
	foreach($results as $result){
		$btnID = $result->paypalbtnID;
	}
	
	return $btnID;
 }
 
 function wp_delete_posting($userGUID, $postingID){
	global $wpdb;

	 $matchUser = checkUser($userGUID, $postingID);

	 if($matchUser) {
		 $postingDelete = $wpdb->update($wpdb->SocialMediaPostings,
			 array(isDeleted => 1),
			 array(posting_id => $postingID));
	 }
	if($postingDelete > 0)
		return true;
	else
		return false;
 }
function checkUser($userGuid, $postingID){
	global $wpdb;

	$checkQuery = "SELECT u.user_guid FROM $wpdb->SocialMediaPostings smp INNER JOIN $wpdb->users u ON smp.user_id = u.ID
					WHERE smp.posting_id = ".$postingID;

	$matchUser = $wpdb->get_var($checkQuery);
	if($matchUser == $userGuid)
		return true;
	else
		return false;
}

 function wp_update_posting($postingID, $postingPart, $postingPartValue){
	global $wpdb;

	$wpdb->update($wpdb->SocialMediaPostings, 
						array(	$postingPart => $postingPartValue),
						array(	posting_id => $postingID));
}
 
 function wp_add_posting($postingTitle,$industryID, $userID, $pricingID,$postingCity, $postingDesc, $minFollowers, $paymentAmount,$mediaID){
	global $wpdb;
	
	$wpdb->insert($wpdb->SocialMediaPostings, 	
					array(user_id => $userID,
							postingCity => $postingCity,
							postingDesc => $postingDesc,
							minFollowers => $minFollowers,
							postingIndustry  => $industryID,
							postingTitle => $postingTitle,
                            pricingType  => $pricingID,
                            paymentAmount => $paymentAmount,
							SocialMediaType => $mediaID,
                            postingDate =>  current_time( 'Y-m-d H:i:s', 1)));
							
	$postingID = $wpdb->insert_id;

	return $postingID;	
	
}
 function wp_delete_facility($facilityID, $metaID){
	global $wpdb;
	$metadeleted = $wpdb->delete($wpdb->facilitymeta,
					array(	facilitymeta_id => $metaID),
					array(	'%d'));
					
	$facilitydelete = $wpdb->delete($wpdb->facilities,
					array(	facility_id => $facilityID),
					array(	'%d'));
					
	if($facilitydelete > 0 && $metadeleted > 0)
		return $facilitydelete.$metadeleted;
	else
		return 'There was a problem deleting facilities';

 }
 
 function wp_update_facility($ID, $facilityID, $metaID, $Name, $description, $address, $photo, $type){
	global $wpdb;
	$userinfo = get_userdata($ID);
	if(empty($photo))
	{
		$wpdb->update($wpdb->facilities, 
						array(	facility_name => $Name,
								facility_description => $description,
								facility_address => $address,
								facility_city => $userinfo->city,
								facility_state => $userinfo->state,
								facility_country => $userinfo->country),
						array(facility_id => $facilityID),
						array(	'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s'),
						array('%d'));
	}
	else
	{
		$wpdb->update($wpdb->facilities, 
						array(	facility_name => $Name,
								facility_description => $description,
								facility_address => $address,
								facility_photo => $photo,
								facility_city => $userinfo->city,
								facility_state => $userinfo->state,
								facility_country => $userinfo->country),
						array(facility_id => $facilityID),
						array(	'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s'),
						array('%d'));
	}				
	$wpdb->update($wpdb->facilitymeta, 
					array(	facilitytype_id => $type),
					array(	facilitymeta_id => $metaID),
					array(	'%d'),
					array(	'%d'));
 }
 
function wp_add_facility($ID, $Name, $Description, $Address, $Photo, $Type){
	global $wpdb;
	$userinfo = get_userdata($ID);
	$wpdb->insert($wpdb->facilities, 
					array(	facility_name => $Name,
							facility_description => $Description,
							facility_address => $Address,
							facility_photo => $Photo,
							facility_city => $userinfo->city,
							facility_state => $userinfo->state,
							facility_country => $userinfo->country),
					array(	'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s')
					);
	$facilityID = $wpdb->insert_id;
	$wpdb->insert($wpdb->facilitymeta, 
					array(	facility_id => $facilityID,
							user_id => $ID,
							facilitytype_id => $Type),
					array(	'%d',
							'%d',
							'%d')
					);
				

	
	return facilityID;
}
 function get_postings($user_id){
	global $wpdb;
	$query = "SELECT fp.posting_id, fp.facility_id, f.facility_name, fp.startdatetime, fp.enddatetime, fp.price, fp.originalPrice from $wpdb->facilityPosting AS fp inner join $wpdb->facilities AS f ON fp.facility_id = f.facility_id inner join $wpdb->facilitymeta AS fm ON fm.facility_id = f.facility_id where fm.user_id = $user_id";
	
	$postings = $wpdb->get_results($query);
	
	return $postings;
}
function get_facilities($user_id){
	global $wpdb;
	$query = "SELECT fm.facilitymeta_id, fm.facility_id, f.facility_name, f.facility_address, f.facility_description, ft.facilitytype_name, f.facility_photo, ft.facilitytype_id from $wpdb->facilitymeta AS fm inner join $wpdb->facilities AS f ON fm.facility_id = f.facility_id inner join $wpdb->facilitytype AS ft on fm.facilitytype_id = ft.facilitytype_id where fm.user_id = $user_id";
	
	$facilities = $wpdb->get_results($query);
	
	return $facilities;
}

function get_industry_types(){
	global $wpdb;
	$query = "SELECT industry_id, industry_name from $wpdb->IndustryType";
	
	$industryTypes = $wpdb->get_results($query);
	
	return $industryTypes;
}

function get_mediaPlatforms(){
	global $wpdb;
	$query = "SELECT ID, Social_Name from $wpdb->socialMediaType";
	
	$mediaPlatforms = $wpdb->get_results($query);
	
	return $mediaPlatforms;
}

function get_paymentTypes(){
	global $wpdb;
	$query = "SELECT ID, Pricing_Name from $wpdb->pricingType";
	
	$paymentTypes = $wpdb->get_results($query);
	
	return $paymentTypes;
}


function get_countries()
{
	global $wpdb;
	$query = "SELECT id, country FROM $wpdb->countries";
	
	$countries = $wpdb->get_results($query);
	
	return $countries;
}
function get_states($countryid)
{
	global $wpdb;
	$query = "SELECT id, state FROM $wpdb->states where countryid = $countryid";
	
	$states = $wpdb->get_results($query);
	
	return $states;
}

function get_cities($stateid)
{
	global $wpdb;
	$query = "SELECT c.id, city, s.state FROM $wpdb->cities AS c inner join $wpdb->states AS s ON c.state = s.id where c.state = $stateid";
	
	$cities = $wpdb->get_results($query);
	
	return $cities;
}

function pretictive_search($search)
{
	global $wpdb;
	$query = "SELECT c.id, city, s.state, s.stateCode, co.country, co.id AS coid FROM $wpdb->cities AS c inner join $wpdb->states AS s ON c.state = s.id inner join $wpdb->countries AS co ON s.countryid = co.id where c.city LIKE '$search%' LIMIT 15";
	
	$predictiveResults = $wpdb->get_results($query);
	
	return $predictiveResults;
}
 
function get_post_location($cityId)
{
	global $wpdb;

		$query = "SELECT co.id AS countryID, c.city AS city,s.id as stateID, s.stateCode AS stateCode, co.countryCode AS countryCode FROM $wpdb->cities AS c inner join $wpdb->states AS s on c.state = s.id inner join $wpdb->countries co on s.countryid = co.id where c.id = $cityId";
		
		$location = $wpdb->get_results($query);
		
		return $location;

}

function custom_breadcrumbs() {
       
    // Settings
    $separator          = '/';
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumbs';
    $home_title         = 'Homepage';
      
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';
       
    // Get the query & post information
    global $post,$wp_query;
       
    // Do not display on the homepage
    if ( !is_front_page() ) {
       
        // Build the breadcrums
        echo '<ul id="' . $breadcrums_id . '"style="float:left" class="' . $breadcrums_class . '">';
           
        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
        echo '<li class="separator separator-home"> ' . $separator . ' </li>';
           
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
              
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';
              
        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
              
            }
              
            $custom_tax_name = get_queried_object()->name;
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';
              
        } else if ( is_single() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
              
            }
              
            // Get post category info
            $category = get_the_category();
             
            if(!empty($category)) {
              
                // Get last category post is in
                $last_category = end(array_values($category));
                  
                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);
                  
                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat">'.$parents.'</li>';
                    $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                }
             
            }
              
            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                   
                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;
               
            }
              
            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
                  
            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
                  
                echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
              
            } else {
                  
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
                  
            }
              
        } else if ( is_category() ) {
               
            // Category page
            echo '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>';
               
        } else if ( is_page() ) {
               
            // Standard page
            if( $post->post_parent ){
                   
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                   
                // Get parents in the right order
                $anc = array_reverse($anc);
                   
                // Parent page loop
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }
                   
                // Display parent pages
                echo $parents;
                   
                // Current page
                echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';
                   
            } else {
                   
                // Just display current page if not parents
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';
                   
            }
               
        } else if ( is_tag() ) {
               
            // Tag page
               
            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
               
            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';
           
        } elseif ( is_day() ) {
               
            // Day archive
               
            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
               
            // Month link
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';
               
            // Day display
            echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';
               
        } else if ( is_month() ) {
               
            // Month Archive
               
            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
               
            // Month display
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';
               
        } else if ( is_year() ) {
               
            // Display year archive
            echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';
               
        } else if ( is_author() ) {
               
            // Auhor archive
               
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
               
            // Display author name
            echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';
           
        } else if ( get_query_var('paged') ) {
               
            // Paginated archives
            echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';
               
        } else if ( is_search() ) {
           
            // Search results page
            echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';
           
        } elseif ( is_404() ) {
               
            // 404 page
            echo '<li>' . 'Error 404' . '</li>';
        }
       
        echo '</ul>';
           
    }
       
}
 
 
function get_terms( $taxonomies, $args = '' ) {
	global $wpdb;
	$empty_array = array();

	$single_taxonomy = ! is_array( $taxonomies ) || 1 === count( $taxonomies );
	if ( ! is_array( $taxonomies ) ) {
		$taxonomies = array( $taxonomies );
	}

	foreach ( $taxonomies as $taxonomy ) {
		if ( ! taxonomy_exists($taxonomy) ) {
			$error = new WP_Error('invalid_taxonomy', __('Invalid taxonomy'));
			return $error;
		}
	}

	$defaults = array('orderby' => 'name', 'order' => 'ASC',
		'hide_empty' => true, 'exclude' => array(), 'exclude_tree' => array(), 'include' => array(),
		'number' => '', 'fields' => 'all', 'slug' => '', 'parent' => '',
		'hierarchical' => true, 'child_of' => 0, 'get' => '', 'name__like' => '', 'description__like' => '',
		'pad_counts' => false, 'offset' => '', 'search' => '', 'cache_domain' => 'core' );
	$args = wp_parse_args( $args, $defaults );
	$args['number'] = absint( $args['number'] );
	$args['offset'] = absint( $args['offset'] );
	if ( !$single_taxonomy || ! is_taxonomy_hierarchical( reset( $taxonomies ) ) ||
		( '' !== $args['parent'] && 0 !== $args['parent'] ) ) {
		$args['child_of'] = 0;
		$args['hierarchical'] = false;
		$args['pad_counts'] = false;
	}

	if ( 'all' == $args['get'] ) {
		$args['child_of'] = 0;
		$args['hide_empty'] = 0;
		$args['hierarchical'] = false;
		$args['pad_counts'] = false;
	}

	/**
	 * Filter the terms query arguments.
	 *
	 * @since 3.1.0
	 *
	 * @param array        $args       An array of arguments.
	 * @param string|array $taxonomies A taxonomy or array of taxonomies.
	 */
	$args = apply_filters( 'get_terms_args', $args, $taxonomies );

	$child_of = $args['child_of'];
	if ( $child_of ) {
		$hierarchy = _get_term_hierarchy( reset( $taxonomies ) );
		if ( ! isset( $hierarchy[ $child_of ] ) ) {
			return $empty_array;
		}
	}

	$parent = $args['parent'];
	if ( $parent ) {
		$hierarchy = _get_term_hierarchy( reset( $taxonomies ) );
		if ( ! isset( $hierarchy[ $parent ] ) ) {
			return $empty_array;
		}
	}

	// $args can be whatever, only use the args defined in defaults to compute the key
	$filter_key = ( has_filter('list_terms_exclusions') ) ? serialize($GLOBALS['wp_filter']['list_terms_exclusions']) : '';
	$key = md5( serialize( wp_array_slice_assoc( $args, array_keys( $defaults ) ) ) . serialize( $taxonomies ) . $filter_key );
	$last_changed = wp_cache_get( 'last_changed', 'terms' );
	if ( ! $last_changed ) {
		$last_changed = microtime();
		wp_cache_set( 'last_changed', $last_changed, 'terms' );
	}
	$cache_key = "get_terms:$key:$last_changed";
	$cache = wp_cache_get( $cache_key, 'terms' );
	if ( false !== $cache ) {

		/**
		 * Filter the given taxonomy's terms cache.
		 *
		 * @since 2.3.0
		 *
		 * @param array        $cache      Cached array of terms for the given taxonomy.
		 * @param string|array $taxonomies A taxonomy or array of taxonomies.
		 * @param array        $args       An array of arguments to get terms.
		 */
		$cache = apply_filters( 'get_terms', $cache, $taxonomies, $args );
		return $cache;
	}

	$_orderby = strtolower( $args['orderby'] );
	if ( 'count' == $_orderby ) {
		$orderby = 'tt.count';
	} else if ( 'name' == $_orderby ) {
		$orderby = 't.name';
	} else if ( 'slug' == $_orderby ) {
		$orderby = 't.slug';
	} else if ( 'term_group' == $_orderby ) {
		$orderby = 't.term_group';
	} else if ( 'none' == $_orderby ) {
		$orderby = '';
	} elseif ( empty($_orderby) || 'id' == $_orderby ) {
		$orderby = 't.term_id';
	} else {
		$orderby = 't.name';
	}
	/**
	 * Filter the ORDERBY clause of the terms query.
	 *
	 * @since 2.8.0
	 *
	 * @param string       $orderby    ORDERBY clause of the terms query.
	 * @param array        $args       An array of terms query arguments.
	 * @param string|array $taxonomies A taxonomy or array of taxonomies.
	 */
	$orderby = apply_filters( 'get_terms_orderby', $orderby, $args, $taxonomies );

	$order = strtoupper( $args['order'] );
	if ( ! empty( $orderby ) ) {
		$orderby = "ORDER BY $orderby";
	} else {
		$order = '';
	}

	if ( '' !== $order && ! in_array( $order, array( 'ASC', 'DESC' ) ) ) {
		$order = 'ASC';
	}

	$where = "tt.taxonomy IN ('" . implode("', '", $taxonomies) . "')";

	$exclude = $args['exclude'];
	$exclude_tree = $args['exclude_tree'];
	$include = $args['include'];

	$inclusions = '';
	if ( ! empty( $include ) ) {
		$exclude = '';
		$exclude_tree = '';
		$inclusions = implode( ',', wp_parse_id_list( $include ) );
	}

	if ( ! empty( $inclusions ) ) {
		$inclusions = ' AND t.term_id IN ( ' . $inclusions . ' )';
		$where .= $inclusions;
	}

	if ( ! empty( $exclude_tree ) ) {
		$exclude_tree = wp_parse_id_list( $exclude_tree );
		$excluded_children = $exclude_tree;
		foreach ( $exclude_tree as $extrunk ) {
			$excluded_children = array_merge(
				$excluded_children,
				(array) get_terms( $taxonomies[0], array( 'child_of' => intval( $extrunk ), 'fields' => 'ids', 'hide_empty' => 0 ) )
			);
		}
		$exclusions = implode( ',', array_map( 'intval', $excluded_children ) );
	} else {
		$exclusions = '';
	}

	if ( ! empty( $exclude ) ) {
		$exterms = wp_parse_id_list( $exclude );
		if ( empty( $exclusions ) ) {
			$exclusions = implode( ',', $exterms );
		} else {
			$exclusions .= ', ' . implode( ',', $exterms );
		}
	}

	if ( ! empty( $exclusions ) ) {
		$exclusions = ' AND t.term_id NOT IN (' . $exclusions . ')';
	}

	/**
	 * Filter the terms to exclude from the terms query.
	 *
	 * @since 2.3.0
	 *
	 * @param string       $exclusions NOT IN clause of the terms query.
	 * @param array        $args       An array of terms query arguments.
	 * @param string|array $taxonomies A taxonomy or array of taxonomies.
	 */
	$exclusions = apply_filters( 'list_terms_exclusions', $exclusions, $args, $taxonomies );

	if ( ! empty( $exclusions ) ) {
		$where .= $exclusions;
	}

	if ( ! empty( $args['slug'] ) ) {
		$slug = sanitize_title( $args['slug'] );
		$where .= " AND t.slug = '$slug'";
	}

	if ( ! empty( $args['name__like'] ) ) {
		$where .= $wpdb->prepare( " AND t.name LIKE %s", '%' . $wpdb->esc_like( $args['name__like'] ) . '%' );
	}

	if ( ! empty( $args['description__like'] ) ) {
		$where .= $wpdb->prepare( " AND tt.description LIKE %s", '%' . $wpdb->esc_like( $args['description__like'] ) . '%' );
	}

	if ( '' !== $parent ) {
		$parent = (int) $parent;
		$where .= " AND tt.parent = '$parent'";
	}

	$hierarchical = $args['hierarchical'];
	if ( 'count' == $args['fields'] ) {
		$hierarchical = false;
	}
	if ( $args['hide_empty'] && !$hierarchical ) {
		$where .= ' AND tt.count > 0';
	}

	$number = $args['number'];
	$offset = $args['offset'];

	// don't limit the query results when we have to descend the family tree
	if ( $number && ! $hierarchical && ! $child_of && '' === $parent ) {
		if ( $offset ) {
			$limits = 'LIMIT ' . $offset . ',' . $number;
		} else {
			$limits = 'LIMIT ' . $number;
		}
	} else {
		$limits = '';
	}

	if ( ! empty( $args['search'] ) ) {
		$like = '%' . $wpdb->esc_like( $args['search'] ) . '%';
		$where .= $wpdb->prepare( ' AND ((t.name LIKE %s) OR (t.slug LIKE %s))', $like, $like );
	}

	$selects = array();
	switch ( $args['fields'] ) {
		case 'all':
			$selects = array( 't.*', 'tt.*' );
			break;
		case 'ids':
		case 'id=>parent':
			$selects = array( 't.term_id', 'tt.parent', 'tt.count' );
			break;
		case 'names':
			$selects = array( 't.term_id', 'tt.parent', 'tt.count', 't.name' );
			break;
		case 'count':
			$orderby = '';
			$order = '';
			$selects = array( 'COUNT(*)' );
			break;
		case 'id=>name':
			$selects = array( 't.term_id', 't.name' );
			break;
		case 'id=>slug':
			$selects = array( 't.term_id', 't.slug' );
			break;
	}

	$_fields = $args['fields'];

	/**
	 * Filter the fields to select in the terms query.
	 *
	 * @since 2.8.0
	 *
	 * @param array        $selects    An array of fields to select for the terms query.
	 * @param array        $args       An array of term query arguments.
	 * @param string|array $taxonomies A taxonomy or array of taxonomies.
	 */
	$fields = implode( ', ', apply_filters( 'get_terms_fields', $selects, $args, $taxonomies ) );

	$join = "INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id";

	$pieces = array( 'fields', 'join', 'where', 'orderby', 'order', 'limits' );

	/**
	 * Filter the terms query SQL clauses.
	 *
	 * @since 3.1.0
	 *
	 * @param array        $pieces     Terms query SQL clauses.
	 * @param string|array $taxonomies A taxonomy or array of taxonomies.
	 * @param array        $args       An array of terms query arguments.
	 */
	$clauses = apply_filters( 'terms_clauses', compact( $pieces ), $taxonomies, $args );
	$fields = isset( $clauses[ 'fields' ] ) ? $clauses[ 'fields' ] : '';
	$join = isset( $clauses[ 'join' ] ) ? $clauses[ 'join' ] : '';
	$where = isset( $clauses[ 'where' ] ) ? $clauses[ 'where' ] : '';
	$orderby = isset( $clauses[ 'orderby' ] ) ? $clauses[ 'orderby' ] : '';
	$order = isset( $clauses[ 'order' ] ) ? $clauses[ 'order' ] : '';
	$limits = isset( $clauses[ 'limits' ] ) ? $clauses[ 'limits' ] : '';

	$query = "SELECT $fields FROM $wpdb->terms AS t $join WHERE $where $orderby $order $limits";

	if ( 'count' == $_fields ) {
		$term_count = $wpdb->get_var($query);
		return $term_count;
	}

	$terms = $wpdb->get_results($query);
	if ( 'all' == $_fields ) {
		update_term_cache($terms);
	}

	if ( empty($terms) ) {
		wp_cache_add( $cache_key, array(), 'terms', DAY_IN_SECONDS );

		/** This filter is documented in wp-includes/taxonomy.php */
		$terms = apply_filters( 'get_terms', array(), $taxonomies, $args );
		return $terms;
	}

	if ( $child_of ) {
		$children = _get_term_hierarchy( reset( $taxonomies ) );
		if ( ! empty( $children ) ) {
			$terms = _get_term_children( $child_of, $terms, reset( $taxonomies ) );
		}
	}

	// Update term counts to include children.
	if ( $args['pad_counts'] && 'all' == $_fields ) {
		_pad_term_counts( $terms, reset( $taxonomies ) );
	}
	// Make sure we show empty categories that have children.
	if ( $hierarchical && $args['hide_empty'] && is_array( $terms ) ) {
		foreach ( $terms as $k => $term ) {
			if ( ! $term->count ) {
				$children = get_term_children( $term->term_id, reset( $taxonomies ) );
				if ( is_array( $children ) ) {
					foreach ( $children as $child_id ) {
						$child = get_term( $child_id, reset( $taxonomies ) );
						if ( $child->count ) {
							continue 2;
						}
					}
				}

				// It really is empty
				unset($terms[$k]);
			}
		}
	}
	reset( $terms );

	$_terms = array();
	if ( 'id=>parent' == $_fields ) {
		while ( $term = array_shift( $terms ) ) {
			$_terms[$term->term_id] = $term->parent;
		}
	} elseif ( 'ids' == $_fields ) {
		while ( $term = array_shift( $terms ) ) {
			$_terms[] = $term->term_id;
		}
	} elseif ( 'names' == $_fields ) {
		while ( $term = array_shift( $terms ) ) {
			$_terms[] = $term->name;
		}
	} elseif ( 'id=>name' == $_fields ) {
		while ( $term = array_shift( $terms ) ) {
			$_terms[$term->term_id] = $term->name;
		}
	} elseif ( 'id=>slug' == $_fields ) {
		while ( $term = array_shift( $terms ) ) {
			$_terms[$term->term_id] = $term->slug;
		}
	}

	if ( ! empty( $_terms ) ) {
		$terms = $_terms;
	}

	if ( $number && is_array( $terms ) && count( $terms ) > $number ) {
		$terms = array_slice( $terms, $offset, $number );
	}

	wp_cache_add( $cache_key, $terms, 'terms', DAY_IN_SECONDS );

	/** This filter is documented in wp-includes/taxonomy */
	$terms = apply_filters( 'get_terms', $terms, $taxonomies, $args );
	return $terms;
}

/**
 * Check if Term exists.
 *
 * Formerly is_term(), introduced in 2.3.0.
 *
 * @since 3.0.0
 *
 * @uses $wpdb
 *
 * @param int|string $term The term to check
 * @param string $taxonomy The taxonomy name to use
 * @param int $parent ID of parent term under which to confine the exists search.
 * @return mixed Returns 0 if the term does not exist. Returns the term ID if no taxonomy is specified
 *               and the term ID exists. Returns an array of the term ID and the term taxonomy ID
 *               if the taxonomy is specified and the pairing exists.
 */
function term_exists($term, $taxonomy = '', $parent = 0) {
	global $wpdb;

	$select = "SELECT term_id FROM $wpdb->terms as t WHERE ";
	$tax_select = "SELECT tt.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_id = t.term_id WHERE ";

	if ( is_int($term) ) {
		if ( 0 == $term )
			return 0;
		$where = 't.term_id = %d';
		if ( !empty($taxonomy) )
			return $wpdb->get_row( $wpdb->prepare( $tax_select . $where . " AND tt.taxonomy = %s", $term, $taxonomy ), ARRAY_A );
		else
			return $wpdb->get_var( $wpdb->prepare( $select . $where, $term ) );
	}

	$term = trim( wp_unslash( $term ) );

	if ( '' === $slug = sanitize_title($term) )
		return 0;

	$where = 't.slug = %s';
	$else_where = 't.name = %s';
	$where_fields = array($slug);
	$else_where_fields = array($term);
	if ( !empty($taxonomy) ) {
		$parent = (int) $parent;
		if ( $parent > 0 ) {
			$where_fields[] = $parent;
			$else_where_fields[] = $parent;
			$where .= ' AND tt.parent = %d';
			$else_where .= ' AND tt.parent = %d';
		}

		$where_fields[] = $taxonomy;
		$else_where_fields[] = $taxonomy;

		if ( $result = $wpdb->get_row( $wpdb->prepare("SELECT tt.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_id = t.term_id WHERE $where AND tt.taxonomy = %s", $where_fields), ARRAY_A) )
			return $result;

		return $wpdb->get_row( $wpdb->prepare("SELECT tt.term_id, tt.term_taxonomy_id FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_id = t.term_id WHERE $else_where AND tt.taxonomy = %s", $else_where_fields), ARRAY_A);
	}

	if ( $result = $wpdb->get_var( $wpdb->prepare("SELECT term_id FROM $wpdb->terms as t WHERE $where", $where_fields) ) )
		return $result;

	return $wpdb->get_var( $wpdb->prepare("SELECT term_id FROM $wpdb->terms as t WHERE $else_where", $else_where_fields) );
}

/**
 * Check if a term is an ancestor of another term.
 *
 * You can use either an id or the term object for both parameters.
 *
 * @since 3.4.0
 *
 * @param int|object $term1 ID or object to check if this is the parent term.
 * @param int|object $term2 The child term.
 * @param string $taxonomy Taxonomy name that $term1 and $term2 belong to.
 * @return bool Whether $term2 is child of $term1
 */
function term_is_ancestor_of( $term1, $term2, $taxonomy ) {
	if ( ! isset( $term1->term_id ) )
		$term1 = get_term( $term1, $taxonomy );
	if ( ! isset( $term2->parent ) )
		$term2 = get_term( $term2, $taxonomy );

	if ( empty( $term1->term_id ) || empty( $term2->parent ) )
		return false;
	if ( $term2->parent == $term1->term_id )
		return true;

	return term_is_ancestor_of( $term1, get_term( $term2->parent, $taxonomy ), $taxonomy );
}

/**
 * Sanitize Term all fields.
 *
 * Relies on sanitize_term_field() to sanitize the term. The difference is that
 * this function will sanitize <strong>all</strong> fields. The context is based
 * on sanitize_term_field().
 *
 * The $term is expected to be either an array or an object.
 *
 * @since 2.3.0
 *
 * @uses sanitize_term_field Used to sanitize all fields in a term
 *
 * @param array|object $term The term to check
 * @param string $taxonomy The taxonomy name to use
 * @param string $context Default is 'display'.
 * @return array|object Term with all fields sanitized
 */
function sanitize_term($term, $taxonomy, $context = 'display') {

	$fields = array( 'term_id', 'name', 'description', 'slug', 'count', 'parent', 'term_group', 'term_taxonomy_id', 'object_id' );

	$do_object = is_object( $term );

	$term_id = $do_object ? $term->term_id : (isset($term['term_id']) ? $term['term_id'] : 0);

	foreach ( (array) $fields as $field ) {
		if ( $do_object ) {
			if ( isset($term->$field) )
				$term->$field = sanitize_term_field($field, $term->$field, $term_id, $taxonomy, $context);
		} else {
			if ( isset($term[$field]) )
				$term[$field] = sanitize_term_field($field, $term[$field], $term_id, $taxonomy, $context);
		}
	}

	if ( $do_object )
		$term->filter = $context;
	else
		$term['filter'] = $context;

	return $term;
}

/**
 * Cleanse the field value in the term based on the context.
 *
 * Passing a term field value through the function should be assumed to have
 * cleansed the value for whatever context the term field is going to be used.
 *
 * If no context or an unsupported context is given, then default filters will
 * be applied.
 *
 * There are enough filters for each context to support a custom filtering
 * without creating your own filter function. Simply create a function that
 * hooks into the filter you need.
 *
 * @since 2.3.0
 *
 * @uses $wpdb
 *
 * @param string $field Term field to sanitize
 * @param string $value Search for this term value
 * @param int $term_id Term ID
 * @param string $taxonomy Taxonomy Name
 * @param string $context Either edit, db, display, attribute, or js.
 * @return mixed sanitized field
 */
function sanitize_term_field($field, $value, $term_id, $taxonomy, $context) {
	$int_fields = array( 'parent', 'term_id', 'count', 'term_group', 'term_taxonomy_id', 'object_id' );
	if ( in_array( $field, $int_fields ) ) {
		$value = (int) $value;
		if ( $value < 0 )
			$value = 0;
	}

	if ( 'raw' == $context )
		return $value;

	if ( 'edit' == $context ) {

		/**
		 * Filter a term field to edit before it is sanitized.
		 *
		 * The dynamic portion of the filter name, $field, refers to the term field.
		 *
		 * @since 2.3.0
		 *
		 * @param mixed $value     Value of the term field.
		 * @param int   $term_id   Term ID.
		 * @param string $taxonomy Taxonomy slug.
		 */
		$value = apply_filters( "edit_term_{$field}", $value, $term_id, $taxonomy );

		/**
		 * Filter the taxonomy field to edit before it is sanitized.
		 *
		 * The dynamic portions of the filter name, $taxonomy, and $field, refer
		 * to the taxonomy slug and taxonomy field, respectively.
		 *
		 * @since 2.3.0
		 *
		 * @param mixed $value   Value of the taxonomy field to edit.
		 * @param int   $term_id Term ID.
		 */
		$value = apply_filters( "edit_{$taxonomy}_{$field}", $value, $term_id );
		if ( 'description' == $field )
			$value = esc_html($value); // textarea_escaped
		else
			$value = esc_attr($value);
	} else if ( 'db' == $context ) {

		/**
		 * Filter a term field value before it is sanitized.
		 *
		 * The dynamic portion of the filter name, $field, refers to the term field.
		 *
		 * @since 2.3.0
		 *
		 * @param mixed  $value    Value of the term field.
		 * @param string $taxonomy Taxonomy slug.
		 */
		$value = apply_filters( "pre_term_{$field}", $value, $taxonomy );

		/**
		 * Filter a taxonomy field before it is sanitized.
		 *
		 * The dynamic portions of the filter name, $taxonomy, and $field, refer
		 * to the taxonomy slug and field name, respectively.
		 *
		 * @since 2.3.0
		 *
		 * @param mixed $value Value of the taxonomy field.
		 */
		$value = apply_filters( "pre_{$taxonomy}_{$field}", $value );
		// Back compat filters
		if ( 'slug' == $field ) {
			/**
			 * Filter the category nicename before it is sanitized.
			 *
			 * Use the pre_{$taxonomy}_{$field} hook instead.
			 *
			 * @since 2.0.3
			 *
			 * @param string $value The category nicename.
			 */
			$value = apply_filters( 'pre_category_nicename', $value );
		}

	} else if ( 'rss' == $context ) {

		/**
		 * Filter the term field for use in RSS.
		 *
		 * The dynamic portion of the filter name, $field, refers to the term field.
		 *
		 * @since 2.3.0
		 *
		 * @param mixed  $value    Value of the term field.
		 * @param string $taxonomy Taxonomy slug.
		 */
		$value = apply_filters( "term_{$field}_rss", $value, $taxonomy );

		/**
		 * Filter the taxonomy field for use in RSS.
		 *
		 * The dynamic portions of the hook name, $taxonomy, and $field, refer
		 * to the taxonomy slug and field name, respectively.
		 *
		 * @since 2.3.0
		 *
		 * @param mixed $value Value of the taxonomy field.
		 */
		$value = apply_filters( "{$taxonomy}_{$field}_rss", $value );
	} else {
		// Use display filters by default.

		/**
		 * Filter the term field sanitized for display.
		 *
		 * The dynamic portion of the filter name, $field, refers to the term field name.
		 *
		 * @since 2.3.0
		 *
		 * @param mixed  $value    Value of the term field.
		 * @param int    $term_id  Term ID.
		 * @param string $taxonomy Taxonomy slug.
		 * @param string $context  Context to retrieve the term field value.
		 */
		$value = apply_filters( "term_{$field}", $value, $term_id, $taxonomy, $context );

		/**
		 * Filter the taxonomy field sanitized for display.
		 *
		 * The dynamic portions of the filter name, $taxonomy, and $field, refer
		 * to the taxonomy slug and taxonomy field, respectively.
		 *
		 * @since 2.3.0
		 *
		 * @param mixed  $value   Value of the taxonomy field.
		 * @param int    $term_id Term ID.
		 * @param string $context Context to retrieve the taxonomy field value.
		 */
		$value = apply_filters( "{$taxonomy}_{$field}", $value, $term_id, $context );
	}

	if ( 'attribute' == $context )
		$value = esc_attr($value);
	else if ( 'js' == $context )
		$value = esc_js($value);

	return $value;
}

/**
 * Count how many terms are in Taxonomy.
 *
 * Default $args is 'hide_empty' which can be 'hide_empty=true' or array('hide_empty' => true).
 *
 * @since 2.3.0
 *
 * @uses get_terms()
 * @uses wp_parse_args() Turns strings into arrays and merges defaults into an array.
 *
 * @param string $taxonomy Taxonomy name
 * @param array|string $args Overwrite defaults. See get_terms()
 * @return int|WP_Error How many terms are in $taxonomy. WP_Error if $taxonomy does not exist.
 */
function wp_count_terms( $taxonomy, $args = array() ) {
	$defaults = array('hide_empty' => false);
	$args = wp_parse_args($args, $defaults);

	// backwards compatibility
	if ( isset($args['ignore_empty']) ) {
		$args['hide_empty'] = $args['ignore_empty'];
		unset($args['ignore_empty']);
	}

	$args['fields'] = 'count';

	return get_terms($taxonomy, $args);
}

/**
 * Will unlink the object from the taxonomy or taxonomies.
 *
 * Will remove all relationships between the object and any terms in
 * a particular taxonomy or taxonomies. Does not remove the term or
 * taxonomy itself.
 *
 * @since 2.3.0
 * @uses wp_remove_object_terms()
 *
 * @param int $object_id The term Object Id that refers to the term
 * @param string|array $taxonomies List of Taxonomy Names or single Taxonomy name.
 */
function wp_delete_object_term_relationships( $object_id, $taxonomies ) {
	$object_id = (int) $object_id;

	if ( !is_array($taxonomies) )
		$taxonomies = array($taxonomies);

	foreach ( (array) $taxonomies as $taxonomy ) {
		$term_ids = wp_get_object_terms( $object_id, $taxonomy, array( 'fields' => 'ids' ) );
		$term_ids = array_map( 'intval', $term_ids );
		wp_remove_object_terms( $object_id, $term_ids, $taxonomy );
	}
}

/**
 * Removes a term from the database.
 *
 * If the term is a parent of other terms, then the children will be updated to
 * that term's parent.
 *
 * The $args 'default' will only override the terms found, if there is only one
 * term found. Any other and the found terms are used.
 *
 * The $args 'force_default' will force the term supplied as default to be
 * assigned even if the object was not going to be termless
 *
 * @since 2.3.0
 *
 * @uses $wpdb
 *
 * @param int $term Term ID
 * @param string $taxonomy Taxonomy Name
 * @param array|string $args Optional. Change 'default' term id and override found term ids.
 * @return bool|WP_Error Returns false if not term; true if completes delete action.
 */
function wp_delete_term( $term, $taxonomy, $args = array() ) {
	global $wpdb;

	$term = (int) $term;

	if ( ! $ids = term_exists($term, $taxonomy) )
		return false;
	if ( is_wp_error( $ids ) )
		return $ids;

	$tt_id = $ids['term_taxonomy_id'];

	$defaults = array();

	if ( 'category' == $taxonomy ) {
		$defaults['default'] = get_option( 'default_category' );
		if ( $defaults['default'] == $term )
			return 0; // Don't delete the default category
	}

	$args = wp_parse_args($args, $defaults);

	if ( isset( $args['default'] ) ) {
		$default = (int) $args['default'];
		if ( ! term_exists( $default, $taxonomy ) ) {
			unset( $default );
		}
	}

	if ( isset( $args['force_default'] ) ) {
		$force_default = $args['force_default'];
	}

	// Update children to point to new parent
	if ( is_taxonomy_hierarchical($taxonomy) ) {
		$term_obj = get_term($term, $taxonomy);
		if ( is_wp_error( $term_obj ) )
			return $term_obj;
		$parent = $term_obj->parent;

		$edit_tt_ids = $wpdb->get_col( "SELECT `term_taxonomy_id` FROM $wpdb->term_taxonomy WHERE `parent` = " . (int)$term_obj->term_id );

		/**
		 * Fires immediately before a term to delete's children are reassigned a parent.
		 *
		 * @since 2.9.0
		 *
		 * @param array $edit_tt_ids An array of term taxonomy IDs for the given term.
		 */
		do_action( 'edit_term_taxonomies', $edit_tt_ids );
		$wpdb->update( $wpdb->term_taxonomy, compact( 'parent' ), array( 'parent' => $term_obj->term_id) + compact( 'taxonomy' ) );

		/**
		 * Fires immediately after a term to delete's children are reassigned a parent.
		 *
		 * @since 2.9.0
		 *
		 * @param array $edit_tt_ids An array of term taxonomy IDs for the given term.
		 */
		do_action( 'edited_term_taxonomies', $edit_tt_ids );
	}

	$objects = $wpdb->get_col( $wpdb->prepare( "SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $tt_id ) );

	foreach ( (array) $objects as $object ) {
		$terms = wp_get_object_terms($object, $taxonomy, array('fields' => 'ids', 'orderby' => 'none'));
		if ( 1 == count($terms) && isset($default) ) {
			$terms = array($default);
		} else {
			$terms = array_diff($terms, array($term));
			if (isset($default) && isset($force_default) && $force_default)
				$terms = array_merge($terms, array($default));
		}
		$terms = array_map('intval', $terms);
		wp_set_object_terms($object, $terms, $taxonomy);
	}

	// Clean the relationship caches for all object types using this term
	$tax_object = get_taxonomy( $taxonomy );
	foreach ( $tax_object->object_type as $object_type )
		clean_object_term_cache( $objects, $object_type );

	// Get the object before deletion so we can pass to actions below
	$deleted_term = get_term( $term, $taxonomy );

	/**
	 * Fires immediately before a term taxonomy ID is deleted.
	 *
	 * @since 2.9.0
	 *
	 * @param int $tt_id Term taxonomy ID.
	 */
	do_action( 'delete_term_taxonomy', $tt_id );
	$wpdb->delete( $wpdb->term_taxonomy, array( 'term_taxonomy_id' => $tt_id ) );

	/**
	 * Fires immediately after a term taxonomy ID is deleted.
	 *
	 * @since 2.9.0
	 *
	 * @param int $tt_id Term taxonomy ID.
	 */
	do_action( 'deleted_term_taxonomy', $tt_id );

	// Delete the term if no taxonomies use it.
	if ( !$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE term_id = %d", $term) ) )
		$wpdb->delete( $wpdb->terms, array( 'term_id' => $term ) );

	clean_term_cache($term, $taxonomy);

	/**
	 * Fires after a term is deleted from the database and the cache is cleaned.
	 *
	 * @since 2.5.0
	 *
	 * @param int     $term         Term ID.
	 * @param int     $tt_id        Term taxonomy ID.
	 * @param string  $taxonomy     Taxonomy slug.
	 * @param mixed   $deleted_term Copy of the already-deleted term, in the form specified
	 *                              by the parent function. WP_Error otherwise.
	 */
	do_action( 'delete_term', $term, $tt_id, $taxonomy, $deleted_term );

	/**
	 * Fires after a term in a specific taxonomy is deleted.
	 *
	 * The dynamic portion of the hook name, $taxonomy, refers to the specific
	 * taxonomy the term belonged to.
	 *
	 * @since 2.3.0
	 *
	 * @param int     $term         Term ID.
	 * @param int     $tt_id        Term taxonomy ID.
	 * @param mixed   $deleted_term Copy of the already-deleted term, in the form specified
	 *                              by the parent function. WP_Error otherwise.
	 */
	do_action( "delete_$taxonomy", $term, $tt_id, $deleted_term );

	return true;
}

/**
 * Deletes one existing category.
 *
 * @since 2.0.0
 * @uses wp_delete_term()
 *
 * @param int $cat_ID
 * @return mixed Returns true if completes delete action; false if term doesn't exist;
 * 	Zero on attempted deletion of default Category; WP_Error object is also a possibility.
 */
function wp_delete_category( $cat_ID ) {
	return wp_delete_term( $cat_ID, 'category' );
}

/**
 * Retrieves the terms associated with the given object(s), in the supplied taxonomies.
 *
 * The following information has to do the $args parameter and for what can be
 * contained in the string or array of that parameter, if it exists.
 *
 * The first argument is called, 'orderby' and has the default value of 'name'.
 * The other value that is supported is 'count'.
 *
 * The second argument is called, 'order' and has the default value of 'ASC'.
 * The only other value that will be acceptable is 'DESC'.
 *
 * The final argument supported is called, 'fields' and has the default value of
 * 'all'. There are multiple other options that can be used instead. Supported
 * values are as follows: 'all', 'ids', 'names', and finally
 * 'all_with_object_id'.
 *
 * The fields argument also decides what will be returned. If 'all' or
 * 'all_with_object_id' is chosen or the default kept intact, then all matching
 * terms objects will be returned. If either 'ids' or 'names' is used, then an
 * array of all matching term ids or term names will be returned respectively.
 *
 * @since 2.3.0
 * @uses $wpdb
 *
 * @param int|array $object_ids The ID(s) of the object(s) to retrieve.
 * @param string|array $taxonomies The taxonomies to retrieve terms from.
 * @param array|string $args Change what is returned
 * @return array|WP_Error The requested term data or empty array if no terms found. WP_Error if any of the $taxonomies don't exist.
 */
function wp_get_object_terms($object_ids, $taxonomies, $args = array()) {
	global $wpdb;

	if ( empty( $object_ids ) || empty( $taxonomies ) )
		return array();

	if ( !is_array($taxonomies) )
		$taxonomies = array($taxonomies);

	foreach ( $taxonomies as $taxonomy ) {
		if ( ! taxonomy_exists($taxonomy) )
			return new WP_Error('invalid_taxonomy', __('Invalid taxonomy'));
	}

	if ( !is_array($object_ids) )
		$object_ids = array($object_ids);
	$object_ids = array_map('intval', $object_ids);

	$defaults = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
	$args = wp_parse_args( $args, $defaults );

	$terms = array();
	if ( count($taxonomies) > 1 ) {
		foreach ( $taxonomies as $index => $taxonomy ) {
			$t = get_taxonomy($taxonomy);
			if ( isset($t->args) && is_array($t->args) && $args != array_merge($args, $t->args) ) {
				unset($taxonomies[$index]);
				$terms = array_merge($terms, wp_get_object_terms($object_ids, $taxonomy, array_merge($args, $t->args)));
			}
		}
	} else {
		$t = get_taxonomy($taxonomies[0]);
		if ( isset($t->args) && is_array($t->args) )
			$args = array_merge($args, $t->args);
	}

	$orderby = $args['orderby'];
	$order = $args['order'];
	$fields = $args['fields'];

	if ( 'count' == $orderby )
		$orderby = 'tt.count';
	else if ( 'name' == $orderby )
		$orderby = 't.name';
	else if ( 'slug' == $orderby )
		$orderby = 't.slug';
	else if ( 'term_group' == $orderby )
		$orderby = 't.term_group';
	else if ( 'term_order' == $orderby )
		$orderby = 'tr.term_order';
	else if ( 'none' == $orderby ) {
		$orderby = '';
		$order = '';
	} else {
		$orderby = 't.term_id';
	}

	// tt_ids queries can only be none or tr.term_taxonomy_id
	if ( ('tt_ids' == $fields) && !empty($orderby) )
		$orderby = 'tr.term_taxonomy_id';

	if ( !empty($orderby) )
		$orderby = "ORDER BY $orderby";

	$order = strtoupper( $order );
	if ( '' !== $order && ! in_array( $order, array( 'ASC', 'DESC' ) ) )
		$order = 'ASC';

	$taxonomies = "'" . implode("', '", $taxonomies) . "'";
	$object_ids = implode(', ', $object_ids);

	$select_this = '';
	if ( 'all' == $fields )
		$select_this = 't.*, tt.*';
	else if ( 'ids' == $fields )
		$select_this = 't.term_id';
	else if ( 'names' == $fields )
		$select_this = 't.name';
	else if ( 'slugs' == $fields )
		$select_this = 't.slug';
	else if ( 'all_with_object_id' == $fields )
		$select_this = 't.*, tt.*, tr.object_id';

	$query = "SELECT $select_this FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN ($taxonomies) AND tr.object_id IN ($object_ids) $orderby $order";

	$objects = false;
	if ( 'all' == $fields || 'all_with_object_id' == $fields ) {
		$_terms = $wpdb->get_results( $query );
		foreach ( $_terms as $key => $term ) {
			$_terms[$key] = sanitize_term( $term, $taxonomy, 'raw' );
		}
		$terms = array_merge( $terms, $_terms );
		update_term_cache( $terms );
		$objects = true;
	} else if ( 'ids' == $fields || 'names' == $fields || 'slugs' == $fields ) {
		$_terms = $wpdb->get_col( $query );
		$_field = ( 'ids' == $fields ) ? 'term_id' : 'name';
		foreach ( $_terms as $key => $term ) {
			$_terms[$key] = sanitize_term_field( $_field, $term, $term, $taxonomy, 'raw' );
		}
		$terms = array_merge( $terms, $_terms );
	} else if ( 'tt_ids' == $fields ) {
		$terms = $wpdb->get_col("SELECT tr.term_taxonomy_id FROM $wpdb->term_relationships AS tr INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tr.object_id IN ($object_ids) AND tt.taxonomy IN ($taxonomies) $orderby $order");
		foreach ( $terms as $key => $tt_id ) {
			$terms[$key] = sanitize_term_field( 'term_taxonomy_id', $tt_id, 0, $taxonomy, 'raw' ); // 0 should be the term id, however is not needed when using raw context.
		}
	}

	if ( ! $terms ) {
		$terms = array();
	} elseif ( $objects && 'all_with_object_id' !== $fields ) {
		$_tt_ids = array();
		$_terms = array();
		foreach ( $terms as $term ) {
			if ( in_array( $term->term_taxonomy_id, $_tt_ids ) ) {
				continue;
			}

			$_tt_ids[] = $term->term_taxonomy_id;
			$_terms[] = $term;
		}
		$terms = $_terms;
	} elseif ( ! $objects ) {
		$terms = array_values( array_unique( $terms ) );
	}
	/**
	 * Filter the terms for a given object or objects.
	 *
	 * @since 2.8.0
	 *
	 * @param array        $terms      An array of terms for the given object or objects.
	 * @param array|int    $object_ids Object ID or array of IDs.
	 * @param array|string $taxonomies A taxonomy or array of taxonomies.
	 * @param array        $args       An array of arguments for retrieving terms for
	 *                                 the given object(s).
	 */
	return apply_filters( 'wp_get_object_terms', $terms, $object_ids, $taxonomies, $args );
}

/**
 * Add a new term to the database.
 *
 * A non-existent term is inserted in the following sequence:
 * 1. The term is added to the term table, then related to the taxonomy.
 * 2. If everything is correct, several actions are fired.
 * 3. The 'term_id_filter' is evaluated.
 * 4. The term cache is cleaned.
 * 5. Several more actions are fired.
 * 6. An array is returned containing the term_id and term_taxonomy_id.
 *
 * If the 'slug' argument is not empty, then it is checked to see if the term
 * is invalid. If it is not a valid, existing term, it is added and the term_id
 * is given.
 *
 * If the taxonomy is hierarchical, and the 'parent' argument is not empty,
 * the term is inserted and the term_id will be given.

 * Error handling:
 * If $taxonomy does not exist or $term is empty,
 * a WP_Error object will be returned.
 *
 * If the term already exists on the same hierarchical level,
 * or the term slug and name are not unique, a WP_Error object will be returned.
 *
 * @global wpdb $wpdb The WordPress database object.

 * @since 2.3.0
 *
 * @param string       $term     The term to add or update.
 * @param string       $taxonomy The taxonomy to which to add the term
 * @param array|string $args {
 *     Arguments to change values of the inserted term.
 *
 *     @type string 'alias_of'    Slug of the term to make this term an alias of.
 *                                Default empty string. Accepts a term slug.
 *     @type string 'description' The term description.
 *                                Default empty string.
 *     @type int    'parent'      The id of the parent term.
 *                                Default 0.
 *     @type string 'slug'        The term slug to use.
 *                                Default empty string.
 * }
 * @return array|WP_Error An array containing the term_id and term_taxonomy_id, WP_Error otherwise.
 */
function wp_insert_term( $term, $taxonomy, $args = array() ) {
	global $wpdb;

	if ( ! taxonomy_exists($taxonomy) ) {
		return new WP_Error('invalid_taxonomy', __('Invalid taxonomy'));
	}
	/**
	 * Filter a term before it is sanitized and inserted into the database.
	 *
	 * @since 3.0.0
	 *
	 * @param string $term     The term to add or update.
	 * @param string $taxonomy Taxonomy slug.
	 */
	$term = apply_filters( 'pre_insert_term', $term, $taxonomy );
	if ( is_wp_error( $term ) ) {
		return $term;
	}
	if ( is_int($term) && 0 == $term ) {
		return new WP_Error('invalid_term_id', __('Invalid term ID'));
	}
	if ( '' == trim($term) ) {
		return new WP_Error('empty_term_name', __('A name is required for this term'));
	}
	$defaults = array( 'alias_of' => '', 'description' => '', 'parent' => 0, 'slug' => '');
	$args = wp_parse_args( $args, $defaults );

	if ( $args['parent'] > 0 && ! term_exists( (int) $args['parent'] ) ) {
		return new WP_Error( 'missing_parent', __( 'Parent term does not exist.' ) );
	}
	$args['name'] = $term;
	$args['taxonomy'] = $taxonomy;
	$args = sanitize_term($args, $taxonomy, 'db');

	// expected_slashed ($name)
	$name = wp_unslash( $args['name'] );
	$description = wp_unslash( $args['description'] );
	$parent = (int) $args['parent'];

	$slug_provided = ! empty( $args['slug'] );
	if ( ! $slug_provided ) {
		$_name = trim( $name );
		$existing_term = get_term_by( 'name', $_name, $taxonomy );
		if ( $existing_term ) {
			$slug = $existing_term->slug;
		} else {
			$slug = sanitize_title( $name );
		}
	} else {
		$slug = $args['slug'];
	}

	$term_group = 0;
	if ( $args['alias_of'] ) {
		$alias = $wpdb->get_row( $wpdb->prepare( "SELECT term_id, term_group FROM $wpdb->terms WHERE slug = %s", $args['alias_of'] ) );
		if ( $alias->term_group ) {
			// The alias we want is already in a group, so let's use that one.
			$term_group = $alias->term_group;
		} else {
			// The alias isn't in a group, so let's create a new one and firstly add the alias term to it.
			$term_group = $wpdb->get_var("SELECT MAX(term_group) FROM $wpdb->terms") + 1;

			/**
			 * Fires immediately before the given terms are edited.
			 *
			 * @since 2.9.0
			 *
			 * @param int    $term_id  Term ID.
			 * @param string $taxonomy Taxonomy slug.
			 */
			do_action( 'edit_terms', $alias->term_id, $taxonomy );
			$wpdb->update($wpdb->terms, compact('term_group'), array('term_id' => $alias->term_id) );

			/**
			 * Fires immediately after the given terms are edited.
			 *
			 * @since 2.9.0
			 *
			 * @param int    $term_id  Term ID
			 * @param string $taxonomy Taxonomy slug.
			 */
			do_action( 'edited_terms', $alias->term_id, $taxonomy );
		}
	}

	if ( $term_id = term_exists($slug) ) {
		$existing_term = $wpdb->get_row( $wpdb->prepare( "SELECT name FROM $wpdb->terms WHERE term_id = %d", $term_id), ARRAY_A );
		// We've got an existing term in the same taxonomy, which matches the name of the new term:
		if ( is_taxonomy_hierarchical($taxonomy) && $existing_term['name'] == $name && $exists = term_exists( (int) $term_id, $taxonomy ) ) {
			// Hierarchical, and it matches an existing term, Do not allow same "name" in the same level.
			$siblings = get_terms($taxonomy, array('fields' => 'names', 'get' => 'all', 'parent' => $parent ) );
			if ( in_array($name, $siblings) ) {
				if ( $slug_provided ) {
					return new WP_Error( 'term_exists', __( 'A term with the name and slug provided already exists with this parent.' ), $exists['term_id'] );
				} else {
					return new WP_Error( 'term_exists', __( 'A term with the name provided already exists with this parent.' ), $exists['term_id'] );
				}
			} else {
				$slug = wp_unique_term_slug($slug, (object) $args);
				if ( false === $wpdb->insert( $wpdb->terms, compact( 'name', 'slug', 'term_group' ) ) ) {
					return new WP_Error('db_insert_error', __('Could not insert term into the database'), $wpdb->last_error);
				}
				$term_id = (int) $wpdb->insert_id;
			}
		} elseif ( $existing_term['name'] != $name ) {
			// We've got an existing term, with a different name, Create the new term.
			$slug = wp_unique_term_slug($slug, (object) $args);
			if ( false === $wpdb->insert( $wpdb->terms, compact( 'name', 'slug', 'term_group' ) ) ) {
				return new WP_Error('db_insert_error', __('Could not insert term into the database'), $wpdb->last_error);
			}
			$term_id = (int) $wpdb->insert_id;
		} elseif ( $exists = term_exists( (int) $term_id, $taxonomy ) )  {
			// Same name, same slug.
			return new WP_Error( 'term_exists', __( 'A term with the name and slug provided already exists.' ), $exists['term_id'] );
		}
	} else {
		// This term does not exist at all in the database, Create it.
		$slug = wp_unique_term_slug($slug, (object) $args);
		if ( false === $wpdb->insert( $wpdb->terms, compact( 'name', 'slug', 'term_group' ) ) ) {
			return new WP_Error('db_insert_error', __('Could not insert term into the database'), $wpdb->last_error);
		}
		$term_id = (int) $wpdb->insert_id;
	}

	// Seems unreachable, However, Is used in the case that a term name is provided, which sanitizes to an empty string.
	if ( empty($slug) ) {
		$slug = sanitize_title($slug, $term_id);

		/** This action is documented in wp-includes/taxonomy.php */
		do_action( 'edit_terms', $term_id, $taxonomy );
		$wpdb->update( $wpdb->terms, compact( 'slug' ), compact( 'term_id' ) );

		/** This action is documented in wp-includes/taxonomy.php */
		do_action( 'edited_terms', $term_id, $taxonomy );
	}

	$tt_id = $wpdb->get_var( $wpdb->prepare( "SELECT tt.term_taxonomy_id FROM $wpdb->term_taxonomy AS tt INNER JOIN $wpdb->terms AS t ON tt.term_id = t.term_id WHERE tt.taxonomy = %s AND t.term_id = %d", $taxonomy, $term_id ) );

	if ( !empty($tt_id) ) {
		return array('term_id' => $term_id, 'term_taxonomy_id' => $tt_id);
	}
	$wpdb->insert( $wpdb->term_taxonomy, compact( 'term_id', 'taxonomy', 'description', 'parent') + array( 'count' => 0 ) );
	$tt_id = (int) $wpdb->insert_id;

	/**
	 * Fires immediately after a new term is created, before the term cache is cleaned.
	 *
	 * @since 2.3.0
	 *
	 * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	do_action( "create_term", $term_id, $tt_id, $taxonomy );

	/**
	 * Fires after a new term is created for a specific taxonomy.
	 *
	 * The dynamic portion of the hook name, $taxonomy, refers
	 * to the slug of the taxonomy the term was created for.
	 *
	 * @since 2.3.0
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_id   Term taxonomy ID.
	 */
	do_action( "create_$taxonomy", $term_id, $tt_id );

	/**
	 * Filter the term ID after a new term is created.
	 *
	 * @since 2.3.0
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_id   Taxonomy term ID.
	 */
	$term_id = apply_filters( 'term_id_filter', $term_id, $tt_id );

	clean_term_cache($term_id, $taxonomy);

	/**
	 * Fires after a new term is created, and after the term cache has been cleaned.
	 *
	 * @since 2.3.0
	 */
	do_action( "created_term", $term_id, $tt_id, $taxonomy );

	/**
	 * Fires after a new term in a specific taxonomy is created, and after the term
	 * cache has been cleaned.
	 *
	 * @since 2.3.0
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_id   Term taxonomy ID.
	 */
	do_action( "created_$taxonomy", $term_id, $tt_id );

	return array('term_id' => $term_id, 'term_taxonomy_id' => $tt_id);
}

/**
 * Create Term and Taxonomy Relationships.
 *
 * Relates an object (post, link etc) to a term and taxonomy type. Creates the
 * term and taxonomy relationship if it doesn't already exist. Creates a term if
 * it doesn't exist (using the slug).
 *
 * A relationship means that the term is grouped in or belongs to the taxonomy.
 * A term has no meaning until it is given context by defining which taxonomy it
 * exists under.
 *
 * @since 2.3.0
 * @uses wp_remove_object_terms()
 *
 * @param int              $object_id The object to relate to.
 * @param array|int|string $terms     A single term slug, single term id, or array of either term slugs or ids.
 *                                    Will replace all existing related terms in this taxonomy.
 * @param array|string     $taxonomy  The context in which to relate the term to the object.
 * @param bool             $append    Optional. If false will delete difference of terms. Default false.
 * @return array|WP_Error Affected Term IDs.
 */
function wp_set_object_terms( $object_id, $terms, $taxonomy, $append = false ) {
	global $wpdb;

	$object_id = (int) $object_id;

	if ( ! taxonomy_exists($taxonomy) )
		return new WP_Error('invalid_taxonomy', __('Invalid taxonomy'));

	if ( !is_array($terms) )
		$terms = array($terms);

	if ( ! $append )
		$old_tt_ids =  wp_get_object_terms($object_id, $taxonomy, array('fields' => 'tt_ids', 'orderby' => 'none'));
	else
		$old_tt_ids = array();

	$tt_ids = array();
	$term_ids = array();
	$new_tt_ids = array();

	foreach ( (array) $terms as $term) {
		if ( !strlen(trim($term)) )
			continue;

		if ( !$term_info = term_exists($term, $taxonomy) ) {
			// Skip if a non-existent term ID is passed.
			if ( is_int($term) )
				continue;
			$term_info = wp_insert_term($term, $taxonomy);
		}
		if ( is_wp_error($term_info) )
			return $term_info;
		$term_ids[] = $term_info['term_id'];
		$tt_id = $term_info['term_taxonomy_id'];
		$tt_ids[] = $tt_id;

		if ( $wpdb->get_var( $wpdb->prepare( "SELECT term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id = %d AND term_taxonomy_id = %d", $object_id, $tt_id ) ) )
			continue;

		/**
		 * Fires immediately before an object-term relationship is added.
		 *
		 * @since 2.9.0
		 *
		 * @param int $object_id Object ID.
		 * @param int $tt_id     Term taxonomy ID.
		 */
		do_action( 'add_term_relationship', $object_id, $tt_id );
		$wpdb->insert( $wpdb->term_relationships, array( 'object_id' => $object_id, 'term_taxonomy_id' => $tt_id ) );

		/**
		 * Fires immediately after an object-term relationship is added.
		 *
		 * @since 2.9.0
		 *
		 * @param int $object_id Object ID.
		 * @param int $tt_id     Term taxonomy ID.
		 */
		do_action( 'added_term_relationship', $object_id, $tt_id );
		$new_tt_ids[] = $tt_id;
	}

	if ( $new_tt_ids )
		wp_update_term_count( $new_tt_ids, $taxonomy );

	if ( ! $append ) {
		$delete_tt_ids = array_diff( $old_tt_ids, $tt_ids );

		if ( $delete_tt_ids ) {
			$in_delete_tt_ids = "'" . implode( "', '", $delete_tt_ids ) . "'";
			$delete_term_ids = $wpdb->get_col( $wpdb->prepare( "SELECT tt.term_id FROM $wpdb->term_taxonomy AS tt WHERE tt.taxonomy = %s AND tt.term_taxonomy_id IN ($in_delete_tt_ids)", $taxonomy ) );
			$delete_term_ids = array_map( 'intval', $delete_term_ids );

			$remove = wp_remove_object_terms( $object_id, $delete_term_ids, $taxonomy );
			if ( is_wp_error( $remove ) ) {
				return $remove;
			}
		}
	}

	$t = get_taxonomy($taxonomy);
	if ( ! $append && isset($t->sort) && $t->sort ) {
		$values = array();
		$term_order = 0;
		$final_tt_ids = wp_get_object_terms($object_id, $taxonomy, array('fields' => 'tt_ids'));
		foreach ( $tt_ids as $tt_id )
			if ( in_array($tt_id, $final_tt_ids) )
				$values[] = $wpdb->prepare( "(%d, %d, %d)", $object_id, $tt_id, ++$term_order);
		if ( $values )
			if ( false === $wpdb->query( "INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id, term_order) VALUES " . join( ',', $values ) . " ON DUPLICATE KEY UPDATE term_order = VALUES(term_order)" ) )
				return new WP_Error( 'db_insert_error', __( 'Could not insert term relationship into the database' ), $wpdb->last_error );
	}

	wp_cache_delete( $object_id, $taxonomy . '_relationships' );

	/**
	 * Fires after an object's terms have been set.
	 *
	 * @since 2.8.0
	 *
	 * @param int    $object_id  Object ID.
	 * @param array  $terms      An array of object terms.
	 * @param array  $tt_ids     An array of term taxonomy IDs.
	 * @param string $taxonomy   Taxonomy slug.
	 * @param bool   $append     Whether to append new terms to the old terms.
	 * @param array  $old_tt_ids Old array of term taxonomy IDs.
	 */
	do_action( 'set_object_terms', $object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids );
	return $tt_ids;
}

/**
 * Add term(s) associated with a given object.
 *
 * @since 3.6.0
 * @uses wp_set_object_terms()
 *
 * @param int $object_id The ID of the object to which the terms will be added.
 * @param array|int|string $terms The slug(s) or ID(s) of the term(s) to add.
 * @param array|string $taxonomy Taxonomy name.
 * @return array|WP_Error Affected Term IDs
 */
function wp_add_object_terms( $object_id, $terms, $taxonomy ) {
	return wp_set_object_terms( $object_id, $terms, $taxonomy, true );
}

/**
 * Remove term(s) associated with a given object.
 *
 * @since 3.6.0
 * @uses $wpdb
 *
 * @param int $object_id The ID of the object from which the terms will be removed.
 * @param array|int|string $terms The slug(s) or ID(s) of the term(s) to remove.
 * @param array|string $taxonomy Taxonomy name.
 * @return bool|WP_Error True on success, false or WP_Error on failure.
 */
function wp_remove_object_terms( $object_id, $terms, $taxonomy ) {
	global $wpdb;

	$object_id = (int) $object_id;

	if ( ! taxonomy_exists( $taxonomy ) ) {
		return new WP_Error( 'invalid_taxonomy', __( 'Invalid Taxonomy' ) );
	}

	if ( ! is_array( $terms ) ) {
		$terms = array( $terms );
	}

	$tt_ids = array();

	foreach ( (array) $terms as $term ) {
		if ( ! strlen( trim( $term ) ) ) {
			continue;
		}

		if ( ! $term_info = term_exists( $term, $taxonomy ) ) {
			// Skip if a non-existent term ID is passed.
			if ( is_int( $term ) ) {
				continue;
			}
		}

		if ( is_wp_error( $term_info ) ) {
			return $term_info;
		}

		$tt_ids[] = $term_info['term_taxonomy_id'];
	}

	if ( $tt_ids ) {
		$in_tt_ids = "'" . implode( "', '", $tt_ids ) . "'";

		/**
		 * Fires immediately before an object-term relationship is deleted.
		 *
		 * @since 2.9.0
		 *
		 * @param int   $object_id Object ID.
		 * @param array $tt_ids    An array of term taxonomy IDs.
		 */
		do_action( 'delete_term_relationships', $object_id, $tt_ids );
		$deleted = $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->term_relationships WHERE object_id = %d AND term_taxonomy_id IN ($in_tt_ids)", $object_id ) );

		/**
		 * Fires immediately after an object-term relationship is deleted.
		 *
		 * @since 2.9.0
		 *
		 * @param int   $object_id Object ID.
		 * @param array $tt_ids    An array of term taxonomy IDs.
		 */
		do_action( 'deleted_term_relationships', $object_id, $tt_ids );
		wp_update_term_count( $tt_ids, $taxonomy );

		return (bool) $deleted;
	}

	return false;
}

/**
 * Will make slug unique, if it isn't already.
 *
 * The $slug has to be unique global to every taxonomy, meaning that one
 * taxonomy term can't have a matching slug with another taxonomy term. Each
 * slug has to be globally unique for every taxonomy.
 *
 * The way this works is that if the taxonomy that the term belongs to is
 * hierarchical and has a parent, it will append that parent to the $slug.
 *
 * If that still doesn't return an unique slug, then it try to append a number
 * until it finds a number that is truly unique.
 *
 * The only purpose for $term is for appending a parent, if one exists.
 *
 * @since 2.3.0
 * @uses $wpdb
 *
 * @param string $slug The string that will be tried for a unique slug
 * @param object $term The term object that the $slug will belong too
 * @return string Will return a true unique slug.
 */
function wp_unique_term_slug($slug, $term) {
	global $wpdb;

	if ( ! term_exists( $slug ) )
		return $slug;

	// If the taxonomy supports hierarchy and the term has a parent, make the slug unique
	// by incorporating parent slugs.
	if ( is_taxonomy_hierarchical($term->taxonomy) && !empty($term->parent) ) {
		$the_parent = $term->parent;
		while ( ! empty($the_parent) ) {
			$parent_term = get_term($the_parent, $term->taxonomy);
			if ( is_wp_error($parent_term) || empty($parent_term) )
				break;
			$slug .= '-' . $parent_term->slug;
			if ( ! term_exists( $slug ) )
				return $slug;

			if ( empty($parent_term->parent) )
				break;
			$the_parent = $parent_term->parent;
		}
	}

	// If we didn't get a unique slug, try appending a number to make it unique.
	if ( ! empty( $term->term_id ) )
		$query = $wpdb->prepare( "SELECT slug FROM $wpdb->terms WHERE slug = %s AND term_id != %d", $slug, $term->term_id );
	else
		$query = $wpdb->prepare( "SELECT slug FROM $wpdb->terms WHERE slug = %s", $slug );

	if ( $wpdb->get_var( $query ) ) {
		$num = 2;
		do {
			$alt_slug = $slug . "-$num";
			$num++;
			$slug_check = $wpdb->get_var( $wpdb->prepare( "SELECT slug FROM $wpdb->terms WHERE slug = %s", $alt_slug ) );
		} while ( $slug_check );
		$slug = $alt_slug;
	}

	return $slug;
}

/**
 * Update term based on arguments provided.
 *
 * The $args will indiscriminately override all values with the same field name.
 * Care must be taken to not override important information need to update or
 * update will fail (or perhaps create a new term, neither would be acceptable).
 *
 * Defaults will set 'alias_of', 'description', 'parent', and 'slug' if not
 * defined in $args already.
 *
 * 'alias_of' will create a term group, if it doesn't already exist, and update
 * it for the $term.
 *
 * If the 'slug' argument in $args is missing, then the 'name' in $args will be
 * used. It should also be noted that if you set 'slug' and it isn't unique then
 * a WP_Error will be passed back. If you don't pass any slug, then a unique one
 * will be created for you.
 *
 * For what can be overrode in $args, check the term scheme can contain and stay
 * away from the term keys.
 *
 * @since 2.3.0
 *
 * @uses $wpdb
 *
 * @param int $term_id The ID of the term
 * @param string $taxonomy The context in which to relate the term to the object.
 * @param array|string $args Overwrite term field values
 * @return array|WP_Error Returns Term ID and Taxonomy Term ID
 */
function wp_update_term( $term_id, $taxonomy, $args = array() ) {
	global $wpdb;

	if ( ! taxonomy_exists($taxonomy) )
		return new WP_Error('invalid_taxonomy', __('Invalid taxonomy'));

	$term_id = (int) $term_id;

	// First, get all of the original args
	$term = get_term ($term_id, $taxonomy, ARRAY_A);

	if ( is_wp_error( $term ) )
		return $term;

	// Escape data pulled from DB.
	$term = wp_slash($term);

	// Merge old and new args with new args overwriting old ones.
	$args = array_merge($term, $args);

	$defaults = array( 'alias_of' => '', 'description' => '', 'parent' => 0, 'slug' => '');
	$args = wp_parse_args($args, $defaults);
	$args = sanitize_term($args, $taxonomy, 'db');
	$parsed_args = $args;

	// expected_slashed ($name)
	$name = wp_unslash( $args['name'] );
	$description = wp_unslash( $args['description'] );

	$parsed_args['name'] = $name;
	$parsed_args['description'] = $description;

	if ( '' == trim($name) )
		return new WP_Error('empty_term_name', __('A name is required for this term'));

	$empty_slug = false;
	if ( empty( $args['slug'] ) ) {
		$empty_slug = true;
		$slug = sanitize_title($name);
	} else {
		$slug = $args['slug'];
	}

	$parsed_args['slug'] = $slug;

	$term_group = isset( $parsed_args['term_group'] ) ? $parsed_args['term_group'] : 0;
	if ( $args['alias_of'] ) {
		$alias = $wpdb->get_row( $wpdb->prepare( "SELECT term_id, term_group FROM $wpdb->terms WHERE slug = %s", $args['alias_of'] ) );
		if ( $alias->term_group ) {
			// The alias we want is already in a group, so let's use that one.
			$term_group = $alias->term_group;
		} else {
			// The alias isn't in a group, so let's create a new one and firstly add the alias term to it.
			$term_group = $wpdb->get_var("SELECT MAX(term_group) FROM $wpdb->terms") + 1;

			/** This action is documented in wp-includes/taxonomy.php */
			do_action( 'edit_terms', $alias->term_id, $taxonomy );
			$wpdb->update( $wpdb->terms, compact('term_group'), array( 'term_id' => $alias->term_id ) );

			/** This action is documented in wp-includes/taxonomy.php */
			do_action( 'edited_terms', $alias->term_id, $taxonomy );
		}

		$parsed_args['term_group'] = $term_group;
	}

	/**
	 * Filter the term parent.
	 *
	 * Hook to this filter to see if it will cause a hierarchy loop.
	 *
	 * @since 3.1.0
	 *
	 * @param int    $parent      ID of the parent term.
	 * @param int    $term_id     Term ID.
	 * @param string $taxonomy    Taxonomy slug.
	 * @param array  $parsed_args An array of potentially altered update arguments for the given term.
	 * @param array  $args        An array of update arguments for the given term.
	 */
	$parent = apply_filters( 'wp_update_term_parent', $args['parent'], $term_id, $taxonomy, $parsed_args, $args );

	// Check for duplicate slug
	$id = $wpdb->get_var( $wpdb->prepare( "SELECT term_id FROM $wpdb->terms WHERE slug = %s", $slug ) );
	if ( $id && ($id != $term_id) ) {
		// If an empty slug was passed or the parent changed, reset the slug to something unique.
		// Otherwise, bail.
		if ( $empty_slug || ( $parent != $term['parent']) )
			$slug = wp_unique_term_slug($slug, (object) $args);
		else
			return new WP_Error('duplicate_term_slug', sprintf(__('The slug &#8220;%s&#8221; is already in use by another term'), $slug));
	}

	/** This action is documented in wp-includes/taxonomy.php */
	do_action( 'edit_terms', $term_id, $taxonomy );
	$wpdb->update($wpdb->terms, compact( 'name', 'slug', 'term_group' ), compact( 'term_id' ) );
	if ( empty($slug) ) {
		$slug = sanitize_title($name, $term_id);
		$wpdb->update( $wpdb->terms, compact( 'slug' ), compact( 'term_id' ) );
	}

	/** This action is documented in wp-includes/taxonomy.php */
	do_action( 'edited_terms', $term_id, $taxonomy );

	$tt_id = $wpdb->get_var( $wpdb->prepare( "SELECT tt.term_taxonomy_id FROM $wpdb->term_taxonomy AS tt INNER JOIN $wpdb->terms AS t ON tt.term_id = t.term_id WHERE tt.taxonomy = %s AND t.term_id = %d", $taxonomy, $term_id) );

	/**
	 * Fires immediate before a term-taxonomy relationship is updated.
	 *
	 * @since 2.9.0
	 *
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	do_action( 'edit_term_taxonomy', $tt_id, $taxonomy );
	$wpdb->update( $wpdb->term_taxonomy, compact( 'term_id', 'taxonomy', 'description', 'parent' ), array( 'term_taxonomy_id' => $tt_id ) );

	/**
	 * Fires immediately after a term-taxonomy relationship is updated.
	 *
	 * @since 2.9.0
	 *
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	do_action( 'edited_term_taxonomy', $tt_id, $taxonomy );

	// Clean the relationship caches for all object types using this term
	$objects = $wpdb->get_col( $wpdb->prepare( "SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $tt_id ) );
	$tax_object = get_taxonomy( $taxonomy );
	foreach ( $tax_object->object_type as $object_type ) {
		clean_object_term_cache( $objects, $object_type );
	}

	/**
	 * Fires after a term has been updated, but before the term cache has been cleaned.
	 *
	 * @since 2.3.0
	 *
	 * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	do_action( "edit_term", $term_id, $tt_id, $taxonomy );

	/**
	 * Fires after a term in a specific taxonomy has been updated, but before the term
	 * cache has been cleaned.
	 *
	 * The dynamic portion of the hook name, $taxonomy, refers to the taxonomy slug.
	 *
	 * @since 2.3.0
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_id   Term taxonomy ID.
	 */
	do_action( "edit_$taxonomy", $term_id, $tt_id );

	/** This filter is documented in wp-includes/taxonomy.php */
	$term_id = apply_filters( 'term_id_filter', $term_id, $tt_id );

	clean_term_cache($term_id, $taxonomy);

	/**
	 * Fires after a term has been updated, and the term cache has been cleaned.
	 *
	 * @since 2.3.0
	 *
	 * @param int    $term_id  Term ID.
	 * @param int    $tt_id    Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	do_action( "edited_term", $term_id, $tt_id, $taxonomy );

	/**
	 * Fires after a term for a specific taxonomy has been updated, and the term
	 * cache has been cleaned.
	 *
	 * The dynamic portion of the hook name, $taxonomy, refers to the taxonomy slug.
	 *
	 * @since 2.3.0
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_id   Term taxonomy ID.
	 */
	do_action( "edited_$taxonomy", $term_id, $tt_id );

	return array('term_id' => $term_id, 'term_taxonomy_id' => $tt_id);
}

/**
 * Enable or disable term counting.
 *
 * @since 2.5.0
 *
 * @param bool $defer Optional. Enable if true, disable if false.
 * @return bool Whether term counting is enabled or disabled.
 */
function wp_defer_term_counting($defer=null) {
	static $_defer = false;

	if ( is_bool($defer) ) {
		$_defer = $defer;
		// flush any deferred counts
		if ( !$defer )
			wp_update_term_count( null, null, true );
	}

	return $_defer;
}

/**
 * Updates the amount of terms in taxonomy.
 *
 * If there is a taxonomy callback applied, then it will be called for updating
 * the count.
 *
 * The default action is to count what the amount of terms have the relationship
 * of term ID. Once that is done, then update the database.
 *
 * @since 2.3.0
 * @uses $wpdb
 *
 * @param int|array $terms The term_taxonomy_id of the terms
 * @param string $taxonomy The context of the term.
 * @return bool If no terms will return false, and if successful will return true.
 */
function wp_update_term_count( $terms, $taxonomy, $do_deferred=false ) {
	static $_deferred = array();

	if ( $do_deferred ) {
		foreach ( (array) array_keys($_deferred) as $tax ) {
			wp_update_term_count_now( $_deferred[$tax], $tax );
			unset( $_deferred[$tax] );
		}
	}

	if ( empty($terms) )
		return false;

	if ( !is_array($terms) )
		$terms = array($terms);

	if ( wp_defer_term_counting() ) {
		if ( !isset($_deferred[$taxonomy]) )
			$_deferred[$taxonomy] = array();
		$_deferred[$taxonomy] = array_unique( array_merge($_deferred[$taxonomy], $terms) );
		return true;
	}

	return wp_update_term_count_now( $terms, $taxonomy );
}

/**
 * Perform term count update immediately.
 *
 * @since 2.5.0
 *
 * @param array $terms The term_taxonomy_id of terms to update.
 * @param string $taxonomy The context of the term.
 * @return bool Always true when complete.
 */
function wp_update_term_count_now( $terms, $taxonomy ) {
	$terms = array_map('intval', $terms);

	$taxonomy = get_taxonomy($taxonomy);
	if ( !empty($taxonomy->update_count_callback) ) {
		call_user_func($taxonomy->update_count_callback, $terms, $taxonomy);
	} else {
		$object_types = (array) $taxonomy->object_type;
		foreach ( $object_types as &$object_type ) {
			if ( 0 === strpos( $object_type, 'attachment:' ) )
				list( $object_type ) = explode( ':', $object_type );
		}

		if ( $object_types == array_filter( $object_types, 'post_type_exists' ) ) {
			// Only post types are attached to this taxonomy
			_update_post_term_count( $terms, $taxonomy );
		} else {
			// Default count updater
			_update_generic_term_count( $terms, $taxonomy );
		}
	}

	clean_term_cache($terms, '', false);

	return true;
}

//
// Cache
//

/**
 * Removes the taxonomy relationship to terms from the cache.
 *
 * Will remove the entire taxonomy relationship containing term $object_id. The
 * term IDs have to exist within the taxonomy $object_type for the deletion to
 * take place.
 *
 * @since 2.3.0
 *
 * @see get_object_taxonomies() for more on $object_type
 *
 * @param int|array $object_ids Single or list of term object ID(s)
 * @param array|string $object_type The taxonomy object type
 */
function clean_object_term_cache($object_ids, $object_type) {
	if ( !is_array($object_ids) )
		$object_ids = array($object_ids);

	$taxonomies = get_object_taxonomies( $object_type );

	foreach ( $object_ids as $id ) {
		foreach ( $taxonomies as $taxonomy ) {
			wp_cache_delete($id, "{$taxonomy}_relationships");
		}
	}

	/**
	 * Fires after the object term cache has been cleaned.
	 *
	 * @since 2.5.0
	 *
	 * @param array  $object_ids An array of object IDs.
	 * @param string $objet_type Object type.
	 */
	do_action( 'clean_object_term_cache', $object_ids, $object_type );
}

/**
 * Will remove all of the term ids from the cache.
 *
 * @since 2.3.0
 * @uses $wpdb
 *
 * @param int|array $ids Single or list of Term IDs
 * @param string $taxonomy Can be empty and will assume tt_ids, else will use for context.
 * @param bool $clean_taxonomy Whether to clean taxonomy wide caches (true), or just individual term object caches (false). Default is true.
 */
function clean_term_cache($ids, $taxonomy = '', $clean_taxonomy = true) {
	global $wpdb;

	if ( !is_array($ids) )
		$ids = array($ids);

	$taxonomies = array();
	// If no taxonomy, assume tt_ids.
	if ( empty($taxonomy) ) {
		$tt_ids = array_map('intval', $ids);
		$tt_ids = implode(', ', $tt_ids);
		$terms = $wpdb->get_results("SELECT term_id, taxonomy FROM $wpdb->term_taxonomy WHERE term_taxonomy_id IN ($tt_ids)");
		$ids = array();
		foreach ( (array) $terms as $term ) {
			$taxonomies[] = $term->taxonomy;
			$ids[] = $term->term_id;
			wp_cache_delete($term->term_id, $term->taxonomy);
		}
		$taxonomies = array_unique($taxonomies);
	} else {
		$taxonomies = array($taxonomy);
		foreach ( $taxonomies as $taxonomy ) {
			foreach ( $ids as $id ) {
				wp_cache_delete($id, $taxonomy);
			}
		}
	}

	foreach ( $taxonomies as $taxonomy ) {
		if ( $clean_taxonomy ) {
			wp_cache_delete('all_ids', $taxonomy);
			wp_cache_delete('get', $taxonomy);
			delete_option("{$taxonomy}_children");
			// Regenerate {$taxonomy}_children
			_get_term_hierarchy($taxonomy);
		}

		/**
		 * Fires once after each taxonomy's term cache has been cleaned.
		 *
		 * @since 2.5.0
		 *
		 * @param array  $ids      An array of term IDs.
		 * @param string $taxonomy Taxonomy slug.
		 */
		do_action( 'clean_term_cache', $ids, $taxonomy );
	}

	wp_cache_set( 'last_changed', microtime(), 'terms' );
}

/**
 * Retrieves the taxonomy relationship to the term object id.
 *
 * @since 2.3.0
 *
 * @uses wp_cache_get() Retrieves taxonomy relationship from cache
 *
 * @param int|array $id Term object ID
 * @param string $taxonomy Taxonomy Name
 * @return bool|array Empty array if $terms found, but not $taxonomy. False if nothing is in cache for $taxonomy and $id.
 */
function get_object_term_cache($id, $taxonomy) {
	$cache = wp_cache_get($id, "{$taxonomy}_relationships");
	return $cache;
}

/**
 * Updates the cache for Term ID(s).
 *
 * Will only update the cache for terms not already cached.
 *
 * The $object_ids expects that the ids be separated by commas, if it is a
 * string.
 *
 * It should be noted that update_object_term_cache() is very time extensive. It
 * is advised that the function is not called very often or at least not for a
 * lot of terms that exist in a lot of taxonomies. The amount of time increases
 * for each term and it also increases for each taxonomy the term belongs to.
 *
 * @since 2.3.0
 * @uses wp_get_object_terms() Used to get terms from the database to update
 *
 * @param string|array $object_ids Single or list of term object ID(s)
 * @param array|string $object_type The taxonomy object type
 * @return null|bool Null value is given with empty $object_ids. False if
 */
function update_object_term_cache($object_ids, $object_type) {
	if ( empty($object_ids) )
		return;

	if ( !is_array($object_ids) )
		$object_ids = explode(',', $object_ids);

	$object_ids = array_map('intval', $object_ids);

	$taxonomies = get_object_taxonomies($object_type);

	$ids = array();
	foreach ( (array) $object_ids as $id ) {
		foreach ( $taxonomies as $taxonomy ) {
			if ( false === wp_cache_get($id, "{$taxonomy}_relationships") ) {
				$ids[] = $id;
				break;
			}
		}
	}

	if ( empty( $ids ) )
		return false;

	$terms = wp_get_object_terms($ids, $taxonomies, array('fields' => 'all_with_object_id'));

	$object_terms = array();
	foreach ( (array) $terms as $term )
		$object_terms[$term->object_id][$term->taxonomy][$term->term_id] = $term;

	foreach ( $ids as $id ) {
		foreach ( $taxonomies as $taxonomy ) {
			if ( ! isset($object_terms[$id][$taxonomy]) ) {
				if ( !isset($object_terms[$id]) )
					$object_terms[$id] = array();
				$object_terms[$id][$taxonomy] = array();
			}
		}
	}

	foreach ( $object_terms as $id => $value ) {
		foreach ( $value as $taxonomy => $terms ) {
			wp_cache_add( $id, $terms, "{$taxonomy}_relationships" );
		}
	}
}

/**
 * Updates Terms to Taxonomy in cache.
 *
 * @since 2.3.0
 *
 * @param array $terms List of Term objects to change
 * @param string $taxonomy Optional. Update Term to this taxonomy in cache
 */
function update_term_cache($terms, $taxonomy = '') {
	foreach ( (array) $terms as $term ) {
		$term_taxonomy = $taxonomy;
		if ( empty($term_taxonomy) )
			$term_taxonomy = $term->taxonomy;

		wp_cache_add($term->term_id, $term, $term_taxonomy);
	}
}

//
// Private
//

/**
 * Retrieves children of taxonomy as Term IDs.
 *
 * @access private
 * @since 2.3.0
 *
 * @uses update_option() Stores all of the children in "$taxonomy_children"
 *	 option. That is the name of the taxonomy, immediately followed by '_children'.
 *
 * @param string $taxonomy Taxonomy Name
 * @return array Empty if $taxonomy isn't hierarchical or returns children as Term IDs.
 */
function _get_term_hierarchy($taxonomy) {
	if ( !is_taxonomy_hierarchical($taxonomy) )
		return array();
	$children = get_option("{$taxonomy}_children");

	if ( is_array($children) )
		return $children;
	$children = array();
	$terms = get_terms($taxonomy, array('get' => 'all', 'orderby' => 'id', 'fields' => 'id=>parent'));
	foreach ( $terms as $term_id => $parent ) {
		if ( $parent > 0 )
			$children[$parent][] = $term_id;
	}
	update_option("{$taxonomy}_children", $children);

	return $children;
}

/**
 * Get the subset of $terms that are descendants of $term_id.
 *
 * If $terms is an array of objects, then _get_term_children returns an array of objects.
 * If $terms is an array of IDs, then _get_term_children returns an array of IDs.
 *
 * @access private
 * @since 2.3.0
 *
 * @param int $term_id The ancestor term: all returned terms should be descendants of $term_id.
 * @param array $terms The set of terms---either an array of term objects or term IDs---from which those that are descendants of $term_id will be chosen.
 * @param string $taxonomy The taxonomy which determines the hierarchy of the terms.
 * @return array The subset of $terms that are descendants of $term_id.
 */
function _get_term_children($term_id, $terms, $taxonomy) {
	$empty_array = array();
	if ( empty($terms) )
		return $empty_array;

	$term_list = array();
	$has_children = _get_term_hierarchy($taxonomy);

	if  ( ( 0 != $term_id ) && ! isset($has_children[$term_id]) )
		return $empty_array;

	foreach ( (array) $terms as $term ) {
		$use_id = false;
		if ( !is_object($term) ) {
			$term = get_term($term, $taxonomy);
			if ( is_wp_error( $term ) )
				return $term;
			$use_id = true;
		}

		if ( $term->term_id == $term_id ) {
			continue;
		}

		if ( $term->parent == $term_id ) {
			if ( $use_id )
				$term_list[] = $term->term_id;
			else
				$term_list[] = $term;

			if ( !isset($has_children[$term->term_id]) )
				continue;

			if ( $children = _get_term_children($term->term_id, $terms, $taxonomy) )
				$term_list = array_merge($term_list, $children);
		}
	}

	return $term_list;
}

/**
 * Add count of children to parent count.
 *
 * Recalculates term counts by including items from child terms. Assumes all
 * relevant children are already in the $terms argument.
 *
 * @access private
 * @since 2.3.0
 * @uses $wpdb
 *
 * @param array $terms List of Term IDs
 * @param string $taxonomy Term Context
 * @return null Will break from function if conditions are not met.
 */
function _pad_term_counts(&$terms, $taxonomy) {
	global $wpdb;

	// This function only works for hierarchical taxonomies like post categories.
	if ( !is_taxonomy_hierarchical( $taxonomy ) )
		return;

	$term_hier = _get_term_hierarchy($taxonomy);

	if ( empty($term_hier) )
		return;

	$term_items = array();

	foreach ( (array) $terms as $key => $term ) {
		$terms_by_id[$term->term_id] = & $terms[$key];
		$term_ids[$term->term_taxonomy_id] = $term->term_id;
	}

	// Get the object and term ids and stick them in a lookup table
	$tax_obj = get_taxonomy($taxonomy);
	$object_types = esc_sql($tax_obj->object_type);
	$results = $wpdb->get_results("SELECT object_id, term_taxonomy_id FROM $wpdb->term_relationships INNER JOIN $wpdb->posts ON object_id = ID WHERE term_taxonomy_id IN (" . implode(',', array_keys($term_ids)) . ") AND post_type IN ('" . implode("', '", $object_types) . "') AND post_status = 'publish'");
	foreach ( $results as $row ) {
		$id = $term_ids[$row->term_taxonomy_id];
		$term_items[$id][$row->object_id] = isset($term_items[$id][$row->object_id]) ? ++$term_items[$id][$row->object_id] : 1;
	}

	// Touch every ancestor's lookup row for each post in each term
	foreach ( $term_ids as $term_id ) {
		$child = $term_id;
		while ( !empty( $terms_by_id[$child] ) && $parent = $terms_by_id[$child]->parent ) {
			if ( !empty( $term_items[$term_id] ) )
				foreach ( $term_items[$term_id] as $item_id => $touches ) {
					$term_items[$parent][$item_id] = isset($term_items[$parent][$item_id]) ? ++$term_items[$parent][$item_id]: 1;
				}
			$child = $parent;
		}
	}

	// Transfer the touched cells
	foreach ( (array) $term_items as $id => $items )
		if ( isset($terms_by_id[$id]) )
			$terms_by_id[$id]->count = count($items);
}

//
// Default callbacks
//

/**
 * Will update term count based on object types of the current taxonomy.
 *
 * Private function for the default callback for post_tag and category
 * taxonomies.
 *
 * @access private
 * @since 2.3.0
 * @uses $wpdb
 *
 * @param array $terms List of Term taxonomy IDs
 * @param object $taxonomy Current taxonomy object of terms
 */
function _update_post_term_count( $terms, $taxonomy ) {
	global $wpdb;

	$object_types = (array) $taxonomy->object_type;

	foreach ( $object_types as &$object_type )
		list( $object_type ) = explode( ':', $object_type );

	$object_types = array_unique( $object_types );

	if ( false !== ( $check_attachments = array_search( 'attachment', $object_types ) ) ) {
		unset( $object_types[ $check_attachments ] );
		$check_attachments = true;
	}

	if ( $object_types )
		$object_types = esc_sql( array_filter( $object_types, 'post_type_exists' ) );

	foreach ( (array) $terms as $term ) {
		$count = 0;

		// Attachments can be 'inherit' status, we need to base count off the parent's status if so
		if ( $check_attachments )
			$count += (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts p1 WHERE p1.ID = $wpdb->term_relationships.object_id AND ( post_status = 'publish' OR ( post_status = 'inherit' AND post_parent > 0 AND ( SELECT post_status FROM $wpdb->posts WHERE ID = p1.post_parent ) = 'publish' ) ) AND post_type = 'attachment' AND term_taxonomy_id = %d", $term ) );

		if ( $object_types )
			$count += (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts WHERE $wpdb->posts.ID = $wpdb->term_relationships.object_id AND post_status = 'publish' AND post_type IN ('" . implode("', '", $object_types ) . "') AND term_taxonomy_id = %d", $term ) );

		/** This action is documented in wp-includes/taxonomy.php */
		do_action( 'edit_term_taxonomy', $term, $taxonomy );
		$wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );

		/** This action is documented in wp-includes/taxonomy.php */
		do_action( 'edited_term_taxonomy', $term, $taxonomy );
	}
}

/**
 * Will update term count based on number of objects.
 *
 * Default callback for the link_category taxonomy.
 *
 * @since 3.3.0
 * @uses $wpdb
 *
 * @param array $terms List of Term taxonomy IDs
 * @param object $taxonomy Current taxonomy object of terms
 */
function _update_generic_term_count( $terms, $taxonomy ) {
	global $wpdb;

	foreach ( (array) $terms as $term ) {
		$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term ) );

		/** This action is documented in wp-includes/taxonomy.php */
		do_action( 'edit_term_taxonomy', $term, $taxonomy );
		$wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );

		/** This action is documented in wp-includes/taxonomy.php */
		do_action( 'edited_term_taxonomy', $term, $taxonomy );
	}
}

/**
 * Generates a permalink for a taxonomy term archive.
 *
 * @since 2.5.0
 *
 * @param object|int|string $term
 * @param string $taxonomy (optional if $term is object)
 * @return string|WP_Error HTML link to taxonomy term archive on success, WP_Error if term does not exist.
 */
function get_term_link( $term, $taxonomy = '') {
	global $wp_rewrite;

	if ( !is_object($term) ) {
		if ( is_int($term) ) {
			$term = get_term($term, $taxonomy);
		} else {
			$term = get_term_by('slug', $term, $taxonomy);
		}
	}

	if ( !is_object($term) )
		$term = new WP_Error('invalid_term', __('Empty Term'));

	if ( is_wp_error( $term ) )
		return $term;

	$taxonomy = $term->taxonomy;

	$termlink = $wp_rewrite->get_extra_permastruct($taxonomy);

	$slug = $term->slug;
	$t = get_taxonomy($taxonomy);

	if ( empty($termlink) ) {
		if ( 'category' == $taxonomy )
			$termlink = '?cat=' . $term->term_id;
		elseif ( $t->query_var )
			$termlink = "?$t->query_var=$slug";
		else
			$termlink = "?taxonomy=$taxonomy&term=$slug";
		$termlink = home_url($termlink);
	} else {
		if ( $t->rewrite['hierarchical'] ) {
			$hierarchical_slugs = array();
			$ancestors = get_ancestors($term->term_id, $taxonomy);
			foreach ( (array)$ancestors as $ancestor ) {
				$ancestor_term = get_term($ancestor, $taxonomy);
				$hierarchical_slugs[] = $ancestor_term->slug;
			}
			$hierarchical_slugs = array_reverse($hierarchical_slugs);
			$hierarchical_slugs[] = $slug;
			$termlink = str_replace("%$taxonomy%", implode('/', $hierarchical_slugs), $termlink);
		} else {
			$termlink = str_replace("%$taxonomy%", $slug, $termlink);
		}
		$termlink = home_url( user_trailingslashit($termlink, 'category') );
	}
	// Back Compat filters.
	if ( 'post_tag' == $taxonomy ) {

		/**
		 * Filter the tag link.
		 *
		 * @since 2.3.0
		 * @deprecated 2.5.0 Use 'term_link' instead.
		 *
		 * @param string $termlink Tag link URL.
		 * @param int    $term_id  Term ID.
		 */
		$termlink = apply_filters( 'tag_link', $termlink, $term->term_id );
	} elseif ( 'category' == $taxonomy ) {

		/**
		 * Filter the category link.
		 *
		 * @since 1.5.0
		 * @deprecated 2.5.0 Use 'term_link' instead.
		 *
		 * @param string $termlink Category link URL.
		 * @param int    $term_id  Term ID.
		 */
		$termlink = apply_filters( 'category_link', $termlink, $term->term_id );
	}

	/**
	 * Filter the term link.
	 *
	 * @since 2.5.0
	 *
	 * @param string $termlink Term link URL.
	 * @param object $term     Term object.
	 * @param string $taxonomy Taxonomy slug.
	 */
	return apply_filters( 'term_link', $termlink, $term, $taxonomy );
}

/**
 * Display the taxonomies of a post with available options.
 *
 * This function can be used within the loop to display the taxonomies for a
 * post without specifying the Post ID. You can also use it outside the Loop to
 * display the taxonomies for a specific post.
 *
 * The available defaults are:
 * 'post' : default is 0. The post ID to get taxonomies of.
 * 'before' : default is empty string. Display before taxonomies list.
 * 'sep' : default is empty string. Separate every taxonomy with value in this.
 * 'after' : default is empty string. Display this after the taxonomies list.
 * 'template' : The template to use for displaying the taxonomy terms.
 *
 * @since 2.5.0
 * @uses get_the_taxonomies()
 *
 * @param array $args Override the defaults.
 */
function the_taxonomies( $args = array() ) {
	$defaults = array(
		'post' => 0,
		'before' => '',
		'sep' => ' ',
		'after' => '',
		/* translators: %s: taxonomy label, %l: list of term links */
		'template' => __( '%s: %l.' )
	);

	$r = wp_parse_args( $args, $defaults );

	echo $r['before'] . join( $r['sep'], get_the_taxonomies( $r['post'], $r ) ) . $r['after'];
}

/**
 * Retrieve all taxonomies associated with a post.
 *
 * This function can be used within the loop. It will also return an array of
 * the taxonomies with links to the taxonomy and name.
 *
 * @since 2.5.0
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @param array $args Override the defaults.
 * @return array List of taxonomies.
 */
function get_the_taxonomies( $post = 0, $args = array() ) {
	$post = get_post( $post );

	$args = wp_parse_args( $args, array(
		/* translators: %s: taxonomy label, %l: list of term links */
		'template' => __( '%s: %l.' ),
	) );

	$taxonomies = array();

	if ( ! $post ) {
		return $taxonomies;
	}

	foreach ( get_object_taxonomies( $post ) as $taxonomy ) {
		$t = (array) get_taxonomy( $taxonomy );
		if ( empty( $t['label'] ) ) {
			$t['label'] = $taxonomy;
		}
		if ( empty( $t['args'] ) ) {
			$t['args'] = array();
		}
		if ( empty( $t['template'] ) ) {
			$t['template'] = $args['template'];
		}

		$terms = get_object_term_cache( $post->ID, $taxonomy );
		if ( false === $terms ) {
			$terms = wp_get_object_terms( $post->ID, $taxonomy, $t['args'] );
		}
		$links = array();

		foreach ( $terms as $term ) {
			$links[] = "<a href='" . esc_attr( get_term_link( $term ) ) . "'>$term->name</a>";
		}
		if ( $links ) {
			$taxonomies[$taxonomy] = wp_sprintf( $t['template'], $t['label'], $links, $terms );
		}
	}
	return $taxonomies;
}

/**
 * Retrieve all taxonomies of a post with just the names.
 *
 * @since 2.5.0
 *
 * @uses get_object_taxonomies()
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
 * @return array
 */
function get_post_taxonomies( $post = 0 ) {
	$post = get_post( $post );

	return get_object_taxonomies($post);
}

/**
 * Determine if the given object is associated with any of the given terms.
 *
 * The given terms are checked against the object's terms' term_ids, names and slugs.
 * Terms given as integers will only be checked against the object's terms' term_ids.
 * If no terms are given, determines if object is associated with any terms in the given taxonomy.
 *
 * @since 2.7.0
 * @uses get_object_term_cache()
 * @uses wp_get_object_terms()
 *
 * @param int $object_id ID of the object (post ID, link ID, ...)
 * @param string $taxonomy Single taxonomy name
 * @param int|string|array $terms Optional. Term term_id, name, slug or array of said
 * @return bool|WP_Error. WP_Error on input error.
 */
function is_object_in_term( $object_id, $taxonomy, $terms = null ) {
	if ( !$object_id = (int) $object_id )
		return new WP_Error( 'invalid_object', __( 'Invalid object ID' ) );

	$object_terms = get_object_term_cache( $object_id, $taxonomy );
	if ( false === $object_terms )
		 $object_terms = wp_get_object_terms( $object_id, $taxonomy );

	if ( is_wp_error( $object_terms ) )
		return $object_terms;
	if ( empty( $object_terms ) )
		return false;
	if ( empty( $terms ) )
		return ( !empty( $object_terms ) );

	$terms = (array) $terms;

	if ( $ints = array_filter( $terms, 'is_int' ) )
		$strs = array_diff( $terms, $ints );
	else
		$strs =& $terms;

	foreach ( $object_terms as $object_term ) {
		if ( $ints && in_array( $object_term->term_id, $ints ) ) return true; // If int, check against term_id
		if ( $strs ) {
			if ( in_array( $object_term->term_id, $strs ) ) return true;
			if ( in_array( $object_term->name, $strs ) )    return true;
			if ( in_array( $object_term->slug, $strs ) )    return true;
		}
	}

	return false;
}

/**
 * Determine if the given object type is associated with the given taxonomy.
 *
 * @since 3.0.0
 * @uses get_object_taxonomies()
 *
 * @param string $object_type Object type string
 * @param string $taxonomy Single taxonomy name
 * @return bool True if object is associated with the taxonomy, otherwise false.
 */
function is_object_in_taxonomy($object_type, $taxonomy) {
	$taxonomies = get_object_taxonomies($object_type);

	if ( empty($taxonomies) )
		return false;

	if ( in_array($taxonomy, $taxonomies) )
		return true;

	return false;
}

/**
 * Get an array of ancestor IDs for a given object.
 *
 * @param int $object_id The ID of the object
 * @param string $object_type The type of object for which we'll be retrieving ancestors.
 * @return array of ancestors from lowest to highest in the hierarchy.
 */
function get_ancestors($object_id = 0, $object_type = '') {
	$object_id = (int) $object_id;

	$ancestors = array();

	if ( empty( $object_id ) ) {

		/** This filter is documented in wp-includes/taxonomy.php */
		return apply_filters( 'get_ancestors', $ancestors, $object_id, $object_type );
	}

	if ( is_taxonomy_hierarchical( $object_type ) ) {
		$term = get_term($object_id, $object_type);
		while ( ! is_wp_error($term) && ! empty( $term->parent ) && ! in_array( $term->parent, $ancestors ) ) {
			$ancestors[] = (int) $term->parent;
			$term = get_term($term->parent, $object_type);
		}
	} elseif ( post_type_exists( $object_type ) ) {
		$ancestors = get_post_ancestors($object_id);
	}

	/**
	 * Filter a given object's ancestors.
	 *
	 * @since 3.1.0
	 *
	 * @param array  $ancestors   An array of object ancestors.
	 * @param int    $object_id   Object ID.
	 * @param string $object_type Type of object.
	 */
	return apply_filters( 'get_ancestors', $ancestors, $object_id, $object_type );
}

/**
 * Returns the term's parent's term_ID
 *
 * @since 3.1.0
 *
 * @param int $term_id
 * @param string $taxonomy
 *
 * @return int|bool false on error
 */
function wp_get_term_taxonomy_parent_id( $term_id, $taxonomy ) {
	$term = get_term( $term_id, $taxonomy );
	if ( !$term || is_wp_error( $term ) )
		return false;
	return (int) $term->parent;
}

/**
 * Checks the given subset of the term hierarchy for hierarchy loops.
 * Prevents loops from forming and breaks those that it finds.
 *
 * Attached to the wp_update_term_parent filter.
 *
 * @since 3.1.0
 * @uses wp_find_hierarchy_loop()
 *
 * @param int $parent term_id of the parent for the term we're checking.
 * @param int $term_id The term we're checking.
 * @param string $taxonomy The taxonomy of the term we're checking.
 *
 * @return int The new parent for the term.
 */
function wp_check_term_hierarchy_for_loops( $parent, $term_id, $taxonomy ) {
	// Nothing fancy here - bail
	if ( !$parent )
		return 0;

	// Can't be its own parent
	if ( $parent == $term_id )
		return 0;

	// Now look for larger loops

	if ( !$loop = wp_find_hierarchy_loop( 'wp_get_term_taxonomy_parent_id', $term_id, $parent, array( $taxonomy ) ) )
		return $parent; // No loop

	// Setting $parent to the given value causes a loop
	if ( isset( $loop[$term_id] ) )
		return 0;

	// There's a loop, but it doesn't contain $term_id. Break the loop.
	foreach ( array_keys( $loop ) as $loop_member )
		wp_update_term( $loop_member, $taxonomy, array( 'parent' => 0 ) );

	return $parent;
}
