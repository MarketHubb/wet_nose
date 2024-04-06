<?php

/**
 * Required
 */
// require_once(__DIR__ . '/../../../vendor/autoload.php');
// $stripe = new \Stripe\StripeClient("sk_test_51O2cExBQlCuWXXZWzpKBUpOlG4LLe9tDBH659wY5uDL1jArOUoZwTQ4sYHmzdabgo2HR8ehVhvuDeVAicbHgliKe00rObSKXLQ");
include 'includes/ajax.php';
include 'includes/woocommerce.php';
include 'includes/hero.php';
include 'includes/helpers.php';
include 'includes/customer.php';
include 'includes/plugins.php';
include 'includes/hooks.php';
include 'includes/content.php';
include 'includes/ingredients.php';
include 'includes/recipes.php';

/**
 * Theme setup.
 */
function tailpress_setup()
{
	add_theme_support('title-tag');

	register_nav_menus(
		array(
			'primary' => __('Primary Menu', 'tailpress'),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	add_theme_support('custom-logo');
	add_theme_support('post-thumbnails');

	add_theme_support('align-wide');
	add_theme_support('wp-block-styles');

	add_theme_support('editor-styles');
	add_editor_style('css/editor-style.css');
}

add_action('after_setup_theme', 'tailpress_setup');

/**
 * Enqueue theme assets.
 */
function tailpress_enqueue_scripts()
{
	$theme = wp_get_theme();

	wp_enqueue_style('tailpress', tailpress_asset('css/app.css'), array(), $theme->get('Version'));
	wp_enqueue_script('tailpress', tailpress_asset('js/app.js'), array(), $theme->get('Version'));
}

add_action('wp_enqueue_scripts', 'tailpress_enqueue_scripts');

// User:: Login + Dashboard
function enqueue_account_styles()
{
	if (is_page([680, 698])) {
		wp_enqueue_style('customer-account', get_template_directory_uri() . '/css/customer-account.css');
	}
}
add_action('wp_enqueue_scripts', 'enqueue_account_styles');

// Site:: 

function custom_styles_and_scripts()
{
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js', array(), null, true);
	wp_enqueue_script('scroller-scripts', get_template_directory_uri() . '/js/scroller.js', array('jquery'), null, true);
	wp_enqueue_style('global', get_template_directory_uri() . '/css/global.css', [], "1.0");
	wp_enqueue_style('scroller-styles', get_template_directory_uri() . '/css/scroller.css', [], "1.0");
	wp_enqueue_style('gravity-styles', get_template_directory_uri() . '/css/gravity.css', ['global'], "1.0");
	// wp_enqueue_style('web-fonts', 'https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&family=Klee+One:wght@600&family=Libre+Baskerville:wght@400;700&family=Source+Sans+3:wght@400;600&display=swap', [], "1.0");
	wp_enqueue_style('web-fonts', 'https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Source+Sans+3:wght@400;600&display=swap', [], "1.0");
	wp_enqueue_style('web-fonts-special', 'https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&display=swap', [], "1.0");

	// Testing (Form and JS logic)
	if (is_page(707)) {
		wp_enqueue_script('testing-scripts', get_template_directory_uri() . '/js/testing.js', array('jquery'), null, true);
	}
	if (!is_singular('ingredient') && !is_singular('recipe') && !is_page(707)) {
		wp_enqueue_script('global-scripts', get_template_directory_uri() . '/js/global.js', array('jquery'), null, true);
		wp_enqueue_script('doggo-profile-scripts', get_template_directory_uri() . '/js/doggo-profile.js', array('jquery'), null, true);
	}

	if (is_singular('recipe')) {
		wp_enqueue_style('recipe-calc-styles', get_template_directory_uri() . '/css/recipe-calc.css', ['global'], "1.0");
		// wp_enqueue_script('doggo-profile-scripts', get_template_directory_uri() . '/js/doggo-profile.js', array('jquery'), null, true);
		wp_enqueue_script('recipe-calc-scripts', get_template_directory_uri() . '/js/recipe-calc.js', array('jquery'), null, true);
	}

	// if (!is_admin()) {
	// 	wp_enqueue_script('stripe', 'https://js.stripe.com/v3/');
	// }
}

add_action('wp_enqueue_scripts', 'custom_styles_and_scripts');

// Custom admin styles and scripts
function admin_style()
{
   	// wp_enqueue_style( 'tailpress', tailpress_asset( 'css/app.css' ));
	wp_enqueue_style('admin', get_template_directory_uri() . '/css/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');

/**
 * Get asset path.
 *
 * @param string  $path Path to asset.
 *
 * @return string
 */
function tailpress_asset($path)
{
	if (wp_get_environment_type() === 'production') {
		return get_stylesheet_directory_uri() . '/' . $path;
	}

	return add_query_arg('time', time(),  get_stylesheet_directory_uri() . '/' . $path);
}

/**
 * Adds option 'li_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The current item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function tailpress_nav_menu_add_li_class($classes, $item, $args, $depth)
{
	if (isset($args->li_class)) {
		$classes[] = $args->li_class;
	}

	if (isset($args->{"li_class_$depth"})) {
		$classes[] = $args->{"li_class_$depth"};
	}

	return $classes;
}

add_filter('nav_menu_css_class', 'tailpress_nav_menu_add_li_class', 10, 4);

/**
 * Adds option 'submenu_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The current item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function tailpress_nav_menu_add_submenu_class($classes, $args, $depth)
{
	if (isset($args->submenu_class)) {
		$classes[] = $args->submenu_class;
	}

	if (isset($args->{"submenu_class_$depth"})) {
		$classes[] = $args->{"submenu_class_$depth"};
	}

	return $classes;
}

add_filter('nav_menu_submenu_css_class', 'tailpress_nav_menu_add_submenu_class', 10, 3);
