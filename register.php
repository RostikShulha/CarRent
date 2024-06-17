<?php
require_once __DIR__ . '/src/helpers.php';
include 'src/connect.php';
checkGuest();
?>

<!DOCTYPE html>
<html lang="ua">
<?php include_once __DIR__ . '/components/head.php'?>
<body>

<form class="card" action="src/actions/register.php" method="post" enctype="multipart/form-data">
    <h2>Зареєструватися</h2>

    <label for="name">
        Ім'я
        <input
            type="text"
            id="name"
            name="name"
            placeholder="Прізвище Ім'я"
            value="<?php echo old('name') ?>"
            <?php echo validationErrorAttr('name'); ?>
        >
        <?php if(hasValidationError('name')): ?>
            <small><?php echo validationErrorMessage('name'); ?></small>
        <?php endif; ?>
    </label>

    <label for="email">
        E-mail
        <input
            type="text"
            id="email"
            name="email"
            placeholder="adress@gmail.com"
            value="<?php echo old('email') ?>"
            <?php echo validationErrorAttr('email'); ?>
        >
        <?php if(hasValidationError('email')): ?>
            <small><?php echo validationErrorMessage('email'); ?></small>
        <?php endif; ?>
    </label>
    
    <label for="avatar">Зображення профілю
        <input
            type="file"
            id="avatar"
            name="avatar"
            <?php echo validationErrorAttr('avatar'); ?>
        >
        <?php if(hasValidationError('avatar')): ?>
            <small><?php echo validationErrorMessage('avatar'); ?></small>
        <?php endif; ?>
    </label> 

    <div class="grid">
        <label class="grid__pass" for="password">
            Пароль
            <input
                type="password"
                id="password"
                name="password"
                placeholder=""
                <?php echo validationErrorAttr('password'); ?>
            >
        </label>

        <label class="grid__pass" for="password_confirmation">
            Підтвердження
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder=""
            >
        </label>
        
    </div>
    <?php if(hasValidationError('password')): ?>
                <small><?php echo validationErrorMessage('password'); ?></small>
            <?php endif; ?>

    <div class="wrapper big-font">
        <input 
            type="checkbox"
            id="terms"
            name="terms"
            class="filter"
        >
        <label for="terms">Я приймаю всі правила та умови використання</label>
    </div>

    <button
        type="submit"
        id="submit"
        disabled
    >Продовжити</button>
</form>

<p class="text_p">
    У мене вже є <a href="/">обліковий запис</a>
</p>

<script src="components/js/component_register.js"></script>
</body>
</html>