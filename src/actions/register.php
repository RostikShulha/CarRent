<?php

require_once __DIR__ . '/../helpers.php';

// Перенесення даних з $_POST в окремі змінні
$avatarPath = null;
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$passwordConfirmation = $_POST['password_confirmation'] ?? null;
$avatar = $_FILES['avatar'] ?? null;

// Перевірка отриманих даних з форми
if (empty($name)) {
    setValidationError('name', 'Невірне ім\'я');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setValidationError('email', 'Неправильна пошта');
}

if (findUser($email)) {
    setValidationError('email', 'Ця електронна пошта вже зареєстрована');
    redirect('/register.php');
}

if (empty($password)) {
    setValidationError('password', 'Пароль пустий');
}

if ($password !== $passwordConfirmation) {
    setValidationError('password', 'Паролі не співпадають');
}

// Перевірка аватара
if ($avatar == null || $avatar['error'] != UPLOAD_ERR_OK) {
    setValidationError('avatar', 'Файл не був завантажений або виникла помилка при завантаженні');
}

if (!empty($avatar) && $avatar['error'] == UPLOAD_ERR_OK) {
    $types = ['image/jpeg', 'image/png'];

    if (!in_array($avatar['type'], $types)) {
        setValidationError('avatar', 'На зображенні профілю вказано неправильний тип файлу');
    }

    if (($avatar['size'] / 1000000) >= 1) {
        setValidationError('avatar', 'Розмір зображення не повинен перевищувати 1 МБ');
    }
}

// Якщо список з помилками валідації не порожній, то перенаправляє назад на форму
if (!empty($_SESSION['validation'])) {
    setOldValue('name', $name);
    setOldValue('email', $email);
    redirect('/register.php');
}

// Завантажте аватар, якщо він був відправлений у формі
if (!empty($avatar) && $avatar['error'] == UPLOAD_ERR_OK) {
    $avatarPath = uploadFile($avatar, 'avatar', 'register.php');
}

// Перевірка, чи аватар був успішно завантажений
if (!$avatarPath) {
    setValidationError('avatar', 'Завантаження аватара не вдалося');
    redirect('/register.php');
}

$pdo = getPDO();

$query = "INSERT INTO users (name, email, avatar, password) VALUES (:name, :email, :avatar, :password)";

$params = [
    'name' => $name,
    'email' => $email,
    'avatar' => $avatarPath,
    'password' => password_hash($password, PASSWORD_DEFAULT)
];

$stmt = $pdo->prepare($query);

try {
    $stmt->execute($params);
} catch (\Exception $e) {
    die($e->getMessage());
}

redirect('/');