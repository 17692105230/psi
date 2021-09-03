<?php

namespace app\common\service;

//use app\common\exception\BaseException;

trait Render
{
    /**
     * 输出错误信息
     * @param int $code
     * @param $msg
     * @throws BaseException
     */
    protected function throwError($msg, $code = 0)
    {
        // throw new BaseException(['code' => $code, 'msg' => $msg]);
    }

    /**
     * 返回封装后的 API 数据
     * @param int $errcode
     * @param string $errmsg
     * @param array $data
     * @return array
     */
    protected function renderJson($errcode = 0, $errmsg = '', $data = [])
    {
        return json(compact('errcode', 'errmsg', 'data'));
    }

    /**
     * 返回成功json
     * @param array $data
     * @param string|array $msg
     * @return array
     */
    protected function renderSuccess($data = [], $msg = 'success', $code = 0)
    {
        return $this->renderJson($code, $msg, $data);
    }

    /**
     * 返回操作失败json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderError($msg = 'error', $code = 1, $data = [])
    {
        return $this->renderJson($code, $msg, $data);
    }
}

