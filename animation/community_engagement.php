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

// Handle comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $poem_id = $conn->real_escape_string($_POST['poem_id']);
    $username = $conn->real_escape_string($_POST['username']);
    $comment = $conn->real_escape_string($_POST['comment']);

    // Insert comment into the comments table
    $sql = "INSERT INTO comments (poem_id, username, comment) VALUES ('$poem_id', '$username', '$comment')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Comment submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Handle challenge response submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['challenge_response'])) {
    $challenge_id = $conn->real_escape_string($_POST['challenge_id']);
    $username = $conn->real_escape_string($_POST['username']);
    $response = $conn->real_escape_string($_POST['response']);

    // Insert response into the challenge_responses table
    $sql = "INSERT INTO challenge_responses (challenge_id, username, response) VALUES ('$challenge_id', '$username', '$response')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Response submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fetch poems
$sql = "SELECT * FROM poems ORDER BY created_at DESC";
$result = $conn->query($sql);

// Fetch comments for each poem
$comments = [];
$sql_comments = "SELECT * FROM comments ORDER BY id DESC";
$result_comments = $conn->query($sql_comments);
while ($row = $result_comments->fetch_assoc()) {
    $comments[$row['poem_id']][] = $row;
}

// Fetch challenges
$challenges = [];
$sql_challenges = "SELECT * FROM challenges ORDER BY id DESC";
$result_challenges = $conn->query($sql_challenges);
while ($row = $result_challenges->fetch_assoc()) {
    $challenges[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Engagement - Poetry Platform</title>
    <style>
        /* (Include the same CSS from previous snippets) */
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

        #poem-list, #challenge-list {
            width: 100%; /* Use full width for mobile */
            padding: 10px;
        }

        .poem, .challenge {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%; /* Use full width for mobile */
            max-width: 600px; /* Set a max width for larger screens */
        }

        .comment-form, .challenge-form {
            margin-top: 20px;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.9);
        }

        .comment-form input,
        .comment-form textarea,
        .challenge-form input,
        .challenge-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .comment-form button,
        .challenge-form button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .comment-form button:hover,
        .challenge-form button:hover {
            background-color: #0056b3;
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
            <a href="community_engagement.php">Community Engagement</a>
            <a href="index.php">About Us</a>
        </div>
        <div id="system-name">Poetry Platform</div>
    </div>

    <div id="poem-list">
        <h2>Published Poems</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="poem">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    <small>Published on <?php echo $row['created_at']; ?></small>
                    <button class="read-aloud-button" onclick="speakPoem('<?php echo htmlspecialchars($row['content']); ?>')">Read Aloud</button>

                    <!-- Comments Section -->
                    <h5>Comments</h5>
                    <div>
                        <?php if (isset($comments[$row['id']])): ?>
                            <?php foreach ($comments[$row['id']] as $comment): ?>
                                <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment']); ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No comments yet.</p>
                        <?php endif; ?>
                    </div>

                    <form class="comment-form" method="POST">
                        <input type="hidden" name="poem_id" value="<?php echo $row['id']; ?>">
                        <input type="text" name="username" placeholder="Your Name" required>
                        <textarea name="comment" placeholder="Leave a comment..." rows="3" required></textarea>
                        <button type="submit">Submit Comment</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No poems published yet.</p>
        <?php endif; ?>
    </div>

    <div id="challenge-list">
        <h2>Poetry Tests and Challenges</h2>
        <?php if (!empty($challenges)): ?>
            <?php foreach ($challenges as $challenge): ?>
                <div class="challenge">
                    <h4><?php echo htmlspecialchars($challenge['prompt']); ?></h4>
                    <small>Challenge ID: <?php echo $challenge['id']; ?></small>

                    <form class="challenge-form" method="POST">
                        <input type="hidden" name="challenge_id" value="<?php echo $challenge['id']; ?>">
                        <input type="text" name="username" placeholder="Your Name" required>
                        <textarea name="response" placeholder="Your response..." rows="3" required></textarea>
                        <button type="submit">Submit Response</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No poetry challenges available at the moment.</p>
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