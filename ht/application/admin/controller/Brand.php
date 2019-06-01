<?php
namespace app\admin\controller;

use app\admin\model\BrandModel;
use think\Db;
class Brand extends Base
{
    //添加品牌
    public function addbrand()
    {
        if(request()->isPost()){
            $par['name']=input('post.name');
            $par['desc']=input('post.desc');
            $par['brandtime']=time();
            $file = request()->file('logo');
            if($file){
               $info= $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    $par['logo']='/uploads/'.$info -> getSaveName();
                    $brand=new BrandModel();
                    $res=$brand->addbra($par);
                    return json(msg(1,$res, '新增成功'));
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError();
                }

            }
        }
        return $this->fetch();
    }
    //品牌列表
    public function brandli()
    {
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['name'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $article = new BrandModel();
            $selectResult = $article->getArticlesByWhere($where, $offset, $limit);

            foreach($selectResult as $key=>$vo){

                $selectResult[$key]['logo'] = '<img src="' . $vo['logo'] . '" width="40px" height="40px">';
                $selectResult[$key]['brandtime'] =date("Y-m-d H:i", $vo['brandtime']);
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $article->getAllArticles($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();

    }
    public function brandedit()
    {
        $brand=new BrandModel();
        if(request()->isPost()){
            $par['id']=input('post.id');
            $par['name']=input('post.name');
            $par['desc']=input('post.desc');
            $file = request()->file('logo');
            if($file){
                $info= $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    $par['logo']='/uploads/'.$info -> getSaveName();
                    $brand=new BrandModel();
                    $res=$brand->editbra($par);
                    return json(msg(1,$res, '编辑成功'));
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError();
                }

            }
        }

        $id = input('param.id');
        $this->assign([
            'bran' => $brand->getOneArticle($id)
        ]);
        return $this->fetch();
    }
    /*删除*/
    public function branddel()
    {
        $id = input('param.id');
        $article = new BrandModel();
        $flag = $article->delebra($id);
        return json(msg(1,$flag, '删除成功'));
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
                'auth' => 'brand/brandedit',
                'href' => url('brand/brandedit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'brand/branddel',
                'href' => "javascript:articleDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}