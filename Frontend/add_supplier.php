<?php
include "db.php";

$success = "";

if (isset($_POST['save'])) {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if ($name == "") {
        $success = "Supplier name is required!";
    } else {

        mysqli_query($conn, "
            INSERT INTO suppliers (name, phone, address)
            VALUES ('$name', '$phone', '$address')
        ");

        $success = "Supplier added successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Supplier</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="navbar">
    <div class="logo">➕ Add Supplier</div>
    <div>
        <a href="suppliers.php" class="button">⬅ Back to Suppliers</a>
    </div>
</div>

<div class="container">

    <?php if ($success != "") { ?>
        <div class="success"><?= $success ?></div>
    <?php } ?>

    <form method="post">
        <input type="text" name="name" placeholder="Supplier Name" required>
        <input type="text" name="phone" placeholder="Phone Number">
        <input type="text" name="address" placeholder="Address">
        <button name="save">Save Supplier</button>
    </form>

</div>

</body>
</html>
