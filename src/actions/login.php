<?php

require_once __DIR__ . '/../helpers.php';

$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setOldValue('email', $email);
    setValidationError('email', 'Недійсний формат електронної пошти');
    setMessage('error', 'Помилка валідації');
    redirect('/');
}

$user = findUser($email);

if (!$user) {
    setMessage('error', "Користувача $email не знайдено");
    redirect('/');
}

if (!password_verify($password, $user['password'])) {
    setMessage('error', 'Невірний пароль');
    redirect('/');
}

$_SESSION['user']['id'] = $user['id'];

redirect('/home.php');