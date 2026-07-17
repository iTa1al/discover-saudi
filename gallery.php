<?php
require 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM regions");
$regions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معرض المناطق</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<?php require 'includes/header.php'; ?>

<div class="hero-card" id="galleryHeader">
    <h1>معرض المناطق</h1>
    <p>ابحث أو فلتر النتائج ثم اضغط على أي منطقة للانتقال إلى صفحة التفاصيل.</p>

    <div class="gallery-filters">
        <input type="text" id="searchInput" placeholder="ابحث عن منطقة أو مدينة...">
        <select id="categoryFilter" class="form-field">
            <option value="الكل">كل المناطق</option>
            <option value="وسطى">وسطى</option>
            <option value="غربية">غربية</option>
            <option value="شرقية">شرقية</option>
            <option value="جنوبية">جنوبية</option>
            <option value="شمالية">شمالية</option>
        </select>
        <span id="resultCount">عدد النتائج: 0</span>
    </div>


    <div class="gallery-grid">
        <?php foreach ($regions as $region): ?>
        <a href="details.php?id=<?= $region['id'] ?>" class="gallery-item">
            <?php if ($region['main_image']): ?>
                <img src="/assets/images/<?= $region['main_image'] ?>" alt="<?= htmlspecialchars($region['name']) ?>">
            <?php endif; ?>
            <span class="gallery-category"><?= htmlspecialchars($region['category']) ?></span>
            <h3><?= htmlspecialchars($region['name']) ?></h3>
            <p><?= htmlspecialchars($region['short_description']) ?></p>
        </a>
        <?php endforeach; ?>
    </div>

</div>

<?php require 'includes/footer.php'; ?>