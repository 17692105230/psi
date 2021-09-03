<?php

namespace app\common\library\wechat\live;

use app\common\library\wechat\WxBase;

/**
 * 微信小程序直播接口
 * Class Room
 * @package app\common\library\wechat\live
 */
class Room extends WxBase
{
    /**
     * 微信小程序直播-获取直播房间列表接口
     * api文档: https://developers.weixin.qq.com/miniprogram/dev/framework/liveplayer/live-player-plugin.html
     * @throws \app\common\exception\BaseException
     */
    public function getLiveRoomList()
    {
        // 微信接口url
        $accessToken = $this->getAccessToken();
        $apiUrl = "https://api.weixin.qq.com/wxa/business/getliveinfo?access_token={$accessToken}";
        // 请求参数
        $params = $this->jsonEncode(['start' => 0, 'limit' => 100]);
        // 执行请求
        $result = $this->post($apiUrl, $params);
        // 记录日志
        $this->doLogs(['describe' => '微信小程序直播-获取直播房间列表接口', 'url' => $apiUrl, 'params' => $params, 'result' => $result]);
        // 返回结果
        $response = $this->jsonDecode($result);
        if (!isset($response['errcode'])) {
            $this->error = 'not found errcode';
            return false;
        }
        // 容错: empty room list
        if ($response['errcode'] > 1 && $response['errcode'] != 9410000) {
            $this->error = $response['errmsg'];
            return false;
        }
        return $response;
    }

}
