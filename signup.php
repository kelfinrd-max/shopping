<?php include 'db.php'; ?>
<?php
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if ($conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')")) {
        header("Location: login.php");
        exit;
    } else echo "<script>alert('Username exists');</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sign Up</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="auth-bg">
  <div class="auth-card animate-fade">
    <h1 class="auth-title">Create Account</h1>
    <form method="post">
      <input name="username" class="auth-input" placeholder="Username" required>
      <input name="password" type="password" class="auth-input" placeholder="Password" required>
      <button name="signup" class="auth-btn">Sign Up</button>
    </form>
    <p class="text-center mt-4">Already have one? <a href="login.php" class="link">Login</a></p>
  </div>
</body>
</html>
