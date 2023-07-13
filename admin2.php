<?php
session_start();


// Define database connection parameters
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

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize($_POST["username"]);
    $password = sanitize($_POST["password"]);

    // Validate input fields
    if (empty($username) || empty($password)) {
        $loginError = "Username and password are required.";
    } else {
        // Check if the admin credentials are valid
        $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $_SESSION['admin_username'] = $username;
            header("Location: logina.php"); // Redirect to the admin page
            exit();
        } else {
            $loginError = "Invalid username or password.";
        }
    }
}


// Close database connection
$conn->close();
?>

<html>
<head>
    <title>Admin Login</title>
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
        input[type="password"] {
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

        .error-message {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <?php
        if (isset($loginError)) {
            echo "<p class='error-message'>$loginError</p>";
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">
			
			<div class="signup-link">
			<Br>
            Don't have an account? <a href="admin1.php">Sign up</a>
        </div>
		<div class="signup-link">
			<Br>
            Don't have an account for users? <a href="login.php">Log in</a>
        </div>
        </form>
    </div>
</body>
</html>