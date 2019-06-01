<?php
namespace app\admin\model;

use think\Model;
use think\Db;
class SpecsModel extends Model
{
    // 确定链接表名
    protected $name = 'specs';

    /**
     * 查询规格
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getArticles($where,$offset,$limit)
    {

        $data= Db::name('specs')
            ->where($where)
            ->limit($offset, $limit)
            ->order('specs_id desc')
            ->select();
        return $data;
        // return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }


    /**
     * 根据搜索条件获取所有的规格数量
     * @param $where
     */
    public function getAllArticles($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 添加规格
     * @param $param
     */
    public function addArticle($param)
    {
        try{
            $result = $this->validate('ArticleValidate')->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('articles/index'), '添加规格成功');
            }
        }catch (\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑规格信息
     * @param $param
     */
    public function editArticle($param)
    {
        try{

            $result = $this->save($param, ['specs_id' => $param['specs_id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('articles/index'), '编辑规格成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据规格的id 获取规格的信息
     * @param $id
     */
    public function getOneArticle($id)
    {
        return $this->where('specs_id', $id)->find();
    }

    /**
     * 删除规格
     * @param $id
     */
    public function delArticle($id)
    {
        try{
            $this->where('specs_id', $id)->delete();
            return msg(1, '', '删除规格成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}
