<?php
/**
 * Twenty Twenty functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

/**
 * Table of Contents:
 * Theme Support
 * Required Files
 * Register Styles
 * Register Scripts
 * Register Menus
 * Custom Logo
 * WP Body Open
 * Register Sidebars
 * Enqueue Block Editor Assets
 * Enqueue Classic Editor Styles
 * Block Editor Settings
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Twenty Twenty 1.0
 */
function twentytwenty_theme_support()
{

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	// Custom background color.
	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'f5efe0',
		)
	);

	// Set content-width.
	global $content_width;
	if (!isset($content_width)) {
		$content_width = 580;
	}

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// Set post thumbnail size.
	set_post_thumbnail_size(1200, 9999);

	// Add custom image size used in Cover Template.
	add_image_size('twentytwenty-fullscreen', 1980, 9999);

	// Custom logo.
	$logo_width = 120;
	$logo_height = 90;

	// If the retina setting is active, double the recommended width and height.
	if (get_theme_mod('retina_logo', false)) {
		$logo_width = floor($logo_width * 2);
		$logo_height = floor($logo_height * 2);
	}

	add_theme_support(
		'custom-logo',
		array(
			'height' => $logo_height,
			'width' => $logo_width,
			'flex-height' => true,
			'flex-width' => true,
		)
	);

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
			'navigation-widgets',
		)
	);

	// Add support for full and wide align images.
	add_theme_support('align-wide');

	// Add support for responsive embeds.
	add_theme_support('responsive-embeds');

	/*
	 * Adds starter content to highlight the theme on fresh sites.
	 * This is done conditionally to avoid loading the starter content on every
	 * page load, as it is a one-off operation only needed once in the customizer.
	 */
	if (is_customize_preview()) {
		require get_template_directory() . '/inc/starter-content.php';
		add_theme_support('starter-content', twentytwenty_get_starter_content());
	}

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/*
	 * Adds `async` and `defer` support for scripts registered or enqueued
	 * by the theme.
	 */
	$loader = new TwentyTwenty_Script_Loader();
	if (version_compare($GLOBALS['wp_version'], '6.3', '<')) {
		add_filter('script_loader_tag', array($loader, 'filter_script_loader_tag'), 10, 2);
	} else {
		add_filter('print_scripts_array', array($loader, 'migrate_legacy_strategy_script_data'), 100);
	}
}

add_action('after_setup_theme', 'twentytwenty_theme_support');

/**
 * REQUIRED FILES
 * Include required files.
 */
require get_template_directory() . '/inc/template-tags.php';

// Handle SVG icons.
require get_template_directory() . '/classes/class-twentytwenty-svg-icons.php';
require get_template_directory() . '/inc/svg-icons.php';

// Handle Customizer settings.
require get_template_directory() . '/classes/class-twentytwenty-customize.php';

// Require Separator Control class.
require get_template_directory() . '/classes/class-twentytwenty-separator-control.php';

// Custom comment walker.
require get_template_directory() . '/classes/class-twentytwenty-walker-comment.php';

// Custom page walker.
require get_template_directory() . '/classes/class-twentytwenty-walker-page.php';

// Custom script loader class.
require get_template_directory() . '/classes/class-twentytwenty-script-loader.php';

// Non-latin language handling.
require get_template_directory() . '/classes/class-twentytwenty-non-latin-languages.php';

// Custom CSS.
require get_template_directory() . '/inc/custom-css.php';

/**
 * Register block patterns and pattern categories.
 *
 * @since Twenty Twenty 2.8
 */
function twentytwenty_register_block_patterns()
{
	require get_template_directory() . '/inc/block-patterns.php';
}

add_action('init', 'twentytwenty_register_block_patterns');

/**
 * Register and Enqueue Styles.
 *
 * @since Twenty Twenty 1.0
 * @since Twenty Twenty 2.6 Enqueue the CSS file for the variable font.
 */
function twentytwenty_register_styles()
{

	$theme_version = wp_get_theme()->get('Version');

	wp_enqueue_style('twentytwenty-style', get_stylesheet_uri(), array(), $theme_version);
	wp_style_add_data('twentytwenty-style', 'rtl', 'replace');

	// Enqueue the CSS file for the variable font, Inter.
	wp_enqueue_style('twentytwenty-fonts', get_theme_file_uri('/assets/css/font-inter.css'), array(), $theme_version, 'all');

	// Add output of Customizer settings as inline style.
	$customizer_css = twentytwenty_get_customizer_css('front-end');
	if ($customizer_css) {
		wp_add_inline_style('twentytwenty-style', $customizer_css);
	}

	// Add print CSS.
	wp_enqueue_style('twentytwenty-print-style', get_template_directory_uri() . '/print.css', null, $theme_version, 'print');
}

add_action('wp_enqueue_scripts', 'twentytwenty_register_styles');

/**
 * Register and Enqueue Scripts.
 *
 * @since Twenty Twenty 1.0
 */
function twentytwenty_register_scripts()
{

	$theme_version = wp_get_theme()->get('Version');

	if ((!is_admin()) && is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	/*
	 * This script is intentionally printed in the head because it involves the page header. The `defer` script loading
	 * strategy ensures that it does not block rendering; being in the head it will start loading earlier so that it
	 * will execute sooner once the DOM has loaded. The $args array is not used here to avoid unintentional footer
	 * placement in WP<6.3; the wp_script_add_data() call is used instead.
	 */
	wp_enqueue_script('twentytwenty-js', get_template_directory_uri() . '/assets/js/index.js', array(), $theme_version);
	wp_script_add_data('twentytwenty-js', 'strategy', 'defer');
}

add_action('wp_enqueue_scripts', 'twentytwenty_register_scripts');

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @since Twenty Twenty 1.0
 * @deprecated Twenty Twenty 2.3 Removed from wp_print_footer_scripts action.
 *
 * @link https://git.io/vWdr2
 */
function twentytwenty_skip_link_focus_fix()
{
	// The following is minified via `terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.
	?>
	<script>
		/(trident|msie)/i.test(navigator.userAgent) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function () { var t, e = location.hash.substring(1); /^[A-z0-9_-]+$/.test(e) && (t = document.getElementById(e)) && (/^(?:a|select|input|button|textarea)$/i.test(t.tagName) || (t.tabIndex = -1), t.focus()) }, !1);
	</script>
	<?php
}

/**
 * Enqueue non-latin language styles.
 *
 * @since Twenty Twenty 1.0
 *
 * @return void
 */
function twentytwenty_non_latin_languages()
{
	$custom_css = TwentyTwenty_Non_Latin_Languages::get_non_latin_css('front-end');

	if ($custom_css) {
		wp_add_inline_style('twentytwenty-style', $custom_css);
	}
}

add_action('wp_enqueue_scripts', 'twentytwenty_non_latin_languages');

/**
 * Register navigation menus uses wp_nav_menu in five places.
 *
 * @since Twenty Twenty 1.0
 */
