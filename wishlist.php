<?php include 'db.php'; ?>
<?php if (!isset($_SESSION['user_id'])) header("Location: login.php"); ?>
<?php $uid = $_SESSION['user_id']; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Wishlist</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <nav class="navbar">
    <h1 class="text-2xl font-bold">❤️ Your Wishlist</h1>
    <a href="index.php" class="nav-btn">← Back to Shop</a>
  </nav>

  <div class="max-w-6xl mx-auto py-10 grid md:grid-cols-3 gap-8">
    <?php
    $w = $conn->query("SELECT p.* FROM wishlist w JOIN products p ON w.product_id=p.id WHERE w.user_id=$uid");
    while($p=$w->fetch_assoc()):
    ?>
    <div class="product-card">
      <img src="<?= $p['image'] ?>" class="product-img" />
      <h2 class="text-xl font-semibold mt-2"><?= $p['name'] ?></h2>
      <p class="text-gray-600 mb-3">$<?= $p['price'] ?></p>
      <button class="add-btn" onclick="addToCart(<?= $p['id'] ?>)">Move to Cart</button>
    </div>
    <?php endwhile; ?>
  </div>

  <script>
  function addToCart(pid){
    fetch('ajax.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=add&pid='+pid})
    .then(r=>r.text()).then(alert);
  }
  </script>
</body>
</html>
