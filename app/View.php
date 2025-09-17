<?php
namespace App;

class View
{
    /**
     * 渲染视图
     * @param string $view 视图路径
     * @param array $data 传递给视图的数据
     */
    public static function render($view, $data = [])
    {
        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} not found");
        }
        
        // 提取数据变量
        extract($data);
        
        // 加载视图
        require $viewPath;
    }
}
    