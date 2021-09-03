<?php


namespace app\web\controller;


use app\Request;
use app\web\model\PurchaseOrders as PurchaseOrdersModel;
use app\web\model\SaleOrders as SaleOrdersModel;
use app\web\model\InventoryOrders as InventoryOrdersModel;
use app\web\model\TransferOrders as TransferOrdersModel;

class Finance extends Controller
{
    //综合单据查询
    public function blendBill(Request $request){
        $search_type = $request->get("search_type",0);//搜索类型0进货单1销售单2盘点单3调拨单
        $start_time = $request->get("start_time","");//开始时间
        $start_time = !empty($start_time) ? strtotime($start_time) : 0;
        $end_time = $request->get("end_time",0);//结束时间
        $end_time = !empty($end_time) ? (strtotime($end_time)+86399) : 0;
        $rows = $request->get("rows",20);

        $list = $this->purchaseList($this->com_id,$start_time,$end_time,$rows);

        if($search_type==1){
            $list = $this->saleList($this->com_id,$start_time,$end_time,$rows);
        }
        if($search_type==2){
            $list = $this->inventoryList($this->com_id,$start_time,$end_time,$rows);
        }
        if($search_type==3){
            $list = $this->transferList($this->com_id,$start_time,$end_time,$rows);
        }

        return $this->renderSuccess($list);
    }

    //采购单列表
    public function purchaseList($com_id,$start_time=0,$end_time=0,$rows=10){
        $model = new PurchaseOrdersModel();
        $mod = $model->with(["supplier","warehouse","user"])
            ->when((!empty($start_time) && !empty($end_time)),function($query) use ($start_time,$end_time){
                return $query->where("create_time","between",[$start_time,$end_time]);
            })
            ->where("com_id","=",$com_id)
            ->where("orders_status","=",9);
        $data = $mod->paginate($rows)->each(function($item,$key){
            $item['contact'] = $item['supplier']['supplier_name'];
        })->toArray();
        //查询总和数量和金额
        $res = $mod->fieldRaw("SUM(goods_number) as sum_number,SUM(orders_rmoney) as sum_money")->select();
        if($res){
            $data['sum_data'] = $res[0];
        }else{
            $data['sum_data']['sum_number'] = 0;
            $data['sum_data']['sum_money'] = 0;
        }
        return $data;
    }

    //销售单列表
    public function saleList($com_id,$start_time=0,$end_time=0,$rows=10){
        $model = new SaleOrdersModel();
        $mod = $model->with(["client","member","warehouse","user"])
            ->when((!empty($start_time) && !empty($end_time)),function($query) use ($start_time,$end_time){
                return $query->where("create_time","between",[$start_time,$end_time]);
            })
            ->where("com_id","=",$com_id)
            ->where("orders_status","=",9);
        $data = $mod->paginate($rows)->each(function($item,$key){
            if($item['orders_type']==0){//销售单
                $item['contact'] = $item['client']['client_name'];
            }else{//零售单
                $item['contact'] = $item['member']['member_name'];
            }
        })->toArray();
        //查询总和数量和金额
        $res = $mod->fieldRaw("SUM(goods_number) as sum_number,SUM(orders_rmoney) as sum_money")->select();
        if($res){
            $data['sum_data'] = $res[0];
        }else{
            $data['sum_data']['sum_number'] = 0;
            $data['sum_data']['sum_money'] = 0;
        }
        return $data;
    }

    //盘点单列表
    public function inventoryList($com_id,$start_time=0,$end_time=0,$rows=10){
        $model = new InventoryOrdersModel();
        $mod = $model->with(["organization","user"])
            ->when((!empty($start_time) && !empty($end_time)),function($query) use ($start_time,$end_time){
                return $query->where("create_time","between",[$start_time,$end_time]);
            })
            ->where("com_id","=",$com_id)
            ->where("orders_status","=",9);
        $data = $mod->paginate($rows)->each(function($item,$key){
            $item['contact'] = "";
        })->toArray();
        //查询总和数量和金额
        $res = $mod->fieldRaw("SUM(goods_number) as sum_number")->select();

        if($res){
            $data['sum_data'] = $res[0];
        }else{
            $data['sum_data']['sum_number'] = 0;
        }
        $data['sum_data']['orders_rmoney'] = 0;
        return $data;
    }

    //调拨单列表
    public function transferList($com_id,$start_time=0,$end_time=0,$rows=10){
        $model = new TransferOrdersModel();
        $mod = $model->with(["inorg","outorg","user"])
            ->when((!empty($start_time) && !empty($end_time)),function($query) use ($start_time,$end_time){
                return $query->where("create_time","between",[$start_time,$end_time]);
            })
            ->where("com_id","=",$com_id)
            ->where("orders_status","=",9);
        $data = $mod->paginate($rows)->each(function($item,$key){
            $item['contact'] = "";
        })->toArray();
        //查询总和数量和金额
        $res = $mod->fieldRaw("SUM(goods_number) as sum_number")->select();

        if($res){
            $data['sum_data'] = $res[0];
        }else{
            $data['sum_data']['sum_number'] = 0;
        }
        $data['sum_data']['orders_rmoney'] = 0;
        return $data;
    }
}