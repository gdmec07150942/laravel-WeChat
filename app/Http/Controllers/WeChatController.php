<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use EasyWeChat\Foundation\Application;

class WeChatController extends Controller
{
//    protected $server;

    public function api()
    {
        $token = 'weixin';
        $sort = array($token, $_GET['timestamp'], $_GET['nonce']);
        sort($sort, SORT_STRING);
        if (sha1(implode($sort)) != $_GET['signature']) {
            return false;
        }
        echo $_GET['echostr'];
        exit;

    }

//    public function __construct(Application $wechat)
//    {
//        $this->wechat = $wechat;
//    }
//    public function server()
//    {
//        $server = $this->wechat->server;
//        $user = $this->wechat->user;
//        $server->setMessageHandler(function ($message) use ($user) {
//            switch ($message->MsgType) {
//                case 'event':
//                    return '收到事件消息';
//                    break;
//                case 'text':
//                    return '收到文字信息';
//                    break;
//                case 'image':
//                    return '收到图片信息';
//                    break;
//                case 'voice':
//                    return '收到语音信息';
//                    break;
//                case 'video':
//                    return '收到视频信息';
//                    break;
//                case 'location':
//                    return '收到坐标信息';
//                    break;
//                case 'link':
//                    return '收到链接信息';
//                    break;
//                default:
//                    return '收到其它信息';
//                    break;
//            }
//
//
//        });
//        $resopne = $server->serve();
//        $resopne->send();
//        $app = app('wechat.official_account');
//        $app->server->push(function($message){
//            return "欢迎关注 overtrue！";
//        });
//
//        return $app->server->serve();
//    }


}
