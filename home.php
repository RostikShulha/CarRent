<?php
    require_once __DIR__ . '/src/helpers.php';
    include 'src/connect.php';

    checkAuth();

    $user = currentUser();?>

<!DOCTYPE html>
<html lang="ua">
<head>
    <meta charset="UTF-8">
    <title>CAR orend</title>
    <link rel="stylesheet" href="assets/qpp.css">
    <link rel="stylesheet" href="src/fonts/Icomoon/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

    <body>
        <header id="header" class="header">
            <div class="header__container" id="home">
                <a href="#" class="logo">Orend</a>
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
        <?php ajax(); ?>

        <div class="background__sort-car">
            <div class="sort-filter__block">
                    <form method="GET" id="filter-form" class="sort-filter">
                        <label for="sort">Сортування:
                            <select class="sort" name="sort" id="sort" onchange="document.getElementById('filter-form').submit()">
                                <option value="1" <?php if ($sort == '1') echo 'selected'; ?>>Від дешевих до дорогих</option>
                                <option value="2" <?php if ($sort == '2') echo 'selected'; ?>>Від дорогих до дешевих</option>
                                <option value="3" <?php if ($sort == '3') echo 'selected'; ?>>Витрати палива, від менших до більших</option>
                                <option value="4" <?php if ($sort == '4') echo 'selected'; ?>>Витрати палива, від більших до менших</option>
                                <option value="5" <?php if ($sort == '5') echo 'selected'; ?>>Рік випуску, за зростанням</option>
                                <option value="6" <?php if ($sort == '6') echo 'selected'; ?>>Рік випуску, за спаданням</option>
                            </select>
                        </label>

                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" id="orend"
                                name="stan[]" 
                                value="3" <?php if (in_array('3', $stan)) echo 'checked'; ?>>
                            <label class="filter__label" for="orend">Готове до оренди прямо зараз</label>
                        </div>
                        

                        <h3>Клас:</h3>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" id="ekonom"
                                name="class[]" 
                                value="1" <?php if (in_array('1', $classes)) echo 'checked'; ?>>
                            <label class="filter__label" for="ekonom">Економ</label>
                        </div>
                        <div class="wrapper">
                            <input class="filter" 
                                type="checkbox" id="serednii" 
                                name="class[]" 
                                value="2" <?php if (in_array('2', $classes)) echo 'checked'; ?>>
                            <label class="filter__label" for="serednii">Середній</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="biznes" 
                                name="class[]" 
                                value="3" <?php if (in_array('3', $classes)) echo 'checked'; ?>>
                            <label class="filter__label" for="biznes">Бізнес</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="premium" 
                                name="class[]" 
                                value="4" <?php if (in_array('4', $classes)) echo 'checked'; ?>>
                            <label class="filter__label" for="premium">Преміум</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="kupe" 
                                name="class[]" 
                                value="5" <?php if (in_array('5', $classes)) echo 'checked'; ?>>
                            <label class="filter__label" for="kupe">Купе</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="kabriolet" 
                                name="class[]" 
                                value="6" <?php if (in_array('6', $classes)) echo 'checked'; ?>>
                            <label class="filter__label" for="kabriolet">Кабріолет</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="pozashlyahovik" 
                                name="class[]" 
                                value="7" <?php if (in_array('7', $classes)) echo 'checked'; ?>>
                            <label class="filter__label" for="pozashlyahovik">Позашляховик / Кросовер</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="miniven" 
                                name="class[]" 
                                value="8" <?php if (in_array('8', $classes)) echo 'checked'; ?>>
                            <label class="filter__label" for="miniven">Мінівен</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="komertsiinyi" 
                                name="class[]" 
                                value="9" <?php if (in_array('9', $classes)) echo 'checked'; ?>>
                            <label class="filter__label" for="komertsiinyi">Комерційний</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="pikap" 
                                name="class[]" 
                                value="10" <?php if (in_array('10', $classes)) echo 'checked'; ?>>
                            <label class="filter__label" for="pikap">Пікап</label>
                        </div>


                        <h3>Коробка передач:</h3>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="ruchna"
                                name="transmission[]" 
                                value="1" <?php if (in_array('1', $transmission)) echo 'checked'; ?>>
                            <label class="filter__label" for="ruchna">Ручна / Механіка</label>
                        </div>
                        <div class="wrapper">
                            <input class="filter" 
                                type="checkbox" 
                                id="avtomat" 
                                name="transmission[]" 
                                value="2" <?php if (in_array('2', $transmission)) echo 'checked'; ?>>
                            <label class="filter__label" for="avtomat">Автомат</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="tiptronik" 
                                name="transmission[]" 
                                value="3" <?php if (in_array('3', $transmission)) echo 'checked'; ?>>
                            <label class="filter__label" for="tiptronik">Типтронік</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="robot" 
                                name="transmission[]" 
                                value="4" <?php if (in_array('4', $transmission)) echo 'checked'; ?>>
                            <label class="filter__label" for="robot">Робот</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="variator" 
                                name="transmission[]" 
                                value="5" <?php if (in_array('5', $transmission)) echo 'checked'; ?>>
                            <label class="filter__label" for="variator">Варіатор</label>
                        </div>


                        <h3>Паливо:</h3>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="benzin"
                                name="fuel[]" 
                                value="1" <?php if (in_array('1', $fuel)) echo 'checked'; ?>>
                            <label class="filter__label" for="benzin">Бензин</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="haz"
                                name="fuel[]" 
                                value="2" <?php if (in_array('2', $fuel)) echo 'checked'; ?>>
                            <label class="filter__label" for="haz">Газ</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="haz-propan-butan"
                                name="fuel[]" 
                                value="3" <?php if (in_array('3', $fuel)) echo 'checked'; ?>>
                            <label class="filter__label" for="haz-propan-butan">Газ пропан-бутан / Бензин</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="hybrid-HEV"
                                name="fuel[]" 
                                value="4" <?php if (in_array('4', $fuel)) echo 'checked'; ?>>
                            <label class="filter__label" for="hybrid-HEV">Гібрид (HEV)</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="hybrid-PHEV"
                                name="fuel[]" 
                                value="5" <?php if (in_array('5', $fuel)) echo 'checked'; ?>>
                            <label class="filter__label" for="hybrid-PHEV">Гібрид (PHEV)</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="hybrid-MHEV"
                                name="fuel[]" 
                                value="6" <?php if (in_array('6', $fuel)) echo 'checked'; ?>>
                            <label class="filter__label" for="hybrid-MHEV">Гібрид (MHEV)</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="diesel"
                                name="fuel[]" 
                                value="7" <?php if (in_array('7', $fuel)) echo 'checked'; ?>>
                            <label class="filter__label" for="diesel">Дизель</label>
                        </div>


                        <h3>Привід:</h3>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="povny"
                                name="drive[]" 
                                value="1" <?php if (in_array('1', $drive)) echo 'checked'; ?>>
                            <label class="filter__label" for="povny">Повний</label>
                        </div>
                        <div class="wrapper">
                            <input class="filter" 
                                type="checkbox" 
                                id="perednii" 
                                name="drive[]" 
                                value="2" <?php if (in_array('2', $drive)) echo 'checked'; ?>>
                            <label class="filter__label" for="perednii">Передній</label>
                        </div>
                        <div class="wrapper">
                            <input 
                                class="filter" 
                                type="checkbox" 
                                id="zadniy" 
                                name="drive[]" 
                                value="3" <?php if (in_array('3', $drive)) echo 'checked'; ?>>
                            <label class="filter__label" for="zadniy">Задній</label>
                        </div>
                        <div class="filter__submit">
                            <button type="submit">Застосувати</button>
                        </div>
                    </form>
            </div>
            <div>
                <?php if ($user['admin_stat'] >= 1): ?>
                    <div>
                        <button class="car car_plus" data-toggle="modal" id="open-modal-btn" data-target="#create">
                            <svg class="icon" width="800px" height="800px" viewBox="0 0 32 32">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                    <g transform="translate(-362.000000, -1037.000000)" class="icon-path">
                                        <path d="M390,1049 L382,1049 L382,1041 C382,1038.79 380.209,1037 378,1037 C375.791,1037 374,1038.79 374,1041 L374,1049 L366,1049 C363.791,1049 362,1050.79 362,1053 C362,1055.21 363.791,1057 366,1057 L374,1057 L374,1065 C374,1067.21 375.791,1069 378,1069 C380.209,1069 382,1067.21 382,1065 L382,1057 L390,1057 C392.209,1057 394,1055.21 394,1053 C394,1050.79 392.209,1049 390,1049">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>
                <?php
                // Отримання списку автомобілів
                $cars = getCars($pdo, $sort, $classes, $drive, $transmission, $fuel, $stan);

                foreach ($cars as $car) {
                    renderCar($car);
                }
                ?>
            </div>
        </div>


        <!-- <form class="car" method="post">
            <img class="car__img" src="uploads/avatar_1716733185.jpg">
            <div class="car__block">
                <div class="car__text">
                    <h3 class="car__name-car">1</h3>
                    <h3 class="car__name-car">2</h3
                ></div>
                <div class="car__text">
                    <table class="car__specs">
                        <tr>
                            <td  class="car__fuel">3</td>
                            <td  class="car__fuel">4</td>
                        </tr>
                        <tr class="car__fuel-drive">
                            <td class="car__fuel">5</td>
                            <td  class="car__fuel">6</td>
                        </tr>
                    </table>
                </div>
                <div class="car__class-price">
                    <div class="car__price">7</div>
                    <div class="car__class">8</div>
                </div>
                
            </div>
        </form> -->



        <form class="modal" id="my-modal" method="post" action="src/actions/home.php" enctype="multipart/form-data">
            <div class="modal__box">
                <button class="modal__close" id="close-my-modal-btn">X</button>
                <h2>Додати запис</h2>
                <table>
                    <tr>
                        <td class="modal__text-form">Марка</td>
                        <td><select id="marka" name="marka">
                            <option value="">Виберіть марку</option>
                                <?php
                                // Отримання марок з бази даних
                                $stmt = $pdo->query('SELECT marka_id, marka_name FROM marka ORDER BY marka_name ASC');
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $row['marka_id'] . '">' . $row['marka_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td class="modal__text-form">Модель</td>
                            <td>
                                <select id="model" name="model">
                                    <option value="">Виберіть спочатку марку</option>
                                </select>
                            </td>
                            <td class="modal__text-form">Трансмісія</td>
                            <td><select type="text" class="form-control modal__form" name="transmission" required>
                            <option value="1">Ручна / Механіка</option>
                            <option value="2">Автомат</option>
                            <option value="3">Типтронік</option>
                            <option value="4">Робот</option>
                            <option value="5">Варіатор</option>
                            </td>
                        </tr>
                        <tr>
                            <td class="modal__text-form">Привід</td>
                            <td><select type="text" class="form-control modal__form" name="drive" required>
                            <option value="1">Повний</option>
                            <option value="2">Передній</option>
                            <option value="3">Задній</option>
                            </td>
                            <td class="modal__text-form">Вид палива</td>
                            <td><select type="text" class="form-control modal__form" name="fuel" required>
                                <option value="1">Бензин</option>
                                <option value="2">Газ</option>
                                <option value="3">Газ пропан-бутан / Бензин</option>
                                <option value="4">Гібрид (HEV)</option>
                                <option value="5">Гібрид (PHEV)</option>
                                <option value="6">Гібрид (MHEV)</option>
                                <option value="7">Дизель</option>
                            </td>
                            <td class="modal__text-form">Стан</td>
                            <td><select type="text" class="form-control modal__form" name="stan" required>
                                <option value="3">Готове до оренди</option>
                                <option value="2">В ремонті</option>
                            </td>
                    </tr>
                    <tr>
                        <td class="modal__text-form">Рік випуску</td>
                        <td><input type="number" min="1908" class="form-control modal__form" name="year" required></td>
                        <td class="modal__text-form">Об'єм двигуна</td>
                        <td><input type="text" class="form-control modal__form" name="size" required></td>
                        <td class="modal__text-form">Клас</td>
                        <td><select type="text" class="form-control modal__form" name="class" required>
                            <option value="1">Економ</option>
                            <option value="2">Середній</option>
                            <option value="3">Бізнес</option>
                            <option value="4">Преміум</option>
                            <option value="5">Купе</option>
                            <option value="6">Кабріолет</option>
                            <option value="7">Позашляховик / Кросовер</option>
                            <option value="8">Мінівен</option>
                            <option value="9">Комерційний</option>
                            <option value="10">Пікап</option>
                        </td>
                        
                    </tr>
                    <tr>
                        <td class="modal__text-form">Ціна за добу ($)</td>
                        <td><input type="number" min="0" class="form-control modal__form" name="price" required></td>
                        <td class="modal__text-form">Розхід на 100км</td>
                        <td><input type="text" class="form-control modal__form" name="consumption" required></td>
                        <td class="modal__text-form">Фото</td>
                        <td><input type="file" class="form-control modal__form" id="picture" name="picture"
                            <?php echo validationErrorAttr('picture'); ?>
                            >
                            <?php if(hasValidationError('picture')): ?>
                                <small><?php echo validationErrorMessage('picture'); ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                <div class="modal-footer">
                    <button class="batt" type="submit" id="add">Зберегти</button>
                    <!-- <button class="batt" type="button" data-dismiss="modal">Закрити</button> -->
                </div>
            </div>
        </form>
        <script src="components/js/component.js"></script>
    </body>
</html>