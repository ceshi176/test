<?php
/*商品*/
namespace app\admin\controller;
use app\admin\model\GoodsitemizeModel;
use think\Db;
class Commodity extends Base
{
    public function checkpending()
    {
        return $this->fetch();
    }
}