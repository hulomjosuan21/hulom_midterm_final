<?php
require_once 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$productCRUD = new ProductCRUD();
$cartCRUD = new CartCRUD();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if (isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
            $cartCRUD->addToCart($user_id, $_POST['product_id'], $quantity);
            header("Location: index.php");
            exit;
        } elseif (isset($_POST['action']) && $_POST['action'] === 'buy_now') {
            $cartCRUD->buyNow($user_id, $_POST['product_id'], $quantity);
            header("Location: confirmation.php");
            exit;
        }
    }
}

$products = $productCRUD->readProducts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Hulom Midterm - Products</title>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">Josuan Shop</div>
            <ul class="nav-links">
                <a href="index.php">Products</a>
                <a href="cart.php">Cart</a>
            </ul>
            <div class="cart">
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="products">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($product->image_url); ?>"
                        alt="<?= htmlspecialchars($product->product_name); ?>" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title"><?= htmlspecialchars($product->product_name); ?></h3>
                        <p class="product-price">$<?= number_format($product->price, 2); ?></p>
                        <div class="product-actions">
                            <form action="index.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product->product_id; ?>">
                                <input type="number" name="quantity" value="1" min="1" class="quantity-input" required>
                                <button type="submit" name="action" value="add_to_cart" class="btn add-to-cart-btn">Add to Cart</button>
                            </form>
                            <form action="index.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product->product_id; ?>">
                                <input type="number" name="quantity" value="1" min="1" class="quantity-input" required>
                                <button type="submit" name="action" value="buy_now" class="btn buy-now-btn">Buy Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </main>

</body>

</html>