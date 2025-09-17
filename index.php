<?php
// 定义应用路径常量
define('APP_PATH', __DIR__ . '/app');
define('BASE_PATH', __DIR__);

// 加载配置文件
$config = require BASE_PATH . '/config.php';
// 从配置中获取路由，确保变量已定义
$routes = $config['routes'] ?? [];

// 自动加载类
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_PATH . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// 获取当前请求信息
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// 处理URL，移除脚本路径
$requestUri = str_replace($scriptName, '', $requestUri);
$position = strpos($requestUri, '?');
if ($position !== false) {
    $requestUri = substr($requestUri, 0, $position);
}
$requestUri = rtrim($requestUri, '/') ?: '/';

// 路由匹配
$found = false;
foreach ($routes as $route) {
    list($method, $uri, $handler, $name) = $route;
    
    if ($method === $requestMethod && $uri === $requestUri) {
        $found = true;
        list($controllerName, $action) = explode('@', $handler);
        $controllerClass = 'App\\Controllers\\' . $controllerName;
        
        if (class_exists($controllerClass) && method_exists($controllerClass, $action)) {
            $controller = new $controllerClass();
            $controller->$action();
        } else {
            http_response_code(404);
            echo "控制器或方法不存在: $controllerClass@$action";
        }
        break;
    }
}

if (!$found) {
    http_response_code(404);
    echo "页面未找到: $requestUri";
}
    