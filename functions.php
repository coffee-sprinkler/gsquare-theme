<?php
/**
 * gsquare-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package gsquare-theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function gsquare_theme_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on gsquare-theme, use a find and replace
		* to change 'gsquare-theme' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'gsquare-theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'gsquare-theme' ),
		)
	);

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
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'gsquare_theme_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'gsquare_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gsquare_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'gsquare_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'gsquare_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function gsquare_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'gsquare-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'gsquare-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'gsquare_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function gsquare_theme_scripts() {
	wp_enqueue_style( 'gsquare-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	
	wp_enqueue_style( 'gsquare-font-style', 'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap');
	wp_enqueue_style( 'gsquare-main-style', get_template_directory_uri() . '/css/main.css');

	wp_style_add_data( 'gsquare-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'gsquare-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	
	wp_enqueue_script( 'toastify', 'https://cdn.jsdelivr.net/npm/toastify-js', array(), '1.11.0', true);

	wp_enqueue_style( 'toastify', 'https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css' );

	wp_enqueue_script( 'gsquare-main-js', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.0', array('strategy' => 'defer',) );

	// Pass the AJAX URL to script.js
	wp_localize_script('gsquare-main-js', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gsquare_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Handle donation process
function process_donation_form() {
	parse_str($_POST['form_data'], $form_data);

	$first_name = sanitize_text_field($form_data['firstName']);
	$last_name = sanitize_text_field($form_data['lastName']);
	$email = sanitize_email($form_data['email']);
	$phone_number = sanitize_text_field($form_data['phone']);
	$donated_amount = floatval(preg_replace("/[^0-9.]/", "", $form_data['donated-amount']));
	$payment_method = sanitize_text_field($form_data['payment-method']);

	// Capitalize first and last names
  $first_name = ucfirst(strtolower($first_name));
  $last_name = ucfirst(strtolower($last_name));

	$post_content = 'Donation Amount: ' .  number_format($donated_amount, 2);

	$post_data = array(
			'post_title'   => $first_name . ' ' . $last_name . ' - $' .$donated_amount,
			'post_content' => $post_content,
			'post_status'  => 'publish',
			'post_type'    => 'donation',
	);

	$post_id = wp_insert_post($post_data);

	if (!is_wp_error($post_id)) {
		update_field('user_info_first_name', $first_name, $post_id);
		update_field('user_info_last_name', $last_name, $post_id);
		update_field('user_info_email', $email, $post_id);
		update_field('user_info_phone_number', $phone_number, $post_id);
		update_field('donated_money', $donated_amount, $post_id);
		update_field('payment_method', $payment_method, $post_id);

		echo 'Donation created successfully!';
	} else {
		error_log('Error creating post: ' . $post_id->get_error_message());
		echo 'Error creating donation!';
	}

	die();
}

add_action('wp_ajax_process_donation_form', 'process_donation_form');
add_action('wp_ajax_nopriv_process_donation_form', 'process_donation_form');

// Get updated dynamic donation content
add_action('wp_ajax_get_dynamic_donation_content', 'get_dynamic_donation_content');
add_action('wp_ajax_nopriv_get_dynamic_donation_content', 'get_dynamic_donation_content');

function get_dynamic_donation_content() {
	// Calculate donations
	$donation_args = array(
			'post_type'      => 'donation', 
			'posts_per_page' => -1, 
	);

	$donation_query = new WP_Query($donation_args);

	$donation_post_total = 0;

	if ($donation_query->have_posts()) :
			while ($donation_query->have_posts()) : $donation_query->the_post();
					$donated_amount = get_field('donated_money');
					$donation_post_total += $donated_amount;
			endwhile;

			wp_reset_postdata();

			// Format the total amount dynamically
			$formatted_total = format_donation_total($donation_post_total);
			
			$formatted_donation_needed = format_amount($donation_needed);

			echo $formatted_total;
	endif;

	wp_die();
}

// Function to format the donation total
function format_donation_total($total_amount) {
    // Check if the total exceeds 1 million
    if ($total_amount >= 1000000) {
        $formatted_total = number_format($total_amount / 1000000, 3) . ' million';
    } else {
        $formatted_total = number_format($total_amount);
    }

    return $formatted_total;
}

function format_amount($amount) {
	if ($amount >= 1000000) {
		$million = floor($amount / 1000000);
		$remainder = $amount % 1000000;
		$formatted_amount = '$' . ($remainder > 0 ? number_format($million) . ' million ' : number_format($million) . ' million');
	} else {
			$formatted_amount = '$' . number_format($amount);
	}
	return $formatted_amount;
}
