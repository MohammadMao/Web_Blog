<?php

session_start();
// if user isn't logged in, head to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// connect to database
include_once '../php_processes/db_connection.php';

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $target_dir = "../user_images/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            // Update profile image path in the database
            $sql = "UPDATE users SET profile_image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $target_file, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();

            // Update session variable
            $_SESSION['profile_image'] = basename($_FILES["profile_image"]["name"]);
        }
    }
}

// Fetch user's posts
$sql = "SELECT * FROM posts WHERE user_id = ? ORDER BY post_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../styles/dashboardStyle.css">
    <!-- Styles for icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <!-- Dark theme script -->
    <script src="../scripts/dark_thame.js"></script>
</head>
<body onload="pageMode()">
    <div class="container">
        <!-- Header -->
        <header>
            <img src="../webblog.png" alt="logo" height="70px">
            <nav>
                <ul>
                    <li><a href="index.html"><center><span class="material-icons">home</span></center>Home</a></li>
                    <li><a href="create_post.html"><center><span class="material-symbols-outlined">post_add</span></center>New Post</a></li>
                    <li><a href="../php_processes/logout.php"><center><span class="material-symbols-outlined">logout</span></center>Logout</a></li>
                    <img src="../moon.png" alt="mode icon" id="thame_icon" onclick="changeMode()">
                </ul>
            </nav>
        </header>

        <aside>
            <h1>Dashboard</h1>
            <h3>Welcome, <?php echo $_SESSION['username']; ?></h3>
        </aside>

        <!-- Main -->
        <main>
            <section class="profile">
                <!-- Display user profile info -->
                <img src="../user_images/<?php echo $_SESSION['profile_image']; ?>" alt="Profile Picture">
                <h3><?php echo $_SESSION['full_name']; ?></h3>
                <h4><?php echo $_SESSION['email']; ?></h4>
                <!-- Form to upload a new profile image -->
                <form action="dashboard.php" method="post" enctype="multipart/form-data">
                    <label for="profile_image">Change Profile Image:</label>
                    <input type="file" name="profile_image" id="profile_image" required>
                    <button type="submit">Upload</button>
                </form>
            </section>

            <section>
                <h1>My Posts</h1>
                <!-- Display all user's posts if any -->
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="post">
                            <h3><?php echo $row['title']; ?></h3>
                            <p align="justify"><?php echo $row['content']; ?></p>
                            <span>Posted on <?php echo $row['post_date']; ?></span>
                            <a href="../php_processes/edit_post.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="../php_processes/delete_post.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- if no posts found -->
                    <p>No posts found.</p>
                <?php endif; ?>
            </section>
        </main>

        <!-- Footer -->
        <footer>
            <p>&copy; 2024 Web Blog. All rights reserved.</p>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="about.html">About</a></li>
                </ul>
            </nav>
            <p>Contact Information: info@webblog.com</p>
        </footer>
    </div>
</body>
</html>
