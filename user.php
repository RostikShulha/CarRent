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
        <?php ajax(); ?>
        <div class="card home">
            <img
                class="avatar"
                src="<?php echo $user['avatar'] ?>"
                alt="<?php echo $user['name'] ?>"
            >
            <h1>Привіт, <?php echo $user['name'] ?>!</h1>
            <form action="src/actions/logout.php" method="post">
                <button role="button">Вийти з облікового запису</button>
            </form>
        </div>
        <script src="components/js/component.js"></script>
    </body>
</html>