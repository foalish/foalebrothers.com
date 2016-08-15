<?php
/**
 * Post advanced form for inclusion in the administration panels.
 *
 * @package WordPress
 * @subpackage Administration
 */

// don't load directly
if ( !defined('ABSPATH') )
	die('-1');

/**
 * @global string  $post_type
 * @global object  $post_type_object
 * @global WP_Post $post
 */
global $post_type, $post_type_object, $post;

wp_enqueue_script('post');
$_wp_editor_expand = $_content_editor_dfw = false;

/**
 * Filter whether to enable the 'expand' functionality in the post editor.
 *
 * @since 4.0.0
 * @since 4.1.0 Added the `$post_type` parameter.
 *
 * @param bool   $expand    Whether to enable the 'expand' functionality. Default true.
 * @param string $post_type Post type.
 */
if ( post_type_supports( $post_type, 'editor' ) && ! wp_is_mobile() &&
	 ! ( $is_IE && preg_match( '/MSIE [5678]/', $_SERVER['HTTP_USER_AGENT'] ) ) &&
	 apply_filters( 'wp_editor_expand', true, $post_type ) ) {

	wp_enqueue_script('editor-expand');
	$_content_editor_dfw = true;
	$_wp_editor_expand = ( get_user_setting( 'editor_expand', 'on' ) === 'on' );
}

if ( wp_is_mobile() )
	wp_enqueue_script( 'jquery-touch-punch' );

/**
 * Post ID global
 * @name $post_ID
 * @var int
 */
$post_ID = isset($post_ID) ? (int) $post_ID : 0;
$user_ID = isset($user_ID) ? (int) $user_ID : 0;
$action = isset($action) ? $action : '';

if ( $post_ID == get_option( 'page_for_posts' ) && empty( $post->post_content ) ) {
	add_action( 'edit_form_after_title', '_wp_posts_page_notice' );
	remove_post_type_support( $post_type, 'editor' );
}

$thumbnail_support = current_theme_supports( 'post-thumbnails', $post_type ) && post_type_supports( $post_type, 'thumbnail' );
if ( ! $thumbnail_support && 'attachment' === $post_type && $post->post_mime_type ) {
	if ( wp_attachment_is( 'audio', $post ) ) {
		$thumbnail_support = post_type_supports( 'attachment:audio', 'thumbnail' ) || current_theme_supports( 'post-thumbnails', 'attachment:audio' );
	} elseif ( wp_attachment_is( 'video', $post ) ) {
		$thumbnail_support = post_type_supports( 'attachment:video', 'thumbnail' ) || current_theme_supports( 'post-thumbnails', 'attachment:video' );
	}
}

if ( $thumbnail_support ) {
	add_thickbox();
	wp_enqueue_media( array( 'post' => $post_ID ) );
}

// Add the local autosave notice HTML
add_action( 'admin_footer', '_local_storage_notice' );

/*
 * @todo Document the $messages array(s).
 */
$permalink = get_permalink( $post_ID );
if ( ! $permalink ) {
	$permalink = '';
}

$messages = array();

$preview_post_link_html = $scheduled_post_link_html = $view_post_link_html = '';
$preview_page_link_html = $scheduled_page_link_html = $view_page_link_html = '';

$preview_url = get_preview_post_link( $post );

$viewable = is_post_type_viewable( $post_type_object );

if ( $viewable ) {

	// Preview post link.
	$preview_post_link_html = sprintf( ' <a target="_blank" href="%1$s">%2$s</a>',
		esc_url( $preview_url ),
		__( 'Preview post' )
	);

	// Scheduled post preview link.
	$scheduled_post_link_html = sprintf( ' <a target="_blank" href="%1$s">%2$s</a>',
		esc_url( $permalink ),
		__( 'Preview post' )
	);

	// View post link.
	$view_post_link_html = sprintf( ' <a href="%1$s">%2$s</a>',
		esc_url( $permalink ),
		__( 'View post' )
	);

	// Preview page link.
	$preview_page_link_html = sprintf( ' <a target="_blank" href="%1$s">%2$s</a>',
		esc_url( $preview_url ),
		__( 'Preview page' )
	);

	// Scheduled page preview link.
	$scheduled_page_link_html = sprintf( ' <a target="_blank" href="%1$s">%2$s</a>',
		esc_url( $permalink ),
		__( 'Preview page' )
	);

	// View page link.
	$view_page_link_html = sprintf( ' <a href="%1$s">%2$s</a>',
		esc_url( $permalink ),
		__( 'View page' )
	);

}

