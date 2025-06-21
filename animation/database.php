<?php
include 'connect.php'; // Include the connection script

// SQL to create database
$sql = "CREATE DATABASE poetry_db";

if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>