<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Basic PHP Framework'; ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        nav { margin-bottom: 20px; padding: 10px; background: #f5f5f5; }
        nav a { margin-right: 15px; text-decoration: none; color: #333; }
        .content { min-height: 400px; }
        footer { margin-top: 20px; padding: 10px; border-top: 1px solid #ddd; text-align: center; }
    </style>
</head>
<body>
    <nav>
        <a href="/">首页</a>
        <a href="/about">关于我们</a>
    </nav>
    
    <div class="content">
        <?php echo $content ?? ''; ?>
    </div>
    
    <footer>
        © <?php echo date('Y'); ?> Basic PHP Framework
    </footer>
</body>
</html>
    