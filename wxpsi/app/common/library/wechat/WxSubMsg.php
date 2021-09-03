<?php

namespace app\common\library\wechat;

/**
 * 小程序订阅消息
 * Class WxSubMsg
 * @package app\common\library\wechat
 */
class WxSubMsg extends WxBase
{
    /**
     * 发送订阅消息
     * @param $param
     * @return bool
     * @throws \app\common\exception\BaseException
     */
    public function sendTemplateMessage($param)
    {
        // 微信接口url
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token={$accessToken}";
        // 构建请求
        $params = [
            'touser' => $param['touser'],
            'template_id' => $param['template_id'],
            'page' => $param['page'],
            'data' => $param['data'],
        ];
        $result = $this->post($url, $this->jsonEncode($params));
        // 记录日志
        $describe = '发送订阅消息';
        $this->doLogs(compact('describe', 'url', 'params', 'result'));
        // 返回结果
        $response = $this->jsonDecode($result);
        if (!isset($response['errcode'])) {
            $this->error = 'not found errcode';
            return false;
        }
        if ($response['errcode'] != 0) {
            $this->error = $response['errmsg'];
            return false;
        }
        return true;
    }

    /**
     * 获取当前帐号下的模板列表
     * @throws \app\common\exception\BaseException
     */
    public function getTemplateList()
    {
        // 微信接口url
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/gettemplate?access_token={$accessToken}";
        // 执行post请求
        $result = $this->get($url);
        // 记录日志
        $this->doLogs(['describe' => '获取当前帐号下的订阅消息模板列表', 'url' => $url, 'result' => $result]);
        // 处理返回结果
        $response = $this->jsonDecode($result);
        if (!isset($response['errcode'])) {
            $this->error = 'not found errcode';
            return false;
        }
        if ($response['errcode'] != 0) {
            $this->error = $response['errmsg'];
            return false;
        }
        return $response;
    }

    /**
     * 添加订阅消息模板
     * [addTemplates 组合模板并添加至帐号下的个人模板库](订阅消息)
     * @param int $tid 模板标题id
     * @param array $kidList 模板关键词列表
     * @param string $sceneDesc 服务场景描述
     * @return bool
     * @throws \app\common\exception\BaseException
     */
    public function addTemplate($tid, $kidList, $sceneDesc)
    {
        // 微信接口url
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/addtemplate?access_token={$accessToken}";
        // 构建请求
        $params = [
            'tid' => $tid,
            'kidList' => $kidList,
            'sceneDesc' => $sceneDesc,
        ];
        // 执行post请求
        $result = $this->post2($url, $params);
        // 记录日志
        $this->doLogs(['describe' => '添加订阅消息模板', 'url' => $url, 'params' => $params, 'result' => $result]);
        // 处理返回结果
        $response = $this->jsonDecode($result);
        if (!isset($response['errcode'])) {
            $this->error = 'not found errcode';
            return false;
        }
        if ($response['errcode'] != 0) {
            $this->error = $response['errmsg'];
            return false;
        }
        return $response;
    }

}