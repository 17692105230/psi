<?php

namespace app\web\controller;


class Index extends Controller
{
    public function index()
    {
        if ($this->request->isAjax()) {
            return $this->renderError('登录失败');
        }
        // 关闭layout
        config(['layout_on' => false], 'view');
        return view('index');
    }

}