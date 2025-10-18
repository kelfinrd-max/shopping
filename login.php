<?php include 'db.php'; ?>
<?php
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $res = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit;
        } else echo "<script>alert('Wrong password');</script>";
    } else echo "<script>alert('User not found');</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="auth-bg">
  <div class="auth-card animate-fade">
    <h1 class="auth-title">Welcome Back</h1>
    <form method="post">
      <input name="username" class="auth-input" placeholder="Username" required>
      <input name="password" type="password" class="auth-input" placeholder="Password" required>
      <button name="login" class="auth-btn">Login</button>
    </form>
    <p class="text-center mt-4">No account? <a href="signup.php" class="link">Sign Up</a></p>
  </div>
</body>
</html>
