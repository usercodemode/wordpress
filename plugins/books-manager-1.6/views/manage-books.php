<h2>Manage Books</h2>

<?php

global $wpdb;
$books = $wpdb->get_results("SELECT * FROM " . my_book_table());

if ($books) {
    echo '<table>';
    echo '<tr><th>ID</th><th>Name</th><th>Author</th><th>Action</th></tr>';

    foreach ($books as $book) {
        echo '<tr>';
        echo '<td>' . $book->id . '</td>';
        echo '<td>' . $book->name . '</td>';
        echo '<td>' . $book->author . '</td>';
        echo '<td><a href="' . admin_url('admin.php?page=manage-books&action=edit&book_id=' . $book->id) . '">Edit</a> | <a href="' . wp_nonce_url(admin_url('admin.php?page=manage-books&action=delete&book_id=' . $book->id), 'delete_book_' . $book->id) . '">Delete</a></td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '<p>No books found.</p>';
}

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['book_id'])) {

    $book_id = intval($_GET['book_id']); // Sanitize book ID
    $book = $wpdb->get_row("SELECT * FROM " . my_book_table() . " WHERE id = $book_id");

    if ($book) {
        // Pre-fill form with existing data
        echo '<h2>Edit Book</h2>';
        // ... (form with pre-filled data based on $book) ...

        echo '<form method="post">
              <input type="hidden" name="book_id" value="' . $book->id . '">
    
              <label for="name">Book Name:</label>
              <input type="text" name="name" id="name" value="' . $book->name . '" required><br>
    
              <label for="author">Author:</label>
              <input type="text" name="author" id="author" value="' . $book->author . '" required><br>
    
              <label for="about">About the Book:</label>
              <textarea name="about" id="about" rows="5">' . $book->about . '</textarea><br>
    
              <label for="book_image">Book Image URL (optional):</label>
              <input type="url" name="book_image" id="book_image" value="' . $book->book_image . '"><br>
    
              <input type="submit" name="submit" value="Update Book">
              </form>';
    } else {
        echo '<p>Book not found.</p>';
    }
}

// Check if update form is submitted
if (isset($_POST['submit']) && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']); // Sanitize book ID
    // Sanitize user input (important!)
    $name = sanitize_text_field($_POST['name']);
    $author = sanitize_text_field($_POST['author']);
    $about = wp_kses($_POST['about'], wp_allowed_protocols()); // Allow basic HTML formatting
    $book_image = sanitize_text_field($_POST['book_image']); // Sanitize image URL (if applicable)

    // Prepare data for update
    $data = array(
        'name' => $name,
        'author' => $author,
        'about' => $about,
        'book_image' => $book_image,
    );

    // Update data in database
    global $wpdb;
    $updated = $wpdb->update(my_book_table(), $data, array('id' => $book_id));

    // Handle success or failure
    if ($updated !== false) {
        echo '<p>Book updated successfully!</p>';
        wp_redirect(admin_url('admin.php?page=manage-books'));
        exit;
    } else {
        echo '<p>Error updating book. Please try again.</p>';
    }
}

// Check if delete action is requested
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['book_id']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_book_' . $_GET['book_id'])) {

    $book_id = intval($_GET['book_id']); // Sanitize book ID

    // Delete book from database
    global $wpdb;
    $deleted = $wpdb->delete(my_book_table(), array('id' => $book_id));

    // Redirect back to manage books page with a message

    if ($deleted !== false) {
        wp_redirect(admin_url('admin.php?page=manage-books&message=book_deleted'));
    } else {
        wp_redirect(admin_url('admin.php?page=manage-books&message=delete_failed'));
    }
    exit;
}
