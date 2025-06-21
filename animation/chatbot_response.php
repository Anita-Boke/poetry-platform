<?php
$servername = "localhost"; // Change if your server is different
$username = "root"; 
$password = ""; 
$dbname = "poetry_db"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the message from the user
$user_message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';

// Logic to respond to the user's message
function getResponse($message) {
    if (stripos($message, "poem") !== false) {
        return "You can explore various poems in our collection!";
    } elseif (stripos($message, "submit") !== false) {
        return "You can submit your poems using the form on the publication page.";
    } else {
        return "I'm sorry, I didn't understand that. Please ask about poems.";
    }
}

// Get response
$response = getResponse($user_message);
echo $response; // Send the response back to the chatbot

$conn->close(); // Close the database connection
?>