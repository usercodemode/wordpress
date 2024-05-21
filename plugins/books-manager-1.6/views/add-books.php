<h2>Add a New Book</h2>
<?php
// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize user input (important!)
    $name = sanitize_text_field($_POST['name']);
    $author = sanitize_text_field($_POST['author']);
    $about = wp_kses($_POST['about'], wp_allowed_protocols()); // Allow basic HTML formatting
    $book_image = sanitize_text_field($_POST['book_image']); // Sanitize image URL (if applicable)

    // Prepare data for insertion
    $data = array(
        'name' => $name,
        'author' => $author,
        'about' => $about,
        'book_image' => $book_image,
    );

    // Insert data into database
    global $wpdb;
    $inserted = $wpdb->insert(my_book_table(), $data);

    // Handle success or failure
    if ($inserted !== false) {
        echo '<p>Book added successfully!</p>';
    } else {
        echo '<p>Error adding book. Please try again.</p>';
    }
}


?>

<form method="post">
    <label for="name">Book Name:</label>
    <input type="text" name="name" id="name" required><br>

    <label for="author">Author:</label>
    <input type="text" name="author" id="author" required><br>

    <label for="about">About the Book:</label>
    <textarea name="about" id="about" rows="5"></textarea><br>

    <label for="book_image">Book Image URL (optional):</label>
    <input type="url" name="book_image" id="book_image"><br>

    <input type="submit" name="submit" value="Add Book">
</form>