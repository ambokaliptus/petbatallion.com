<?php

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

// Function to create a new user account
function createAccount($username, $password, $email)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO user_accounts (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);
    $stmt->execute();
    $stmt->close();
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Create a new user account
    createAccount($username, $password, $email);
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>User Account Management</title>
    <style>
          body {
             font-family: Arial, sans-serif;
            background-color: #f2f2f2;
			background-image: url('aw.jpg');
			background-repeat: no-repeat;
			background-size: cover;
		}

        h2 {
            color: #333;
        }

        form {
            width: 300px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        label {
            display: inline-block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
   <cENTEr> <h2>Create User Account</h2>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <input type="submit" name="submit" value="Create Account">

			<div class="signup-link">
			<Br>
            Do you have an account? <a href="login.php">Log in</a>
        </div>
    </form>

    <hr>
</body>
</html>
