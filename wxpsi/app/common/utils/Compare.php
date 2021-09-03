<?php


namespace app\common\utils;

/**
 * Class Compare 比较两个数组 得到 添加的，删除的，更新的
 * @package app\common\utils
 * Date: 2021/5/19
 * Time: 11:54
 */
class Compare
{
    // 参数
    private $originData = [];
    private $newData = [];
    private $connector = '#';
    private $map = [];
    // 处理后的结果
    private $updateResult = [];
    private $addResult = [];
    private $delResult = [];
    // 是否已经处理标志
    private $processed = false;

    public function __construct($originData = [], $newData = [], $map = [], $connector = null)
    {
        $originData && $this->setOriginData($originData);
        $newData && $this->setNewData($newData);
        $map && $this->setMap($map);
        $connector && $this->setConnector($connector);
    }

    public function setMap($map)
    {
        $this->map = $map;
    }

    public function setOriginData($originData)
    {
        $this->originData = $originData;
    }

    public function setNewData($newData)
    {
        $this->newData = $newData;
    }

    public function setConnector($connector)
    {
        $this->connector = $connector;
    }

    public function handle()
    {
        $origin_temp = $this->processingData($this->originData);
        $new_temp = $this->processingData($this->newData);
        // 更新的数据
        $this->updateResult = array_values(array_intersect_key($new_temp, $origin_temp));
        // 删除的
        $this->delResult = array_values(array_diff_key($origin_temp, $new_temp));
        // 添加的
        $this->addResult = array_values(array_diff_key($new_temp, $origin_temp));
        $this->processed = true;
    }

    public function getUpdateResult()
    {
        return $this->getResult('updateResult');
    }

    public function getAddResult()
    {
        return $this->getResult('addResult');
    }

    public function getDelResult()
    {
        return $this->getResult('delResult');
    }


    public function getAllResult(&$add,&$update,&$delete)
    {
        $add = $this->getAddResult();
        $update = $this->getUpdateResult();
        $delete = $this->getDelResult();
        return true;
    }


    private function getResult($name)
    {
        if ($this->processed) {
            return $this->$name;
        }

        throw new \Exception('数据尚未处理');
    }

    public function processingData($data)
    {
        $origin_temp = [];
        foreach ($data as $v) {
            $temp_key = '';
            foreach ($this->map as $val) {
                $temp_key .= $v[$val] . $this->connector;
            }
            $origin_temp[$temp_key] = $v;
        }
        return $origin_temp;
    }

    public function getWhere($data,$base = [])
    {
        $where = [];
        foreach ($this->map as $key) {
            $where[$key] = $data[$key];
        }

        if ($base) {
            return array_merge($where,$base);
        }

        return $where;
    }



}