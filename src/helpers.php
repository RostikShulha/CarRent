<?php

session_start();

require_once __DIR__ . '/config.php';

function redirect(string $path)
{
    header("Location: $path");
    die();
}

function setValidationError(string $fieldName, string $message): void
{
    $_SESSION['validation'][$fieldName] =  '<span class="error-message">' . $message . '</span>';
}

function hasValidationError(string $fieldName): bool
{
    return isset($_SESSION['validation'][$fieldName]);
}

function validationErrorAttr(string $fieldName): string
{
    return isset($_SESSION['validation'][$fieldName]) ? 'aria-invalid="true"' : '';
}

function validationErrorMessage(string $fieldName): string
{
    $message = $_SESSION['validation'][$fieldName] ?? '';
    unset($_SESSION['validation'][$fieldName]);
    return $message;
}

function setOldValue(string $key, mixed $value): void
{
    $_SESSION['old'][$key] = $value;
}

function old(string $key)
{
    $value = $_SESSION['old'][$key] ?? '';
    unset($_SESSION['old'][$key]);
    return $value;
}

function uploadFile(array $file, string $prefix = ''): string
{
    $uploadPath = __DIR__ . '/../uploads';

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = $prefix . '_' . time() . ".$ext";

    if (!move_uploaded_file($file['tmp_name'], "$uploadPath/$fileName")) {
        die('Помилка під час завантаження файлу на сервер');
    }

    return "uploads/$fileName";
}

function setMessage(string $key, string $message): void
{
    $_SESSION['message'][$key] = $message;
}

function hasMessage(string $key): bool
{
    return isset($_SESSION['message'][$key]);
}

function getMessage(string $key): string
{
    $message = $_SESSION['message'][$key] ?? '';
    unset($_SESSION['message'][$key]);
    return $message;
}

function getPDO(): PDO
{
    try {
        return new \PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8;dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
    } catch (\PDOException $e) {
        die("Connection error: {$e->getMessage()}");
    }
}

function findUser(string $email): array|bool
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}


