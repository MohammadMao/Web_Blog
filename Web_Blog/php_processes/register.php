<?php

session_start();
header('Content-Type: application/json');

// for error reporing
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// connect to database
include_once 'db_connection.php';

$errors = [];
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];

    // Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        while ($row = $check_result->fetch_assoc()) {
            if ($row['username'] === $username) {
                $errors[] = "Username already exists";
            }
            if ($row['email'] === $email) {
                $errors[] = "Email already exists";
            }
        }
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Handle profile image upload
        $profile_image_name = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $profile_image_name = $username . '.jpg'; // naming image
            $upload_dir = '../user_images/'; // Directory to store images
            $target_file = $upload_dir . $profile_image_name;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                // Image successfully uploaded
            } else {
                $errors[] = "Error uploading profile image.";
            }
        }

        // Insert user data into the database
        $sql = "INSERT INTO users (username, password, email, full_name, profile_image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $hashed_password, $email, $full_name, $profile_image_name);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['profile_image'] = $profile_image_name;
            $response['success'] = true;
            echo json_encode($response);
            $stmt->close();
            $check_stmt->close();
            $conn->close();
            exit;
        } else {
            $errors[] = "Registration failed. Please try again.";
            $stmt->close();
            $check_stmt->close();
            $conn->close();
        }
    }

}

// Return errors if any
if (!empty($errors)) {
    $response['success'] = false;
    $response['errors'] = $errors;
    echo json_encode($response);
}
?>