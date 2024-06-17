<?php

require_once __DIR__ . '/../helpers.php';

// Перенесення даних з $_POST в окремі змінні

$picturePath = null;
$stan = $_POST['stan'] ?? null;
$class = $_POST['class'] ?? null;
$model = $_POST['model'] ?? null;
$year = $_POST['year'] ?? null;
$drive = $_POST['drive'] ?? null;
$transmission = $_POST['transmission'] ?? null;
$fuel = $_POST['fuel'] ?? null;
$consumption = $_POST['consumption'] ?? null;
$size = $_POST['size'] ?? null;
$price = $_POST['price'] ?? null;
$picture = $_FILES['picture'] ?? null;

// Перевірка отриманих даних з форми

if ($picture == null || $picture['error'] != UPLOAD_ERR_OK) {
  setValidationError('picture', 'Файл не був завантажений або виникла помилка при завантаженні');
  $errors = true;
}

if (!empty($picture)) {
  $types = ['image/jpeg', 'image/png'];

  if (!in_array($picture['type'], $types)) {
    setValidationError('picture', 'На зображенні профілю вказано неправильний тип файлу');
  }

  if (($picture['size'] / 1000000) >= 1) {
    setValidationError('picture', 'Розмір зображення не повинен перевищувати 1 МБ');
  }
}

//  Завантаження аватарки, якщо вона була відправлена у формі

if (!empty($picture)) {
  $picturePath = uploadFile($picture, 'picture', 'home.php');
}

// $pdo = getPDO();

// $query = "INSERT INTO users (name, email, picture, password) VALUES (:name, :email, :picture, :password)";

// $params = [
//  'stan' => $name,
//  'email' => $email,
//  'picture' => $avatarPath,
// ];

// $stmt = $pdo->prepare($query);

// try {
//  $stmt->execute($params);
// } catch (\Exception $e) {
//  die($e->getMessage());
// }


$sql = "INSERT INTO car_list (stan_id, class_id, model_id, list_year, drive_id, transmission_id, fuel_id, list_consumption, list_size, list_price, list_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$query = $pdo->prepare($sql);
$query->execute([$stan, $class, $model, $year, $drive, $transmission, $fuel, $consumption, $size, $price, $picturePath]);

if ($query) {
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
}


redirect('/');