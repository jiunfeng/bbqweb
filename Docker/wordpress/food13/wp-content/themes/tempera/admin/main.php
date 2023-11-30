<?php

// Frontend
require_once(get_template_directory() . "/admin/defaults.php");					// default options
require_once(get_template_directory() . "/admin/prototypes.php");				// prototype functions
require_once(get_template_directory() . "/includes/custom-styles.php");			// custom styling
require_once(get_template_directory() . "/admin/customizer.php");				// customizer hook

// Admin side
if( is_admin() ) {
	require_once(get_template_directory() . "/admin/settings.php");				// theme settings
	require_once(get_template_directory() . "/admin/sanitize.php");				// settings sanitizers
	include(get_template_directory() . "/admin/schemes.php");					// preset color schemes
}

// Get the theme options and make sure defaults are used if no values are set
function tempera_get_theme_options() {
	global $tempera_defaults;
	$optionsTempera = get_option( 'tempera_settings', $tempera_defaults );
	$optionsTempera = array_merge((array)$tempera_defaults, (array)$optionsTempera);
	$optionsTempera['id'] = "tempera_settings";
	$optionsTempera = tempera_maybe_migrate_options( $optionsTempera );
	return $optionsTempera;
}
$temperas = tempera_get_theme_options();

//  Hooks/Filters
//add_action('admin_init', 'tempera_init_fn' ); // hooked by settings plugin
add_action('admin_menu', 'tempera_add_page_fn');
add_action('init', 'tempera_init');

// Graceful handling of options that change built-in values
function tempera_maybe_migrate_options($options_array) {
	// changes in 1.8.0
	foreach ( array( 'tempera_fonttitle', 'tempera_fontside', 'tempera_headingsfont', 'tempera_sitetitlefont', 'tempera_menufont' ) as $opt_id ) {
		if ($options_array[$opt_id] == 'General Font') $options_array[$opt_id] = 'font-general';
	}
	return $options_array;
}

// Registering and enqueuing all scripts and styles for the init hook
function tempera_init() {
	load_theme_textdomain( 'tempera', get_template_directory_uri() . '/languages' );
}

// Creating the tempera subpage
function tempera_add_page_fn() {
$page = add_theme_page('Tempera Settings', 'Tempera Settings', 'edit_theme_options', 'tempera-page', 'tempera_page_fn');
	add_action( 'admin_print_styles-'.$page, 'tempera_admin_styles' );
	add_action( 'admin_print_scripts-'.$page, 'tempera_admin_scripts' );
}

// Adding the styles for the Tempera admin page used when tempera_add_page_fn() is launched
function tempera_admin_styles() {
	wp_register_style( 'jquery-ui-style',get_template_directory_uri() . '/js/jqueryui/css/ui-lightness/jquery-ui-1.8.16.custom.css', NULL, _CRYOUT_THEME_VERSION );
	wp_enqueue_style( 'jquery-ui-style' );
	wp_register_style( 'tempera-admin-style',get_template_directory_uri() . '/admin/css/admin.css', NULL, _CRYOUT_THEME_VERSION );
	wp_enqueue_style( 'tempera-admin-style' );
}

// Adding the styles for the Tempera admin page used when tempera_add_page_fn() is launched
function tempera_admin_scripts() {
	// The farbtastic color selector already included in WP
	wp_enqueue_script('farbtastic');
	wp_enqueue_style( 'farbtastic' );

	// jQuery accordion and slider libraries already included in WP
    wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-ui-tooltip');
	
	// For backwards compatibility where Tempera is installed on older versions of WP where the ui accordion and slider are not included
	if (!wp_script_is('jquery-ui-accordion',$list='registered')) {
		wp_register_script('cryout_accordion',get_template_directory_uri() . '/admin/js/accordion-slider.js', array('jquery'), _CRYOUT_THEME_VERSION );
		wp_enqueue_script('cryout_accordion');
		}
	// For the WP uploader
    if(function_exists('wp_enqueue_media')) {
         wp_enqueue_media();
	} else {
         wp_enqueue_script('media-upload');
         wp_enqueue_script('thickbox');
         wp_enqueue_style('thickbox');
	}
	// The js used in the admin
	wp_register_script('cryout-admin-js',get_template_directory_uri() . '/admin/js/admin.js', NULL, _CRYOUT_THEME_VERSION );
	wp_enqueue_script('cryout-admin-js');
}

