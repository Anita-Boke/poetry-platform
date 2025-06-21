<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Poetry Platform</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh; /* Full viewport height */
            overflow: hidden; /* Prevent body scrolling */
            background-color: #f0f0f0;
            position: relative;
        }

        #background-container {
            position: absolute;
            top: 50px; /* Adjusted to leave space for the notification bar */
            left: 50px;
            right: 50px;
            bottom: 50px; /* Allow space for the header */
            background-image: url('background.jpg'); /* Replace with actual background image */
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
            z-index: 2; /* Ensure it's above background */
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
            top: 40px; /* Adjusted position */
            right: 0;
            z-index: 2; /* Ensure it’s above background */
        }

        #search {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        #content {
            max-width: 500px;
            margin: 70px 0; /* Space for header */
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative; /* Ensure it's above the background */
            z-index: 1; /* Ensure it is above the background */
        }

        h2 {
            color: #333;
        }

        .section {
            margin-bottom: 40px;
        }

        .animated-img {
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
            margin: 20px 0;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        #team {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .team-member {
            text-align: center;
            margin: 15px;
            flex: 1 1 200px;
        }

        .team-member img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
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

        

        <div id="content">
            <h2>About Us</h2>
            <div class="section">
                <h2>Our Mission</h2>
                <p>At Poetry Platform, we believe in the power of words to inspire, connect, and transform lives. Our mission is to create a space where poets and poetry lovers can come together to share their art and connect with others.</p>
                <img src="public.jpg" alt="Our Mission" class="animated-img"> <!-- Replace with your image -->
            </div>

            <div class="section">
                <h2>Our Team</h2>
                <div id="team">
                    <div class="team-member">
                        <img src="WhatsApp Image 2023-07-13 at 16.52.09.jpg" alt="Team Member 1"> <!-- Replace with your image -->
                        <h3>Anita Boke</h3>
                        <p>Founder & CEO</p>
                    </div>
                    <div class="team-member">
                        <img src="a4.jpg" alt="Team Member 2"> <!-- Replace with your image -->
                        <h3>Benjamin Nyundo</h3>
                        <p>CTO</p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Our Values</h2>
                <p>We are committed to fostering a community that values creativity, inclusivity, and support for all poets. Our platform is built on the foundation of respect and encouragement.</p>
                <img src="values.jpg" alt="Our Values" class="animated-img"> <!-- Replace with your image -->
            </div>
        </div>
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