function twentytwenty_menus()
{

	$locations = array(
		'primary' => __('Desktop Horizontal Menu', 'twentytwenty'),
		'expanded' => __('Desktop Expanded Menu', 'twentytwenty'),
		'mobile' => __('Mobile Menu', 'twentytwenty'),
		'footer' => __('Footer Menu', 'twentytwenty'),
		'social' => __('Social Menu', 'twentytwenty'),
	);

	register_nav_menus($locations);
}

add_action('init', 'twentytwenty_menus');

/**
 * Get the information about the logo.
 *
 * @since Twenty Twenty 1.0
 *
 * @param string $html The HTML output from get_custom_logo (core function).
 * @return string
 */
function twentytwenty_get_custom_logo($html)
{

	$logo_id = get_theme_mod('custom_logo');

	if (!$logo_id) {
		return $html;
	}

	$logo = wp_get_attachment_image_src($logo_id, 'full');

	if ($logo) {
		// For clarity.
		$logo_width = esc_attr($logo[1]);
		$logo_height = esc_attr($logo[2]);

		// If the retina logo setting is active, reduce the width/height by half.
		if (get_theme_mod('retina_logo', false)) {
			$logo_width = floor($logo_width / 2);
			$logo_height = floor($logo_height / 2);

			$search = array(
				'/width=\"\d+\"/iU',
				'/height=\"\d+\"/iU',
			);

			$replace = array(
				"width=\"{$logo_width}\"",
				"height=\"{$logo_height}\"",
			);

			// Add a style attribute with the height, or append the height to the style attribute if the style attribute already exists.
			if (false === strpos($html, ' style=')) {
				$search[] = '/(src=)/';
				$replace[] = "style=\"height: {$logo_height}px;\" src=";
			} else {
				$search[] = '/(style="[^"]*)/';
				$replace[] = "$1 height: {$logo_height}px;";
			}

			$html = preg_replace($search, $replace, $html);

		}
	}

	return $html;
}

add_filter('get_custom_logo', 'twentytwenty_get_custom_logo');

if (!function_exists('wp_body_open')) {

	/**
	 * Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
	 *
	 * @since Twenty Twenty 1.0
	 */
	function wp_body_open()
	{
		/** This action is documented in wp-includes/general-template.php */
		do_action('wp_body_open');
	}
}

/**
 * Include a skip to content link at the top of the page so that users can bypass the menu.
 *
 * @since Twenty Twenty 1.0
 */
function twentytwenty_skip_link()
{
	echo '<a class="skip-link screen-reader-text" href="#site-content">' .
		/* translators: Hidden accessibility text. */
		__('Skip to the content', 'twentytwenty') .
		'</a>';
}

add_action('wp_body_open', 'twentytwenty_skip_link', 5);

/**
 * Register widget areas.
 *
 * @since Twenty Twenty 1.0
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function twentytwenty_sidebar_registration()
{
	$shared_args = array(
		'name' => esc_html__('Footer #', 'twentytwenty'),
		'id' => 'sidebar-1',
		'description' => esc_html__('Add widgets in this area will be displayed in the footer.', 'twentytwenty'),
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
	);

	// Footer Sidebars #1
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name' => __('Footer #1', 'twentytwenty'),
				'id' => 'sidebar-1',
				'description' => __('Widgets in this area will be displayed in the 1 column in the footer.', 'twentytwenty'),
			)
		)
	);
	// Footer Sidebars #2
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name' => __('Footer #2', 'twentytwenty'),
				'id' => 'sidebar-2',
				'description' => __('Widgets in this area will be displayed in the 2 column in the footer.', 'twentytwenty'),
			)
		)
	);
	// Footer Sidebars #3
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name' => __('Footer #3', 'twentytwenty'),
				'id' => 'sidebar-3',
				'description' => __('Widgets in this area will be displayed in the 3 column in the footer.', 'twentytwenty'),
			)
		)
	);


	// Sidebar 4: Social
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name' => __('Footer Social', 'twentytwenty'),
				'id' => 'sidebar-4',
				'description' => __('Widgets for social links.', 'twentytwenty'),
			)
		)
	);

	// Sidebar 5: Copyright
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name' => __('Footer Copyright', 'twentytwenty'),
				'id' => 'sidebar-5',
				'description' => __('Widgets for copyright info.', 'twentytwenty'),
			)
		)
	);

	// Module #9
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name' => __('Categories #9', 'twentytwenty'),
				'id' => 'sidebar-9',
				'description' => __('Widgets in this area will be displayed in the 9 column in the footer.', 'twentytwenty'),
			)
		)
	);

	// Module #10
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name' => __('Categories #10', 'twentytwenty'),
				'id' => 'sidebar-10',
				'description' => __('Widgets in this area will be displayed in the 10 column in the footer.', 'twentytwenty'),
			)
		)
	);

	// Module #11
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name' => __('Archive #11', 'twentytwenty'),
				'id' => 'sidebar-11',
				'description' => __('Widgets in this area will be displayed in the archive sidebar (latest posts fallback if empty).', 'twentytwenty'),
				'class' => 'latest-widget-area',  // Custom class cho CSS
				'before_widget' => '<div class="widget latest-posts-widget %2$s">',  // Custom cho widget này
			)
		)
	);

	// Module #12
	register_sidebar(array(
		'name' => __('Comments Sidebar #12', 'twentytwenty'),
		'id' => 'sidebar-12',
		'description' => __('Widgets in this area will be displayed in the right sidebar for recent comments.', 'twentytwenty'),
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	//Module #13
	register_sidebar(array(
		'name' => __('Page Sidebar #13', 'twentytwenty'),
		'id' => 'sidebar-13',
		'description' => __('Widgets in this area will be displayed in the right sidebar for recent page.', 'twentytwenty'),
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	//Module #14
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name' => __('Comments #14', 'twentytwenty'),
				'id' => 'sidebar-14',
				'description' => __('Widgets in this area will be displayed in the 14 column in the comment.', 'twentytwenty'),
			)
		)
	);

	//Module #15
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name' => __('Last post #15', 'twentytwenty'),
				'id' => 'sidebar-15',
				'description' => __('Widgets in this area will be displayed in the 14 column in the Last post.', 'twentytwenty'),
			)
		)
	);



}
add_action('widgets_init', 'twentytwenty_sidebar_registration');

/**
 * Enqueue supplemental block editor styles.
 *
 * @since Twenty Twenty 1.0
 * @since Twenty Twenty 2.4 Removed a script related to the obsolete Squared style of Button blocks.
 * @since Twenty Twenty 2.6 Enqueue the CSS file for the variable font.
 */
