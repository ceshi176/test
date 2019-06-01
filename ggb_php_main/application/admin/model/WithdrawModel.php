<?php
namespace app\admin\model;

use think\Model;
use think\Db;
class WithdrawModel extends Model
{
    // 确定链接表名
    protected $name = 'withdraw';

    /**
     * 查询提现表
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getArticlesByWhere($where, $offset, $limit)
    {
        $data=Db::name('withdraw')
            ->alias('w')
            ->join('userinfo u','w.uid=u.u_id')
            ->field('w.id,w.uid,w.money,w.create_time,w.handle_time,w.mode,w.bank_card,w.opening_bank,w.realname,w.status,u.phone')
            ->where($where)
            ->limit($offset, $limit)
            ->order('w.id desc')
            ->select();
        return $data;
    }

    /**
     * 根据搜索条件
     * @param $where
     */
    public function getAllArticles($where)
    {
       //       return $this->where($where)->count();
        return  Db::name('withdraw')
            ->alias('w')
            ->join('userinfo u','w.uid=u.u_id')
            ->field('w.id,w.uid,w.money,w.create_time,w.handle_time,w.mode,w.bank_card,w.opening_bank,w.realname,w.status,u.phone')
            ->where($where)
            ->count();
    }
    /*
     *
     * 审核不通过
     */
    public function nopass($id)
    {
       $res= Db::name('withdraw')
            ->alias('w')
            ->join('userinfo u','w.uid=u.u_id')
            ->field('w.id,w.uid,w.money,w.create_time,w.handle_time,w.mode,w.bank_card,w.opening_bank,w.realname,w.status,u.phone')
          ->where('w.id', $id)->update(['w.status' => -1]);

       return $res;
    }
    /**
     * 编辑文章信息
     * @param $param
     */
    public function editArticle($param)
    {
        try{

            $result = $this->validate('ArticleValidate')->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('articles/index'), '编辑文章成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据文章的id 获取文章的信息
     * @param $id
     */
    public function getOneArticle($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除文章
     * @param $id
     */
    public function delArticle($id)
    {
        try{
            $this->where('id', $id)->delete();
            return msg(1, '', '删除文章成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}
