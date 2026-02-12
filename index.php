<?php
include "db.php";

// Fetch products
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Prabodha Fashion Billing System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="page-wrapper">

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="logo">
            Prabodha Fashion – Product List
        </div>

        <div class="nav-buttons">
            <a href="add_product.php" class="button">+ Add Product</a>
            <a href="billing.php" class="button secondary">🧾 Billing</a>
        </div>
    </div>

    <!-- TABLE SECTION -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Price (Rs.)</th>
                    <th>Stock</th>
                    <th>Item Code</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$row['id']."</td>";
                        echo "<td>".$row['name']."</td>";
                        echo "<td>".$row['size']."</td>";
                        echo "<td>Rs. ".number_format($row['price'], 2)."</td>";
                        echo "<td>".$row['stock']."</td>";
                        echo "<td>".$row['itemCode']."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-data'>No products found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
