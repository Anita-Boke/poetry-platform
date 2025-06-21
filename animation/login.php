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

// Handle user login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Query to check if the user exists
    $sql = "SELECT password FROM users WHERE username='$usernameOrEmail' OR email='$usernameOrEmail'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['password'])) {
            echo "<script>
                    alert('Login successful!');
                    window.location.href = 'home.php'; // Redirect to home page
                  </script>";
            exit; // Stop script execution after redirect
        } else {
            echo "<script>alert('Invalid username or password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Poetry Platform</title>
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
            background-color: rgba(255, 255, 255, 0.8); /* White background */
            padding: 10px;
            width: 100%;
            position: absolute; /* Positioned at the top */
            top: 0;
            left: 0;
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
            margin-right: 20px; /* Space between search and buttons */
        }

        #auth-buttons {
            display: flex;
            gap: 10px;
        }

        #login-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 300px;
            z-index: 1; /* Ensure it is above the background */
            position: relative; /* For proper stacking context */
        }

        #login-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #login-form button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #login-form button:hover {
            background-color: #0056b3;
        }

        .signup-link {
            text-align: center;
            margin-top: 10px;
        }

        .signup-link a {
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

        <form id="login-form" method="POST" action="">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <div class="signup-link">
                <span>Don't have an account? </span><a href="signup.php">Sign Up</a>
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