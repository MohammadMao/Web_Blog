<?php

session_start();
include_once 'db_connection.php';

$errors = []; // Array to store validation errors

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // If no errors, proceed with login
    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $row['email'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['profile_image'] = $row['profile_image'];
                header("Location: ../views/index.html");
            } else {
                $errors[] = "Invalid password";
            }
        } else {
            $errors[] = "Username not found";
        }

        $stmt->close();
    }
    
    $conn->close();
}

// Display errors
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p>Error: $error</p>";
    }
}
?>
