<?php include 'db.php'; ?>
<?php if (!isset($_SESSION['user_id'])) header("Location: login.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <nav class="navbar">
    <h1 class="text-2xl font-bold">💳 Checkout</h1>
    <a href="cart.php" class="nav-btn">← Back to Cart</a>
  </nav>

  <div class="checkout-container animate-fade">
    <h2 class="text-2xl font-semibold mb-4">Order Summary</h2>
    <?php
    $uid = $_SESSION['user_id'];
    $cart = $conn->query("SELECT p.name,p.price,c.quantity FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=$uid");
    $total = 0;
    while($r=$cart->fetch_assoc()){
      $st=$r['price']*$r['quantity'];
      $total+=$st;
      echo "<p>{$r['name']} (x{$r['quantity']}) - $$st</p>";
    }
    echo "<hr class='my-4'>";
    echo "<p class='text-lg font-bold'>Total: $$total</p>";
    ?>
    <button class="checkout-btn mt-4">Place Order</button>
  </div>
</body>
</html>
