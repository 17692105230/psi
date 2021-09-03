<?php


namespace app\web\model;
use app\common\model\web\Attachment as AttachModel;

class Attachment extends AttachModel
{

    /** @var int 附件类型 附件 */
    public const ATTACHMENT_CATEGORY_ATTACH = 1;
    /** @var int 附件类型 图片 */
    public const ATTACHMENT_CATEGORY_IMG = 2;

    /** @var int 订单类型 采购 */
    public const ATTACHMENT_ORDER_TYPE_PURCHASE = 1;



}