<?php

namespace addons\wechat\library;

use app\common\model\Page;
use app\common\model\WechatConfig;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Transfer;

/**
 * Wechat服务类
 */
class Wechat
{

    public function __construct()
    {
        
    }

    public static function appConfig()
    {
        return array(
            'signin'  => array(
                'name'   => '签到送积分',
                'config' => array(
                )
            ),
            'blog'    => array(
                'name'   => '关联博客',
                'config' => array(
                    array(
                        'type'    => 'text',
                        'caption' => '日志ID',
                        'field'   => 'id',
                        'options' => ''
                    )
                )
            ),
            'article' => array(
                'name'   => '关联文章',
                'config' => array(
                    array(
                        'type'    => 'text',
                        'caption' => '文章ID',
                        'field'   => 'id',
                        'options' => ''
                    )
                )
            ),
            'page'    => array(
                'name'   => '关联单页',
                'config' => array(
                    array(
                        'type'    => 'text',
                        'caption' => '单页ID',
                        'field'   => 'id',
                        'options' => ''
                    )
                )
            ),
            'service' => array(
                'name'   => '在线客服',
                'config' => array(
                )
            ),
        );
    }

    // 微信输入交互内容指令
    public function command($obj, $openid, $content, $context)
    {
        $response = FALSE;
        if (isset($content['app']))
        {
            switch ($content['app'])
            {
                case 'signin':
                case 'blog':
                case 'article':
                case 'page':
                    break;
                case 'service':
                    $service = (array) json_decode(WechatConfig::value('service'), true);
                    list($begintime, $endtime) = explode('-', $service['onlinetime']);
                    $session = $obj->app->staff_session;
                    $staff = $obj->app->staff;

                    $kf_account = $session->get($openid)->kf_account;
                    $time = time();
                    if (!$kf_account && ($time < strtotime(date("Y-m-d {$begintime}")) || $time > strtotime(date("Y-m-d {$endtime}"))))
                    {
                        return $service['offlinemsg'];
                    }
                    if (!$kf_account)
                    {
                        $kf_list = $staff->onlines()->kf_online_list;
                        if ($kf_list)
                        {
                            $kfarr = [];
                            foreach ($kf_list as $k => $v)
                            {
                                $kfarr[$v['kf_account']] = $v['accepted_case'];
                            }
                            $kfkeys = array_keys($kfarr, min($kfarr));
                            $kf_account = reset($kfkeys);
                            $session->create($kf_account, $openid);
                            $response = $service['waitformsg'];
                        }
                        else
                        {
                            $response = $service['nosessionmsg'];
                        }
                    }
                    else
                    {
                        $server = $obj->app->server;
                        $server->setMessageHandler(function($message) {
                            return new Transfer();
                        });
                        $response = $server->serve();
                        $response->send();
                        exit;
                    }

                    break;
                default:
                    break;
            }
        }
        else
        {
            $response = isset($content['content']) ? $content['content'] : $response;
        }
        return $response;
    }

    // 微信点击菜单event指令
    public function response($obj, $openid, $content, $context)
    {
        $response = FALSE;
        if (isset($content['app']))
        {
            switch ($content['app'])
            {
                case 'signin':
                    break;
                case 'blog':
                    $id = explode(',', $content['id']);
                    $bloglist = addons\blog\model\Post::all($id);
                    $response = [];
                    foreach ($bloglist as $k => $blog)
                    {
                        if ($blog)
                        {
                            $news = new News();
                            $news->title = $blog['title'];
                            $news->url = addon_url('blog/index/post', ['id' => $blog['id']], true, true);
                            $news->image = cdnurl($blog['thumb']);
                            $news->description = $blog['description'];
                            $response[] = $news;
                        }
                    }

                case 'page':
                    $id = isset($content['id']) ? $content['id'] : 0;
                    $pageinfo = Page::get($id);
                    if ($pageinfo)
                    {
                        $news = new News();
                        $news->title = $pageinfo['title'];
                        $news->url = $pageinfo['url'] ? $pageinfo['url'] : url('index/page/show', ['id' => $pageinfo['id']], true, true);
                        $news->image = cdnurl($pageinfo['image']);
                        $news->description = $pageinfo['description'];
                        return $news;
                    }
                    break;
                case 'service':
                    $response = $this->command($obj, $openid, $content, $context);
                    break;
                default:
                    break;
            }
        }
        else
        {
            $response = isset($content['content']) ? $content['content'] : $response;
        }
        return $response;
    }

}
