<?php
require 'includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: gallery.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM regions WHERE id = ?");
$stmt->execute([$id]);
$region = $stmt->fetch();

if (!$region) {
    header('Location: gallery.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($region['name']) ?>  معالم السعودية - </title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<?php require 'includes/header.php'; ?>

<div class="hero-card" id="detailsCard">

    <?php if ($region['main_image']): ?>
        <img src="/assets/images/<?= $region['main_image'] ?>" class="details-hero-img">
    <?php endif; ?>

    <h1><?= htmlspecialchars($region['name']) ?></h1>
    <p class="details-desc"><?= htmlspecialchars($region['description']) ?></p>


    <div id="quick-card">
    <h2>المعلومات السريعة</h2>
    <ul id="quickinfo-list">
        <li><strong>الموقع:</strong> <?= htmlspecialchars($region['category']) ?></li>
        <li><strong>أبرز المميزات:</strong> <?= htmlspecialchars($region['features']) ?></li>
        <li><strong>الأنشطة:</strong> <?= htmlspecialchars($region['short_description']) ?></li>
    </ul>
    </div>

    <h2>أبرز المعالم</h2>
    <ul id="landmarks-list">
        <?php
        $landmarks = explode('،', $region['landmarks']); // So it splits the landmarks by , .
        foreach ($landmarks as $landmark): ?>
            <li><?= htmlspecialchars(trim($landmark)) ?></li>
        <?php endforeach; ?>
    </ul>

    <h2 id='galleryDivide'>معرض الصور</h2>
    <div class="details-gallery">
        <?php if ($region['gallery1']): ?>
            <img src="/assets/images/<?= $region['gallery1'] ?>">
        <?php endif; ?>
        <?php if ($region['gallery2']): ?>
            <img src="/assets/images/<?= $region['gallery2'] ?>">
        <?php endif; ?>
        <?php if ($region['gallery3']): ?>
            <img src="/assets/images/<?= $region['gallery3'] ?>">
        <?php endif; ?>
    </div>

</div>

<?php require 'includes/footer.php'; ?>