<?php
session_start();
require '../includes/db.php'; // Start the database connection via the code in db.php.

$error = ''; // Error holder.

if (isset($_SESSION['admin'])) { // When you already logged in, and didn't log out, you get redirected to the dashboard each time.
    header('Location: dashboard.php');
    exit; // Stop code here.
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? ''; // ?? '' Is just saying if there is no username/pass use the empty string.

    if ($username !== '' && $password !== '') {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?"); // Using mySQL "?" here, and remember "admins" is the SQL table with the admin users and passwords.
        $stmt->execute([$username]); // Execute the query.

        $admin = $stmt->fetch(); // Get the result.

        if ($admin && password_verify($password, $admin['password'])) { // $admin did we find the user? && we check the password via password_verify method.
            $_SESSION['admin'] = $admin['username']; // for a sucessfull login, now the username is stored in the global session array (we can use in each page).
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
        }
        }else{
            $error = '';
        }
    }
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>معالم السعودية</title>
  <link rel="stylesheet" href="../assets/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="admin">
   <nav>
   <div class="logo">
    <img src="../assets/images/logo.png" alt="اكتشف السعودية" height="40">
    <h1>لوحة التحكم</h1>
   </div>

  <div class="nav-links">
    <a href="../index.php">زيارة الموقع</a>
    <button id="nightMode">الوضع الليلي</button>
  </div>
  </nav>
  
  <div class="hero-card">
    <h2>تسجيل دخول المشرف</h2>

    <p class="error"><?= $error ? htmlspecialchars($error) : '&nbsp;' ?></p>

    <form method="POST" action="" id="loginForm">
        <label>اسم المستخدم</label>
        <input type="text" name="username" maxlength="50" placeholder="مثال: admin">
        <label>كلمة المرور</label>
        <input type="password" name="password" maxlength="255" placeholder="••••••••">
        <button type="submit" class="hero-btn">دخول</button>
    </form>
  </div>

<?php require '../includes/footer.php'; ?>