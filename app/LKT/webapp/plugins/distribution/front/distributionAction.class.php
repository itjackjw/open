<?php

/**
 * [Laike System] Copyright (c) 2017-2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */

require_once(MO_WEBAPP_DIR . "/plugins/PluginAction.class.php");

class distributionAction extends PluginAction
{

    public function home()
    {
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $id = addslashes(trim($request->getParameter('cid'))); //  '分类ID'
        $paegr = addslashes(trim($request->getParameter('page'))); //  '页面'

        $select = addslashes(trim($request->getParameter('select'))); //  选中的方式 0 默认  1 销量   2价格
        if ($select == 0) {
            $select = 'a.add_date';
        } elseif ($select == 1) {
            $select = 'a.volume';
        } else {
            $select = 'a.price';
        }

        $sort = addslashes(trim($request->getParameter('sort'))); // 排序方式  1 asc 升序   0 desc 降序
        if ($sort) {
            $sort = ' asc ';
        } else {
            $sort = ' desc ';
        }

        $appConfig = $this->getAppInfo();
        $img = $appConfig['imageRootUrl'];

        if (!$paegr) {
            $paegr = 1;
        }
        $start = ($paegr - 1) * 10;
        $end = 10;

        $sql = "select a.*,b.leve1  from lkt_product_list AS a,lkt_detailed_pro AS b where a.id = b.pro_id   AND a.num > 0  order by $select $sort LIMIT $start,$end   ";
        $r = $db->select($sql);

        if ($r) {
            $product = [];
            foreach ($r as $k => $v) {
                $imgurl = $img . $v->imgurl;
                $pid = $v->id;
                $sql_ttt = "select price,yprice from lkt_configure where pid ='$pid' order by price asc ";
                $r_ttt = $db->select($sql_ttt);
                $price = $r_ttt[0]->yprice;
                $price_yh = $r_ttt[0]->price;

                $attr = unserialize($v->initial);
                $attr = array_values($attr);
                if ($attr) {
                    if (gettype($attr[0]) != 'string') unset($attr[0]);
                }

                $product[$k] = array('id' => $v->id, 'name' => $v->product_title, 'price' => $price, 'price_yh' => $price_yh, 'imgurl' => $imgurl, 'volume' => $v->volume, 's_type' => $v->s_type, 'fan' => $price*$v->leve1/100);
            }
            echo json_encode(array('status' => 1, 'pro' => $product));
            exit;
        } else {
            echo json_encode(array('status' => 0, 'err' => '没有了！'));
            exit;
        }

    }


    public function detailed_commission()
    {//确认收货后增加佣金明细
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $order_id = $request->getParameter('order_id'); // 订单号
        $r = $db->select("select Referee,s_money from lkt_detailed_commission where sNo ='$order_id' and recycle =0");
        echo json_encode(array('list' => $r));
        exit();


    }

    public function commission()
    {//返现

    }

    public function membership()
    {//会员人数
        $db = DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $pagesize = 10;
        $page = addslashes($request->getParameter('page'));
        $start = 0;
        if ($page) {
            $start = ($page - 1) * $pagesize;
        }
        $openid = addslashes($request->getParameter('openid'));
        $r = $db->select("select user_id from lkt_user where wx_id = '$openid' ");
        $num = 0;
        $total = 0;
        $r01 = '';
        if ($r) {
            $user_id = $r[0]->user_id;
            $r01 = $db->select("select user_id,user_name,headimgurl,wx_id as openid,Register_data from lkt_user where Referee = '$user_id' order by Register_data desc limit $start,$pagesize");
            $r001 = $db->select("select user_id,user_name,headimgurl,wx_id as openid,Register_data from lkt_user where Referee = '$user_id' order by Register_data desc");
            $num = count($r001);
            $total = ceil($num / $pagesize);
            if ($r01) {
                foreach ($r01 as $key => $value) {
                    $user_id01 = $value->user_id;
                    $r02 = $db->selectrow("select user_id from lkt_user where Referee = '$user_id01'");
                    $value->num = $r02;
                }
            }
        }

        echo json_encode(array('r01' => $r01, 'num' => $num, 'total' => $total));
        exit();

    }

    public function money()
    {//预计佣金


    }

    public function show()
    {//佣金详情

    }
}

?>