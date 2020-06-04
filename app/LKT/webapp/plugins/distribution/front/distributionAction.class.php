<?php

/**
 * [Laike System] Copyright (c) 2017-2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */

require_once(MO_WEBAPP_DIR . "/plugins/PluginAction.class.php");

class distributionAction extends PluginAction {

    
   public function detailed_commission(){//确认收货后增加佣金明细
       $db = DBAction::getInstance();
       $request = $this->getContext()->getRequest();
       $order_id = $request->getParameter('order_id'); // 订单号
       $r = $db->select("select Referee,s_money from lkt_detailed_commission where sNo ='$order_id' and recycle =0");
       echo json_encode(array('list' => $r));
       exit();
        

   }

   public function pt_detailed_commission(){//拼团确认收货后增加佣金明细
       echo json_encode(array('res'=>'正在开发中!','status'=>1));
        exit();
    
   }

   public function commission(){//返现
        echo json_encode(array('res'=>'正在开发中!','status'=>1));
        exit();
   }

   public function membership(){//会员人数
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
       $total = 0 ;
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

  public function money(){//预计佣金
        echo json_encode(array('res'=>'正在开发中!','status'=>1));
        exit();

   }
  
  public function show(){//佣金详情
        echo json_encode(array('res'=>'正在开发中!','status'=>1));
        exit();
    }
}
?>