function twentytwenty_block_editor_styles()
{

	$theme_version = wp_get_theme()->get('Version');

	// Enqueue the editor styles.
	wp_enqueue_style('twentytwenty-block-editor-styles', get_theme_file_uri('/assets/css/editor-style-block.css'), array(), $theme_version, 'all');
	wp_style_add_data('twentytwenty-block-editor-styles', 'rtl', 'replace');

	// Add inline style from the Customizer.
	$customizer_css = twentytwenty_get_customizer_css('block-editor');
	if ($customizer_css) {
		wp_add_inline_style('twentytwenty-block-editor-styles', $customizer_css);
	}

	// Enqueue the CSS file for the variable font, Inter.
	wp_enqueue_style('twentytwenty-fonts', get_theme_file_uri('/assets/css/font-inter.css'), array(), $theme_version, 'all');

	// Add inline style for non-latin fonts.
	$custom_css = TwentyTwenty_Non_Latin_Languages::get_non_latin_css('block-editor');
	if ($custom_css) {
		wp_add_inline_style('twentytwenty-block-editor-styles', $custom_css);
	}
}

if (is_admin() && version_compare($GLOBALS['wp_version'], '6.3', '>=')) {
	add_action('enqueue_block_assets', 'twentytwenty_block_editor_styles', 1, 1);
} else {
	add_action('enqueue_block_editor_assets', 'twentytwenty_block_editor_styles', 1, 1);
}

/**
 * Enqueue classic editor styles.
 *
 * @since Twenty Twenty 1.0
 * @since Twenty Twenty 2.6 Enqueue the CSS file for the variable font.
 */
function twentytwenty_classic_editor_styles()
{

	$classic_editor_styles = array(
		'/assets/css/editor-style-classic.css',
		'/assets/css/font-inter.css',
	);

	add_editor_style($classic_editor_styles);
}

add_action('init', 'twentytwenty_classic_editor_styles');

/**
 * Output Customizer settings in the classic editor.
 * Adds styles to the head of the TinyMCE iframe. Kudos to @Otto42 for the original solution.
 *
 * @since Twenty Twenty 1.0
 *
 * @param array $mce_init TinyMCE styles.
 * @return array TinyMCE styles.
 */
function twentytwenty_add_classic_editor_customizer_styles($mce_init)
{

	$styles = twentytwenty_get_customizer_css('classic-editor');

	if (!$styles) {
		return $mce_init;
	}

	if (!isset($mce_init['content_style'])) {
		$mce_init['content_style'] = $styles . ' ';
	} else {
		$mce_init['content_style'] .= ' ' . $styles . ' ';
	}

	return $mce_init;
}

add_filter('tiny_mce_before_init', 'twentytwenty_add_classic_editor_customizer_styles');

/**
 * Output non-latin font styles in the classic editor.
 * Adds styles to the head of the TinyMCE iframe. Kudos to @Otto42 for the original solution.
 *
 * @param array $mce_init TinyMCE styles.
 * @return array TinyMCE styles.
 */
function twentytwenty_add_classic_editor_non_latin_styles($mce_init)
{

	$styles = TwentyTwenty_Non_Latin_Languages::get_non_latin_css('classic-editor');

	// Return if there are no styles to add.
	if (!$styles) {
		return $mce_init;
	}

	if (!isset($mce_init['content_style'])) {
		$mce_init['content_style'] = $styles . ' ';
	} else {
		$mce_init['content_style'] .= ' ' . $styles . ' ';
	}

	return $mce_init;
}

add_filter('tiny_mce_before_init', 'twentytwenty_add_classic_editor_non_latin_styles');

/**
 * Block Editor Settings.
 * Add custom colors and font sizes to the block editor.
 *
 * @since Twenty Twenty 1.0
 */
function twentytwenty_block_editor_settings()
{

	// Block Editor Palette.
	$editor_color_palette = array(
		array(
			'name' => __('Accent Color', 'twentytwenty'),
			'slug' => 'accent',
			'color' => twentytwenty_get_color_for_area('content', 'accent'),
		),
		array(
			'name' => _x('Primary', 'color', 'twentytwenty'),
			'slug' => 'primary',
			'color' => twentytwenty_get_color_for_area('content', 'text'),
		),
		array(
			'name' => _x('Secondary', 'color', 'twentytwenty'),
			'slug' => 'secondary',
			'color' => twentytwenty_get_color_for_area('content', 'secondary'),
		),
		array(
			'name' => __('Subtle Background', 'twentytwenty'),
			'slug' => 'subtle-background',
			'color' => twentytwenty_get_color_for_area('content', 'borders'),
		),
	);

	// Add the background option.
	$background_color = get_theme_mod('background_color');
	if (!$background_color) {
		$background_color_arr = get_theme_support('custom-background');
		$background_color = $background_color_arr[0]['default-color'];
	}
	$editor_color_palette[] = array(
		'name' => __('Background Color', 'twentytwenty'),
		'slug' => 'background',
		'color' => '#' . $background_color,
	);

	// If we have accent colors, add them to the block editor palette.
	if ($editor_color_palette) {
		add_theme_support('editor-color-palette', $editor_color_palette);
	}

	// Block Editor Font Sizes.
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name' => _x('Small', 'Name of the small font size in the block editor', 'twentytwenty'),
				'shortName' => _x('S', 'Short name of the small font size in the block editor.', 'twentytwenty'),
				'size' => 18,
				'slug' => 'small',
			),
			array(
				'name' => _x('Regular', 'Name of the regular font size in the block editor', 'twentytwenty'),
				'shortName' => _x('M', 'Short name of the regular font size in the block editor.', 'twentytwenty'),
				'size' => 21,
				'slug' => 'normal',
			),
			array(
				'name' => _x('Large', 'Name of the large font size in the block editor', 'twentytwenty'),
				'shortName' => _x('L', 'Short name of the large font size in the block editor.', 'twentytwenty'),
				'size' => 26.25,
				'slug' => 'large',
			),
			array(
				'name' => _x('Larger', 'Name of the larger font size in the block editor', 'twentytwenty'),
				'shortName' => _x('XL', 'Short name of the larger font size in the block editor.', 'twentytwenty'),
				'size' => 32,
				'slug' => 'larger',
			),
		)
	);

	add_theme_support('editor-styles');

	// If we have a dark background color then add support for dark editor style.
	// We can determine if the background color is dark by checking if the text-color is white.
	if ('#ffffff' === strtolower(twentytwenty_get_color_for_area('content', 'text'))) {
		add_theme_support('dark-editor-style');
	}
}

add_action('after_setup_theme', 'twentytwenty_block_editor_settings');

/**
 * Overwrite default more tag with styling and screen reader markup.
 *
 * @param string $html The default output HTML for the more tag.
 * @return string
 */
function twentytwenty_read_more_tag($html)
{
	return preg_replace('/<a(.*)>(.*)<\/a>/iU', sprintf('<div class="read-more-button-wrap"><a$1><span class="faux-button">$2</span> <span class="screen-reader-text">"%1$s"</span></a></div>', get_the_title(get_the_ID())), $html);
}

