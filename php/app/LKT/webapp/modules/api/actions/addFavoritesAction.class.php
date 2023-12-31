<?php
/**
 * [Laike System] Copyright (c) 2017-2023 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */

require_once 'BaseAction.class.php';

class addFavoritesAction extends BaseAction
{

    // 点击收藏
    public function index()
    {
        $openid = addslashes($_POST['openid']); // 微信id
        $pid    = addslashes($_POST['pid']); // 产品id
        // 根据微信id,查询用户id
        $sql     = "select user_id from lkt_user where wx_id = '$openid' ";
        $r       = lkt_gets($sql);
        $user_id = $r[0]->user_id;
        // 根据用户id,产品id,查询收藏表
        $sql = "select * from lkt_user_collection where user_id = '$user_id' and p_id = '$pid'";
        $r   = lkt_gets($sql);
        if ($r) {
            echo json_encode(array('status' => 0, 'err' => '已收藏！'));
            exit();
        } else {
            // 在收藏表里添加一条数据
            $sql = "insert into lkt_user_collection(user_id,p_id,add_time) values('$user_id','$pid',CURRENT_TIMESTAMP)";
            $r   = lkt_insert($sql);
            if ($r) {
                echo json_encode(array('status' => 1, 'succ' => '收藏成功!', 'id' => $r));
                exit();
            } else {
                echo json_encode(array('status' => 0, 'err' => '网络繁忙！'));
                exit();
            }
        }

    }

    // 查看收藏
    public function collection()
    {
        $openid = addslashes($_POST['openid']); // 微信id
        $appConfig = $this->getAppInfo();
        $img       = $appConfig['imageRootUrl'];

        // 根据微信id,查询用户id
        $sql     = "select user_id from lkt_user where wx_id = '$openid'";
        $r       = lkt_gets($sql);
        $user_id = $r[0]->user_id;
        $sql     = "
select l.id,a.id as pid,a.product_title,a.imgurl as img,c.price
    from lkt_user_collection as l, lkt_product_list AS a,(select min(price) price,pid from lkt_configure group by pid) AS c
    where
    l.p_id = a.id and a.id = c.pid and l.user_id = '$user_id' and a.num >0  order by l.add_time desc
";
        $r   = lkt_gets($sql);
        $arr = [];
        if ($r) {
            foreach ($r as $k => $v) {
                $array           = (array) $v;
                $array['price']  = $v->price;
                $array['imgurl'] = $img . $v->img;
                $v               = (object) $array;
                $arr[$k]         = $v;
            }
            echo json_encode(array('status' => 1, 'list' => $arr));
            exit();
        } else {
            echo json_encode(array('status' => 1, 'list' => ''));
            exit();
        }
    }

    // 取消收藏
    public function removeFavorites()
    {
        $id = addslashes($_POST['id']);
        $sql = "delete from lkt_user_collection where id = '$id'";
        $r = lkt_execute($sql);
        if ($r > 0) {
            echo json_encode(array('status' => 1, 'succ' => '已取消！'));
            exit();
        } else {
            echo json_encode(array('status' => 0, 'err' => '网络繁忙！'));
            exit();
        }
    }

    public function alldel()
    {
        $request  = $this->getContext()->getRequest();
        $openid   = addslashes(trim($request->getParameter('openid'))); // 微信id
        $sql_user = 'select user_id from lkt_user where wx_id=\'' . $openid . '\'';
        $r_user   = lkt_gets($sql_user);
        $userid   = $r_user[0]->user_id;
        $sql      = "delete from lkt_user_collection where user_id = '$userid'";
        $r        = lkt_execute($sql);
        if ($r) {
            echo json_encode(array('status' => 1, 'succ' => '删除成功！'));
            exit();
        } else {
            echo json_encode(array('status' => 0, 'err' => '删除失败！'));
            exit();
        }
    }
}
