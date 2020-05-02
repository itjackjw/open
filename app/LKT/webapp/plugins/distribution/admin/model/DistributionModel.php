<?php



class DistributionModel
{


    /**
     * 
     * @description: 分销商品显示 
     * @param $data
     * @return: 
     */
    public static function selectProAll($data, $last = '')
    {
        $whereStr = '';
        $dd = array();
        if (isset($data['name'])) {
            $dd[] = $data['name'];
            $whereStr .= "  AND b.product_title like ? ";
        }
        if (isset($data['id'])) {
            $dd[] = $data['id'];
            $whereStr .= "  AND a.id = ? ";
        }
        $res = LaiketuiDB::select("select a.*, b.product_title,b.volume,b.imgurl,b.num,b.status as sta from lkt_detailed_pro AS a,lkt_product_list AS b where a.pro_id = b.id AND b.num > 0  " . $whereStr . " order by a.id desc  ", $dd);
        return $res;
    }

    /**
     * @description: 删除分销商品
     * @param {type} 
     * @return: 
     */
    public static function delPro($id)
    {
        $res = LaiketuiDB::delete("delete from lkt_detailed_pro where id =  ?", array($id));
        if ($res) {
            return $res;
        }
        return;
    }
    /**
     * @description: //修改分销商品信息
     * @param {type} 
     * @return: 
     */
    public static function updataPro($data, $type)
    {

        switch ($type) {

            case "status";
                $res = LaiketuiDB::updateData("update lkt_detailed_pro set status=? where id = ?", $data);
                break;

            default:
                $res = LaiketuiDB::updateData("update lkt_detailed_pro set leve = ?,leve1 = ?,leve2 = ?,leve3 = ?,type = ?,commissions = ?,is_show = ? where id = ?", $data);
                break;
        }
        return $res;
    }

    /**
     * @description: 添加分销商品
     * @param {type} 
     * @return: 
     */
    public static  function insertSql($data)
    {
        $dd[] =  $data['id'];
        $dd[] = $data['leve'] ? $data['leve'] : '0';
        $dd[] =  $data['leve1'] ? $data['leve1'] : '0';
        $dd[] = $data['leve2'] ? $data['leve2'] : '0';
        $dd[] = $data['leve3'] ? $data['leve3'] : '0';
        $dd[] = $data['type'];
        $dd[] = $data['commissions'];
        $dd[] = 0;
        $dd[] = $data['is_show'] ? $data['is_show'] : '0';

        $res = LaiketuiDB::add("insert into lkt_detailed_pro(pro_id,leve,leve1,leve2,leve3,type,commissions,status,is_show) values(?,?,?,?,?,?,?,?,?)", $dd);
        return $res;
    }

    /**
     * @description: //删除分销订单
     * @param {type} 
     * @return: 
     */
    public static function delOrder($data)
    {
        $res = LaiketuiDB::updateData("update lkt_detailed_commission set recycle = ? where id = ?", $data);
        return $res;
    }


    /**
     * 
     * @description: 分销订单显示
     * @param $data
     * @return: 
     */
    public static function selectAll($data, $last = '')
    {
        $dd = array();
        $whereStr = '';
        //名字
        if (isset($data['username'])) {
            $dd[] = $data['username'];
            $dd[] = $data['username'];
            $whereStr .= " and (b.user_name LIKE ? or b.user_id like ?) ";
        }
        //订单号
        if (isset($data['sNo'])) {
            $dd[] = $data['sNo'];
            $whereStr .= " and r_sNo like ?";
        }
        //开始时间
        if (isset($data['start_time'])) {
            $dd[] = $data['start_time'];
            $whereStr .= " and addtime >= ?";
        }
        //结束时间
        if (isset($data['end_time'])) {
            $dd[] = $data['end_time'];
            $whereStr .= " and addtime <= ?";
        }
        $res = LaiketuiDB::select("select a.id,MIN(a.userid) AS userid,MIN(a.sNo) AS sNo,MIN(a.money) AS money,MIN(a.s_money) AS s_money,MIN(a. STATUS) AS STATUS,MIN(a.addtime) AS addtime,MIN(a.type) AS type,a.*, min(b.user_name) AS R_user_name,min(b.user_id) AS user_id,min(b.user_name) AS R_user_name,min(b.user_id) AS user_id from lkt_detailed_commission as a,lkt_user as b ,lkt_order_details as c  where a.Referee = b.user_id and a.sNo = c.r_sNo and a.recycle = 0" . $whereStr . " group by a.id order by MIN(a.addtime) DESC", $dd);

        return $res;
    }
}
