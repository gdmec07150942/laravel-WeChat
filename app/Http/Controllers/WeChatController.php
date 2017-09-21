<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use EasyWeChat\Support\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Monolog\Logger;

class WeChatController extends Controller
{
//    protected $server;

    public function api()
    {
        //$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postStr = file_get_contents('php://input');
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            if($keyword == 'hello')
            {
                $msgType = "text";
                $contentStr = date("Y-m-d H:i:s",time());
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
        }else{
            echo "";
            exit;
        }

    }
//    public function responseMsg()
//    {
////        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
//
//        $postStr = file_get_contents('php://input');
//        if (!empty($postStr)){
//            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
//            $fromUsername = $postObj->FromUserName;
//            $toUsername = $postObj->ToUserName;
//            $keyword = trim($postObj->Content);
//            $time = time();
//            $textTpl = "<xml>
//                        <ToUserName><![CDATA[%s]]></ToUserName>
//                        <FromUserName><![CDATA[%s]]></FromUserName>
//                        <CreateTime>%s</CreateTime>
//                        <MsgType><![CDATA[%s]]></MsgType>
//                        <Content><![CDATA[%s]]></Content>
//                        <FuncFlag>0</FuncFlag>
//                        </xml>";
//            if($keyword == 'hello')
//            {
//                $msgType = "text";
//                $contentStr = date("Y-m-d H:i:s",time());
//                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
//                echo $resultStr;
//            }
//        }else{
//            echo "";
//            exit;
//        }
//    }
    private $serve;
    private $user;
    private $auth;

    public function __construct(Application $app)
    {
        $this->serve = $app->server;
        $this->user = $app->user;
        $this->auth = $app->oauth;
    }

    public function serve()
    {
        $options = Config::get('wechat');
        $wechat = new Application($options);
        $serve = $wechat->server;
        $userApi = $wechat->user;
        $serve->setMessageHandler(function ($message) use ($userApi, $wechat) {
            switch ($message->MsgType) {
                case 'event':
                    switch ($message->Event) {
                        case 'subscribe':
                            return '您好，欢迎关注测试公众号';
                            break;
                        case 'CLICK':
                            switch ($message->EventKey) {
                                case 'CLICK_BUTTON':
                                    return '点我没用，说了你不信';
                                    break;
                                case 'CLICK_VOTED_FOR_ME':
                                    return '谢谢你这么帅还这么爱我';
                                    break;
                                case 'MENTION':
                                    $msg = "回复【用户】 测试用户接口\n回复【上传】 测试图片上传\n回复【图文】 测试图文消息\n回复【模板】 测试模板消息";
                                    return $msg;
                                    break;
                            }
                            break;
                    }
                    break;
                case 'text':
                    try {
                        if (str_contains($message->Content, '用户')) {
                            $toUser = $message->ToUserName;
                            $openId = $message->FromUserName;
                            $nickname = $userApi->get($openId)->nickname;
                            $this->logs('text-success', '信息接受: ' . $toUser . ' 信息来自： ' . $openId);
                            return '你的openId: ' . $openId . ' 信息来自：' . $toUser . ' 用户名是: ' . $nickname;
                        } else if (str_contains($message->Content, '图文')) {
                            return new News([
                                'title' => '图文消息测试',
                                'description' => '点击查看大图',
                                'url' => 'http://www.yiokit.com/laravel-wechat-test/public/images/sex.jpg',
                                'image' => 'http://www.yiokit.com/laravel-wechat-test/public/images/miss.jpg',
                                // ...
                            ]);
                        } else if (str_contains($message->Content, '上传')) {
                            $photo = $wechat->material->uploadImage(public_path() . '/images/sex.jpg');
                            session('images_url', $photo->url);
                            return '图片素材上传成功，media_id：' . $photo->media_id . "\n 图片存放地址： " . $photo->url;
                        } else if (str_contains($message->Content, '模板')) {
                            $wechat->notice->send(
                                [
                                    'touser' => $message->FromUserName,
                                    'template_id' => 'w37u2Jhs30qAljTDBsG0Kz4bIVgfvDj_qNd0zbXP97s',
                                    'url' => 'http://www.baidu.com',
                                    'data' => [
                                        "first" => "恭喜你购买成功！",
                                        "name" => "巧克力",
                                        "price" => "39.8元",
                                        "remark" => "欢迎再次购买！",
                                    ],
                                ]

                            );
                            return '';
                        } else {
                            return '你发的信息:' . $message->Content;
                        }
                    } catch (\Exception $e) {
                        $this->logs('text-error', '出现错误，错误信息：' . $e->getMessage());
                        return '公众号出错，错误信息： ' . $e->getMessage();
                    }
                    break;
                case 'image':
                    return '您发送了一张图片,图片地址：' . $message->PicUrl;
                    break;
                case 'voice':
                    return '您发送了一段语音';
                    break;
                case 'video':
                    return '您发送了一段视频';
                    break;
                case 'location':
                    return '您发送了一个定位';
                    break;
                case 'link':
                    return '您发送了一个链接';
                    break;
                // ... 其它消息
                default:
                    return null;
                    break;
            }
        });
        return $serve->serve();
    }

    public function test2()
    {
        // 未登录
        if (empty(session('wechat_user'))) {
            session('target_url', 'http://www.yiokit.com/laravel-wechat-test/public/user/profile');
            return $this->auth->redirect();
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            // $oauth->redirect()->send();
        } else {
            // 已经登录过
            $user = session('wechat_user');
            return '已经登录过了';
        }

    }

    public function test1()
    {
        // 获取 OAuth 授权结果用户信息
        $user = $this->auth->user();
        session('wechat_user', $user->toArray());
        $targetUrl = empty(session('target_url')) ? '/' : session('target_url');
        return view('welcome');
//        return '目标跳转链接:'.$targetUrl;
        //header('location:'. $targetUrl); // 跳转到 user/profile
    }

    public function test()
    {
    }
}