add_filter('the_content_more_link', 'twentytwenty_read_more_tag');

/**
 * Enqueues scripts for customizer controls & settings.
 *
 * @since Twenty Twenty 1.0
 *
 * @return void
 */
function twentytwenty_customize_controls_enqueue_scripts()
{
	$theme_version = wp_get_theme()->get('Version');

	// Add main customizer js file.
	wp_enqueue_script('twentytwenty-customize', get_template_directory_uri() . '/assets/js/customize.js', array('jquery'), $theme_version);

	// Add script for color calculations.
	wp_enqueue_script('twentytwenty-color-calculations', get_template_directory_uri() . '/assets/js/color-calculations.js', array('wp-color-picker'), $theme_version);

	// Add script for controls.
	wp_enqueue_script('twentytwenty-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array('twentytwenty-color-calculations', 'customize-controls', 'underscore', 'jquery'), $theme_version);
	wp_localize_script('twentytwenty-customize-controls', 'twentyTwentyBgColors', twentytwenty_get_customizer_color_vars());
}

add_action('customize_controls_enqueue_scripts', 'twentytwenty_customize_controls_enqueue_scripts');

/**
 * Enqueue scripts for the customizer preview.
 *
 * @since Twenty Twenty 1.0
 *
 * @return void
 */
function twentytwenty_customize_preview_init()
{
	$theme_version = wp_get_theme()->get('Version');

	wp_enqueue_script('twentytwenty-customize-preview', get_theme_file_uri('/assets/js/customize-preview.js'), array('customize-preview', 'customize-selective-refresh', 'jquery'), $theme_version, array('in_footer' => true));
	wp_localize_script('twentytwenty-customize-preview', 'twentyTwentyBgColors', twentytwenty_get_customizer_color_vars());
	wp_localize_script('twentytwenty-customize-preview', 'twentyTwentyPreviewEls', twentytwenty_get_elements_array());

	wp_add_inline_script(
		'twentytwenty-customize-preview',
		sprintf(
			'wp.customize.selectiveRefresh.partialConstructor[ %1$s ].prototype.attrs = %2$s;',
			wp_json_encode('cover_opacity'),
			wp_json_encode(twentytwenty_customize_opacity_range())
		)
	);
}

add_action('customize_preview_init', 'twentytwenty_customize_preview_init');

/**
 * Get accessible color for an area.
 *
 * @since Twenty Twenty 1.0
 *
 * @param string $area    The area we want to get the colors for.
 * @param string $context Can be 'text' or 'accent'.
 * @return string Returns a HEX color.
 */
function twentytwenty_get_color_for_area($area = 'content', $context = 'text')
{

	// Get the value from the theme-mod.
	$settings = get_theme_mod(
		'accent_accessible_colors',
		array(
			'content' => array(
				'text' => '#000000',
				'accent' => '#cd2653',
				'secondary' => '#6d6d6d',
				'borders' => '#dcd7ca',
			),
			'header-footer' => array(
				'text' => '#000000',
				'accent' => '#cd2653',
				'secondary' => '#6d6d6d',
				'borders' => '#dcd7ca',
			),
		)
	);

	// If we have a value return it.
	if (isset($settings[$area]) && isset($settings[$area][$context])) {
		return $settings[$area][$context];
	}

	// Return false if the option doesn't exist.
	return false;
}

/**
 * Returns an array of variables for the customizer preview.
 *
 * @since Twenty Twenty 1.0
 *
 * @return array
 */
function twentytwenty_get_customizer_color_vars()
{
	$colors = array(
		'content' => array(
			'setting' => 'background_color',
		),
		'header-footer' => array(
			'setting' => 'header_footer_background_color',
		),
	);
	return $colors;
}

/**
 * Get an array of elements.
 *
 * @since Twenty Twenty 1.0
 *
 * @return array
 */
function twentytwenty_get_elements_array()
{

	// The array is formatted like this:
	// [key-in-saved-setting][sub-key-in-setting][css-property] = [elements].
	$elements = array(
		'content' => array(
			'accent' => array(
				'color' => array('.color-accent', '.color-accent-hover:hover', '.color-accent-hover:focus', ':root .has-accent-color', '.has-drop-cap:not(:focus):first-letter', '.wp-block-button.is-style-outline', 'a'),
				'border-color' => array('blockquote', '.border-color-accent', '.border-color-accent-hover:hover', '.border-color-accent-hover:focus'),
				'background-color' => array('button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file .wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.bg-accent', '.bg-accent-hover:hover', '.bg-accent-hover:focus', ':root .has-accent-background-color', '.comment-reply-link'),
				'fill' => array('.fill-children-accent', '.fill-children-accent *'),
			),
			'background' => array(
				'color' => array(':root .has-background-color', 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.wp-block-button', '.comment-reply-link', '.has-background.has-primary-background-color:not(.has-text-color)', '.has-background.has-primary-background-color *:not(.has-text-color)', '.has-background.has-accent-background-color:not(.has-text-color)', '.has-background.has-accent-background-color *:not(.has-text-color)'),
				'background-color' => array(':root .has-background-background-color'),
			),
			'text' => array(
				'color' => array('body', '.entry-title a', ':root .has-primary-color'),
				'background-color' => array(':root .has-primary-background-color'),
			),
			'secondary' => array(
				'color' => array('cite', 'figcaption', '.wp-caption-text', '.post-meta', '.entry-content .wp-block-archives li', '.entry-content .wp-block-categories li', '.entry-content .wp-block-latest-posts li', '.wp-block-latest-comments__comment-date', '.wp-block-latest-posts__post-date', '.wp-block-embed figcaption', '.wp-block-image figcaption', '.wp-block-pullquote cite', '.comment-metadata', '.comment-respond .comment-notes', '.comment-respond .logged-in-as', '.pagination .dots', '.entry-content hr:not(.has-background)', 'hr.styled-separator', ':root .has-secondary-color'),
				'background-color' => array(':root .has-secondary-background-color'),
			),
			'borders' => array(
				'border-color' => array('pre', 'fieldset', 'input', 'textarea', 'table', 'table *', 'hr'),
				'background-color' => array('caption', 'code', 'code', 'kbd', 'samp', '.wp-block-table.is-style-stripes tbody tr:nth-child(odd)', ':root .has-subtle-background-background-color'),
				'border-bottom-color' => array('.wp-block-table.is-style-stripes'),
				'border-top-color' => array('.wp-block-latest-posts.is-grid li'),
				'color' => array(':root .has-subtle-background-color'),
			),
		),
		'header-footer' => array(
			'accent' => array(
				'color' => array('body:not(.overlay-header) .primary-menu > li > a', 'body:not(.overlay-header) .primary-menu > li > .icon', '.modal-menu a', '.footer-menu a, .footer-widgets a:where(:not(.wp-block-button__link))', '#site-footer .wp-block-button.is-style-outline', '.wp-block-pullquote:before', '.singular:not(.overlay-header) .entry-header a', '.archive-header a', '.header-footer-group .color-accent', '.header-footer-group .color-accent-hover:hover'),
				'background-color' => array('.social-icons a', '#site-footer button:not(.toggle)', '#site-footer .button', '#site-footer .faux-button', '#site-footer .wp-block-button__link', '#site-footer .wp-block-file__button', '#site-footer input[type="button"]', '#site-footer input[type="reset"]', '#site-footer input[type="submit"]'),
			),
			'background' => array(
				'color' => array('.social-icons a', 'body:not(.overlay-header) .primary-menu ul', '.header-footer-group button', '.header-footer-group .button', '.header-footer-group .faux-button', '.header-footer-group .wp-block-button:not(.is-style-outline) .wp-block-button__link', '.header-footer-group .wp-block-file__button', '.header-footer-group input[type="button"]', '.header-footer-group input[type="reset"]', '.header-footer-group input[type="submit"]'),
				'background-color' => array('#site-header', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal', '.menu-modal-inner', '.search-modal-inner', '.archive-header', '.singular .entry-header', '.singular .featured-media:before', '.wp-block-pullquote:before'),
			),
			'text' => array(
				'color' => array('.header-footer-group', 'body:not(.overlay-header) #site-header .toggle', '.menu-modal .toggle'),
				'background-color' => array('body:not(.overlay-header) .primary-menu ul'),
				'border-bottom-color' => array('body:not(.overlay-header) .primary-menu > li > ul:after'),
				'border-left-color' => array('body:not(.overlay-header) .primary-menu ul ul:after'),
			),
			'secondary' => array(
				'color' => array('.site-description', 'body:not(.overlay-header) .toggle-inner .toggle-text', '.widget .post-date', '.widget .rss-date', '.widget_archive li', '.widget_categories li', '.widget cite', '.widget_pages li', '.widget_meta li', '.widget_nav_menu li', '.powered-by-wordpress', '.footer-credits .privacy-policy', '.to-the-top', '.singular .entry-header .post-meta', '.singular:not(.overlay-header) .entry-header .post-meta a'),
			),
			'borders' => array(
				'border-color' => array('.header-footer-group pre', '.header-footer-group fieldset', '.header-footer-group input', '.header-footer-group textarea', '.header-footer-group table', '.header-footer-group table *', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal nav *', '.footer-widgets-outer-wrapper', '.footer-top'),
				'background-color' => array('.header-footer-group table caption', 'body:not(.overlay-header) .header-inner .toggle-wrapper::before'),
			),
		),
	);

	/**
	 * Filters Twenty Twenty theme elements.
	 *
	 * @since Twenty Twenty 1.0
	 *
	 * @param array Array of elements.
	 */
	return apply_filters('twentytwenty_get_elements_array', $elements);
}

/**
 * Enqueue scripts and styles.
 */

function twentytwenty_child_enqueue_styles()
{
	// Enqueue parent theme's style
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
	// Enqueue child theme's style
	wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'), '1.0.0');
	// Enqueue Bootstrap CSS
	wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', array(), '4.0.0');
	// Enqueue Font Awesome CSS
	wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0');
	// Enqueue jQuery
	wp_enqueue_script('jquery');
	// Enqueue Bootstrap JS
	wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', array('jquery'), '4.0.0', true);

}
add_action('wp_enqueue_scripts', 'twentytwenty_child_enqueue_styles');

function custom_posts_per_page($query)
{
	if (!is_admin() && $query->is_main_query()) {
		// Giới hạn cho trang chủ
		if ($query->is_home()) {
			$query->set('posts_per_page', 2);
		}

		// Giới hạn cho trang tìm kiếm
		if ($query->is_search()) {
			$query->set('posts_per_page', 2);
		}
	}
}
add_action('pre_get_posts', 'custom_posts_per_page');

// Giới hạn excerpt mặc định
function custom_excerpt_length($length)
{
	return 30; // số từ
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

// Thêm "Xem thêm" vào excerpt
function custom_excerpt_more($more)
{
	return '... <a class="read-more" href="' . get_permalink() . '">Xem thêm »</a>';
}
add_filter('excerpt_more', 'custom_excerpt_more');

// Đăng ký custom widget cho Latest Posts Sidebar (có thể chỉnh sửa trong WP Admin > Appearance > Widgets)
class Latest_Posts_Sidebar_Widget extends WP_Widget
{

	// Constructor
	public function __construct()
	{
		$widget_ops = array(
			'classname' => 'latest_posts_sidebar_widget',
			'description' => __('Hiển thị danh sách bài viết mới nhất chia 2 cột, với số thứ tự, badge comment và nút Xem nhiều. Có thể chỉnh số lượng bài và tiêu đề.', 'twentytwenty'),
		);
		parent::__construct(
			'latest_posts_sidebar_widget',
			__('Latest Posts Sidebar (2 Cột)', 'twentytwenty'),
			$widget_ops
		);
	}

	// Output HTML của widget
	public function widget($args, $instance)
	{
		// Lấy options từ instance
		$title = !empty($instance['title']) ? $instance['title'] : 'Xem mới';
		$num_posts = !empty($instance['num_posts']) ? absint($instance['num_posts']) : 8;

		echo $args['before_widget'];

		// Title
		if (!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
		}

		// Gọi function hiển thị latest posts (với param số lượng)
		display_latest_posts_sidebar_custom($title, $num_posts);

		echo $args['after_widget'];
	}

	// Form chỉnh sửa trong WP Admin
	public function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : 'Xem mới';
		$num_posts = !empty($instance['num_posts']) ? absint($instance['num_posts']) : 8;
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
				<?php esc_attr_e('Tiêu đề:', 'twentytwenty'); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
				name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
				value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('num_posts')); ?>">
				<?php esc_attr_e('Số bài viết (tổng, chia 2 cột):', 'twentytwenty'); ?>
			</label>
			<input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('num_posts')); ?>"
				name="<?php echo esc_attr($this->get_field_name('num_posts')); ?>" type="number" step="2" min="2" max="20"
				value="<?php echo esc_attr($num_posts); ?>" size="3">
			<br><small>(Phải là số chẵn để chia đều 2 cột)</small>
		</p>
		<?php
	}

	// Update options khi save
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		$instance['num_posts'] = (!empty($new_instance['num_posts'])) ? absint($new_instance['num_posts']) : 8;
		return $instance;
	}
}

// Đăng ký widget
function register_latest_posts_sidebar_widget()
{
	register_widget('Latest_Posts_Sidebar_Widget');
}
add_action('widgets_init', 'register_latest_posts_sidebar_widget');

// Function hỗ trợ hiển thị latest posts (custom với số lượng)
if (!function_exists('display_latest_posts_sidebar_custom')) {
	function display_latest_posts_sidebar_custom($title = 'Xem mới', $total_posts = 8)
	{
		$posts_per_column = $total_posts / 2; // Giả sử chẵn

		// Args chung
		$args_base = array(
			'post_status' => 'publish',
			'post_type' => 'post',
			'ignore_sticky_posts' => true,
		);

		// Query cột trái: posts 1 đến n/2
		$args_left = $args_base;
		$args_left['posts_per_page'] = $posts_per_column;
		$args_left['offset'] = 0;
		$left_posts = new WP_Query($args_left);

		// Query cột phải: posts n/2 +1 đến n
		$args_right = $args_base;
		$args_right['posts_per_page'] = $posts_per_column;
		$args_right['offset'] = $posts_per_column;
		$right_posts = new WP_Query($args_right);

		if ($left_posts->have_posts() || $right_posts->have_posts()): ?>
			<div class="view-more-btn">
				<a href="<?php echo get_post_type_archive_link('post'); ?>">Xem nhiều</a>
			</div>
			<div class="latest-posts-grid">
				<div class="latest-column left">
					<?php
					$i = 1;
					if ($left_posts->have_posts()):
						while ($left_posts->have_posts()):
							$left_posts->the_post(); ?>
							<div class="latest-item">
								<span class="item-number"><?php echo $i; ?></span>
								<div class="item-content">
									<a href="<?php the_permalink(); ?>" class="item-title"><?php the_title(); ?></a>
									<span class="item-badge"><?php echo get_comments_number(); ?></span>
								</div>
							</div>
							<?php $i++; endwhile;
					endif; ?>
				</div>
				<div class="latest-column right">
					<?php
					$j = $posts_per_column + 1;
					if ($right_posts->have_posts()):
						while ($right_posts->have_posts()):
							$right_posts->the_post(); ?>
							<div class="latest-item">
								<span class="item-number"><?php echo $j; ?></span>
								<div class="item-content">
									<a href="<?php the_permalink(); ?>" class="item-title"><?php the_title(); ?></a>
									<span class="item-badge"><?php echo get_comments_number(); ?></span>
								</div>
							</div>
							<?php $j++; endwhile;
					endif; ?>
				</div>
			</div>

			<?php
			$left_posts->reset_postdata();
			$right_posts->reset_postdata();
		else: ?>
			<p>Không có bài viết mới.</p>
		<?php endif;
	}
}

// Fallback: Nếu sidebar-11 rỗng, auto hiển thị widget (tùy chọn)
function fallback_latest_posts_if_empty()
{
	if (is_active_sidebar('sidebar-11')) {
		dynamic_sidebar('sidebar-11');
	} else {
		the_widget('Latest_Posts_Sidebar_Widget', array('title' => 'Xem mới', 'num_posts' => 8));
	}
}


// Custom Widget cho Recent Comments (danh sách excerpt, numbered, giống ảnh)
class Recent_Comments_Sidebar_Widget extends WP_Widget
{

	// Constructor
	public function __construct()
	{
		$widget_ops = array(
			'classname' => 'recent_comments_sidebar_widget',
			'description' => __('Hiển thị danh sách comments mới nhất với excerpt ngắn. Có thể chỉnh số lượng và tiêu đề.', 'twentytwenty'),
		);
		parent::__construct(
			'recent_comments_sidebar_widget',
			__('Recent Comments Sidebar', 'twentytwenty'),
			$widget_ops
		);
	}

	// Output HTML của widget
	public function widget($args, $instance)
	{
		// Lấy options
		$title = !empty($instance['title']) ? $instance['title'] : 'Comments';
		$num_comments = !empty($instance['num_comments']) ? absint($instance['num_comments']) : 5;
		$excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 20; // Độ dài excerpt

		echo $args['before_widget'];

		// Title
		if (!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
		}

		// Gọi function hiển thị comments
		display_recent_comments_sidebar($num_comments, $excerpt_length);

		echo $args['after_widget'];
	}

	// Form chỉnh sửa trong WP Admin
	public function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : 'Comments';
		$num_comments = !empty($instance['num_comments']) ? absint($instance['num_comments']) : 5;
		$excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 20;
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
				<?php esc_attr_e('Tiêu đề:', 'twentytwenty'); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
				name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
				value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('num_comments')); ?>">
				<?php esc_attr_e('Số comments:', 'twentytwenty'); ?>
			</label>
			<input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('num_comments')); ?>"
				name="<?php echo esc_attr($this->get_field_name('num_comments')); ?>" type="number" step="1" min="1" max="20"
				value="<?php echo esc_attr($num_comments); ?>" size="3">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>">
				<?php esc_attr_e('Độ dài excerpt (ký tự):', 'twentytwenty'); ?>
			</label>
			<input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>"
				name="<?php echo esc_attr($this->get_field_name('excerpt_length')); ?>" type="number" step="1" min="10"
				max="100" value="<?php echo esc_attr($excerpt_length); ?>" size="3">
		</p>
		<?php
	}

	// Update options
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		$instance['num_comments'] = (!empty($new_instance['num_comments'])) ? absint($new_instance['num_comments']) : 5;
		$instance['excerpt_length'] = (!empty($new_instance['excerpt_length'])) ? absint($new_instance['excerpt_length']) : 20;
		return $instance;
	}
}

