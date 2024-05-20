<?php

/*
Plugin Name: Books Manager
Plugin URI: http://localhost:8000 
Description: This plugin helps you manage your book collection.
Version: 1.1
Author: [YOUR_NAME]
Author URI: http://localhost:8000?author
*/

// Don't load anything further if WordPress is not loaded
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Implementing code for adding menu with icon and title Book Manager

// Function to register the admin menu
function books_manager_menu() {
  add_menu_page(
    'Books Manager', // Page Title
    'Books Manager', // Menu Text
    'manage_options', // Capability (only admins can access)
    'books-manager', // Unique menu slug
    '', // Function to call when menu is clicked (left empty for now)
    'dashicons-book', // Menu icon (dashicons available from WordPress)
    6 // Menu position (can be adjusted based on other menus)
  );
}

// Add our function to the admin_menu hook
add_action( 'admin_menu', 'books_manager_menu' );

?>