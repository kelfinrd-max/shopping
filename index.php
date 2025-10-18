<?php include 'db.php'; ?>
<?php if (!isset($_SESSION['user_id'])) header("Location: login.php"); ?>
<?php
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$q = "SELECT * FROM products WHERE name LIKE '%$search%'";
if ($category) $q .= " AND category='$category'";
$products = $conn->query($q);
$cats = $conn->query("SELECT DISTINCT category FROM products");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Smart Cart</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <nav class="navbar">
    <div class="flex items-center gap-2">
      <img src="assets/logo.png" class="h-8"> 
      <h1 class="text-2xl font-bold">Smart Cart</h1>
    </div>
    <div class="flex gap-4">
      <form method="get" class="flex gap-2">
        <input name="search" value="<?= $search ?>" placeholder="Search products..." class="search-bar">
        <select name="category" class="filter-select">
          <option value="">All</option>
          <?php while($c = $cats->fetch_assoc()): ?>
          <option <?= $c['category']==$category?'selected':'' ?>><?= $c['category'] ?></option>
          <?php endwhile; ?>
        </select>
        <button class="nav-btn">Search</button>
      </form>
      <a href="wishlist.php" class="nav-btn">❤️ Wishlist</a>
      <a href="cart.php" class="nav-btn">🛒 Cart</a>
      <a href="logout.php" class="nav-btn">Logout</a>
    </div>
  </nav>

  <div class="banner">
    <img src="assets/banner.jpg" class="banner-img">
    <div class="banner-text">Shop Smarter. Save More 💸</div>
  </div>

  <div class="max-w-6xl mx-auto py-10 grid md:grid-cols-3 gap-8">
    <?php while($p = $products->fetch_assoc()): ?>
    <div class="product-card animate-slide">
      <img src="<?= $p['image'] ?>" class="product-img" />
      <h2 class="text-xl font-semibold mt-2"><?= $p['name'] ?></h2>
      <p class="text-yellow-500">⭐ <?= $p['rating'] ?>/5</p>
      <p class="text-gray-600 mb-3">$<?= $p['price'] ?></p>
      <div class="flex gap-2">
        <button class="add-btn flex-1" onclick="addToCart(<?= $p['id'] ?>)">Add to Cart</button>
        <button class="buy-btn flex-1" onclick="buyNow(<?= $p['id'] ?>)">Buy Now</button>
      </div>
      <button class="wishlist-btn mt-2" onclick="addWishlist(<?= $p['id'] ?>)">❤️ Add to Wishlist</button>
    </div>
    <?php endwhile; ?>
  </div>

  <script>
  function addToCart(pid){
    fetch('ajax.php', {
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:'action=add&pid='+pid
    }).then(r=>r.text()).then(alert);
  }
  function buyNow(pid){
    addToCart(pid);
    window.location='checkout.php';
  }
  function addWishlist(pid){
    fetch('ajax.php', {
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:'action=wishlist&pid='+pid
    }).then(r=>r.text()).then(alert);
  }
  </script>
</body>
</html>
