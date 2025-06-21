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

// Function to fetch poems from the database (if needed for other pages)
function fetchPoems($conn) {
    $sql = "SELECT title, content FROM poems";
    $result = $conn->query($sql);
    $poems = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $poems[] = $row;
        }
    }
    return $poems;
}

$poems = fetchPoems($conn); // Fetch poems (not displayed on this page)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Poetry Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
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
            top: 50px; /* 10px from the top margin */
            left: 50px; /* 10px from the left margin */
            right: 50px; /* 10px from the right margin */
            bottom: 50px; /* 10px from the bottom margin */
            background-image: url('background.jpg'); /* Replace with the path to your background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); /* Add a shadow effect */
            overflow: hidden; /* Ensure the sliding image stays within bounds */
        }

        #sliding-image {
            position: absolute;
            top: 50%;
            right: -300px; /* Start off-screen on the right */
            transform: translateY(-50%); /* Center vertically */
            animation: slideInImage 1s forwards;
            width: 300px;
            height: auto;
        }

        @keyframes slideInImage {
            to {
                right: calc(50% - 150px); /* Slide to the center */
                transform: translateY(-50%); /* Ensure vertical centering */
            }
        }

        #notification-bar,
        header {
            position: relative;
            z-index: 1; /* Ensure the content is above the background image */
        }

        #notification-bar {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            margin-left: auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 60px 20px 20px;
        }

        #auth-buttons {
            display: flex;
            gap: 10px;
        }

        #page-content {
            padding: 20px;
            margin-top: 20px;
            animation: slideInContent 1s forwards;
            transform: translateX(-100%); /* Start off-screen on the left */
            position: relative;
            z-index: 1; /* Ensure it is above the background image */
        }

        @keyframes slideInContent {
            to {
                transform: translateX(0); /* Slide to the center */
            }
        }

        /* Chatbot styles */
        #chatbot {
            position: fixed;
            bottom: 70px; /* Adjusted to avoid overlap with the icon */
            right: 20px;
            width: 300px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            display: none; /* Hidden by default */
            flex-direction: column;
        }
        
        #chatbot-header {
            background: #007BFF;
            color: white;
            padding: 10px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        #chatbot-messages {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
        }

        #user-input {
            display: flex;
        }

        #user-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
        }

        #user-input button {
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        #user-input button:hover {
            background: #0056b3;
        }

        /* Chatbot icon */
        #chatbot-icon {
            position: fixed;
            bottom: 60px;
            right: 60px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        #chatbot-icon:hover {
            background: #0056b3;
        }

        /* Social media styles */
        #social-media {
            position: absolute;
            bottom: 20px; /* Position above the bottom */
            left: 50%;
            transform: translateX(-50%); /* Center the icons */
            display: flex;
            gap: 15px; /* Spacing between icons */
        }

        #social-media a {
            color: #333;
            font-size: 24px; /* Size of icons */
            transition: color 0.3s;
        }

        #social-media a:hover {
            color: #007BFF; /* Change color on hover */
        }
    </style>
</head>
<body>
    <div id="background-container">
        <img id="sliding-image" src="spare-a-thought.jpg" alt="Sliding Image"> <!-- Replace with your image -->

        <div id="notification-bar">
            <div id="nav-links">
                <a href="index.php">Home</a>
                <a href="publication.php">Publication</a>
                <a href="about.php">About Us</a>
                <a href="community_engagement.php">Community Engagement</a>
            </div>
            <div id="system-name">Poetry Platform</div>
        </div>

        <header>
            <div id="auth-buttons">
                <a href="signup.php"><button id="signup-button">Sign Up</button></a>
                <a href="login.php"><button id="login-button">Login</button></a>
            </div>
        </header>

        <div id="page-content">
            <h2>WELCOME</h2>
            <p>Welcome to our Poetry Platform.<br> Here, you can explore a variety of<br> poems and submit your own works.</p>
        </div>

        <!-- Social Media Icons -->
        <div id="social-media">
            <a href="https://twitter.com/yourprofile" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="https://instagram.com/yourprofile" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://tiktok.com/@yourprofile" target="_blank"><i class="fab fa-tiktok"></i></a>
        </div>

    </div>

    <!-- Chatbot HTML -->
    <div id="chatbot">
        <div id="chatbot-header">Chatbot</div>
        <div id="chatbot-messages"></div>
        <div id="user-input">
            <input type="text" id="user-message" placeholder="Ask me about poems..." />
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <!-- Chatbot Icon -->
    <button id="chatbot-icon" onclick="toggleChatbot()">💬</button>

    <script>
        const chatbot = document.getElementById('chatbot');
        const messagesContainer = document.getElementById('chatbot-messages');
        const userMessageInput = document.getElementById('user-message');

        // Function to toggle chatbot visibility
        function toggleChatbot() {
            chatbot.style.display = chatbot.style.display === 'none' ? 'flex' : 'none';
            if (chatbot.style.display === 'flex') {
                userMessageInput.focus(); // Focus input when opened
            }
        }

        // Function to send a message
        function sendMessage() {
            const message = userMessageInput.value.trim();
            if (message) {
                displayMessage('You: ' + message);
                userMessageInput.value = '';
                respondToMessage(message);
            }
        }

        // Function to display messages
        function displayMessage(message) {
            const messageElement = document.createElement('div');
            messageElement.textContent = message;
            messagesContainer.appendChild(messageElement);
            messagesContainer.scrollTop = messagesContainer.scrollHeight; // Scroll to the bottom
        }

        // Function to respond based on the user's message
        function respondToMessage(message) {
            // Make an AJAX call to the server to get the response from the database
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "chatbot_response.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    displayMessage('Bot: ' + xhr.responseText);
                }
            };
            xhr.send("message=" + encodeURIComponent(message));
        }
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>