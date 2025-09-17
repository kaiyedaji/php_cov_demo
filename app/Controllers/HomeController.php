<?php
namespace App\Controllers;

use App\View;

class HomeController
{
    public function index()
    {
        $data = [
            'title' => '首页',
            'message' => '欢迎使用基础PHP框架',
            'items' => ['功能1', '功能2', '功能3']
        ];
        
        View::render('home/index', $data);
    }
    
    public function about()
    {
        $data = [
            'title' => '关于我们',
            'content' => '这是一个极简的PHP框架示例'
        ];
        
        View::render('home/about', $data);
    }
}
    