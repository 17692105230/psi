<?php


namespace app\web\model;

use app\common\model\web\Company as companyModel;
use app\common\utils\BuildCode;
use app\web\model\User as userModel;
use app\web\model\Role;
use app\common\utils\ToolHelper as ToolHelper;
use app\web\model\Organization as OrganizationModel;
use think\facade\Db;

class Company extends companyModel
{
    /**
     * 注册
     * @param $data
     * @return array
     */
    public function register_company($data): array
    {
        $company_name = $data['company_name']; // 企业名字
        $company_phone = $data['company_phone']; // 企业联系电话
        $company_contact = $data['company_contact']; // 企业联系人
        $user_name = $data['user_name']; // 用户名
        $password = $data['password']; // 密码
        $idcode = $data['idcode']; // 用户证件号
        $user_phone = $data['user_phone']; // 用户手机号
        try {
            //开始添加
            DB::startTrans();
            // 添加企业信息
            $company_id = $this->insert([
                'company_name' => $company_name,
                'company_phone' => $company_phone,
                'company_contact' => $company_contact,
                'create_time' => time()
            ], true);
            if (!$company_id) {
                throw new \Exception("添加企业信息失败");
            }
            // 自动添加默认基础信息
            if(!$this->addBaseData($company_id)){
                throw new \Exception($this->error);
            }
            // 添加用户信息
            $userModel = new userModel();
            $super_id = Role::SUPER_ADMIN_ROLE_ID;
            $user_data = [
                'user_name' => $user_name,
                'user_idcode' => $idcode,
                'user_phone' => $user_phone,
                'create_time' => time(),
                'update_time' => time(),
                'user_last_time' => time(),
                'user_last_ip' => ToolHelper::get_ip(),
                'user_role_ids' => ",{$super_id},", // 超级管理员
                'com_id' => $company_id,
            ];
            $user_row = array_merge($userModel->make_password($password), $user_data);
            $user_id = $userModel->insert($user_row, true);
            // 更新企业超级管理员账号
            $is_update = $this->update([
                'user_id' => $user_id,
            ], [
                'company_id' => $company_id
            ]);
            if (!$is_update) {
                throw new \Exception("更新企业信息失败");
            }
            $user_data['user_id'] = $user_id;
            $token = $userModel->save_token($user_data);
            $user_data['token'] = $token;
            Db::commit();
            return $user_data;
        } catch (\Exception $e) {
            Db::rollback();
            $this->setError('注册失败:' . $e->getMessage() . $e->getLine());
            return [];
        }
    }