function currentUser(): array|false
{
    $pdo = getPDO();

    if (!isset($_SESSION['user'])) {
        return false;
    }

    $userId = $_SESSION['user']['id'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

function currentCar(): array|false
{
    $pdo = getPDO();

    if (!isset($_SESSION['car'])) {
        return false;
    }

    $carId = $_SESSION['car']['id'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM car_list WHERE list_id = :id");
    $stmt->execute(['car' => $carId]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

function logout(): void
{
    unset($_SESSION['user']['id']);
    redirect('/');
}

function checkAuth(): void
{
    if (!isset($_SESSION['user']['id'])) {
        redirect('/');
    }
}

function checkGuest(): void
{
    if (isset($_SESSION['user']['id'])) {
        redirect('/home.php');
    }
}

include 'connect.php';

// Запит для отримання списку авто
function getCars($pdo, $sort, $classes, $drive, $transmission, $fuel, $stan) {
    $orderBy = '';

    switch ($sort) {
        case '1':
            $orderBy = 'ORDER BY car_list.list_price ASC';
            break;
        case '2':
            $orderBy = 'ORDER BY car_list.list_price DESC';
            break;
        case '3':
            $orderBy = 'ORDER BY car_list.list_consumption ASC';
            break;
        case '4':
            $orderBy = 'ORDER BY car_list.list_consumption DESC';
            break;
        case '5':
            $orderBy = 'ORDER BY car_list.list_year ASC';
            break;
        case '6':
            $orderBy = 'ORDER BY car_list.list_year DESC';
            break;
        default:
            $orderBy = '';
    }

    $filters = [];
    $params = [];

    if (!empty($classes)) {
        $placeholders = implode(',', array_fill(0, count($classes), '?'));
        $filters[] = "car_list.class_id IN ($placeholders)";
        $params = array_merge($params, $classes);
    }

    if (!empty($drive)) {
        $placeholders = implode(',', array_fill(0, count($drive), '?'));
        $filters[] = "car_list.drive_id IN ($placeholders)";
        $params = array_merge($params, $drive);
    }

    if (!empty($transmission)) {
        $placeholders = implode(',', array_fill(0, count($transmission), '?'));
        $filters[] = "car_list.transmission_id IN ($placeholders)";
        $params = array_merge($params, $transmission);
    }

    if (!empty($fuel)) {
        $placeholders = implode(',', array_fill(0, count($fuel), '?'));
        $filters[] = "car_list.fuel_id IN ($placeholders)";
        $params = array_merge($params, $fuel);
    }

    if (!empty($stan)) {
        $placeholders = implode(',', array_fill(0, count($stan), '?'));
        $filters[] = "car_list.stan_id IN ($placeholders)";
        $params = array_merge($params, $stan);
    }

    $filterSql = '';
    if (!empty($filters)) {
        $filterSql = 'WHERE ' . implode(' AND ', $filters);
    }

    $query = "SELECT 
        car_list.list_id,
        car_list.list_year,
        car_list.list_size,
        car_list.list_price,
        car_list.list_consumption,
        car_list.list_picture,
        transmission.transmission_name,
        fuel.fuel_name,
        class.class_name,
        marka.marka_name,
        model.model_name,
        stan.stan_name,
        drive.drive_name
    FROM
        car_list
    JOIN
        transmission ON car_list.transmission_id = transmission.transmission_id
    JOIN
        fuel ON car_list.fuel_id = fuel.fuel_id
    JOIN
        class ON car_list.class_id = class.class_id
    JOIN
        model ON car_list.model_id = model.model_id
    JOIN
        marka ON model.marka_id = marka.marka_id
    JOIN
        stan ON car_list.stan_id = stan.stan_id
    JOIN
        drive ON car_list.drive_id = drive.drive_id
    $filterSql
    $orderBy";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Отримання параметрів фільтрації з GET-запиту
$sort = isset($_GET['sort']) ? $_GET['sort'] : null;
$classes = isset($_GET['class']) ? $_GET['class'] : [];
$drive = isset($_GET['drive']) ? $_GET['drive'] : [];
$transmission = isset($_GET['transmission']) ? $_GET['transmission'] : [];
$fuel = isset($_GET['fuel']) ? $_GET['fuel'] : [];
$stan = isset($_GET['stan']) ? $_GET['stan'] : [];

$cars = getCars($pdo, $sort, $classes, $drive, $transmission, $fuel, $stan);


// Картка з даними про авто
function renderCar($car) {
    ?>
    <a href="car.php?id=<?= $car['list_id'] ?>" class="car__link">
        <div class="car">
            <div class="car__container-img">
                <?php
            $carImgClass = ($car['stan_name'] == 'Орендується') ? 'car__img_rented' : 'car__img';
            ?>
            <img class="<?= $carImgClass ?>" src="<?= htmlspecialchars($car['list_picture']) ?>">
            </div>
            <div class="car__block">
                <div class="car__text car__name-year">
                    <h3 class="car__name-car"><?= htmlspecialchars($car['marka_name'] . ' ' . $car['model_name']) ?></h3>
                    <h3 class="car__name-car"><?= htmlspecialchars($car['list_year']) ?></h3>
                </div>
                <div class="car__text">
                    <table class="car__specs">
                        <tr>
                            <td class="car__comp"><i class="icon-ico_fuel ico"></i><?= htmlspecialchars($car['fuel_name']) ?></td>
                            <td class="car__comp"><i class="icon-fuel-gauge_16645545 ico"></i><?= number_format($car['list_consumption'], 2) ?>л / 100км</td>
                        </tr>
                        <tr>
                            <td class="car__comp"><i class="icon-ico_transmission ico"></i><?= htmlspecialchars($car['transmission_name']) ?></td>
                            <td class="car__comp"><i class="icon-ico_drive ico"></i><?= htmlspecialchars($car['drive_name']) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="car__class-price">
                    <div class="car__class"><?= htmlspecialchars($car['class_name']) ?></div>
                    <div class="car__price"><?= number_format($car['list_price'], 0) ?> $ / доба</div>
                </div>
            </div>
        </div>
    </a>
    <?php
}

function ajax()
{
    $pdo = getPDO();

    // Обробка AJAX-запиту для отримання моделей
    if (isset($_POST['marka_id'])) {
        $markaID = $_POST['marka_id'];
        $stmt = $pdo->prepare("SELECT model_id, model_name FROM model WHERE marka_id = ? ORDER BY model_name ASC");
        $stmt->execute([$markaID]);
        $models = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($models as $model) {
            echo "<option value='{$model['model_id']}'>{$model['model_name']}</option>";
        }
        exit;
    }
}
