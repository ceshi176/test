<?php
namespace app\admin\model;

use think\Model;
use think\Db;
class BrandModel extends Model
{
    // 确定链接表名
    protected $name = 'brand';

    public function addbra($arr)
    {
        $show= Db::name('brand')->insert($arr);
        return $show;
    }
    /**
     * 查询品牌
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getArticlesByWhere($where, $offset, $limit)
    {
        return $this->field('id,name,logo,desc,brandtime')->where($where)->limit($offset, $limit)->order('id desc')->select();
    }
    /**
     * 根据搜索条件获取所有的品牌数量
     * @param $where
     */
    public function getAllArticles($where)
    {
        return $this->where($where)->count();
    }
    /**
     * 根据文章的id 获取文章的信息
     * @param $id
     */
    public function getOneArticle($id)
    {
        return $this->where('id', $id)->find();
    }
    /*编辑品牌*/
    public function editbra($par)
    {
        $edit=$this->where('id', $par['id'])
            ->update($par);
        return $edit;
    }
    /*删除*/
    public function delebra($id)
    {
        $dele=$this->where('id', $id)->delete();
        return $dele;
    }
    /*查询商品品牌*/
    public function seleition()
    {
        $seleition=$this->field('id,name')->select();
        return $seleition;
    }
}