// The settings sectoions. All the referenced functions are found in admin-functions.php
function tempera_init_fn(){

	register_setting('tempera_settings', 'tempera_settings', 'tempera_settings_validate');

	do_action('tempera_pre_settings_fields');

/**************
   sections
**************/

	add_settings_section('layout_section', __('Layout Settings','tempera'), 'cryout_section_layout_fn', 'tempera-page');
	add_settings_section('header_section', __('Header Settings','tempera'), 'cryout_section_header_fn', 'tempera-page');
	add_settings_section('presentation_section', __('Presentation Page','tempera'), 'cryout_section_presentation_fn', 'tempera-page');
	add_settings_section('text_section', __('Text Settings','tempera'), 'cryout_section_text_fn', 'tempera-page');
	add_settings_section('appereance_section',__('Color Settings','tempera') , 'cryout_section_appereance_fn', 'tempera-page');
	add_settings_section('graphics_section', __('Graphics Settings','tempera') , 'cryout_section_graphics_fn', 'tempera-page');
	add_settings_section('post_section', __('Post Information Settings','tempera') , 'cryout_section_post_fn', 'tempera-page');
	add_settings_section('excerpt_section', __('Post Excerpt Settings','tempera') , 'cryout_section_excerpt_fn', 'tempera-page');
	add_settings_section('featured_section', __('Featured Image Settings','tempera') , 'cryout_section_featured_fn', 'tempera-page');
	add_settings_section('socials_section', __('Social Media Settings','tempera') , 'cryout_section_social_fn', 'tempera-page');
	add_settings_section('misc_section', __('Miscellaneous Settings','tempera') , 'cryout_section_misc_fn', 'tempera-page');

/*** layout ***/
	add_settings_field('tempera_side', __('Main Layout','tempera') , 'cryout_setting_side_fn', 'tempera-page', 'layout_section');
	add_settings_field('tempera_sidewidth', __('Content / Sidebar Width','tempera') , 'cryout_setting_sidewidth_fn', 'tempera-page', 'layout_section');
	add_settings_field('tempera_magazinelayout', __('Magazine Layout','tempera') , 'cryout_setting_magazinelayout_fn', 'tempera-page', 'layout_section');
	add_settings_field('tempera_mobile', __('Responsiveness','tempera') , 'cryout_setting_mobile_fn', 'tempera-page', 'layout_section');

/*** presentation ***/
	add_settings_field('tempera_frontpage', __('Enable Presentation Page','tempera') , 'cryout_setting_frontpage_fn', 'tempera-page', 'presentation_section');
	add_settings_field('tempera_frontposts', __('Show Posts on Presentation Page','tempera') , 'cryout_setting_frontposts_fn', 'tempera-page', 'presentation_section');
	add_settings_field('tempera_frontslider', __('Slider Settings','tempera') , 'cryout_setting_frontslider_fn', 'tempera-page', 'presentation_section');
	add_settings_field('tempera_frontslider2', __('Slides','tempera') , 'cryout_setting_frontslider2_fn', 'tempera-page', 'presentation_section');
	add_settings_field('tempera_frontcolumns', __('Presentation Page Columns','tempera') , 'cryout_setting_frontcolumns_fn', 'tempera-page', 'presentation_section');
	add_settings_field('tempera_fronttext', __('Extras','tempera') , 'cryout_setting_fronttext_fn', 'tempera-page', 'presentation_section');

/*** header ***/
	add_settings_field('tempera_hheight', __('Header Height','tempera') , 'cryout_setting_hheight_fn', 'tempera-page', 'header_section');
	add_settings_field('tempera_himage', __('Header Image','tempera') , 'cryout_setting_himage_fn', 'tempera-page', 'header_section');
	add_settings_field('tempera_siteheader', __('Site Header','tempera') , 'cryout_setting_siteheader_fn', 'tempera-page', 'header_section');
	add_settings_field('tempera_logoupload', __('Custom Logo Upload','tempera') , 'cryout_setting_logoupload_fn', 'tempera-page', 'header_section');
	add_settings_field('tempera_headermargin', __('Header Content Spacing','tempera') , 'cryout_setting_headermargin_fn', 'tempera-page', 'header_section');
	// favicon functionality removed @tempera 1.8.0
	add_settings_field('tempera_headerwidgetwidth', __('Header Widget Width','tempera') , 'cryout_setting_headerwidgetwidth_fn', 'tempera-page', 'header_section');

/*** text ***/
	add_settings_field('tempera_fontfamily', __('General Font','tempera') , 'cryout_setting_fontfamily_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_fonttitle', __('Post Title Font ','tempera') , 'cryout_setting_fonttitle_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_fontside', __('Widget Title Font','tempera') , 'cryout_setting_fontside_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_sitetitlefont', __('Site Title Font','tempera') , 'cryout_setting_sitetitlefont_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_menufont', __('Main Menu Font','tempera') , 'cryout_setting_menufont_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_fontheadings', __('Headings Font','tempera') , 'cryout_setting_fontheadings_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_textalign', __('Force Text Align','tempera') , 'cryout_setting_textalign_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_paragraphspace', __('Paragraph spacing','tempera') , 'cryout_setting_paragraphspace_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_parindent', __('Paragraph Indent','tempera') , 'cryout_setting_parindent_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_headingsindent', __('Headings Indent','tempera') , 'cryout_setting_headingsindent_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_lineheight', __('Line Height','tempera') , 'cryout_setting_lineheight_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_wordspace', __('Word Spacing','tempera') , 'cryout_setting_wordspace_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_letterspace', __('Letter Spacing','tempera') , 'cryout_setting_letterspace_fn', 'tempera-page', 'text_section');
	add_settings_field('tempera_letterspace', __('Uppercase Text','tempera') , 'cryout_setting_uppercasetext_fn', 'tempera-page', 'text_section');

/*** appereance ***/

    add_settings_field('tempera_sitebackground', __('Background Image','tempera') , 'cryout_setting_sitebackground_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_generalcolors', __('General','tempera') , 'cryout_setting_generalcolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_accentcolors', __('Accents','tempera') , 'cryout_setting_accentcolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_titlecolors', __('Site Title','tempera') , 'cryout_setting_titlecolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_menucolors', __('Main Menu','tempera') , 'cryout_setting_menucolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_topmenucolors', __('Top Bar','tempera') , 'cryout_setting_topmenucolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_contentcolors', __('Content','tempera') , 'cryout_setting_contentcolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_frontpagecolors', __('Presentation Page','tempera') , 'cryout_setting_frontpagecolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_sidecolors', __('Sidebar Widgets','tempera') , 'cryout_setting_sidecolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_widgetcolors', __('Footer Widgets','tempera') , 'cryout_setting_widgetcolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_linkcolors', __('Links','tempera') , 'cryout_setting_linkcolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_metacolors', __('Post metas','tempera') , 'cryout_setting_metacolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_socialcolors', __('Socials','tempera') , 'cryout_setting_socialcolors_fn', 'tempera-page', 'appereance_section');
	add_settings_field('tempera_caption', __('Caption type','tempera') , 'cryout_setting_caption_fn', 'tempera-page', 'appereance_section');

/*** graphics ***/

	add_settings_field('tempera_topbar', __('Top Bar','tempera') , 'cryout_setting_topbar_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_breadcrumbs', __('Breadcrumbs','tempera') , 'cryout_setting_breadcrumbs_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_pagination', __('Pagination','tempera') , 'cryout_setting_pagination_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_menualign', __('Menu Alignment','tempera') , 'cryout_setting_menualign_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_searchbar', __('Search Bar Locations','tempera') , 'cryout_setting_searchbar_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_contentmargins', __('Content Margins','tempera') , 'cryout_setting_contentmargins_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_image', __('Post Images Border','tempera') , 'cryout_setting_image_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_contentlist', __('Content List Bullets','tempera') , 'cryout_setting_contentlist_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_pagetitle', __('Page Titles','tempera') , 'cryout_setting_pagetitle_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_categetitle', __('Category Titles','tempera') , 'cryout_setting_categtitle_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_tables', __('Hide Tables','tempera') , 'cryout_setting_tables_fn', 'tempera-page', 'graphics_section');
	add_settings_field('tempera_backtop', __('Back to Top button','tempera') , 'cryout_setting_backtop_fn', 'tempera-page', 'graphics_section');

/*** post metas***/
	add_settings_field('tempera_metapos', __('Meta Bar Position','tempera') , 'cryout_setting_metapos_fn', 'tempera-page', 'post_section');
	add_settings_field('tempera_metashowblog', __('Show on Blog Metas','tempera') , 'cryout_setting_metashowblog_fn', 'tempera-page', 'post_section');
	add_settings_field('tempera_metashowsingle', __('Show on Single Pages','tempera') , 'cryout_setting_metashowsingle_fn', 'tempera-page', 'post_section');
	add_settings_field('tempera_comtext', __('Text Under Comments','tempera') , 'cryout_setting_comtext_fn', 'tempera-page', 'post_section');
	add_settings_field('tempera_comclosed', __('Comments are closed text','tempera') , 'cryout_setting_comclosed_fn', 'tempera-page', 'post_section');
	add_settings_field('tempera_comoff', __('Comments off','tempera') , 'cryout_setting_comoff_fn', 'tempera-page', 'post_section');
	add_settings_field('tempera_comlabels', __('Comment Fields Labels','tempera') , 'cryout_setting_comlabels_fn', 'tempera-page', 'post_section');

/*** post exceprts***/
	add_settings_field('tempera_excerpthome', __('Home Page','tempera') , 'cryout_setting_excerpthome_fn', 'tempera-page', 'excerpt_section');
	add_settings_field('tempera_excerptsticky', __('Sticky Posts','tempera') , 'cryout_setting_excerptsticky_fn', 'tempera-page', 'excerpt_section');
	add_settings_field('tempera_excerptarchive', __('Archive and Category Pages','tempera') , 'cryout_setting_excerptarchive_fn', 'tempera-page', 'excerpt_section');
	add_settings_field('tempera_excerptwords', __('Number of Words for Post Excerpts ','tempera') , 'cryout_setting_excerptwords_fn', 'tempera-page', 'excerpt_section');
	add_settings_field('tempera_excerptdots', __('Excerpt suffix','tempera') , 'cryout_setting_excerptdots_fn', 'tempera-page', 'excerpt_section');
	add_settings_field('tempera_excerptcont', __('Continue reading link text ','tempera') , 'cryout_setting_excerptcont_fn', 'tempera-page', 'excerpt_section');
	add_settings_field('tempera_excerpttags', __('HTML tags in Excerpts','tempera') , 'cryout_setting_excerpttags_fn', 'tempera-page', 'excerpt_section');

/*** featured ***/
	add_settings_field('tempera_fpost', __('Featured Images as POST Thumbnails ','tempera') , 'cryout_setting_fpost_fn', 'tempera-page', 'featured_section');
	add_settings_field('tempera_fauto', __('Auto Select Images From Posts ','tempera') , 'cryout_setting_fauto_fn', 'tempera-page', 'featured_section');
	add_settings_field('tempera_falign', __('Thumbnails Alignment ','tempera') , 'cryout_setting_falign_fn', 'tempera-page', 'featured_section');
	add_settings_field('tempera_fsize', __('Thumbnails Size ','tempera') , 'cryout_setting_fsize_fn', 'tempera-page', 'featured_section');
	add_settings_field('tempera_fheader', __('Featured Images as HEADER Images ','tempera') , 'cryout_setting_fheader_fn', 'tempera-page', 'featured_section');

/*** socials ***/
	add_settings_field('tempera_socials1', __('Link nr. 1','tempera') , 'cryout_setting_socials1_fn', 'tempera-page', 'socials_section');
	add_settings_field('tempera_socials2', __('Link nr. 2','tempera') , 'cryout_setting_socials2_fn', 'tempera-page', 'socials_section');
	add_settings_field('tempera_socials3', __('Link nr. 3','tempera') , 'cryout_setting_socials3_fn', 'tempera-page', 'socials_section');
	add_settings_field('tempera_socials4', __('Link nr. 4','tempera') , 'cryout_setting_socials4_fn', 'tempera-page', 'socials_section');
	add_settings_field('tempera_socials5', __('Link nr. 5','tempera') , 'cryout_setting_socials5_fn', 'tempera-page', 'socials_section');
	add_settings_field('tempera_socialshow', __('Socials display','tempera') , 'cryout_setting_socialsdisplay_fn', 'tempera-page', 'socials_section');

/*** misc ***/
	add_settings_field('tempera_iecompat', __('Internet Explorer Compatibility Tag','tempera') , 'cryout_setting_iecompat_fn', 'tempera-page', 'misc_section');
	add_settings_field('tempera_fitvids', __('FitVids','tempera') , 'cryout_setting_fitvids_fn', 'tempera-page', 'misc_section');
	add_settings_field('tempera_editorstyle', __('Editor Styling','tempera') , 'cryout_setting_editorstyle_fn', 'tempera-page', 'misc_section');
	add_settings_field('tempera_copyright', __('Custom Footer Text','tempera') , 'cryout_setting_copyright_fn', 'tempera-page', 'misc_section');
	add_settings_field('tempera_customcss', __('Custom CSS','tempera') , 'cryout_setting_customcss_fn', 'tempera-page', 'misc_section');
	add_settings_field('tempera_customjs', __('Custom JavaScript','tempera') , 'cryout_setting_customjs_fn', 'tempera-page', 'misc_section');

	do_action('tempera_post_settings_fields');

} // tempera_init_fn()

