<?php
namespace app\web\controller;

/**
 * 单元格编辑
 * Class CellEditing
 * @package app\web\controller
 */
class CellEditing extends Controller
{
	public function LoadApplyList()
	{
		return json(json_decode('{"total":2,"rows":[{"orders_id":2,"orders_code":"SP180401093802243","orders_date":1525449600,"client_id":3,"client_name":"张志超","orders_status":9},{"orders_id":1,"orders_code":"SP180309234651672","orders_date":0,"client_id":2,"client_name":"小蜜","orders_status":9}],"status":null}'));
	}

	public function loadInventoryDetails()
	{
		return json(json_decode('{"errcode":0,"errmsg":"查询成功","rows":[{"details_id":622,"orders_code":"SI180726214952254","goods_code":"321","color_id":4,"size_id":8,"goods_number":1,"children_code":13,"user_id":1,"goods_anumber":0,"goods_lnumber":1,"goods_status":0,"create_time":1597655021,"update_time":1597655021,"lock_version":0,"goods_name":"裙子123","goods_barcode":"","color_name":"黑色","size_name":"3XL"},{"details_id":621,"orders_code":"SI180726214952254","goods_code":"321","color_id":4,"size_id":8,"goods_number":1,"children_code":13,"user_id":1,"goods_anumber":0,"goods_lnumber":1,"goods_status":0,"create_time":1597655021,"update_time":1597655021,"lock_version":0,"goods_name":"裙子123","goods_barcode":"","color_name":"黑色","size_name":"3XL"},{"details_id":620,"orders_code":"SI180726214952254","goods_code":"321","color_id":8,"size_id":6,"goods_number":1,"children_code":13,"user_id":1,"goods_anumber":0,"goods_lnumber":1,"goods_status":0,"create_time":1597655021,"update_time":1597655021,"lock_version":0,"goods_name":"裙子123","goods_barcode":"","color_name":"深灰色","size_name":"XL"}],"total":3}'));
	}

	public function getGoodsColorData()
	{
		return json(json_decode('[{"color_id":2,"color_name":"红色"},{"color_id":3,"color_name":"蓝色"},{"color_id":4,"color_name":"黑色"},{"color_id":10,"color_name":"花白色"},{"color_id":6,"color_name":"中咖色"},{"color_id":7,"color_name":"浅灰色"},{"color_id":8,"color_name":"深灰色"},{"color_id":15,"color_name":"美女"}]'));
	}

	public function getGoodsSizeData()
	{

		return json(json_decode('[{"size_id":3,"size_name":"S"},{"size_id":4,"size_name":"M"},{"size_id":5,"size_name":"L"},{"size_id":6,"size_name":"XL"},{"size_id":7,"size_name":"2XL"},{"size_id":8,"size_name":"3XL"},{"size_id":10,"size_name":"31码"},{"size_id":11,"size_name":"32码"},{"size_id":12,"size_name":"33码"},{"size_id":13,"size_name":"34码"},{"size_id":14,"size_name":"35码"},{"size_id":15,"size_name":"36码"},{"size_id":16,"size_name":"37码"}]'));
	}

	public function loadModuleRows()
	{
		return json(json_decode(''));
	}
}