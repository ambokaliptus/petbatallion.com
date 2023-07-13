<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lopez";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize($_POST["username"]);
    $password = sanitize($_POST["password"]);
    $email = sanitize($_POST["email"]);

    // Validate input fields
    if (empty($username) || empty($password) || empty($email)) {
        echo "All fields are required.";
    } else {
        // Check if the username already exists
        $query = "SELECT * FROM admin WHERE username = '$username'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            echo "Username already exists. Please choose a different username.";
        } else {
            // Insert admin account into the database
            $query = "INSERT INTO admin (username, password, email) VALUES ('$username', '$password', '$email')";
            if ($conn->query($query) === TRUE) {
                echo "Registration successful. You can now log in as an admin.";
            } else {
                echo "Error: " . $query . "<br>" . $conn->error;
            }
        }
    }

    // Close database connection
    $conn->close();
}
?>

<html>
<head>
    <title>Admin Registration</title>
    <style>
            body {
             font-family: Arial, sans-serif;
            background-color: #f2f2f2;
			background-image: url('aw.jpg');
  background-repeat: no-repeat;
  background-size: cover;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
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
    <div class="container">
        <h2>Admin Registration</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <input type="submit" value="Register">
			<div class="signup-link">
			<Br>
            Do you have an account? <a href="admin2.php">Log in</a>
        </div>
        </form>
    </div>
</body>
</html>
