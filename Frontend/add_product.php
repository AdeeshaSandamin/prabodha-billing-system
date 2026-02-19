<?php
include "db.php";

$success = "";

// DEBUG (remove later)
echo "<pre>";
echo "</pre>";

if (isset($_POST['save'])) {
    $name  = $_POST['name'];
    $size  = $_POST['size'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $itemCode = $_POST['itemCode'];

    $sql = "INSERT INTO products (name, size, price, stock,itemCode)
            VALUES ('$name', '$size', '$price', '$stock' ,'$itemCode')";

    if (mysqli_query($conn, $sql)) {
        $success = "✅ Product saved successfully!";
        $_POST = []; // clear form
    } else {
        $success = "❌ DB Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Add New Product</h2>

<?php if ($success != "") { ?>
    <p style="color: green; font-weight: bold;"><?php echo $success; ?></p>
<?php } ?>

<form method="post" action="">
    <input type="text" name="name" placeholder="Product Name"
           value="<?php echo $_POST['name'] ?? ''; ?>" required><br>

    <input type="text" name="size" placeholder="Size (S/M/L/32)"
           value="<?php echo $_POST['size'] ?? ''; ?>" required><br>

    <input type="number" name="price" placeholder="Price"
           value="<?php echo $_POST['price'] ?? ''; ?>" required><br>

    <input type="number" name="stock" placeholder="Stock"
           value="<?php echo $_POST['stock'] ?? ''; ?>" required><br>
    <input type="text" name="itemCode" placeholder="Item_Code"
           value="<?php echo $_POST['itemCode'] ?? ''; ?>" required><br>       

    <button type="submit" name="save">Save Product</button>
</form>

<a href="index.php">⬅ Back to Product List</a>

</body>
</html>