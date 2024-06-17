<?php
require_once __DIR__ . '/src/helpers.php';

checkGuest();
?>

<!DOCTYPE html>
<html lang="ua">
<?php include_once __DIR__ . '/components/head.php'?>
<body>

<form class="card" action="src/actions/login.php" method="post">
    <h2>Вхід</h2>

    <?php if(hasMessage('error')): ?>
        <div class="notice error"><?php echo getMessage('error') ?></div>
    <?php endif; ?>

    <label for="email">
        E-mail
        <input
            type="text"
            id="email"
            name="email"
            placeholder=""
            value="<?php echo old('email') ?>"
            <?php echo validationErrorAttr('email'); ?>
        >
        <?php if(hasValidationError('email')): ?>
            <small><?php echo validationErrorMessage('email'); ?></small>
        <?php endif; ?>
    </label>

    <label for="password">
        Пароль
        <input
            type="password"
            id="password"
            name="password"
            placeholder=""
        >
    </label>

    <button
        type="submit"
        class="submit"
        id="submit"
    >Продовжити</button>
</form>

<p class="text_p">
    У мене ще немає <a href="/register.php">облікового запису</a>
</p>

<?php include_once __DIR__ . '/components/scripts.php' ?>
</body>
</html>