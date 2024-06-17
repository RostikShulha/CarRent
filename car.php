<?php
require_once __DIR__ . '/src/helpers.php';

try {
    checkAuth();
    
    $carId = $_GET['id'] ?? null;
    $user = currentUser();

    // Логування переданого ID
    error_log("Received car ID: " . $carId);

    if (!$carId) {
        error_log("Car ID is missing. Redirecting to home page.");
        redirect('/');
    }

    $pdo = getPDO();

    // Спрощений запит для отримання інформації про автомобіль
    $stmtCarList = $pdo->prepare("
        SELECT cl.*, 
               t.transmission_name, 
               f.fuel_name, 
               c.class_name, 
               m.marka_name, 
               md.model_name, 
               s.stan_name, 
               s.stan_id, 
               d.drive_name, 
               tr.user_id
        FROM car_list cl
        LEFT JOIN transmission t ON cl.transmission_id = t.transmission_id
        LEFT JOIN fuel f ON cl.fuel_id = f.fuel_id
        LEFT JOIN class c ON cl.class_id = c.class_id
        LEFT JOIN model md ON cl.model_id = md.model_id
        LEFT JOIN marka m ON md.marka_id = m.marka_id
        LEFT JOIN stan s ON cl.stan_id = s.stan_id
        LEFT JOIN drive d ON cl.drive_id = d.drive_id
        LEFT JOIN transactions tr ON cl.list_id = tr.list_id
        WHERE cl.list_id = :id
    ");

    $stmtCarList->execute(['id' => $carId]);
    $car = $stmtCarList->fetch(PDO::FETCH_ASSOC);

    // Логування результату запиту
    if ($car) {
        error_log("Car found: " . json_encode($car));
    } else {
        error_log("Car with ID $carId not found. Redirecting to home page.");
        redirect('/');
    }

    // Запит для отримання інформації про транзакції
    $stmtTransactions = $pdo->prepare("
        SELECT * 
        FROM car_list cl
        JOIN transactions tr ON cl.list_id = tr.list_id
        WHERE tr.user_id = :user_id 
        AND cl.list_id = :id 
        AND cl.stan_id = 1
    ");

    $stmtTransactions->execute(['id' => $carId, 'user_id' => $user['id']]);
    $transaction = $stmtTransactions->fetch(PDO::FETCH_ASSOC);

    // Тут можна додати додаткову логіку обробки даних

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    redirect('/');
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    redirect('/');
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($car['marka_name'] . ' ' . $car['model_name']) ?> &nbsp; <?= htmlspecialchars($car['list_year']) ?></title>
    <link rel="stylesheet" href="assets/qpp.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<header id="header" class="header">
    <div class="header__container" id="home">
        <a href="home.php" class="logo">Orend</a>
        <nav>
            <a href="user.php" class="user_link">
                <ul>
                <li>
                    <label class="header__label" for="avatar"><?php echo $user['name'] ?></label>
                </li>
                <li>
                    <img class="avatar" src="<?php echo $user['avatar'] ?>" alt="<?php echo $user['name'] ?>">
                </li>
            </ul>
            </a>
        </nav>
    </div>
</header>

<body>
    <div class="list__spis">
        <div class="list__block">
            <a href="home.php" class="list__button" id="list__button-home">Повернутися до вибору авто</a>
            <a 
                class="list__button" 
                id="list__button-stan" 
                <?= ($car['stan_id'] == '3') ? 'href="#date-picker"' : '' ?>
                data-stan-id="<?= htmlspecialchars($car['stan_id']) ?>"><?= htmlspecialchars($car['stan_name']) ?>
            </a>
        </div>
        <div class="list__block">
            <img src="<?= htmlspecialchars($car['list_picture']) ?>" alt="Фото авто" class="list__img">
            <div class="list__specs">
                <h1><?= htmlspecialchars($car['marka_name'] . ' ' . $car['model_name']) ?></h1>
                <p>Рік: <?= htmlspecialchars($car['list_year']) ?></p>
                <p>Об'єм двигуна: <?= htmlspecialchars($car['list_size']) ?> л</p>
                <p>Ціна: <?= number_format($car['list_price'], 0) ?> $ / доба</p>
                <p>Тип палива: <?= htmlspecialchars($car['fuel_name']) ?></p>
                <p>Клас: <?= htmlspecialchars($car['class_name']) ?></p>
                <p>Трансмісія: <?= htmlspecialchars($car['transmission_name']) ?></p>
                <p>Привід: <?= htmlspecialchars($car['drive_name']) ?></p>
                <p id="date-picker">Витрата палива: <?= number_format($car['list_consumption'], 2) ?> л / 100км</p>
            </div>
        </div>
        <?php if ($car['stan_id'] == '3'): ?>
        <div class="list__block">
            <form id="transaction-form" action="" class="list__form">
                <div class="list__label-date">
                    <label for="start-date">Дата початку оренди</label>
                    <input type="date" class="list__date" id="start-date" value="2000-01-01">
                </div>
                <div class="list__label-date">
                    <label for="end-date">Дата кінця оренди</label>
                    <input type="date" class="list__date" id="end-date" value="2000-01-01">
                </div>
            </form>
            <div class="list__specs list__specs_button_under">
                <button data-toggle="modal" id="date-submit" data-target="#create"></button>
                Розрахувати ціну
            </div>
        </div>
        <?php endif; ?>
        <?php if ($car['stan_id'] == 1 && $transaction && $transaction['user_id'] == $user['id']): ?>
            <form class="list__block" method="post" action="src/actions/stop_renting.php">
                <button id="list__stop-rent" name="stop">Припинити оренду</button>
                <input type="hidden" name="list_id" value="<?= htmlspecialchars($car['list_id']) ?>">
            </form>
        <?php endif ?>
        <div class="list__block">
            <div class="list__description"></div>
        </div>
    </div>
    <form class="modal" id="my-modal" method="post" action="src/actions/submit_transaction.php">
        <div class="modal__box modal_list">
            <button class="modal__close" id="close-my-modal-btn">X</button>
            <h2>Чек</h2>
            <div class="modal__inline">
                <div id="rental-user"><h4>Користувач:&nbsp;&nbsp;<?= htmlspecialchars($user['name']) ?></h4></div>
                <div id="rental-car"><h4>Авто:&nbsp;&nbsp;<?= htmlspecialchars($car['marka_name'] . ' ' . $car['model_name'])?> &nbsp; <?= htmlspecialchars($car['list_year']) ?></h4></div>
                <div id="rental-date-start"></div>
                <div id="rental-date-end"></div>
                <div id="rental-price"></div>
            </div>
            <div class="modal-footer"></div>
            <button type="submit" name="pay">Сплатити</button>
        </div>

        <!-- Приховані поля для передачі даних -->
        <input type="hidden" name="start_date" id="hidden-start-date">
        <input type="hidden" name="end_date" id="hidden-end-date">
        <input type="hidden" name="rental_days" id="hidden-rental-days">
        <input type="hidden" name="total_price" id="hidden-total-price">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
        <input type="hidden" name="car_id" value="<?= htmlspecialchars($carId) ?>">
    </form>
    <script>
        const priceString = <?= json_encode(number_format($car['list_price'], 0, '.', '')) ?>;
    </script>
    <script src="components/js/car.js"></script>
</body>
</html>