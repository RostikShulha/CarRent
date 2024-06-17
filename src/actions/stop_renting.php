<?php
require_once __DIR__ . '/../helpers.php';
checkAuth();

$user = currentUser();
$carId = $_POST['list_id'] ?? null;
$userId = $user['id'];

error_log("userId: $userId");
error_log("carId: $carId");
error_log("POST data: " . print_r($_POST, true));

if (isset($_POST['stop'])) { // Перевірка наявності POST-запиту та car_id

    $pdo = getPDO();
    $stmt = $pdo->prepare("UPDATE transactions
      SET transaction_client_bats = :userId
      WHERE user_id = :userId
        AND list_id = :carId
        AND CURDATE() BETWEEN transaction_start_date AND transaction_end_date
        AND transaction_admin_bats = 0
        AND transaction_client_bats = 0");

    $stmt->execute(['userId' => $userId, 'carId' => $carId]);

    // Оновлення стану автомобіля на 3 (або будь-яке інше значення за необхідності)
    $stmt = $pdo->prepare("UPDATE car_list SET stan_id = 3 WHERE list_id = :carId");
    $stmt->execute(['carId' => $carId]);

    // Перенаправлення на сторінку з даними про автомобіль
    redirect("/car.php?id={$carId}");
} else {
    error_log("Redirecting to home, condition failed.");
    // Якщо POST-запит не був викликаний або відсутній car_id, перенаправлення на головну сторінку
    redirect('/');
}
?>