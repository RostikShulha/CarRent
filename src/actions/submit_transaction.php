<?php
require_once __DIR__ . '/../helpers.php';
checkAuth();

$user = currentUser();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay'])) {
    $userId = $_POST['user_id'];
    $carId = $_POST['car_id'];
    $transaction_start_date = $_POST['start_date'];
    $transaction_end_date = $_POST['end_date'];
    $rental_days = $_POST['rental_days'];
    $transaction_price = $_POST['total_price'];

    if (!$carId || !$transaction_start_date || !$transaction_end_date || !$rental_days || !$transaction_price) {
        // Перевірка на наявність всіх необхідних даних
        redirect('/');
    }

    $pdo = getPDO();
    $stmt = $pdo->prepare("INSERT INTO transactions (list_id, user_id, transaction_price, transaction_start_date, transaction_end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$carId, $user['id'], $transaction_price, $transaction_start_date, $transaction_end_date]);

    // Оновлення стану автомобіля на 1, якщо дата початку транзакції менше або дорівнює сьогоднішній даті
    // та дата завершення транзакції більше або дорівнює сьогоднішній даті
    if ($transaction_start_date <= date('Y-m-d') && $transaction_end_date >= date('Y-m-d')) {
        $stmt = $pdo->prepare("UPDATE car_list SET stan_id = 1 WHERE list_id = ?");
        $stmt->execute([$carId]);
    }

    // Перенаправлення на сторінку з даними про автомобіль
    redirect("/car.php?id={$carId}");
}
?>