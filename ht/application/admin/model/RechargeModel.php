<?php
namespace app\admin\model;

use think\Model;
use think\Db;
class RechargeModel extends Model
{
    // 确定链接表名
    protected $name = 'recharge';

    /**
     * 查询充值
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getArticlesByWhere($where, $offset, $limit)
    {
        $data=Db::name('recharge')
            ->alias('r')
            ->join('userinfo u','r.uid=u.u_id')
            ->field('r.id,r.uid,r.mobile,r.account,r.mode,r.pay_status,r.order_sn,r.ctime,u.phone')
            ->where($where)
            ->limit($offset, $limit)
            ->order('r.id desc')
            ->select();
        return $data;
    }

    /**
     * 根据搜索条件获取所有的充值数量
     * @param $where
     */
    public function getAllArticles($where)
    {
        $data=Db::name('recharge')
            ->alias('r')
            ->join('userinfo u','r.uid=u.u_id')
            ->field('r.id,r.uid,r.mobile,r.account,r.mode,r.pay_status,r.order_sn,r.ctime,u.phone')
            ->where($where)
            ->count();
        return $data;

    }


    /**
     * 编辑充值信息
     * @param $param
     */
    public function editArticle($param)
    {
        try{

            $result = $this->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('recharge/deposit'), '编辑充值记录成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据充值的id 获取充值的信息
     * @param $id
     */
    public function getOneArticle($id)
    {
        $data=Db::name('recharge')
            ->alias('r')
            ->join('userinfo u','r.uid=u.u_id')
            ->field('r.id,r.uid,r.mobile,r.account,r.mode,r.pay_status,r.order_sn,r.ctime,u.phone')
            ->where('r.id', $id)
            ->find();
        return $data;
    }

    /**
     * 删除充值
     * @param $id
     */
    public function delArticle($id)
    {
        try{
            $this->where('id', $id)->delete();
            return msg(1, '', '删除充值成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}