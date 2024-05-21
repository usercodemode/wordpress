<?php

/*
Plugin Name: Books Manager
Plugin URI: http://localhost:8000 
Description: This plugin helps you manage your book collection.
Version: 1.2
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
    '', // Function to call when menu is clicked (left empty)
    'dashicons-book', // Menu icon
    6 // Menu position
  );

  // Submenus
  add_submenu_page(
    'books-manager', // Parent slug (from top-level menu)
    'Add a New Book', // Submenu title
    'Add Books', // Submenu text displayed on menu
    'manage_options', // Capability
    'add-books', // Unique submenu slug (points to add-books.php)
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

?>