<?php

namespace addons\upyun\controller;

use app\common\model\Attachment;
use think\addons\Controller;

/**
 * 又拍云上传
 *
 */
class Index extends Controller
{

    public function index()
    {
        $this->error("当前插件暂无前台页面");
    }
    
    //上传异步通知
    public function notify()
    {
        $url = $this->request->post("url");
        $code = $this->request->post("code");
        $message = $this->request->post("message");
        $sign = $this->request->post("sign");
        $time = $this->request->post("time");
        $extparam = $this->request->post("ext-param");
        if ($url && $code && $message && $time && $sign)
        {
            $config = get_addon_config('upyun');
            $arr = [$code, $message, $url, $time, $config['formkey']];
            if ($extparam)
            {
                $arr[] = $extparam;
            }
            if ($sign == md5(implode('&', $arr)))
            {
                $params = array(
                    'filesize'    => $this->request->param("file_size", 0),
                    'imagewidth'  => $this->request->param("image-width", 0),
                    'imageheight' => $this->request->param("image-height", 0),
                    'imagetype'   => $this->request->param("image-type", ''),
                    'imageframes' => $this->request->param("image-frames", 1),
                    'mimetype'    => $this->request->param("mimetype", ''),
                    'extparam'    => $extparam ? $extparam : '',
                    'url'         => $url,
                    'uploadtime'  => $time,
                    'storage'     => 'upyun'
                );
                Attachment::create($params);
                echo "success";
            }
            else
            {
                echo "failure";
            }
        }
        else
        {
            echo "failure";
        }
        return;
    }

}
