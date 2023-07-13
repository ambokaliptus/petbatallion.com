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

 	if (isset($_POST['checkout'])) {
	echo '<meta http-equiv="refresh" content="3;url=lupet.php">';
    echo "All products successfully purchased. Thank you! Redirecting back to page";
    exit;
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

<html>
<head>
    <title>Product Management</title>
    <style>
	
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
		 body {
        font-family: Arial, sans-serif;
        margin: 20px;
         background-image: url('aw.jpg'); 
       
    }
		 }
        h1, h2 {
            margin-bottom: 10px;
        }

        form {
            margin-bottom: 20px;
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

        input[type="text"],
        input[type="number"],
        button {
            padding: 6px;
            font-size: 14px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            color: #0645ad;
            text-decoration: none;
            margin-right: 8px;
        }

        a:hover {
            text-decoration: underline;
        }
.product-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex; /* Displays the list items horizontally */
}

.product-list li {
  margin-right: 10px; /* Adjust spacing between items */
}

.product-list img {
  max-width: 100px; /* Set a maximum width for the product images */
  height: auto; /* Maintain aspect ratio */
}
.product-table {
  border-collapse: collapse;
}

.product-table td {
  padding: 10px;
  border: 4px solid #ddd;
  text-align: center;
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
<head>
    <title>PHP Navigation Bar</title>
    <style>
        /* CSS styling for the navigation bar */
        ul.navbar {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
			height: 50;
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
		 body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
        }

        .header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 1800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product-grid {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .product {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            flex: 0 0 auto;
            margin-right: 20px;
            width: 300px;
        }

        .product img {
            max-width: 50%;
            height: auto;
            margin-bottom: 10px;
        }

        .product h3 {
            margin-bottom: 10px;
        }

        .product p {
            color: #888;
        }

        .product-price {
            font-weight: bold;
            margin-top: 10px;
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

    <center><h1>Product Management</h1>

    
    <h2>Add Product</h2><br>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="price" step="0.01" placeholder="Product Price" required>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="insert">Add Product</button>
		<a href="ph.php">Purchase History</a>
		
    </form>


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
        <th>Name</th>
        <th>Price</th>
        <th>Image</th>
    </tr>
    <?php
    $totalPrice = 0; // Initialize total price variable
    foreach ($products as $product) {
        $totalPrice += $product['price']; // Add current product price to total
    ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><?php echo $product['name']; ?></td>
            <td><?php echo $product['price']; ?></td>
            <td><img src="<?php echo $product['image']; ?>" alt="Product Image" width="100"></td>
            <td>
                <a href="?delete=<?php echo $product['id']; ?>">Delete</a>
               
				
             
            </td>
        </tr>
    <?php } ?>
    <tr>
        <th colspan="2"></td>
        <th>Total Price: <?php echo $totalPrice; ?>
		<form action="" method="POST">
    <button type="submit" name="checkout">Checkout</button>
	 
</form>
		</td>
		
        
    </tr>
</table>
	
</body>
  <div class="container">
        <h2>Featured Products</h2>

        <div class="product-grid">
            <div class="product">
                <img src="6.jpg" alt="Product 1">
                <h3>DOG FOOD</h3>
				<br>100php
                
            </div>
            <div class="product">
                <img src="1.jpg" alt="Product 2">
                <h3>DOG SHAMPOO</h3>
                <br>200php
            </div>
            <div class="product">
                <img src="3.jpg" alt="Product 3">
                <h3>DOG TREATS</h3>
               <br>80php
            </div>
			<div class="product">
                <img src="4.jpg" alt="Product 3">
                <h3>DOG CLOTHES</h3>
              <br>50php
            </div>
			<div class="product">
                <img src="5.jpg" alt="Product 3">
                <h3>DOG ACCESSORIES</h3>
                <br>250php
            </div>
        </div>
    </div>
	
<br><br><Br><Br><br><br><Br><Br><br>><br><br><Br><Br><br>
  <div class="footer">
        <p>&copy; 2023 Pet Products. All rights reserved.</p>
    </div>


</html>

