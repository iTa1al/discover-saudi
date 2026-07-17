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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['logout'])) { // We have !isset($_POST['logout']) in the if statement because the logout is a form with 'POST' and this rules out the logout form.
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $short_description = trim($_POST['short_description'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $features = trim($_POST['features'] ?? '');
    $landmarks = trim($_POST['landmarks'] ?? '');

    // Handle image uploads
    $upload_dir = '../assets/images/';
    $main_image = '';
    $gallery1 = '';
    $gallery2 = '';
    $gallery3 = '';

    if ($_FILES['main_image']['size'] > 0) { // This is _POST of file types, taking the image from _FILES, and checking is the size > 0? if not then there is no image.
        $main_image = time() . '_' . $_FILES['main_image']['name']; // This creates a unique file name, incase of uploading two files with the same name. (e.g.: 12359235_riyadh.jpg)
        move_uploaded_file($_FILES['main_image']['tmp_name'], $upload_dir . $main_image); // tmp_name is the path of the image, and we will move the file to the image directory.
    }

    if ($_FILES['gallery1']['size'] > 0) {
        $gallery1 = time() . '_g1_' . $_FILES['gallery1']['name'];
        move_uploaded_file($_FILES['gallery1']['tmp_name'], $upload_dir . $gallery1);
    }

    if ($_FILES['gallery2']['size'] > 0) {
        $gallery2 = time() . '_g2_' . $_FILES['gallery2']['name'];
        move_uploaded_file($_FILES['gallery2']['tmp_name'], $upload_dir . $gallery2);
    }

    if ($_FILES['gallery3']['size'] > 0) {
        $gallery3 = time() . '_g3_' . $_FILES['gallery3']['name'];
        move_uploaded_file($_FILES['gallery3']['tmp_name'], $upload_dir . $gallery3);
    }

    $stmt = $pdo->prepare("INSERT INTO regions (name, category, short_description, description, features, landmarks, main_image, gallery1, gallery2, gallery3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $category, $short_description, $description, $features, $landmarks, $main_image, $gallery1, $gallery2, $gallery3]);

    $_SESSION['success'] = 'تم إضافة السجل بنجاح';
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إضافة معلم</title>
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
    <a href="dashboard.php">لوحة التحكم</a>
    <button id="nightMode">الوضع الليلي</button>
    <form method="POST" action="" style="display:inline;">
    <button type="submit" name="logout" class="del">تسجيل الخروج</button>
    </form>

  </div>
  </nav>
  
 <div class="hero-card" id ="addCard">
    <h2>إضافة مكان جديد</h2>

  <form method="POST" action="" id="addForm" enctype="multipart/form-data">
    <label>اسم المكان*</label>
    <input type="text" name="name" maxlength="100" placeholder="مثال: الرياض">

    <label>الصورة الرئيسية للمكان*</label>
    <input type="file" name="main_image" class="img-upload" accept="image/*">

    <label>الوصف*</label>
    <textarea name="description" class="form-field" rows="4" placeholder="اكتب وصفاً تفصيلياً..."></textarea>

    <label>الموقع*</label>
    <select name="category" class="form-field">
        <option value="">اختر المنطقة...</option>
        <option value="وسطى">وسطى</option>
        <option value="غربية">غربية</option>
        <option value="شرقية">شرقية</option>
        <option value="جنوبية">جنوبية</option>
        <option value="شمالية">شمالية</option>
    </select>

    <label>المميزات*</label>
    <input type="text" name="features" placeholder="مثال: مواقع أثرية، طبيعة جميلة">

    <label>الأنشطة*</label>
    <input type="text" name="short_description" maxlength="255" placeholder="مثال: زيارة المتاحف، التسوق">

    <label>المعالم* (الفصل بينها بفاصلة)</label>
    <input type="text" name="landmarks" placeholder="مثال: برج المملكة، قصر المصمك، الدرعية">

    <h3>صور المعرض</h3>

    <label>صورة المعرض الأولى*</label>
    <input type="file" name="gallery1" class="img-upload" accept="image/*">

    <label>صورة المعرض الثانية (اختياري)</label>
    <input type="file" name="gallery2" class="img-upload" accept="image/*">

    <label>صورة المعرض الثالثة (اختياري)</label>
    <input type="file" name="gallery3" class="img-upload" accept="image/*">

    <button type="submit" class="hero-btn">إضافة المكان</button>
</form>
</div>
<?php require '../includes/footer.php'; ?>