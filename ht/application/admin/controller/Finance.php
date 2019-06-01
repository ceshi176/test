<?php
/*提现审核*/
namespace app\admin\controller;

use app\admin\model\WithdrawModel;
use app\admin\model\RechargeModel;


class Finance extends Base
{
     //审核提现
    public function index()
    {
        if(request()->isAjax()){

            $param = input('param.');


            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['u.phone'] = ['like', '%' . $param['searchText'] . '%'];
            }
            if (!empty($param['searchTet'])) {
                $where['w.status'] = ['like', '%' . $param['searchTet'] . '%'];
            }


            $article = new WithdrawModel();
            $selectResult = $article->getArticlesByWhere($where, $offset, $limit);
           // print_r($selectResult);exit;
            foreach($selectResult as $key=>$vo){
                if($vo['status']=='0'){
                    $selectResult[$key]['status']='审核中';
                }elseif ($vo['status']=='1'){
                    $selectResult[$key]['status']='审核通过';
                }elseif ($vo['status']=='2'){
                    $selectResult[$key]['status']='审核拒绝';
                }elseif ($vo['status']=='9'){
                    $selectResult[$key]['status']='付款失败';
                }elseif ($vo['status']=='-2'){
                    $selectResult[$key]['status']='付款成功';
                }elseif ($vo['status']=='-3'){
                    $selectResult[$key]['status']='删除作废';
                }
                if($vo['mode']=='0'){
                    $selectResult[$key]['mode']='支付宝';
                }elseif ($vo['mode']=='1'){
                    $selectResult[$key]['mode']='银行卡';
                }
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $article->getAllArticles($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
       }
        return $this->fetch();
    }
    //审核列表
    public function index1()
    {

    }
    //审核通过
    public function adopt()
    {

        $id =input('get.id');
        //print_r($id);
   }
    //审核不通过
    public function notpass()
   {
       $id =input('get.id');
       $notpass = new WithdrawModel();
       $no=$notpass->nopass($id);
       return json(msg('1' ,$no, '审核拒绝'));

    }

    /**
 * 拼装操作按钮
 * @param $id
 * @return array
 */
    private function makeButton($id)
    {
        return [
            '通过' => [
                'auth' => 'finance/adopt',
                'href' => url('finance/adopt', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '拒绝' => [
                'auth' => 'finance/notpass',
                'href' => "javascript:notpass(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ],


        ];
    }

}