<?php
include "db.php";

$result = mysqli_query($conn, "SELECT * FROM suppliers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Suppliers - Prabodha Fashion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="logo">🏢 Supplier Management</div>
    <div class="nav-buttons">
        <a href="add_supplier.php" class="button">+ Add Supplier</a>
        <a href="index.php" class="button secondary">← Back to Products</a>
    </div>
</div>

<div class="card full-width">

    <h3>Supplier List</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Created</th>
        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No suppliers found</td></tr>";
        }
        ?>
    </table>

</div>

</body>
</html>

