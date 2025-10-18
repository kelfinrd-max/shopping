<?php include 'db.php'; ?>
<?php if (!isset($_SESSION['user_id'])) header("Location: login.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Your Cart</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <nav class="navbar">
    <h1 class="text-2xl font-bold">🛍 Your Cart</h1>
    <a href="index.php" class="nav-btn">Shop More</a>
  </nav>
  <div id="cartContainer" class="max-w-4xl mx-auto mt-8"></div>

  <script>
  function loadCart(){
    fetch('ajax.php?action=view')
    .then(r=>r.text())
    .then(html=>document.getElementById('cartContainer').innerHTML=html);
  }
  function updateQty(id,delta){
    fetch('ajax.php',{
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:'action=update&id='+id+'&delta='+delta
    }).then(r=>r.text()).then(()=>loadCart());
  }
  function applyCoupon(){
    let code=document.getElementById('coupon_code').value;
    fetch('ajax.php',{
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:'action=coupon&code='+code
    }).then(r=>r.text()).then(html=>document.getElementById('cartContainer').innerHTML=html);
  }
  loadCart();
  
function addToCart(pid) {
  fetch('ajax.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'action=add&pid=' + pid
  })
  .then(res => res.json())
  .then(data => showAlert(data.message, data.status));
}

function updateQty(id, delta) {
  fetch('ajax.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'action=update&id=' + id + '&delta=' + delta
  })
  .then(res => res.json())
  .then(() => viewCart());
}

function applyCoupon() {
  let code = document.getElementById('coupon_code').value;
  fetch('ajax.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'action=coupon&code=' + code
  })
  .then(res => res.text())
  .then(html => document.getElementById('cart-container').innerHTML = html);
}

function viewCart() {
  fetch('ajax.php?action=view')
  .then(res => res.text())
  .then(html => document.getElementById('cart-container').innerHTML = html);
}

function showAlert(message, type) {
  const box = document.createElement('div');
  box.className = 'fixed top-6 right-6 p-4 rounded-lg shadow-lg text-white ' +
                  (type === 'success' ? 'bg-green-600' : 'bg-red-600');
  box.innerText = message;
  document.body.appendChild(box);
  setTimeout(() => box.remove(), 3000);
}


  </script>
</body>
</html>
