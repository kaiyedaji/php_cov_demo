<?php $title = '关于我们'; ?>
<?php ob_start(); ?>

<h1>关于本框架</h1>
<p><?php echo $content; ?></p>
<p>这是一个轻量级PHP框架，包含路由、MVC结构和基础功能。</p>

<?php $content = ob_get_clean(); ?>
<?php require APP_PATH . '/Views/layouts/main.php'; ?>
    