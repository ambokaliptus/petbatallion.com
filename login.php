<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lopez";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM user_accounts WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<script>alert("Access Granted")</script>';
            setcookie($username, $username, time()+900, "/","",0);
            header('location: lupet.php');
        } else {
            echo '<script>alert("Incorrect password")</script>';
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!-- Rest of your HTML code -->


<html>
<head>
    <title>User Login</title>
    <style>
     body {
        font-family: Arial, sans-serif;
        margin: 20px;
         background-image: url('aw.jpg'); 
       
    }

        .login-container {
            background-color: #ffffff;
            width: 400px;
            margin: 0 auto;
            margin-top: 100px;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333333;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #555555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 3px;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" name="login" value="Login"><br><br>
		<div class="signup-link">
            Don't have an account? <a href="user.php">Sign up</a>
        </div>
        </form>
    </div>
</body>



</html>