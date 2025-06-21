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

// Handle poem submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // Insert new poem into the poems table
    $sql = "INSERT INTO poems (title, content) VALUES ('$title', '$content')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Poem submitted successfully!');
                window.location.href = 'publication.php'; // Redirect to publication page
              </script>";
        exit; // Stop script execution after redirect
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fetch poems
$sql = "SELECT * FROM poems ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publication - Poetry Platform</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            overflow: hidden; /* Prevent body scrolling */
            background-color: #f0f0f0;
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
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            overflow-y: auto; /* Enable vertical scrolling */
            padding: 20px; /* Add padding for better layout */
        }

        #notification-bar {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2;
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
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            position: absolute;
            top: 40px;
            right: 0;
            z-index: 2;
        }

        #search {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        #poem-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 100%; /* Use full width for mobile */
            max-width: 400px; /* Set a max width for larger screens */
            margin: 70px 0; /* Add margin for spacing */
        }

        #poem-form input,
        #poem-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #poem-form button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #poem-form button:hover {
            background-color: #0056b3;
        }

        .poem {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%; /* Use full width for mobile */
            max-width: 600px; /* Set a max width for larger screens */
        }

        .read-aloud-button {
            background-color: #28a745; /* Green background */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 10px;
        }

        .read-aloud-button:hover {
            background-color: #218838; /* Darker green on hover */
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
            <a href="community_engagement.php">Community Engagement</a>
        </div>
        <div id="system-name">Poetry Platform</div>
    </div>

    <header>
        <form id="search-form" method="GET" action="search.php"> <!-- Updated to form -->
            <input type="text" id="search" name="query" placeholder="Search for a poem ..." required>
            <button type="submit">🔍</button> <!-- Search button with icon -->
        </form>
    </header>

    <form id="poem-form" method="POST">
        <h2>Submit a New Poem</h2>
        <input type="text" name="title" placeholder="Poem Title" required>
        <textarea name="content" placeholder="Write your poem here..." rows="5" required></textarea>
        <button type="submit">Submit Poem</button>
    </form>

    <div id="poem-list">
        <h2>Published Poems</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="poem">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    <small>Published on <?php echo $row['created_at']; ?></small>
                    <button class="read-aloud-button" onclick="speakPoem('<?php echo htmlspecialchars($row['content']); ?>')">Read Aloud</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No poems published yet.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    // Function to read the poem aloud
    function speakPoem(poemContent) {
        const utterance = new SpeechSynthesisUtterance(poemContent);
        utterance.lang = 'en-US'; // Set language if needed
        speechSynthesis.speak(utterance);
    }
</script>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>