<?php
/**
 * oneanima functions and definitions
 *
 * @package oneanima
 */

/**
 * Clean up the output of the <head> block
 */
function oneanima_clean_header() {
  remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
  remove_action( 'wp_head', 'feed_links', 2 );       // Display the links to the general feeds: Post and Comment Feed
  remove_action( 'wp_head', 'rsd_link' );            // Display the link to the Really Simple Discovery service endpoint, EditURI link
  remove_action( 'wp_head', 'wlwmanifest_link' );    // Display the link to the Windows Live Writer manifest file.
  remove_action( 'wp_head', 'index_rel_link' );      // index link
  remove_action( 'wp_head', 'wp_generator' );        // Display the XHTML generator that is generated on the wp_head hook, WP version
}
add_action( 'init', 'oneanima_clean_header' );

function oneanima_nofollow_cat_posts($text) {
global $post;
        if( in_category(1) ) { // SET CATEGORY ID HERE
                $text = stripslashes(wp_rel_nofollow($text));
        }
        return $text;
}
add_filter('the_content', 'oneanima_nofollow_cat_posts');

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
  $content_width = 640; /* pixels */

if ( ! function_exists( 'oneanima_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function oneanima_setup() {

  /**
   * Make theme available for translation
   * Translations can be filed in the /languages/ directory
   * If you're building a theme based on _s, use a find and replace
   * to change '_s' to the name of your theme in all the template files
   */
  load_theme_textdomain( 'oneanima', get_template_directory() . '/languages' );

  /**
   * Add default posts and comments RSS feed links to head
   */
  add_theme_support( 'automatic-feed-links' );

  /**
   * This theme uses wp_nav_menu() in one location.
   */
  register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'oneanima' ),
  ) );

  /**
   * Enable support for Post Formats
   */
  add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

  // Switches default core markup for search form, comment form, and comments
  // to output valid HTML5.
  add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
}
endif; // oneanima_setup
add_action( 'after_setup_theme', 'oneanima_setup' );

/**
 * Enqueue scripts and styles
 */
function oneanima_scripts() {
  wp_enqueue_style( 'oneanima-style', get_stylesheet_uri() . '/stylesheets/style.css', array('ttfmake-main-style') );
}
add_action( 'wp_enqueue_scripts', 'oneanima_scripts' );

function oneanima_add_body_class( $classes ) {
  global $post;
  if ( isset( $post ) ) {
      $classes[] = $post->post_type . '-' . $post->post_name;
  }
  if ( !is_front_page() ) {
    $classes[] = 'not-home';
  }
  $classes[] = 'oneanima-custom';
  return $classes;
}

add_filter( 'body_class', 'oneanima_add_body_class' );