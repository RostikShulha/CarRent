<!DOCTYPE html>
<html lang="ua">
<head>
    <meta charset="UTF-8">
    <title>CAR оренда</title>
    <link rel="stylesheet" href="assets/qpp.css">
    <link rel="stylesheet" href="src/fonts/Icomoon/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
    require_once __DIR__ . '/src/helpers.php';
    include 'src/connect.php';

    checkAuth();

    $user = currentUser();

    $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
    $classes = isset($_GET['class']) ? $_GET['class'] : [];

    $cars = getCars($pdo, $sort, $classes);
    ?>

    <div class="card home">
        <img
            class="avatar"
            src="<?php echo $user['avatar']; ?>"
            alt="<?php echo $user['name']; ?>"
        >
        <h1>Привіт, <?php echo $user['name']; ?>!</h1>
        <form action="src/actions/logout.php" method="post">
            <button role="button">Вийти з облікового запису</button>
        </form>
    </div>

    <form method="GET" id="filter-form">
        <label for="sort">Сортування:
            <select name="sort" id="sort" onchange="document.getElementById('filter-form').submit()">
                <option value="1" <?php if ($sort == '1') echo 'selected'; ?>>Від дешевих до дорогих</option>
                <option value="2" <?php if ($sort == '2') echo 'selected'; ?>>Від дорогих до дешевих</option>
                <option value="3" <?php if ($sort == '3') echo 'selected'; ?>>Витрати палива, від менших до більших</option>
                <option value="4" <?php if ($sort == '4') echo 'selected'; ?>>Витрати палива, від більших до менших</option>
                <option value="5" <?php if ($sort == '5') echo 'selected'; ?>>Рік випуску, за зростанням</option>
                <option value="6" <?php if ($sort == '6') echo 'selected'; ?>>Рік випуску, за спаданням</option>
            </select>
        </label>

        <span>Клас:</span>
        <div>
            <i class="icon-ico_check"></i>
            <i>djkfsflj</i>
        </div>
        <div class="wrapper">
            <input type="checkbox" id="ekonom" />
            <label for="ekonom">Економ</label>
        </div>
        <div class="wrapper">
            <input type="checkbox" id="ekonom" />
            <label for="ekonom">Економ</label>
        </div>
        <div class="cal">
            <input type="checkbox" name="class[]" value="2" <?php if (in_array('2', $classes)) echo 'checked'; ?>>
            <span>Середній</span>
        </div>
        <div class="cal">
            <input type="checkbox" name="class[]" value="3" <?php if (in_array('3', $classes)) echo 'checked'; ?>>
            <span>Бізнес</span>
        </div>
        <div class="cal">
            <input type="checkbox" name="class[]" value="4" <?php if (in_array('4', $classes)) echo 'checked'; ?>>
            <span>Преміум</span>
        </div>
        <div class="cal">
            <input type="checkbox" name="class[]" value="5" <?php if (in_array('5', $classes)) echo 'checked'; ?>>
            <span>Купе</span>
        </div>
        <div class="cal">
            <input type="checkbox" name="class[]" value="6" <?php if (in_array('6', $classes)) echo 'checked'; ?>>
            <span>Кабріолет</span>
        </div>
        <div class="cal">
            <input type="checkbox" name="class[]" value="7" <?php if (in_array('7', $classes)) echo 'checked'; ?>>
            <span>Позашляховик / Кросовер</span>
        </div>
        <div class="cal">
            <input type="checkbox" name="class[]" value="8" <?php if (in_array('8', $classes)) echo 'checked'; ?>>
            <span>Мінівен</span>
        </div>
        <div class="cal">
            <input type="checkbox" name="class[]" value="9" <?php if (in_array('9', $classes)) echo 'checked'; ?>>
            <span>Комерційний</span>
        </div>
        <div class="cal">
            <input type="checkbox" name="class[]" value="10" <?php if (in_array('10', $classes)) echo 'checked'; ?>>
            <span>Пікап</span>
        </div>

        <button type="submit">Застосувати</button>
    </form>

    <button class="car car_plus" data-toggle="modal" id="open-modal-btn" data-target="#create">+</button>

    <div class="col-12">
        <?php
        foreach ($cars as $car) {
            renderCar($car);
        }
        ?>
    </div>

    <?php include_once __DIR__ . '/components/scripts.php'; ?>
    <script src="components/component.js"></script>
</body>
</html>