<?php
session_start();
require '../includes/db.php';

// Not logged in? Go to login
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

if (isset($_GET['delete_id'])) {
    // Get the region's image filenames first
    $stmt = $pdo->prepare("SELECT main_image, gallery1, gallery2, gallery3 FROM regions WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    $region = $stmt->fetch();

    // Delete image files
    if ($region) {
        $upload_dir = '../assets/images/';
        if ($region['main_image'] && file_exists($upload_dir . $region['main_image'])) {
            unlink($upload_dir . $region['main_image']);
        }
        if ($region['gallery1'] && file_exists($upload_dir . $region['gallery1'])) {
            unlink($upload_dir . $region['gallery1']);
        }
        if ($region['gallery2'] && file_exists($upload_dir . $region['gallery2'])) {
            unlink($upload_dir . $region['gallery2']);
        }
        if ($region['gallery3'] && file_exists($upload_dir . $region['gallery3'])) {
            unlink($upload_dir . $region['gallery3']);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM regions WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);

    $_SESSION['success'] = 'تم حذف السجل بنجاح';
    header('Location: dashboard.php');
    exit;
}

$success = '';
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}


$stmt = $pdo->query("SELECT * FROM regions");
$regions = $stmt->fetchAll(); // Get all rows.

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>معالم السعودية - مشرف</title>
  <link rel="stylesheet" href="../assets/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="admin">
   <nav>
   <div class="logo">
    <img src="../assets/images/logo.png" alt="اكتشف السعودية" height="40">
    <h1>لوحة تحكم المشرف</h1>
   </div>

  <div class="nav-links">
    <button id="nightMode">الوضع الليلي</button>

    <form method="POST" action="" style="display:inline;">
    <button type="submit" name="logout" class="del">تسجيل الخروج</button>
    </form>

  </div>
  </nav>
  
 <div class="hero-card">
    <h2>إدارة المحتوى</h2>
    <p>استخدم هذه الصفحة لإدارة محتوى الموقع من خلال عرض السجلات وإضافة أو تعديل أو حذف المحتوى</p>

    <?php if ($success): ?>
        <div class="success-box"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <table id="admin_content">
        <thead>
            <tr>
                <th>ID</th>
                <th>المنطقة</th>
                <th>التصنيف</th>
                <th>الوصف</th>
                <th>الإجراءات</th>
            </tr>
        </thead>  
        <tbody>
            <?php foreach ($regions as $region): ?>  <!-- foreach region(row) in regions(table). -->
            <tr>
                <td><?= $region['id'] ?></td>
                <td><?= htmlspecialchars($region['name']) ?></td>
                <td><?= htmlspecialchars($region['category']) ?></td>
                <td><?= htmlspecialchars($region['short_description']) ?></td>
                <td>
                <a href="update.php?id=<?= $region['id'] ?>" class="hero-btn">تعديل</a> <!-- Redirect to the UPDATE page, with the id being in the URL to use in the code. -->
                <a href="?delete_id=<?= $region['id'] ?>" class="del delete-link">حذف</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="add.php" class="hero-btn">إضافة محتوى جديد</a>

</div>
<?php require '../includes/footer.php'; ?>