// Đăng ký widget
function register_recent_comments_sidebar_widget()
{
	register_widget('Recent_Comments_Sidebar_Widget');
}
add_action('widgets_init', 'register_recent_comments_sidebar_widget');

// Function hỗ trợ hiển thị recent comments (numbered list, excerpt)
if (!function_exists('display_recent_comments_sidebar')) {
	function display_recent_comments_sidebar($num_comments = 5, $excerpt_length = 20)
	{
		$comments = get_comments(array(
			'number' => $num_comments,
			'status' => 'approve',
			'post_status' => 'publish',
			'type' => 'comment', // Chỉ comment, không ping/trackback
		));

		if ($comments): ?>
			<div class="recent-comments-list">
				<?php
				$i = 1;
				foreach ($comments as $comment):
					$comment_excerpt = wp_trim_words(strip_tags($comment->comment_content), $excerpt_length / 4, '...'); // Trim words thay vì ký tự để tự nhiên hơn
					?>
					<div class="comment-item">
						<span class="item-number"><?php echo $i; ?></span>
						<div class="item-content">
							<a href="<?php echo get_comment_link($comment); ?>"
								class="item-title"><?php echo esc_html($comment_excerpt); ?></a>
						</div>
					</div>
					<?php
					$i++;
				endforeach;
				?>
			</div>
		<?php else: ?>
			<p>Không có bình luận mới.</p>
		<?php endif;
	}
}

