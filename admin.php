<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lopez";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Register admin account
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO admin (username, password, email, admin) VALUES ('$username', '$hashed_password', '$email', 1)";
    $result = $conn->query($sql);

    if ($result) {
        echo "Admin account registered successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['username'] = $row['username'];
            $_SESSION['admin'] = $row['admin'];
            echo "Login successful.";
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }
}

// Delete user account
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result) {
        echo "User account deleted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Search user account
if (isset($_POST['search'])) {
    $username = $_POST['search_username'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Username: " . $row['username'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
    } else {
        echo "User account not found.";
    }
}

// Edit user account
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET username = '$username', email = '$email' WHERE id = $id";
    $result = $conn->query($sql);

    if ($result) {
        echo "User account updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
<html>
<head>
    <title>Admin Account Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .container label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .container input[type="text"],
        .container input[type="password"],
        .container input[type="email"],
        .container input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Login form -->
        <h2>Login</h2>
        <form method="post" action="">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <input type="submit" name="login" value="Login">
        </form>
    </div>
</body>
</html>
