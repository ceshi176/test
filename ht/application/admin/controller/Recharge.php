<?php
/*充值*/
namespace app\admin\controller;

use app\admin\model\RechargeModel;

class Recharge extends Base
{
    //充值记录
    public function deposit()
    {
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['u.phone'] = ['like', '%' . $param['searchText'] . '%'];
            }
            if (!empty($param['searchTex'])) {
                $where['pay_status'] = ['like', '%' . $param['searchTex'] . '%'];
            }

            $article = new RechargeModel();
            $selectResult = $article->getArticlesByWhere($where, $offset, $limit);

            foreach($selectResult as $key=>$vo){
                if($vo['pay_status']=='1'){
                    $selectResult[$key]['pay_status']='待付款';
                }elseif ($vo['pay_status']=='2'){
                    $selectResult[$key]['pay_status']='已付款';
                }
                $selectResult[$key]['ctime'] =date("Y-m-d H:i", $vo['ctime']);
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $article->getAllArticles($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }
    //删除
    public function rechedle()
    {
        $id = input('param.id');
        $article = new RechargeModel();
        $flag = $article->delArticle($id);
        return json(msg($flag['code'], $flag['data'], $flag['msg']));
    }
    //编辑
    public function rechedit()
    {
        $article = new RechargeModel();
        if(request()->isPost()){

            $param = input('post.');
            $flag = $article->editArticle($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');
        $this->assign([
            'rech' => $article->getOneArticle($id)
        ]);
        return $this->fetch();
    }
    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return [
            '编辑' => [
                'auth' => 'recharge/rechedit',
                'href' => url('recharge/rechedit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'recharge/rechedle',
                'href' => "javascript:rechedle(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}