/* translators: Publish box date format, see http://php.net/date */
$scheduled_date = date_i18n( __( 'M j, Y @ H:i' ), strtotime( $post->post_date ) );

$messages['post'] = array(
	 0 => '', // Unused. Messages start at index 1.
	 1 => __( 'Post updated.' ) . $view_post_link_html,
	 2 => __( 'Custom field updated.' ),
	 3 => __( 'Custom field deleted.' ),
	 4 => __( 'Post updated.' ),
	/* translators: %s: date and time of the revision */
	 5 => isset($_GET['revision']) ? sprintf( __( 'Post restored to revision from %s.' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	 6 => __( 'Post published.' ) . $view_post_link_html,
	 7 => __( 'Post saved.' ),
	 8 => __( 'Post submitted.' ) . $preview_post_link_html,
	 9 => sprintf( __( 'Post scheduled for: %s.' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_post_link_html,
	10 => __( 'Post draft updated.' ) . $preview_post_link_html,
);
$messages['page'] = array(
	 0 => '', // Unused. Messages start at index 1.
	 1 => __( 'Page updated.' ) . $view_page_link_html,
	 2 => __( 'Custom field updated.' ),
	 3 => __( 'Custom field deleted.' ),
	 4 => __( 'Page updated.' ),
	/* translators: %s: date and time of the revision */
	 5 => isset($_GET['revision']) ? sprintf( __( 'Page restored to revision from %s.' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	 6 => __( 'Page published.' ) . $view_page_link_html,
	 7 => __( 'Page saved.' ),
	 8 => __( 'Page submitted.' ) . $preview_page_link_html,
	 9 => sprintf( __( 'Page scheduled for: %s.' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_page_link_html,
	10 => __( 'Page draft updated.' ) . $preview_page_link_html,
);
$messages['attachment'] = array_fill( 1, 10, __( 'Media attachment updated.' ) ); // Hack, for now.

/**
 * Filter the post updated messages.
 *
 * @since 3.0.0
 *
 * @param array $messages Post updated messages. For defaults @see $messages declarations above.
 */
$messages = apply_filters( 'post_updated_messages', $messages );

$message = false;
if ( isset($_GET['message']) ) {
	$_GET['message'] = absint( $_GET['message'] );
	if ( isset($messages[$post_type][$_GET['message']]) )
		$message = $messages[$post_type][$_GET['message']];
	elseif ( !isset($messages[$post_type]) && isset($messages['post'][$_GET['message']]) )
		$message = $messages['post'][$_GET['message']];
}

$notice = false;
$form_extra = '';
if ( 'auto-draft' == $post->post_status ) {
	if ( 'edit' == $action )
		$post->post_title = '';
	$autosave = false;
	$form_extra .= "<input type='hidden' id='auto_draft' name='auto_draft' value='1' />";
} else {
	$autosave = wp_get_post_autosave( $post_ID );
}

$form_action = 'editpost';
$nonce_action = 'update-post_' . $post_ID;
$form_extra .= "<input type='hidden' id='post_ID' name='post_ID' value='" . esc_attr($post_ID) . "' />";

// Detect if there exists an autosave newer than the post and if that autosave is different than the post
if ( $autosave && mysql2date( 'U', $autosave->post_modified_gmt, false ) > mysql2date( 'U', $post->post_modified_gmt, false ) ) {
	foreach ( _wp_post_revision_fields() as $autosave_field => $_autosave_field ) {
		if ( normalize_whitespace( $autosave->$autosave_field ) != normalize_whitespace( $post->$autosave_field ) ) {
			$notice = sprintf( __( 'There is an autosave of this post that is more recent than the version below. <a href="%s">View the autosave</a>' ), get_edit_post_link( $autosave->ID ) );
			break;
		}
	}
	// If this autosave isn't different from the current post, begone.
	if ( ! $notice )
		wp_delete_post_revision( $autosave->ID );
	unset($autosave_field, $_autosave_field);
}

$post_type_object = get_post_type_object($post_type);

// All meta boxes should be defined and added before the first do_meta_boxes() call (or potentially during the do_meta_boxes action).
require_once( ABSPATH . 'wp-admin/includes/meta-boxes.php' );


$publish_callback_args = null;
if ( post_type_supports($post_type, 'revisions') && 'auto-draft' != $post->post_status ) {
	$revisions = wp_get_post_revisions( $post_ID );

	// We should aim to show the revisions metabox only when there are revisions.
	if ( count( $revisions ) > 1 ) {
		reset( $revisions ); // Reset pointer for key()
		$publish_callback_args = array( 'revisions_count' => count( $revisions ), 'revision_id' => key( $revisions ) );
		add_meta_box('revisionsdiv', __('Revisions'), 'post_revisions_meta_box', null, 'normal', 'core');
	}
}

if ( 'attachment' == $post_type ) {
	wp_enqueue_script( 'image-edit' );
	wp_enqueue_style( 'imgareaselect' );
	add_meta_box( 'submitdiv', __('Save'), 'attachment_submit_meta_box', null, 'side', 'core' );
	add_action( 'edit_form_after_title', 'edit_form_image_editor' );

	if ( wp_attachment_is( 'audio', $post ) ) {
		add_meta_box( 'attachment-id3', __( 'Metadata' ), 'attachment_id3_data_meta_box', null, 'normal', 'core' );
	}
} else {
	add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', null, 'side', 'core', $publish_callback_args );
}

if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post_type, 'post-formats' ) )
	add_meta_box( 'formatdiv', _x( 'Format', 'post format' ), 'post_format_meta_box', null, 'side', 'core' );

// all taxonomies
foreach ( get_object_taxonomies( $post ) as $tax_name ) {
	$taxonomy = get_taxonomy( $tax_name );
	if ( ! $taxonomy->show_ui || false === $taxonomy->meta_box_cb )
		continue;

	$label = $taxonomy->labels->name;

	if ( ! is_taxonomy_hierarchical( $tax_name ) )
		$tax_meta_box_id = 'tagsdiv-' . $tax_name;
	else
		$tax_meta_box_id = $tax_name . 'div';

	add_meta_box( $tax_meta_box_id, $label, $taxonomy->meta_box_cb, null, 'side', 'core', array( 'taxonomy' => $tax_name ) );
}

if ( post_type_supports($post_type, 'page-attributes') )
	add_meta_box('pageparentdiv', 'page' == $post_type ? __('Page Attributes') : __('Attributes'), 'page_attributes_meta_box', null, 'side', 'core');

if ( $thumbnail_support && current_user_can( 'upload_files' ) )
	add_meta_box('postimagediv', esc_html( $post_type_object->labels->featured_image ), 'post_thumbnail_meta_box', null, 'side', 'low');

if ( post_type_supports($post_type, 'excerpt') )
	add_meta_box('postexcerpt', __('Excerpt'), 'post_excerpt_meta_box', null, 'normal', 'core');

if ( post_type_supports($post_type, 'trackbacks') )
	add_meta_box('trackbacksdiv', __('Send Trackbacks'), 'post_trackback_meta_box', null, 'normal', 'core');

if ( post_type_supports($post_type, 'custom-fields') )
	add_meta_box('postcustom', __('Custom Fields'), 'post_custom_meta_box', null, 'normal', 'core');

/**
 * Fires in the middle of built-in meta box registration.
 *
 * @since 2.1.0
 * @deprecated 3.7.0 Use 'add_meta_boxes' instead.
 *
 * @param WP_Post $post Post object.
 */
do_action( 'dbx_post_advanced', $post );

// Allow the Discussion meta box to show up if the post type supports comments,
// or if comments or pings are open.
if ( comments_open( $post ) || pings_open( $post ) || post_type_supports( $post_type, 'comments' ) ) {
	add_meta_box( 'commentstatusdiv', __( 'Discussion' ), 'post_comment_status_meta_box', null, 'normal', 'core' );
}

$stati = get_post_stati( array( 'public' => true ) );
if ( empty( $stati ) ) {
	$stati = array( 'publish' );
}
$stati[] = 'private';

if ( in_array( get_post_status( $post ), $stati ) ) {
	// If the post type support comments, or the post has comments, allow the
	// Comments meta box.
	if ( comments_open( $post ) || pings_open( $post ) || $post->comment_count > 0 || post_type_supports( $post_type, 'comments' ) ) {
		add_meta_box( 'commentsdiv', __( 'Comments' ), 'post_comment_meta_box', null, 'normal', 'core' );
	}
}

if ( ! ( 'pending' == get_post_status( $post ) && ! current_user_can( $post_type_object->cap->publish_posts ) ) )
	add_meta_box('slugdiv', __('Slug'), 'post_slug_meta_box', null, 'normal', 'core');

if ( post_type_supports($post_type, 'author') ) {
	if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) )
		add_meta_box('authordiv', __('Author'), 'post_author_meta_box', null, 'normal', 'core');
}

/**
 * Fires after all built-in meta boxes have been added.
 *
 * @since 3.0.0
 *
 * @param string  $post_type Post type.
 * @param WP_Post $post      Post object.
 */
do_action( 'add_meta_boxes', $post_type, $post );

/**
 * Fires after all built-in meta boxes have been added, contextually for the given post type.
 *
 * The dynamic portion of the hook, `$post_type`, refers to the post type of the post.
 *
 * @since 3.0.0
 *
 * @param WP_Post $post Post object.
 */
do_action( 'add_meta_boxes_' . $post_type, $post );

/**
 * Fires after meta boxes have been added.
 *
 * Fires once for each of the default meta box contexts: normal, advanced, and side.
 *
 * @since 3.0.0
 *
 * @param string  $post_type Post type of the post.
 * @param string  $context   string  Meta box context.
 * @param WP_Post $post      Post object.
 */
do_action( 'do_meta_boxes', $post_type, 'normal', $post );
/** This action is documented in wp-admin/edit-form-advanced.php */
do_action( 'do_meta_boxes', $post_type, 'advanced', $post );
/** This action is documented in wp-admin/edit-form-advanced.php */
do_action( 'do_meta_boxes', $post_type, 'side', $post );

add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );

if ( 'post' == $post_type ) {
	$customize_display = '<p>' . __('The title field and the big Post Editing Area are fixed in place, but you can reposition all the other boxes using drag and drop. You can also minimize or expand them by clicking the title bar of each box. Use the Screen Options tab to unhide more boxes (Excerpt, Send Trackbacks, Custom Fields, Discussion, Slug, Author) or to choose a 1- or 2-column layout for this screen.') . '</p>';

	get_current_screen()->add_help_tab( array(
		'id'      => 'customize-display',
		'title'   => __('Customizing This Display'),
		'content' => $customize_display,
	) );

	$title_and_editor  = '<p>' . __('<strong>Title</strong> &mdash; Enter a title for your post. After you enter a title, you&#8217;ll see the permalink below, which you can edit.') . '</p>';
	$title_and_editor .= '<p>' . __( '<strong>Post editor</strong> &mdash; Enter the text for your post. There are two modes of editing: Visual and Text. Choose the mode by clicking on the appropriate tab.' ) . '</p>';
	$title_and_editor .= '<p>' . __( 'Visual mode gives you a WYSIWYG editor. Click the last icon in the row to get a second row of controls. ') . '</p>';
	$title_and_editor .= '<p>' . __( 'The Text mode allows you to enter HTML along with your post text. Line breaks will be converted to paragraphs automatically.' ) . '</p>';
	$title_and_editor .= '<p>' . __( 'You can insert media files by clicking the icons above the post editor and following the directions. You can align or edit images using the inline formatting toolbar available in Visual mode.' ) . '</p>';
	$title_and_editor .= '<p>' . __( 'You can enable distraction-free writing mode using the icon to the right. This feature is not available for old browsers or devices with small screens, and requires that the full-height editor be enabled in Screen Options.' ) . '</p>';
	$title_and_editor .= '<p>' . __( 'Keyboard users: When you&#8217;re working in the visual editor, you can use <kbd>Alt + F10</kbd> to access the toolbar.' ) . '</p>';

	get_current_screen()->add_help_tab( array(
		'id'      => 'title-post-editor',
		'title'   => __('Title and Post Editor'),
		'content' => $title_and_editor,
	) );

	get_current_screen()->set_help_sidebar(
			'<p>' . sprintf(__('You can also create posts with the <a href="%s">Press This bookmarklet</a>.'), 'tools.php') . '</p>' .
			'<p><strong>' . __('For more information:') . '</strong></p>' .
			'<p>' . __('<a href="https://codex.wordpress.org/Posts_Add_New_Screen" target="_blank">Documentation on Writing and Editing Posts</a>') . '</p>' .
			'<p>' . __('<a href="https://wordpress.org/support/" target="_blank">Support Forums</a>') . '</p>'
	);
} elseif ( 'page' == $post_type ) {
	$about_pages = '<p>' . __('Pages are similar to posts in that they have a title, body text, and associated metadata, but they are different in that they are not part of the chronological blog stream, kind of like permanent posts. Pages are not categorized or tagged, but can have a hierarchy. You can nest pages under other page