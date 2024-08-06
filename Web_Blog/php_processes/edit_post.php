<?php
session_start();

// if not logged in head to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.html");
    exit();
}

// connect to database
include_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $post = $result->fetch_assoc();
    } else {
        echo "Post not found or you don't have permission to edit this post.";
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../views/dashboard.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../styles/postStyle.css">
    <!-- styles for icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <!-- Dark theme script -->
    <script src="../scripts/dark_thame.js"></script>
</head>
<body onload="pageMode()">
    <div class="container">
        <header>
            <div class="header-left">
                <!-- logo -->
                <img src="../webblog.png" alt="logo" height="70px">
            </div>
            <div class="header-right">
                <nav>
                    <!-- Navbar -->
                    <ul>
                    <li><a href="../views/index.html"><center><span class="material-icons">home</span></center>Home</a></li>
                        <li><a href="../views/dashboard.php"><center><span class="material-symbols-outlined">space_dashboard</span></center>Dashboard</a></li>
                        <li><a href="logout.php"><center><span class="material-symbols-outlined">logout</span></center>Logout</a></li>
                        <img src="../moon.png" alt="mode icon" id="thame_icon" onclick="changeMode()">
                    </ul>
                </nav>
            </div>
        </header>
	<main>
        <!-- Edit form -->
    <div class="create-post">
        <h2>Edit Post</h2>
        <form action="update_post.php" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            <textarea name="content" placeholder="Write your post here..." rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            <button type="submit">Update Post</button>
        </form>
    </div>
	</main>
	<footer>
            <p>&copy; 2024 Web Blog. All rights reserved.</p>
            <nav>
                <ul>
                    <li><a href="../views/index.html">Home</a></li>
                    <li><a href="../views/about.html">About</a></li>
                </ul>
            </nav>
            <p>Contact Information: info@webblog.com</p>
        </footer>
    </div>
</body>
</html>
