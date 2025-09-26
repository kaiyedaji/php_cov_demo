<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{

    public function login()
    {
        $username = $_GET['username'];
        $password = $_GET['password'];
        if($username=='root'and$password=='root'){
            echo 'success';
        }else{
            echo 'fail' ;
        };
        if($username=='haha'and$password=='haha'){
            echo '111';
        }else{
            echo '2222';
        }
        return $username;
    }

    public function userInfo()
    {
        return ['name'=>'zhangsan','age'=>18];
    }

    //

    public function test()
    {
        return 'test';
    }

    public function example()
    {
        return 'example';
    }
}