    /**
     * 产生企业信息之后 添加一些基础信息
     * zll
     * Date: 2021/6/16
     * Time: 16:09
     */
    public function addBaseData($com_id){
        //获取当前时间戳
        $time = time();
        //基本数据
        $baseData = [
            "com_id"=>$com_id,
            "create_time"=>$time,
            "update_time"=>$time,
        ];
        //声明模型
        $sizeModel = new Size();
        $colorModel = new Color();
        $categoryModel = new Category();
        $orgModel = new OrganizationModel();
        $supplierModel = new Supplier();
        $clientBaseModel = new ClientBase();
        $clientAccountModel = new ClientAccount();
        $settlementModel = new Settlement();
        $goodsModel = new Goods();
        $goodsDetailsModel = new GoodsDetails();
        $goodsAssistModel = new GoodsAssist();

        //组装信息---
        //尺码
        $sizeData = [
            array_merge(
                [
                    "size_name"=>"L",
                    "size_code"=>"L1",
                ]
                ,
                $baseData
            ),
            array_merge(
                [
                    "size_name"=>"XL",
                    "size_code"=>"XL1",
                ]
                ,
                $baseData
            ),
        ];

        //色码
        $colorData = [
            array_merge(
                [
                    "color_name"=>"黑色",
                    "color_code"=>"black1",
                ],
                $baseData
            ),
            array_merge(
                [
                    "color_name"=>"红色",
                    "color_code"=>"red1",
                ],
                $baseData
            ),
        ];

        //商品分类
        $categoryData = array_merge(
            [
                "category_name" => "衣服",
                "sort" => 100
            ],
            $baseData
        );

        //仓库
        $orgData = array_merge(
            [
                "org_name"=>"旗舰店",
                "org_head"=>"管理员",
                "org_phone"=>"18888888888",
                "org_type"=>1,
            ],
            $baseData
        );

        //供应商
        $supplierData = array_merge(
            [
                "supplier_name" => "默认供应商",
                "supplier_director" => "管理员",
                "supplier_phone" => "18888888888",
                "supplier_mphone" => "18888888888",
                "supplier_discount" => 10,
                "supplier_email" => "123@qq.com",
                "supplier_address" => "默认地址",
            ],
            $baseData
        );

        //客户
        $clientBaseData = array_merge(
            [
                "client_code"=>BuildCode::dateCode("M"),
                "client_name"=>"默认客户",
                "client_phone"=>"18888888888",
            ],
            $baseData
        );

        //结算账户
        $settlementData = array_merge(
            [
                "settlement_name" => "默认结算",
                "settlement_code" => "88888888",
                "account_holder" => "管理员",
                "settlement_status" => 1,
            ],
            $baseData
        );

        //新增数据---
        $this->startTrans();
        try{
        //保存尺码
        $sizeModel->saveAll($sizeData);

        //获取尺码id数组
        $size_ids = $sizeModel->where("com_id","=",$com_id)->column("size_id");

        //保存颜色
        $colorModel->saveAll($colorData);

        //获取色码id数组
        $color_ids = $colorModel->where("com_id","=",$com_id)->column("color_id");

        //保存商品分类
        $categoryId = $categoryModel->insertGetId($categoryData);

        //保存仓库
        $org_id = $orgModel->insertGetId($orgData);

        //保存供应商
        $supplier_id = $supplierModel->insertGetId($supplierData);

        //保存客户
        $clientBase_id = $clientBaseModel->insertGetId($clientBaseData);
        $clientAccountData = array_merge(
            ["client_id" => $clientBase_id],
            $baseData
        );
        $clientAccountModel->insert($clientAccountData);

        //保存结算账户
        $settlement_id = $settlementModel->insertGetId($settlementData);

        //拼接商品基础信息
        $goodsCode = BuildCode::dateCode("GOOD");
        $goodsData = array_merge(
            [
                "goods_code" => $goodsCode,
                "goods_serial" => $goodsCode,
                "goods_barcode" => $goodsCode,
                "goods_name" => "服装A",
                "goods_pprice" => 100,
                "goods_wprice" => 110,
                "goods_srprice" => 200,
                "goods_rprice" => 190,
                "goods_bnumber" => 100,
                "category_id" => $categoryId,
            ],
            $baseData
        );
        //获取商品id
        $goods_id = $goodsModel->insertGetId($goodsData);
        //拼接商品详情
        $goodsDetailData = [];
        foreach ($color_ids as $colorid){
            foreach ($size_ids as $sizeid){
                $goodsDetailData[] = array_merge(
                    [
                        "goods_code" => $goodsCode,
                        "goods_id" => $goods_id,
                        "color_id" => $colorid,
                        "size_id" => $sizeid,
                        "history_number" => 0,
                    ],
                    $baseData
                );
            }
        }
        //保存商品详情
        $goodsDetailsModel->saveAll($goodsDetailData);
        //拼接商品图片数据
        $assistData = array_merge(
            [
                "goods_id" => $goods_id,
                "assist_category" => "image",
                "assist_name" => "image/0be19511e17148c7",
                "assist_extension" => "jpg",
                "assist_url" => "psi-test-1256793516.cos.ap-beijing.myqcloud.com/image/0be19511e17148c7",
                "assist_sort" => 0,
                "assist_md5" => "image/0be19511e17148c7",
                "assist_sha1" => "image/0be19511e17148c7",
            ],
            $baseData
        );
        $goodsAssistModel->insert($assistData);
        $this->commit();
        return true;
        }catch (\Exception $e){
            $this->rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }

}