function tempera_theme_settings_placeholder() { 
	global $wp_version;
	if (function_exists('tempera_theme_settings_restore') ):
			tempera_theme_settings_restore();
	else:
?>
   <div id="tempera-settings-placeholder">
		<h3>Where are the theme settings?</h3>
		<p>According to the <a href="https://make.wordpress.org/themes/2015/04/21/this-weeks-meeting-important-information-regarding-theme-options/" target="_blank">Wordpress Theme Review Guidelines</a>, starting with Tempera v1.2 we had to remove the settings page from the theme and transfer all the settings to the <a href="http://codex.wordpress.org/Appearance_Customize_Screen" target="_blank">Customizer</a> interface.</p>
		<p>However, we feel that the Customizer interface does not provide the right medium (in space of terms and usability) for our existing theme options. We've created our settings with a certain layout that is not really compatible with the Customizer interface.</p>
		<p>As an alternative solution that allows us to keep updating and improving our theme we have moved the settings functionality to the separate <a href="https://wordpress.org/plugins/cryout-theme-settings/" target="_blank">Cryout Serious Theme Settings</a> plugin. To restore the theme settings page to previous functionality, all you need to do is install this free plugin with a couple of clicks.</p>
		<h3>How do I restore the settings?</h3>
		<p><strong>Navigate <a href="themes.php?page=tempera-extra-plugins">to this page</a> to install and activate the Cryout Serious Theme Settings plugin, then return here to find the settings page in all its past glory.</strong></p>
		<p>The plugin is compatible with all our themes that are affected by this change and only needs to be installed once.</p>
   </div>
<?php
	endif;
} // tempera_theme_settings_placeholder()

 // Display the admin options page
