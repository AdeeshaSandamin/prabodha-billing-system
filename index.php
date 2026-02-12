<?php
include "db.php";

// Fetch products
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prabodha Fashion Billing System</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        table {
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            border: 1px solid #555;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Prabodha Fashion – Product List</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Size</th>
        <th>Price (Rs.)</th>
        <th>Stock</th>
        <th>Item Code</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['size']."</td>";
            echo "<td>".$row['price']."</td>";
            echo "<td>".$row['stock']."</td>";
            echo "<td>".$row['itemCode']."</td>";

            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No products found</td></tr>";
    }
    ?>

</table>

</body>
</html>