<?php
return [
    'app_name' => '基础PHP框架',
    'debug' => true,
    // 路由定义
    'routes' => [
        ['GET', '/', 'HomeController@index', '首页'],
        ['GET', '/about', 'HomeController@about', '关于我们']
    ]
];
    