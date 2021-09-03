<?php

/**
 * 腾讯云COS
 * @Auther: Fly
 */

namespace app\common\utils;

use Exception;
use Qcloud;


class COS
{
    /** @var string  云 API 密钥 SecretId */
    private $secret_id;
    /** @var string  云 API 密钥 SecretKey */
    private $secret_key;
    /** @var string  存储桶名称 */
    private $bucket_name;
    /** @var string  存储桶地域 */
    private $region;
    /** @var string 文件夹名 */
    private $folder_name = "image";
    /** @var string 项目路径 */
    private $root_path;

    private $cos_client;

    public function __construct()
    {
        $this->secret_id = env('cos.secret_id');
        $this->secret_key = env('cos.secret_key');
        $this->bucket_name = env('cos.bucket_name');
        $this->region = env('cos.region');
        $this->root_path = app()->getRootPath() . 'public';
        $this->cos_client = new Qcloud\Cos\Client([
            'region' => $this->region,
            'schema' => 'http', //协议头部，默认为http
            'credentials' => [
                'secretId' => $this->secret_id,
                'secretKey' => $this->secret_key,
            ]
        ]);
    }

    /**
     * 上传文件流 最大支持上传5G文件
     * @param $path string 文件路径
     * @return array
     * @throws Exception
     */
    public function put_object(string $path): array
    {
        try {
            $filename = "$this->root_path/$path"; // 文件名
            if (!file_exists($filename)) {
                throw new Exception("文件不存在", 9000);
            }
            $path_info = pathinfo($filename); // 文件信息
            $file = fopen($filename, 'rb');
            $content_type = mime_content_type($filename); // 文件类型
            $file_size = filesize($filename); // 文件大小
            $name = substr(md5(uniqid($this->folder_name)), 0, 16);
            $response = $this->cos_client->putObject([
                'Bucket' => "$this->bucket_name",
                'Key' => $this->folder_name . '/' . $name,
                'Body' => $file,
                'ContentType' => $content_type,
            ]);
            $data = $this->object_to_array($response);
            if (!$data) {
                throw new Exception("上传图片失败", 9001);
            }
            $result = array_shift($data);
            if (file_exists($filename)) {
                unlink($filename); // 删除本地文件
            }
            return [
                'key' => $result['Key'], // cos文件名
                'assist_url' => $result['Location'], // 访问地址
                'extension' => $path_info['extension'], // 文件后缀
                'size' => $file_size, // 文件大小
                'name' => $name,
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 删除多个key
     * @param array $keys ['Key' => "image/bc9b93ae8b970a40199e512b04"]
     * @return array|void
     * @throws Exception
     */
    public function delete_objects(array $keys): array
    {
        try {
            $bucket = $this->bucket_name;
            $result = $this->cos_client->deleteObjects([
                'Bucket' => $bucket,
                'Objects' => $keys
            ]);
            $data = $this->object_to_array($result);
            // 请求成功
            return array_values($data);
        } catch (Exception $e) {
            // 请求失败
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 设置文件夹名-附件目录
     */
    public function set_folder_name_attachment()
    {
        $this->folder_name = "attachment";
    }

    /**
     * 设置文件夹名-图片目录
     */
    public function set_folder_name_image()
    {
        $this->folder_name = "image";
    }

    /**
     * 对象转数组
     * @param $obj
     * @return array|void
     */
    private function object_to_array($obj): array
    {
        $arr = (array)$obj;
        foreach ($arr as $k => $v) {
            if (gettype($v) == 'resource') {
                return [];
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $arr[$k] = (array)$this->object_to_array($v);
            }
        }
        return $arr;
    }
}