function tempera_page_fn() {
 // Load the import form page if the import button has been pressed
	if (isset($_POST['tempera_import'])) {
		tempera_import_form();
		return;
	}
 // Load the import form  page after upload button has been pressed
	if (isset($_POST['tempera_import_confirmed'])) {
		tempera_import_file();
		return;
	}

 // Load the presets  page after presets button has been pressed
	if (isset($_POST['tempera_presets'])) {
		tempera_init_fn();
		tempera_presets();
		return;
	}


 if (!current_user_can('edit_theme_options'))  {
    wp_die( __('Sorry, but you do not have sufficient permissions to access this page.','tempera') );
  }?>


<div class="wrap cryout-admin"><!-- Admin wrap page -->
<h2 id="empty-placeholder-heading-for-wp441-notice-forced-move"></h2>
<?php
if ( isset( $_GET['settings-updated'] ) ) {
    echo "<div class='updated fade' style='clear:left;'><p>";
	echo _e('Tempera settings updated successfully.','tempera');
	echo "</p></div>";
}
?>
<div id="jsAlert" class=""><b>Checking jQuery functionality...</b><br/><em>If this message remains visible after the page has loaded then there is a problem with your WordPress jQuery library. This can have several causes, including incompatible plugins.
The Tempera Settings page cannot function without jQuery. </em></div>

<div id="lefty"><!-- Left side of page - the options area -->
<div>
	<div id="admin_header"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/admin/images/tempera-logo.png' ?>" /> </div>
	<div id="admin_links">
		<a target="_blank" href="https://www.cryoutcreations.eu/wordpress-themes/tempera">Tempera Homepage</a>
		<a target="_blank" href="https://www.cryoutcreations.eu/forum">Support</a>
		<a target="_blank" href="https://www.cryoutcreations.eu">Cryout Creations</a>
	</div>
	<div style="clear: both;"></div>
</div>
	<div id="main-options">
		<?php
		tempera_theme_settings_placeholder();
		$tempera_theme_data = get_transient( 'tempera_theme_info');
		?>
		<span id="version">
		Tempera v<?php echo _CRYOUT_THEME_VERSION; ?> by <a href="https://www.cryoutcreations.eu" target="_blank">Cryout Creations</a>
		</span>
	</div><!-- main-options -->
</div><!--lefty -->


<div id="righty" ><!-- Right side of page - Coffee, RSS tips and others -->

	<?php do_action('tempera_before_righty') ?>

	<div id="tempera-donate" class="postbox donate">
		<h3 class="hndle"> Coffee Break </h3>
		<div class="inside"><?php echo "<p>While looking at Tempera you will notice what may appear as colors. You'll see them within images, in links and menus, defining borders and backgrounds, as part of animations, hover effects and more.  </p>
<p>But don't let that fool you, those are not colors. What you're actually seeing is a complex mix of coffee and our own blood - you'd be surprised to see how many hues we can get by mixing those two. But as of late we've been feeling pretty dizzy and light headed and it's not from the lack of blood (we are secretly vampires).</p>
<p>What's causing the dizziness is the limited amount of coffee. Every morning we have to make one very tough decision: either use coffee to make colors for Tempera or drink it and stay awake to develop Tempera. It's a choice we'd rather not make so...</p>"; ?>
			<div style="display:block;float:none;margin:0 auto;text-align:center;">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_donations">
					<input type="hidden" name="business" value="KYL26KAN4PJC8">
					<input type="hidden" name="item_name" value="Cryout Creations / Tempera Theme donation">
					<input type="hidden" name="currency_code" value="EUR">
					<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHosted">
					<input type="image" src="<?php echo esc_url( get_template_directory_uri() ) . '/admin/images/coffee.png' ?>" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>

			<p>Or socially smother, caress and embrace us:</p>
			<div class="social-buttons">
				<a href="https://www.facebook.com/cryoutcreations" target="_blank" title="Follow us on Facebook">
					<img src="<?php echo esc_url( get_template_directory_uri() ). '/admin/images/icon-facebook.png' ?>" alt="Facebook">
				</a>
				<a href="https://twitter.com/cryoutcreations" target="_blank" title="Follow us on Twitter">
					<img src="<?php echo esc_url( get_template_directory_uri() ) . '/admin/images/icon-twitter.png' ?>" alt="Twitter">
				</a>
				<a href="https://profiles.wordpress.org/cryout-creations/" target="_blank" title="Check out our WordPress.org creations">
					<img src="<?php echo esc_url( get_template_directory_uri() ) . '/admin/images/icon-wporg.png' ?>" alt="WordPress.org">
				</a>
			</div>

		</div><!-- inside -->
	</div><!-- donate -->
	
	<?php do_action('tempera_after_righty') ?>

</div><!--  righty -->
</div><!--  wrap -->

<script type="text/javascript">
var reset_confirmation = '<?php echo esc_html(__('Reset Tempera Settings to Defaults?','tempera')); ?>';
var tempera_help_icon = '<?php echo esc_url( get_template_directory_uri() ) ?>/images/crycon-tooltip.png';

jQuery(document).ready(function(){
	if (vercomp(jQuery.ui.version,"1.9.0")) {
		tooltip_terain();
		jQuery('.colorthingy').each(function(){
			id = "#"+jQuery(this).attr('id');
			startfarb(id,id+'2');
		});
	} else {
		jQuery("#main-options").addClass('oldwp');
		setTimeout(function(){jQuery('#tempera_slideType').trigger('click')},1000);
		jQuery('.colorthingy').each(function(){
			id = "#"+jQuery(this).attr('id');
			jQuery(this).on('keyup',function(){coloursel(this)});
			coloursel(this);
		});
		/* warn about the old partially unsupported version */
		jQuery("#jsAlert").after("<div class='updated fade' style='clear:left; font-size: 16px;'><p>Tempera has detected you are running an older version of Wordpress (jQuery) and will be running in compatibility mode. Some features may not work correctly. Consider updating your Wordpress to the latest version.</p></div>");
	}
});
jQuery('#jsAlert').hide();
</script>

<?php } // tempera_page_fn()
?>
