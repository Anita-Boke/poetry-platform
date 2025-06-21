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

// Handle user signup
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Insert new user into the users table
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Sign up successful!');
                window.location.href = 'home.php'; // Redirect to home page
              </script>";
        exit; // Stop script execution after redirect
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Poetry Platform</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            overflow: hidden;
            background-color: #f0f0f0; /* Light gray background color */
            position: relative;
        }

        #background-container {
            position: absolute;
            top: 50px;
            left: 50px;
            right: 50px;
            bottom: 50px;
            background-image: url('background.jpg'); /* Replace with your background image */
            background-size: cover;
            background-position: center;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5); /* Inner shadow effect */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #notification-bar {
            background-color: rgba(255, 255, 255, 0.9); /* White background */
            padding: 10px;
            width: 99%;
            position: absolute; /* Positioned at the top */
            top: 0;
            left: 0;
            right: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2; /* Ensure it is above the background */
        }

        #nav-links {
            display: flex;
            gap: 15px;
        }

        #nav-links a {
            text-decoration: none;
            color: #333;
            padding: 5px 10px;
            transition: color 0.3s;
        }

        #nav-links a:hover {
            color: #007BFF;
        }

        #system-name {
            font-weight: bold;
            font-size: 1.5em;
            margin-right: 10px;
        }

        header {
            display: flex;
            justify-content: flex-end; /* Align to the right */
            align-items: center;
            padding: 10px 20px;
            position: absolute;
            top: 40px; /* Adjust as necessary for spacing */
            right: 0;
            z-index: 2; /* Ensure it is above the background */
        }

        #search {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        #auth-buttons {
            display: flex;
            gap: 10px;
        }

        #signup-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 300px;
            z-index: 1; /* Ensure it is above the background */
            position: relative; /* For proper stacking context */
        }

        #signup-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #signup-form button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #signup-form button:hover {
            background-color: #0056b3;
        }

        .login-link {
            text-align: center;
            margin-top: 10px;
        }

        .login-link a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div id="background-container">
        <div id="notification-bar">
            <div id="nav-links">
                <a href="index.php">Home</a>
                <a href="publication.php">Publication</a>
                <a href="about.php">About Us</a>
            </div>
            <div id="system-name">Poetry Platform</div>
        </div>

        <header>
            <input type="text" id="search" placeholder="Search for a poem or author..." oninput="searchPoems()">
            <div id="auth-buttons">
                <a href="signup.php"><button id="signup-button">Sign Up</button></a>
                <a href="login.php"><button id="login-button">Login</button></a>
            </div>
        </header>

        <form id="signup-form" method="POST" action="">
            <h2>Sign Up</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
            <div class="login-link">
                <span>Already have an account? </span><a href="login.php">Login</a>
            </div>
        </form>
    </div>

    <script>
        function searchPoems() {
            const query = document.getElementById('search').value;
            console.log(`Searching for: ${query}`);
            // Implement search logic here
        }
    </script>
</body>
</html>