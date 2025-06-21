<?php
$servername = "localhost"; // Change if your server is different
$username = "root";        // Your MySQL username
$password = "";            // Your MySQL password
$dbname = "poetry_db";     // Database name

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Use the created database
$conn->select_db($dbname);

// SQL to create tables
$tableQueries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS poems (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        image VARCHAR(255) DEFAULT NULL, -- Optional image path
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS comments (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        poem_id INT(11) NOT NULL,
        username VARCHAR(50) NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (poem_id) REFERENCES poems(id) ON DELETE CASCADE
    )",

    "CREATE TABLE IF NOT EXISTS login_attempts (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) NOT NULL,
        login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45) NOT NULL,
        success BOOLEAN NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",

    "CREATE TABLE IF NOT EXISTS challenges (
        id INT AUTO_INCREMENT PRIMARY KEY,
        prompt TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS challenge_responses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        challenge_id INT NOT NULL,
        username VARCHAR(255) NOT NULL,
        response TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
    )"
];

// Execute each table creation query
foreach ($tableQueries as $tableQuery) {
    if ($conn->query($tableQuery) === TRUE) {
        echo "Table created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Insert challenges if the table is empty
$insertChallenges = [
    "INSERT INTO challenges (prompt) VALUES ('Write a poem about the changing seasons.')",
    "INSERT INTO challenges (prompt) VALUES ('Create a haiku about your favorite place.')",
    "INSERT INTO challenges (prompt) VALUES ('Compose a poem using the theme of love.')",
    "INSERT INTO challenges (prompt) VALUES ('Write a poem that includes the word \"dream\".')",
    "INSERT INTO challenges (prompt) VALUES ('Create a poem inspired by a piece of art you love.')"
];

// Check if the challenges table is empty before inserting
$checkChallenges = "SELECT COUNT(*) as count FROM challenges";
$result = $conn->query($checkChallenges);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    foreach ($insertChallenges as $challengeQuery) {
        if ($conn->query($challengeQuery) === TRUE) {
            echo "Challenge inserted successfully.<br>";
        } else {
            echo "Error inserting challenge: " . $conn->error . "<br>";
        }
    }
} else {
    echo "Challenges already exist in the database.<br>";
}

// Close the connection
$conn->close();
?>