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

// Get the search query
$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

// Fetch poems matching the search query
$sql = "SELECT * FROM poems WHERE title LIKE '%$query%' OR content LIKE '%$query%' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Poetry Platform</title>
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .poem {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="poem">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
            <small>Published on <?php echo $row['created_at']; ?></small>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No results found.</p>
<?php endif; ?>

<a href="publication.php">Back to Publication</a>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>