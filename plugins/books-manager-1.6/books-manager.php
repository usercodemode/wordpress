<?php

/*
Plugin Name: Books Manager
Plugin URI: http://localhost:8000 
Description: This plugin helps you manage your book collection.
Version: 1.6
Author: [YOUR_NAME]
Author URI: http://localhost:8000?author
*/

// Don't load anything further if WordPress is not loaded
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Some useful constants
define('PLUGIN_DIR_PATH', plugin_dir_path(__FILE__)); // gives path eg. /folder1/folder2
define('PLUGIN_URL', plugin_dir_url(__FILE__)); // gives url eg. http://localhost:8000
define('PLUGIN_VERSION', '1.0');

// Implementing code for adding menu with icon and title Book Manager

// Function to register the admin menu
function books_manager_menu() {
  // Top-level menu
  $books_manager_page = add_menu_page(
    'Books Manager', // Page Title
    'Books Manager', // Menu Text
    'manage_options', // Capability
    'books-manager', // Unique menu slug
    'add_books_function', // Function to call when menu is clicked
    'dashicons-book', // Menu icon
    6 // Menu position
  );

  // Submenus 

  add_submenu_page(
    'books-manager', // Parent slug (from top-level menu)
    'Add a New Book', // Submenu title
    'Add Books', // Submenu text displayed on menu
    'manage_options', // Capability
    'books-manager', // Keeping slug same as main menu will make extra submenu with name 'Books Manager' disappear
    'add_books_function' // Function to execute
  );

  add_submenu_page(
    'books-manager', // Parent slug
    'Manage Your Books', // Submenu title
    'Manage Books', // Submenu text displayed on menu
    'manage_options', // Capability
    'manage-books', // Unique submenu slug (points to manage-books.php)
    'manage_books_function' // Function to execute
  );
}

function add_books_function()
{
    include_once PLUGIN_DIR_PATH . '/views/add-books.php';
}

function manage_books_function()
{

    include_once PLUGIN_DIR_PATH . '/views/manage-books.php';
}

// Add our function to the admin_menu hook
add_action( 'admin_menu', 'books_manager_menu' );

// Adding css and javascript file 
function custom_plugin_assets()
{
    // css and js files
    wp_enqueue_style('booksmanager_style', PLUGIN_URL.'assets/css/style.css', '', PLUGIN_VERSION);
    wp_enqueue_script('booksmanager_script', PLUGIN_URL.'assets/js/script.js', '', PLUGIN_VERSION, false);
}

add_action('init', 'custom_plugin_assets');

// Table name
function my_book_table() {
  global $wpdb;
  return $wpdb->prefix . "books_manager"; //wp_my_books
}

// Generate table function
function my_book_generates_table_script() {

  global $wpdb;
  require_once ABSPATH . 'wp-admin/includes/upgrade.php';

  $sql = "  CREATE TABLE `" . my_book_table() . "` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `about` longtext,
  `book_image` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

dbDelta($sql);
}

// Create table on plugin activation
register_activation_hook(__FILE__, "my_book_generates_table_script");

// Drop table function
function drop_table_plugin_books() {
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS " . my_book_table());
}

// Drop table on plugin deactivation
register_deactivation_hook(__FILE__, "drop_table_plugin_books");
// register_uninstall_hook(__FILE__,"drop_table_plugin_books");

?>