// Fallback: Nếu sidebar-12 rỗng, auto hiển thị recent comments (tùy chọn)
function fallback_recent_comments_if_empty()
{
	if (is_active_sidebar('sidebar-12')) {
		dynamic_sidebar('sidebar-12');
	} else {
		the_widget('Recent_Comments_Sidebar_Widget', array('title' => 'Comments', 'num_comments' => 5, 'excerpt_length' => 20));
	}
}


// Custom Widget cho Categories (bullet vàng, text xanh, giống ảnh)
class Categories_Sidebar_Widget extends WP_Widget
{

	// Constructor
	public function __construct()
	{
		$widget_ops = array(
			'classname' => 'categories_sidebar_widget',
			'description' => __('Hiển thị danh sách categories với bullet vàng và style tùy chỉnh. Có thể chỉnh số lượng và tiêu đề.', 'twentytwenty'),
		);
		parent::__construct(
			'categories_sidebar_widget',
			__('Categories Sidebar (Custom)', 'twentytwenty'),
			$widget_ops
		);
	}

	// Output HTML của widget
	public function widget($args, $instance)
	{
		// Lấy options
		$title = !empty($instance['title']) ? $instance['title'] : 'Categories';
		$num_categories = !empty($instance['num_categories']) ? absint($instance['num_categories']) : 0; // 0 = all

		echo $args['before_widget'];

		// Title
		if (!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
		}

		// Gọi function hiển thị categories
		display_categories_sidebar_custom($num_categories);

		echo $args['after_widget'];
	}

