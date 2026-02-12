<?php
session_start();
include "db.php";

/* ===============================
   INITIALIZE CART
================================= */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ===============================
   REMOVE SINGLE ITEM
================================= */
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

/* ===============================
   CLEAR FULL BILL
================================= */
if (isset($_POST['clear_bill'])) {
    $_SESSION['cart'] = [];
}

/* ===============================
   SEARCH PRODUCT
================================= */
$product = null;

if (isset($_POST['search'])) {
    $itemCode = $_POST['itemCode'];

    $sql = "SELECT * FROM products WHERE itemCode='$itemCode'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Product not found!');</script>";
    }
}

/* ===============================
   ADD TO CART (WITH STRICT STOCK CHECK)
================================= */
if (isset($_POST['add'])) {

    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $qty = $_POST['quantity'];

    if ($qty <= 0) {
        echo "<script>alert('Quantity must be greater than 0');</script>";
    } else {

        // Get latest stock
        $stockCheck = mysqli_query($conn, "SELECT stock FROM products WHERE id = $product_id");
        $stockRow = mysqli_fetch_assoc($stockCheck);
        $available_stock = $stockRow['stock'];

        // Calculate already added quantity in cart
        $alreadyInCart = 0;
        foreach ($_SESSION['cart'] as $item) {
            if ($item['product_id'] == $product_id) {
                $alreadyInCart += $item['quantity'];
            }
        }

        if (($qty + $alreadyInCart) > $available_stock) {
            echo "<script>alert('Not enough stock available!');</script>";
        } else {

            $subtotal = $price * $qty;

            $_SESSION['cart'][] = [
                'product_id' => $product_id,
                'name' => $name,
                'price' => $price,
                'quantity' => $qty,
                'subtotal' => $subtotal
            ];
        }
    }
}

/* ===============================
   SAVE BILL
================================= */
if (isset($_POST['save_bill'])) {

    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Cart is empty!');</script>";
        exit();
    }

    // FINAL STOCK CHECK
    foreach ($_SESSION['cart'] as $item) {

        $checkStock = mysqli_query($conn,
            "SELECT stock FROM products WHERE id = {$item['product_id']}"
        );

        $rowStock = mysqli_fetch_assoc($checkStock);

        if ($item['quantity'] > $rowStock['stock']) {
            echo "<script>alert('Stock changed! Not enough quantity available.');</script>";
            exit();
        }
    }

    // Calculate total
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['subtotal'];
    }

    // Insert bill
    mysqli_query($conn, "INSERT INTO bills (total_amount) VALUES ('$total')");
    $bill_id = mysqli_insert_id($conn);

    // Insert bill items + reduce stock
    foreach ($_SESSION['cart'] as $item) {

        mysqli_query($conn, "INSERT INTO bill_items 
            (bill_id, product_id, quantity, subtotal)
            VALUES 
            ('$bill_id', '{$item['product_id']}', '{$item['quantity']}', '{$item['subtotal']}')");

        mysqli_query($conn, "UPDATE products 
            SET stock = stock - {$item['quantity']} 
            WHERE id = {$item['product_id']}");
    }

    $_SESSION['cart'] = [];

    echo "<script>alert('Bill Saved Successfully!'); window.location='billing.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Billing - Prabodha Fashion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>🧾 Billing / Invoice</h2>

<div class="billing-wrapper">

    <!-- LEFT SIDE -->
    <div class="billing-left">

        <form method="post" class="card">
            <input type="text" name="itemCode" placeholder="Enter Item Code" required>
            <button name="search">Search Product</button>
        </form>

        <?php if ($product) { ?>
            <div class="card">
                <h3>Product Details</h3>
                <p><strong>Name:</strong> <?= $product['name']; ?></p>
                <p><strong>Price:</strong> Rs. <?= number_format($product['price'],2); ?></p>
                <p><strong>Stock:</strong> <?= $product['stock']; ?></p>

                <form method="post">
                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                    <input type="hidden" name="name" value="<?= $product['name']; ?>">
                    <input type="hidden" name="price" value="<?= $product['price']; ?>">

                    <input type="number" name="quantity" min="1" placeholder="Enter Quantity" required>
                    <button name="add">Add to Cart</button>
                </form>
            </div>
        <?php } ?>

    </div>

    <!-- RIGHT SIDE -->
    <div class="billing-right">

        <h3>Invoice Summary</h3>

        <table>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>

            <?php
            $grand_total = 0;

            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $index => $item) {
                    $grand_total += $item['subtotal'];
                    echo "<tr>
                            <td>{$item['name']}</td>
                            <td>Rs. ".number_format($item['price'],2)."</td>
                            <td>{$item['quantity']}</td>
                            <td>Rs. ".number_format($item['subtotal'],2)."</td>
                            <td><a href='billing.php?remove=$index' style='color:red;'>Remove</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No items in cart</td></tr>";
            }
            ?>
        </table>

        <div class="total-box">
            <strong>Grand Total: Rs. <?= number_format($grand_total, 2); ?></strong>
        </div>

        <?php if (!empty($_SESSION['cart'])) { ?>
            <form method="post" style="margin-top:10px;">
                <button name="save_bill" class="checkout">
                    💾 Save & Complete Bill
                </button>
            </form>

            <form method="post" style="margin-top:10px;">
                <button name="clear_bill" style="background:#dc3545;">
                    ❌ Clear Bill
                </button>
            </form>
        <?php } ?>

    </div>

</div>

</body>
</html>
