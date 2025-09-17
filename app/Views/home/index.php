<?php $title = '首页'; ?>
<?php ob_start(); ?>

<h1><?php echo $message; ?></h1>
<p>框架特性：</p>
<ul>
    <?php foreach ($items as $item): ?>
        <li><?php echo $item; ?></li>
    <?php endforeach; ?>
</ul>

<?php $content = ob_get_clean(); ?>
<?php require APP_PATH . '/Views/layouts/main.php'; ?>
    