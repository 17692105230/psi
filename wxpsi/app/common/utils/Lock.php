<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/23
 * Time: 8:40
 */

namespace app\common\utils;

/**
 * 文件锁封装类(创建对象时候请指定自己对应的加锁文件，如果不指定会与其他地方调用的锁共享一个锁文件。)
 * Class Lock
 * @package util
 * @author gxd
 */
class Lock
{
    private $file = "./lock";   //锁文件名称
    private $handler = false;   //锁文件句柄

    /**
     * Lock constructor.
     * @param string $file 加锁使用的文件
     */
    public function __construct($file = "./lock")
    {
        $this->file = $file;
    }

    /**
     * 执行加锁
     * @param int $operation 加锁方式:LOCK_SH(共享)、LOCK_EX(独占)
     */
    public function lock($operation = LOCK_EX)
    {
        if (!file_exists($this->file)) {
            $dir_path = dirname($this->file);
            if (!is_dir($dir_path) && !mkdir($dir_path, 0755, true)) {
                return false;
            }
            $this->handler = fopen($this->file, "w+");
        } else {
            $this->handler = fopen($this->file, "r+");
        }
        if (!$this->handler) {
            return false;
        }
        while (!flock($this->handler, $operation));
        return true;
    }

    /**
     * 释放锁
     * @return bool
     */
    public function unlock()
    {
        if (!$this->handler) {
            return true;
        }
        $res = flock($this->handler, LOCK_UN);
        fclose($this->handler);
        $this->handler = "";
        return $res;
    }

    /**
     * 加锁调用函数
     * @param function $callback 回调函数:请参考call_user_func_array($callback, array $param_arr)
     * @param array $param_arr 函数参数
     * @return mixed 返回函数调用结果，如果返回false加锁失败或者函数调用失败，
     */
    public function lockCallFunc($callback, $param_arr)
    {
        if (!$this->lock()) {
            return false;
        }
        $res = call_user_func_array($callback, $param_arr);
        $this->unlock();
        return $res;
    }

    /**
     * 析构函数，如果文件没有关闭则关闭文件
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        if ($this->handler) {
            fclose($this->handler);
        }
    }
}