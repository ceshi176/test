<?php
namespace app\admin\controller;

use app\admin\model\GoodsitemizeModel;
use app\admin\model\SpecsModel;
use app\admin\model\BrandModel;
use think\Db;
class Goodsitemize extends Base
{

    //商品分类添加
    public function additemize()
    {
        $itemize=new GoodsitemizeModel();
        if(request()->isPost()){
            $arr=input('post.');
            $item=$itemize->additem($arr);
            return json(msg('1' ,$item, '添加成功'));
        }

        $show=$itemize->typeshow();
        $list=getree($show);
        $this->assign('list',$list);
        return $this->fetch();
    }

     //商品分类展示
    public function listitemize()
    {
        $itemize=new GoodsitemizeModel();
        $item=$itemize->listitem();
        $lis=getree($item);
        $this->assign('item',$lis);
        return $this->fetch();
    }
    //分类删除
    public function deleitemize()
    {
        $arr=input();
        $id=$arr['id'];
        $itemize=new GoodsitemizeModel();
        $lis=$itemize->deleitem($id);
        if($lis)
        {
            $this->success('删除成功', 'goodsitemize/listitemize');
        }
    }

        public function norms()
    {
        if(request()->isPost()){
            $pid=input('post.pro_id');
            $where['pid']=$pid;
            $region= Db::name('goods_itemize')
                ->where($where)
                ->select();

            return json(msg('1' ,$region, '添加成功'));

        }
        $itemize=new GoodsitemizeModel();
        $lis=$itemize->parentclass();
        $this->assign('par',$lis);
        return $this->fetch();
    }

    //分类展示级别
        public function superior(){
            $level_id=input('post.level_id');
           // print_r($level_id);exit();
            $where['pid']=$level_id;
            $reg= Db::name('goods_itemize')
                ->where($where)
                ->select();
            // print_r($region);exit;
            return json(msg('1' ,$reg, '添加成功'));
}
        //商品规格新增
        public function specs()
        {
            if(request()->isPost()) {
                $arr = input('post.');
                //print_r($arr);exit;
                $res1 = Db::name('specs')->insert(['specs_name' => $arr['specs_name'], 'specs_memo' => $arr['specs_memo'],'specstime'=>time()]);
                $userId = Db::name('specs')->getLastInsID();
                foreach ($arr['specsdata_name'] as $k=>$v){
                    $inse = Db::name('specsdata')->insert(['specsdata_name' => $v, 'specs_id' =>$userId]);
                }
                $this->success('新增成功');

            }
            return $this->fetch();
        }
        //规格列表
        public function specslist()
        {
            if(request()->isAjax()){

                $param = input('param.');

                $limit = $param['pageSize'];
                $offset = ($param['pageNumber'] - 1) * $limit;

                $where = [];
                if (!empty($param['searchText'])) {
                    $where['specs_name'] = ['like', '%' . $param['searchText'] . '%'];
                }

                $article = new SpecsModel();
                $selectResult = $article->getArticles($where, $offset, $limit);
               //print_r($selectResult);exit;
                foreach($selectResult as $key=>$vo){
                    $res= Db::name('specsdata')->where('ecs_id',$vo['specs_id'])->select();
                    $selectResult[$key]['specsdata_name'] =array_column($res, 'specsdata_name');
                    $selectResult[$key]['specstime'] = date("Y-m-d H:i", $vo['specstime']);
                    $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['specs_id']));
                }
                //print_r($re);exit;
                $return['total'] = $article->getAllArticles($where);  // 总数据
                $return['rows'] = $selectResult;

                return json($return);
            }

            return $this->fetch();
        }
        //规格删除
        public function specsdele()
        {
            $id = input('param.specs_id');
            $article = new SpecsModel();
            $flag = $article->delArticle($id);
            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }
        //规格编辑
        public function specsedit()
        {
            $specs = new SpecsModel();
            if(request()->isPost()) {
                $specs_name = input('post.specs_name');
                $specs_memo = input('post.specs_memo');
                $specs_id = input('post.specs_id');
                $ec=input('post.');
              //print_r($id);
                $data= Db::name('specs')
                    ->where('specs_id',$specs_id)
                    ->update(['specs_name' => $specs_name],['specs_memo' =>$specs_memo]);


            //print_r($id);exit;
             foreach ($ec['specsdata_name'] as $k=>$vv){
                 $where['ecs_id']=$specs_id;

                     $data=Db::name('specsdata') ->where($where)
                         ->update(['specsdata_name' => $vv]);


                }
                $this->success('编辑成功', 'goodsitemize/specslist');
            }

            $id = input('param.specs_id');
            $specs= Db::name('specs')->where('specs_id',$id)->find();
            $res= Db::name('specsdata')->where('ecs_id',$specs['specs_id'])->select();
            //print_r($res);exit;
            $this->assign('specs',$specs);
            $this->assign('specsdata',$res);

            return $this->fetch();
        }
        //商品添加
        public function addition()
        {
             //商品分类查询
            $itemize=new GoodsitemizeModel();
            $show=$itemize->typeshow();
            $list=getree($show);
            //品牌查询
            $seleition=new BrandModel();
            $leition=$seleition->seleition();
            $this->assign('list',$list);
            $this->assign('ition',$leition);

            return $this->fetch();
        }
        /**
         * 拼装操作按钮
         * @param $id
         * @return array
         */
    private function makeButton($specs_id)
        {
            return [
                '编辑' => [
                    'auth' => 'goodsitemize/specsedit',
                    'href' => url('goodsitemize/specsedit', ['specs_id' => $specs_id]),
                    'btnStyle' => 'primary',
                    'icon' => 'fa fa-paste'
                ],
                '删除' => [
                    'auth' => 'goodsitemize/specsdele',
                    'href' => "javascript:articleDel(" . $specs_id . ")",
                    'btnStyle' => 'danger',
                    'icon' => 'fa fa-trash-o'
                ]
            ];
        }

}