<?php
// Start or resume a session
session_start();

// Database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "our_crew";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registration Process
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Note: You should hash the password

    // Perform validation here

    // Save user details in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'user')";
    $conn->query($sql);
}

// Login Process
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the user's credentials
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];

            // Redirect to different pages based on role
            if ($_SESSION['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            echo "Invalid email or password";
        }
    } else {
        echo "Invalid email or password";
    }
}

// Access Control for Role Management Page
function isUserAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Role Management Page
if (isUserAdmin() && isset($_GET['action'])) {
    // Code for creating, editing, and deleting user roles
    // Implement access control to ensure only admins can perform role management operations
}

// HTML forms
?>
<!DOCTYPE html>
<html>
<head>
    <title>Our Crew Project</title>
</head>
<body>
    <!-- Registration Form -->
    <form action="" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>

    <!-- Login Form -->
    <form action="" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <?php
    // Role Management Page Access Control
    if (isUserAdmin()) {
        echo '<a href="role_management.php">Role Management</a>';
    }
    ?>

</body>
</html>
