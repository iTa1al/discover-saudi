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

// Delete from update page
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("SELECT main_image, gallery1, gallery2, gallery3 FROM regions WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    $del_region = $stmt->fetch();

    if ($del_region) {
        $upload_dir = '../assets/images/';
        if ($del_region['main_image'] && file_exists($upload_dir . $del_region['main_image'])) unlink($upload_dir . $del_region['main_image']);
        if ($del_region['gallery1'] && file_exists($upload_dir . $del_region['gallery1'])) unlink($upload_dir . $del_region['gallery1']);
        if ($del_region['gallery2'] && file_exists($upload_dir . $del_region['gallery2'])) unlink($upload_dir . $del_region['gallery2']);
        if ($del_region['gallery3'] && file_exists($upload_dir . $del_region['gallery3'])) unlink($upload_dir . $del_region['gallery3']);
    }

    $stmt = $pdo->prepare("DELETE FROM regions WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);

    $_SESSION['success'] = 'تم حذف السجل بنجاح';
    header('Location: dashboard.php');
    exit;
}



// Get the region to edit
$id = $_GET['id'] ?? null; // Get the current id in the URL, and check if it exists otherwise send back.
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM regions WHERE id = ?"); // Now get the row that has that id.
$stmt->execute([$id]);
$region = $stmt->fetch();

if (!$region) { // If that id does not exist just return.
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['logout'])) {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $short_description = trim($_POST['short_description'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $features = trim($_POST['features'] ?? '');
    $landmarks = trim($_POST['landmarks'] ?? '');

    // Keep existing images unless new ones uploaded
    $upload_dir = '../assets/images/';
    $main_image = $region['main_image'];
    $gallery1 = $region['gallery1'];
    $gallery2 = $region['gallery2'];
    $gallery3 = $region['gallery3']; // So these previous are taking the current images the row has.

    if ($_FILES['main_image']['size'] > 0) { // And now checks the _FILES if it has new images or not for each, if so update.
        if ($main_image && file_exists($upload_dir . $main_image)) unlink($upload_dir . $main_image);
        $main_image = time() . '_' . $_FILES['main_image']['name'];
        move_uploaded_file($_FILES['main_image']['tmp_name'], $upload_dir . $main_image);
    }

    if ($_FILES['gallery1']['size'] > 0) {
        if ($gallery1 && file_exists($upload_dir . $gallery1)) unlink($upload_dir . $gallery1);
        $gallery1 = time() . '_g1_' . $_FILES['gallery1']['name'];
        move_uploaded_file($_FILES['gallery1']['tmp_name'], $upload_dir . $gallery1);
    }

    if ($_FILES['gallery2']['size'] > 0) {
        if ($gallery2 && file_exists($upload_dir . $gallery2)) unlink($upload_dir . $gallery2);
        $gallery2 = time() . '_g2_' . $_FILES['gallery2']['name'];
        move_uploaded_file($_FILES['gallery2']['tmp_name'], $upload_dir . $gallery2);
    }

    if ($_FILES['gallery3']['size'] > 0) {
        if ($gallery3 && file_exists($upload_dir . $gallery3)) unlink($upload_dir . $gallery3);
        $gallery3 = time() . '_g3_' . $_FILES['gallery3']['name'];
        move_uploaded_file($_FILES['gallery3']['tmp_name'], $upload_dir . $gallery3);
    }

    $stmt = $pdo->prepare("UPDATE regions SET name=?, category=?, short_description=?, description=?, features=?, landmarks=?, main_image=?, gallery1=?, gallery2=?, gallery3=? WHERE id=?");
    $stmt->execute([$name, $category, $short_description, $description, $features, $landmarks, $main_image, $gallery1, $gallery2, $gallery3, $id]);

    $_SESSION['success'] = 'تم تحديث السجل بنجاح';
    header('Location: dashboard.php');
    exit;
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
  
 <div class="update-container">
    <!-- Right card: Preview -->
    <div class="hero-card" id="previewCard">
    <h2>المعلومات الحالية</h2>
    <p>المكان المحدد للتحديث: <strong><?= htmlspecialchars($region['name']) ?></strong></p>

    <a href="?delete_id=<?= $region['id'] ?>" class="del delete-link">حذف</a>

    <h3>المعاينة</h3>

    <h4>الصورة الرئيسية الحالية</h4>
    <?php if ($region['main_image']): ?>
        <img src="../assets/images/<?= $region['main_image'] ?>" class="preview-img">
    <?php else: ?>
        <p>لا توجد صورة</p>
    <?php endif; ?>

    <h4>صور المعرض الحالية</h4>
    <div class="preview-gallery">
        <?php if ($region['gallery1']): ?>
            <img src="../assets/images/<?= $region['gallery1'] ?>" class="preview-thumb">
        <?php endif; ?>
        <?php if ($region['gallery2']): ?>
            <img src="../assets/images/<?= $region['gallery2'] ?>" class="preview-thumb">
        <?php endif; ?>
        <?php if ($region['gallery3']): ?>
            <img src="../assets/images/<?= $region['gallery3'] ?>" class="preview-thumb">
        <?php endif; ?>
    </div>
    </div>

        <div class="hero-card" id="addCard">
        <h2>تحديث مكان</h2>

        <form method="POST" action="" id="updateForm" enctype="multipart/form-data">
            <label>اسم المكان*</label>
            <input type="text" name="name" maxlength="100" value="<?= htmlspecialchars($region['name']) ?>">

            <label>تحديث الصورة الرئيسية (اختياري)</label>
            <input type="file" name="main_image" class="img-upload" accept="image/*">

            <label>الوصف*</label>
            <textarea name="description" class="form-field" rows="4"><?= htmlspecialchars($region['description']) ?></textarea>

            <label>الموقع*</label>
            <select name="category" class="form-field">
                <option value="وسطى" <?= $region['category'] === 'وسطى' ? 'selected' : '' ?>>وسطى</option>
                <option value="غربية" <?= $region['category'] === 'غربية' ? 'selected' : '' ?>>غربية</option>
                <option value="شرقية" <?= $region['category'] === 'شرقية' ? 'selected' : '' ?>>شرقية</option>
                <option value="جنوبية" <?= $region['category'] === 'جنوبية' ? 'selected' : '' ?>>جنوبية</option>
                <option value="شمالية" <?= $region['category'] === 'شمالية' ? 'selected' : '' ?>>شمالية</option>
            </select>

            <label>المميزات*</label>
            <input type="text" name="features" value="<?= htmlspecialchars($region['features']) ?>">

            <label>الأنشطة*</label>
            <input type="text" name="short_description" maxlength="255" value="<?= htmlspecialchars($region['short_description']) ?>">

            <label>المعالم* (الفصل بينها بفاصلة)</label>
            <input type="text" name="landmarks" value="<?= htmlspecialchars($region['landmarks']) ?>">

            <h3>تحديث صور المعرض (اختياري)</h3>

            <label>صورة المعرض الأولى</label>
            <input type="file" name="gallery1" class="img-upload" accept="image/*">

            <label>صورة المعرض الثانية</label>
            <input type="file" name="gallery2" class="img-upload" accept="image/*">

            <label>صورة المعرض الثالثة</label>
            <input type="file" name="gallery3" class="img-upload" accept="image/*">

            <button type="submit" class="hero-btn">حفظ التعديلات</button>
        </form>
    </div>

</div>

<?php require '../includes/footer.php'; ?>