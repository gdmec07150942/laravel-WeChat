<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    //
    public $menu;

    /**
     * MenuController constructor.
     * @param $app
     */
    public function __construct(Application $app)
    {
        $this->menu = $app->menu;
    }

    public function menu()
    {
        $buttons = [
            [
                "type" => "click",
                "name" => "点我没用",
                "key"  => "CLICK_BUTTON"
            ],
            [
                "name"       => "二级菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "授权测试",
                        "url"  => "http://www.yiokit.com/laravel-wechat-test/public/user/profile"
                    ],
                    [
                        "type" => "click",
                        "name" => "输入提示",
                        "key"  => "MENTION"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我",
                        "key" => "CLICK_VOTED_FOR_ME"
                    ],
                ],
            ],
        ];
        $this->menu->add($buttons);
    }

    public function menus()
    {
        $menus = $this->menu->all();
        dd($menus);die;
    }

    public function delete()
    {
        $result = $this->menu->destroy();
        dd($result);
    }
}