	// Form chỉnh sửa trong WP Admin
	public function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : 'Categories';
		$num_categories = !empty($instance['num_categories']) ? absint($instance['num_categories']) : 0;
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
				<?php esc_attr_e('Tiêu đề:', 'twentytwenty'); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
				name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
				value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('num_categories')); ?>">
				<?php esc_attr_e('Số categories (0 = tất cả):', 'twentytwenty'); ?>
			</label>
			<input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('num_categories')); ?>"
				name="<?php echo esc_attr($this->get_field_name('num_categories')); ?>" type="number" step="1" min="0" max="50"
				value="<?php echo esc_attr($num_categories); ?>" size="3">
		</p>
		<?php
	}

	// Update options
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		$instance['num_categories'] = (!empty($new_instance['num_categories'])) ? absint($new_instance['num_categories']) : 0;
		return $instance;
	}
}

// Đăng ký widget
function register_categories_sidebar_widget()
{
	register_widget('Categories_Sidebar_Widget');
}
add_action('widgets_init', 'register_categories_sidebar_widget');

// Function hỗ trợ hiển thị custom categories (bullet vàng, text xanh)
if (!function_exists('display_categories_sidebar_custom')) {
	function display_categories_sidebar_custom($num = 0)
	{
		$args = array(
			'orderby' => 'name',
			'order' => 'ASC',
			'echo' => 0, // Return string
			'show_count' => 0,
			'hierarchical' => false,
			'number' => $num, // Số lượng nếu >0
			'title_li' => '', // Không title
		);
		$categories_list = wp_list_categories($args);

		if ($categories_list): ?>
			<ul class="custom-categories-list">
				<?php echo $categories_list; ?>
			</ul>
		<?php else: ?>
			<p>Không có categories.</p>
		<?php endif;
	}
}

// Fallback: Nếu sidebar-9 rỗng, auto hiển thị categories
function fallback_categories_if_empty()
{
	if (is_active_sidebar('sidebar-9')) {
		dynamic_sidebar('sidebar-9');
	} else {
		the_widget('Categories_Sidebar_Widget', array('title' => 'Categories', 'num_categories' => 0));
	}
}



// Custom Widget cho Recent Posts (giống ảnh: date box, nền xanh nhạt, button dưới)
class Recent_Posts_Sidebar_Widget extends WP_Widget
{

	public function __construct()
	{
		$widget_ops = array(
			'classname' => 'recent_posts_sidebar_widget',
			'description' => __('Hiển thị recent posts với date box (day sup month / year), nền xanh nhạt cho item, và button Xem tất cả. Chỉnh số lượng và title trong admin.', 'twentytwenty'),
		);
		parent::__construct(
			'recent_posts_sidebar_widget',
			__('Recent Posts (Giống Ảnh)', 'twentytwenty'),
			$widget_ops
		);
	}

	public function widget($args, $instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : 'TIN TỨC MỚI';
		$num_posts = !empty($instance['num_posts']) ? absint($instance['num_posts']) : 3;

		echo $args['before_widget'];
		if (!empty($title)) {
			echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
		}

		// Query và output giống ảnh
		$recent_posts_query = new WP_Query(array(
			'posts_per_page' => $num_posts,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
		));

		if ($recent_posts_query->have_posts()): ?>
			<ul class="recent-custom-list">
				<?php while ($recent_posts_query->have_posts()):
					$recent_posts_query->the_post(); ?>
					<li class="recent-item">
						<div class="date-box">
							<div class="day-year">
								<span class="day"><?php echo get_the_date('d'); ?></span>
								<sup class="year"><?php echo get_the_date('y'); ?></sup>
							</div>
							<div class="month"><?php echo get_the_date('m'); ?></div>
						</div>
						<div class="title-box">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</div>
					</li>
				<?php endwhile;
				wp_reset_postdata(); ?>
			</ul>
			<div class="view-all">
				<a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">XEM TẤT CẢ TIN TỨC</a>
			</div>
		<?php else: ?>
			<p>Không có bài viết mới.</p>
		<?php endif;

		echo $args['after_widget'];
	}

	public function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : 'TIN TỨC MỚI';
		$num_posts = !empty($instance['num_posts']) ? absint($instance['num_posts']) : 3;
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Tiêu đề:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
				name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
				value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('num_posts')); ?>">Số bài viết:</label>
			<input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('num_posts')); ?>"
				name="<?php echo esc_attr($this->get_field_name('num_posts')); ?>" type="number" step="1" min="1" max="10"
				value="<?php echo esc_attr($num_posts); ?>" size="3">
		</p>
		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		$instance['num_posts'] = (!empty($new_instance['num_posts'])) ? absint($new_instance['num_posts']) : 3;
		return $instance;
	}
}

// Đăng ký widget
function register_recent_posts_sidebar_widget()
{
	register_widget('Recent_Posts_Sidebar_Widget');
}
add_action('widgets_init', 'register_recent_posts_sidebar_widget');



// =======================
// CUSTOM WIDGET: PAGE LIST FOR SIDEBAR 13
// =======================
class Page_List_Widget extends WP_Widget
{

	function __construct()
	{
		parent::__construct(
			'page_list_widget',
			__('Danh sách Trang (Sidebar #13)', 'twentytwenty'),
			['description' => __('Hiển thị danh sách các trang dạng list có hình đại diện.', 'twentytwenty')]
		);
	}

