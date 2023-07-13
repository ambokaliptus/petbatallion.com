<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lopez";

try {
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
die("Error connecting to the database: " . $e->getMessage());
}

if (isset($_POST['insert'])) {
$name = $_POST['name'];
$price = $_POST['price'];

$image = $_FILES['image'];
$imageFileName = $image['name'];
$imageFilePath = 'product_images/' . $imageFileName;

if (move_uploaded_file($image['tmp_name'], $imageFilePath)) {
$query = "INSERT INTO products (name, price, image) VALUES (:name, :price, :image)";

try {
$stmt = $pdo->prepare($query);
$stmt->bindParam(":name", $name);
$stmt->bindParam(":price", $price);
$stmt->bindParam(":image", $imageFilePath);
$stmt->execute();
echo "Product inserted successfully.";
} catch (PDOException $e) {
die("Error inserting product: " . $e->getMessage());
}
} else {
// Image upload failed
die("Error uploading image.");
}
}

if (isset($_GET['delete'])) {
$id = $_GET['delete'];

// Validate and sanitize the ID parameter
$id = filter_var($id, FILTER_VALIDATE_INT);

if ($id === false) {
die("Invalid product ID.");
}

$query = "DELETE FROM products WHERE id = :id";

try {
$stmt = $pdo->prepare($query);
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();
echo "Product deleted successfully.";
} catch (PDOException $e) {
die("Error deleting product: " . $e->getMessage());
}
}

if (isset($_POST['update'])) {
$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];

$query = "UPDATE products SET name = :name, price = :price WHERE id = :id";

try {
$stmt = $pdo->prepare($query);
$stmt->bindParam(":id", $id);
$stmt->bindParam(":name", $name);
$stmt->bindParam(":price", $price);
$stmt->execute();
echo "Product updated successfully.";
} catch (PDOException $e) {
die("Error updating product: " . $e->getMessage());
}
}

$query = "SELECT * FROM products";

try {
$stmt = $pdo->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
die("Error retrieving products: " . $e->getMessage());
}

if (isset($_POST['checkout'])) {
// Purchase logic here
// You can perform necessary actions like updating the database, sending emails, etc.
// Assuming you want to display a success message after the purchase

// Move purchased products to the purchase history table
$purchaseDate = date('Y-m-d H:i:s');
foreach ($products as $product) {
$productId = $product['id'];
$productName = $product['name'];
$productPrice = $product['price'];
$imageFilePath = $product['image'];

$query = "INSERT INTO purchase_history (product_id, product_name, product_price, image, purchase_date)
VALUES (:product_id, :product_name, :product_price, :image, :purchase_date)";

try {
$stmt = $pdo->prepare($query);
$stmt->bindParam(":product_id", $productId);
$stmt->bindParam(":product_name", $productName);
$stmt->bindParam(":product_price", $productPrice);
$stmt->bindParam(":image", $imageFilePath);
$stmt->bindParam(":purchase_date", $purchaseDate);
$stmt->execute();
} catch (PDOException $e) {
die("Error inserting purchase record: " . $e->getMessage());
}
}

// Clear the products table after purchase
$query = "TRUNCATE TABLE products";

try {
$stmt = $pdo->prepare($query);
$stmt->execute();
echo "All products successfully purchased. Thank you!";

// Send email
$to = "example@example.com";
$subject = "Product Purchase";
$message = "All products successfully purchased. Thank you!";
$headers = "From: your_email@example.com\r\n";

// Send email using PHP's mail() function
if (mail($to, $subject, $message, $headers)) {
echo "Email sent successfully.";
} else {
echo "Error sending email.";
}

exit; // Stop executing the rest of the code
} catch (PDOException $e) {
die("Error clearing products table: " . $e->getMessage());
}
}

$query = "SELECT * FROM purchase_history";

try {
$stmt = $pdo->query($query);
$purchaseHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
die("Error retrieving purchase history: " . $e->getMessage());
}


?>

<!-- Your HTML code goes here -->

<html>
<head>
<title>Purchase History</title>
<style>
 body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
			 background-image: url('aw.jpg');
        }
table {
width: 100%;
border-collapse: collapse;
}

th, td {
padding: 8px;
text-align: left;
border-bottom: 1px solid #ddd;
}

th {
background-color: #f2f2f2;
}

td:last-child {
text-align: center;
}

        /* CSS styling for the navigation bar */
        ul.navbar {
            list-style-type: none;
            margin: -5;
            padding: -5;
            overflow: hidden;
            background-color: #333;
        }

        li.navitem {
            float: left;
        }

        li.navitem a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 12px;
            text-decoration: none;
        }

        li.navitem a:hover {
            background-color: #111;
        }
		.footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
   
</style>
</head>
<body>
    <ul class="navbar">

        <li class="navitem"><a href="lupet.php">Product Mangement</a></li>
        <li class="navitem"><a href="ph.php">Purchase History</a></li>
    </ul>
</body>
<body>
<h2>Purchase History</h2>

<table>
<style>
td{
background-color: white;
}

th{
	background-color: pink;
}
</style>
<tr>
<th>ID</th>
<th>Product Name</th>
<th>Product Price</th>
<th>Image</th>
<th>Purchase Date</th>
</tr>
<?php foreach ($purchaseHistory as $purchase) { ?>
<tr>
<td><?php echo $purchase['id']; ?></td>
<td><?php echo $purchase['product_name']; ?></td>
<td><?php echo $purchase['product_price']; ?></td>
<td><img src="<?php echo $purchase['image']; ?>" alt="Product Image" width="100"></td>
<td><?php echo $purchase['purchase_date']; ?></td>
</tr>
<?php } ?>
</table>
</body>
<Br><br><Br><Br><Br><br><Br><Br><Br><br><Br><Br><Br><br><Br><Br><Br><br><Br><Br><Br><br><Br><Br><Br><br><Br><Br><Br><br><Br><Br><br>
  <div class="footer">
        <p>&copy; 2023 Pet Products. All rights reserved.</p>
    </div>
</html>
<?php


