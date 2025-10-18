<?php
include 'db.php';

$uid = $_SESSION['user_id'] ?? 0;
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

header('Content-Type: text/html; charset=utf-8');

// ====================== ADD TO CART ======================
if ($action === 'add') {
    $pid = intval($_POST['pid']);
    if (!$uid) {
        echo json_encode(["status" => "error", "message" => "Please log in to add items."]);
        exit;
    }

    $exists = $conn->query("SELECT * FROM cart WHERE user_id=$uid AND product_id=$pid");
    if ($exists->num_rows)
        $conn->query("UPDATE cart SET quantity=quantity+1 WHERE user_id=$uid AND product_id=$pid");
    else
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($uid,$pid,1)");

    echo json_encode(["status" => "success", "message" => "🛒 Added to cart!"]);
    exit;
}

// ====================== UPDATE QUANTITY ======================
if ($action === 'update') {
    $id = intval($_POST['id']);
    $delta = intval($_POST['delta']);
    $conn->query("UPDATE cart SET quantity=GREATEST(quantity+$delta,1) WHERE id=$id AND user_id=$uid");
    echo json_encode(["status" => "success", "message" => "Quantity updated."]);
    exit;
}

// ====================== APPLY COUPON / VIEW CART ======================
if ($action === 'coupon' || $action === 'view') {
    $discount = 0;

    if ($action === 'coupon' && isset($_POST['code'])) {
        $code = $conn->real_escape_string(trim($_POST['code']));
        $r = $conn->query("SELECT discount FROM coupons WHERE code='$code'");
        if ($r && $r->num_rows > 0) {
            $row = $r->fetch_assoc();
            $discount = $row['discount'];
            echo "<script>showAlert('Coupon Applied: $discount% OFF', 'success');</script>";
        } else {
            echo "<script>showAlert('Invalid Coupon Code!', 'error');</script>";
        }
    }


    $cart = $conn->query("SELECT c.id,p.name,p.price,c.quantity 
                          FROM cart c 
                          JOIN products p ON c.product_id=p.id 
                          WHERE c.user_id=$uid");

    $total = 0;
    echo "<div class='cart-box animate-fade'>";
    echo "<table class='cart-table w-full text-sm md:text-base'>
            <tr class='bg-gray-200 text-gray-800'>
                <th class='p-2'>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>";

    while ($r = $cart->fetch_assoc()) {
        $st = $r['price'] * $r['quantity'];
        $total += $st;
        echo "<tr class='border-b'>
                <td class='p-2'>{$r['name']}</td>
                <td>
                    <button class='qty-btn' onclick='updateQty({$r['id']},-1)'>−</button>
                    <span class='px-2'>{$r['quantity']}</span>
                    <button class='qty-btn' onclick='updateQty({$r['id']},1)'>+</button>
                </td>
                <td>\${$r['price']}</td>
                <td>\$$st</td>
              </tr>";
    }

    $final = $total - ($total * ($discount / 100));
    echo "</table>
        <div class='coupon-area flex mt-4'>
          <input id='coupon_code' placeholder='Coupon Code' class='coupon-input border p-2 rounded-l-md flex-grow'>
          <button onclick='applyCoupon()' class='coupon-btn bg-blue-600 text-white px-4 rounded-r-md'>Apply</button>
        </div>
        <div class='mt-4 text-right'>
          <p class='text-gray-700'>Subtotal: <b>\$$total</b></p>
          <p class='text-green-600'>Discount: $discount%</p>
          <p class='text-blue-700 font-bold text-xl'>Final Total: \$$final</p>
          <button class='checkout-btn mt-4 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition'
            onclick=\"window.location='checkout.php'\">
            Proceed to Checkout
          </button>
        </div>
    </div>";
    exit;
}

// ====================== ADD TO WISHLIST ======================
if ($action === 'wishlist') {
    $pid = intval($_POST['pid']);
    if (!$uid) {
        echo json_encode(["status" => "error", "message" => "Please log in to save wishlist."]);
        exit;
    }

    $conn->query("INSERT IGNORE INTO wishlist (user_id, product_id) VALUES ($uid,$pid)");
    echo json_encode(["status" => "success", "message" => "❤️ Added to Wishlist!"]);
    exit;
}

// ====================== INVALID REQUEST ======================
echo json_encode(["status" => "error", "message" => "Invalid request."]);
exit;
?>