	function widget($args, $instance)
	{
		echo $args['before_widget'];
		if (!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}

		// Lấy danh sách các trang
		$pages = get_pages([
			'sort_column' => 'menu_order',
			'sort_order' => 'asc',
			'post_status' => 'publish',
			'number' => 5, // số lượng trang muốn hiển thị
		]);

		if (!empty($pages)) {
			echo '<div class="page-list-widget">';
			foreach ($pages as $page) {
				$thumbnail = get_the_post_thumbnail_url($page->ID, 'medium');
				if (!$thumbnail) {
					$thumbnail = 'https://via.placeholder.com/300x150?text=No+Image';
				}
				$excerpt = $page->post_excerpt ? $page->post_excerpt : wp_trim_words($page->post_content, 20);

				echo '<div class="page-item">';
				echo '<a href="' . get_permalink($page->ID) . '" class="page-thumb"><img src="' . esc_url($thumbnail) . '" alt=""></a>';
				echo '<div class="page-info">';
				echo '<h4 class="page-title"><a href="' . get_permalink($page->ID) . '">' . esc_html($page->post_title) . '</a></h4>';
				echo '<p class="page-excerpt">' . esc_html($excerpt) . '</p>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		} else {
			echo '<p>Chưa có trang nào.</p>';
		}

		echo $args['after_widget'];
	}

	function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : __('Trang mới nhất', 'twentytwenty');
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Tiêu đề:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
				name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = [];
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
	}
}

function register_page_list_widget()
{
	register_widget('Page_List_Widget');
}
add_action('widgets_init', 'register_page_list_widget');



// =======================
// CUSTOM WIDGET: Comment LIST FOR SIDEBAR 14
// =======================

class Comment_Style14_Widget extends WP_Widget
{
	function __construct()
	{
		parent::__construct(
			'comment_style14_widget',
			__('Comments (Sidebar #14)', 'twentytwenty'),
			['description' => __('Hiển thị bình luận dạng khối có hỗ trợ trả lời (threaded).', 'twentytwenty')]
		);
	}

	function widget($args, $instance)
	{
		echo $args['before_widget'];

		$title = !empty($instance['title']) ? $instance['title'] : __('Bình luận mới', 'twentytwenty');
		echo $args['before_title'] . esc_html($title) . $args['after_title'];

		// Lấy bình luận cha (comment_depth = 1)
		$comments = get_comments([
			'number' => 10,
			'status' => 'approve',
			'post_status' => 'publish',
			'parent' => 0,
		]);

		if (!empty($comments)) {
			echo '<div class="container comment-style14-wrapper">';
			echo '<div class="row">';
			foreach ($comments as $comment) {
				$this->render_comment($comment);
			}
			echo '</div>';
			echo '</div>';
		} else {
			echo '<p>Chưa có bình luận nào.</p>';
		}

		echo $args['after_widget'];
	}

	private function render_comment($comment, $is_top_level = true)
	{
		$avatar = get_avatar_url($comment->comment_author_email, ['size' => 50]);
		$content = esc_html($comment->comment_content); // Full content như mockup
		$author = esc_html($comment->comment_author);

		if ($is_top_level) {
			echo '<div class="media comment-box">';
		} else {
			echo '<div class="media">';
		}

		echo '<div class="media-left">';
		echo '<a href="#">';
		echo '<img class="img-responsive user-photo" src="' . esc_url($avatar) . '" alt="' . esc_attr($author) . '">';
		echo '</a>';
		echo '</div>';

		echo '<div class="media-body">';
		echo '<h4 class="media-heading">' . $author . '</h4>';
		echo '<p>' . $content . '</p>';

		// Lấy các reply (bình luận con)
		$replies = get_comments([
			'parent' => $comment->comment_ID,
			'status' => 'approve',
			'order' => 'ASC',
		]);

		if ($replies) {
			foreach ($replies as $reply) {
				$this->render_comment($reply, false);
			}
		}

		echo '</div>'; // media-body
		echo '</div>'; // media
	}

	function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : __('Bình luận mới', 'twentytwenty'); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Tiêu đề:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
				name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = [];
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
	}
}

function register_comment_style14_widget()
{
	register_widget('Comment_Style14_Widget');
}
add_action('widgets_init', 'register_comment_style14_widget');

// Enqueue Bootstrap nếu chưa có (thêm vào functions.php của theme)
function enqueue_bootstrap_for_comment_widget()
{
	wp_enqueue_style('bootstrap-css', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css');
	wp_enqueue_script('jquery');
	wp_enqueue_script('bootstrap-js', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js', array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap_for_comment_widget');


// Module 15: Latest News Timeline
class Latest_News_Widget extends WP_Widget
{
	function __construct()
	{
		parent::__construct(
			'latest_news_widget',
			__('Latest News (Sidebar #15)', 'twentytwenty'),
			['description' => __('Hiển thị tin tức mới nhất dạng timeline Bootstrap.', 'twentytwenty')]
		);
	}

	function widget($args, $instance)
	{
		echo $args['before_widget'];

		$title = !empty($instance['title']) ? $instance['title'] : __('Latest News', 'twentytwenty');
		$num_posts = !empty($instance['num_posts']) ? absint($instance['num_posts']) : 5;

		echo $args['before_title'] . esc_html($title) . $args['after_title'];

		// Lấy bài viết mới nhất
		$posts = get_posts([
			'numberposts' => $num_posts,
			'post_status' => 'publish',
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DESC',
		]);

		if (!empty($posts)) {
			echo '<div class="container mt-3 mb-3">';
			echo '<div class="row"><div class="col-12">';
			echo '<ul class="timeline">';

			foreach ($posts as $post) {
				$post_link = get_permalink($post->ID);
				$post_title = get_the_title($post->ID);
				$post_date = get_the_date('j F, Y', $post->ID);
				$post_excerpt = wp_trim_words(strip_tags(get_the_content(null, false, $post)), 25, '...');

				echo '<li>';
				echo '<a target="_blank" href="' . esc_url($post_link) . '">' . esc_html($post_title) . '</a>';
				echo '<a href="' . esc_url($post_link) . '" class="float-right">' . esc_html($post_date) . '</a>';
				echo '<p>' . esc_html($post_excerpt) . '</p>';
				echo '</li>';
			}

			echo '</ul>';
			echo '</div></div></div>';
		} else {
			echo '<p>Chưa có tin tức nào.</p>';
		}

		echo $args['after_widget'];
	}

	function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : __('Latest News', 'twentytwenty');
		$num_posts = !empty($instance['num_posts']) ? $instance['num_posts'] : 5;
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Tiêu đề:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
				name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('num_posts'); ?>">Số bài viết hiển thị:</label>
			<input class="small-text" id="<?php echo $this->get_field_id('num_posts'); ?>"
				name="<?php echo $this->get_field_name('num_posts'); ?>" type="number"
				value="<?php echo esc_attr($num_posts); ?>" min="1" max="20">
		</p>
		<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = [];
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['num_posts'] = (!empty($new_instance['num_posts'])) ? absint($new_instance['num_posts']) : 5;
		return $instance;
	}
}

// Đăng ký widget
function register_latest_news_widget()
{
	register_widget('Latest_News_Widget');
}
add_action('widgets_init', 'register_latest_news_widget');