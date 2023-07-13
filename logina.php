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

// Delete user account
if (isset($_GET["delete_id"])) {
    $deleteId = sanitize($_GET["delete_id"]);
    $query = "DELETE FROM user_accounts WHERE id = $deleteId";
    if ($conn->query($query) === TRUE) {
        echo "User account deleted successfully.";
    } else {
        echo "Error deleting user account: " . $conn->error;
    }
}

// Update user account
if (isset($_POST["update_id"])) {
    $updateId = sanitize($_POST["update_id"]);
    $username = sanitize($_POST["username"]);
    $email = sanitize($_POST["email"]);

    $query = "UPDATE user_accounts SET username = '$username', email = '$email' WHERE id = $updateId";
    if ($conn->query($query) === TRUE) {
        echo "User account updated successfully.";
    } else {
        echo "Error updating user account: " . $conn->error;
    }
}

// Search user accounts
if (isset($_POST["search"])) {
    $searchKeyword = sanitize($_POST["search"]);
    $query = "SELECT * FROM user_accounts WHERE username LIKE '%$searchKeyword%'";
    $result = $conn->query($query);
} else {
    $query = "SELECT * FROM user_accounts";
    $result = $conn->query($query);
}

// Close database connection
$conn->close();
?>

<html>
<head>
    <title>Admin Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
            color: #337ab7;
        }

        a:hover {
            text-decoration: underline;
        }

        .no-accounts {
            text-align: center;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h2>User Accounts</h2>

    <!-- Search form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Search by username:</label>
        <input type="text" name="search">
        <input type="submit" value="Search">
    </form>

    <!-- User accounts table -->
    <?php if ($result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["username"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td>
                        <a href="?delete_id=<?php echo $row["id"]; ?>">Delete</a>
                        <a href="edit.php?id=<?php echo $row["id"]; ?>">Edit</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p class="no-accounts">No user accounts found.</p>
    <?php } ?>
</body>
</html>