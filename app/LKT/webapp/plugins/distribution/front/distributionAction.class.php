<?php

/**
 * [Laike System] Copyright (c) 2017-2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */

require_once(MO_WEBAPP_DIR . "/plugins/PluginAction.class.php");

class distributionAction extends PluginAction {

    
   public function detailed_commission(){//确认收货后增加佣金明细
        echo json_encode(array('res'=>'正在开发中!','status'=>1));
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
       // 每页显示多少条数据
       $page = $request->getParameter('page');

       // 页码
       if ($page) {
           $start = ($page - 1) * $pagesize;
       } else {
           $start = 0;
       }
       $openid = $request->getParameter('openid');
       $r = $db->select("select user_id from lkt_user where wx_id = '$openid' ");
       $num = 0;
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
       } else {
           $r01 = '';
           $total = 0;
       }

       echo json_encode(array('r01' => $r01, 'num' => $num, 'total' => $total));
       exit();
       echo json_encode(array('res'=>'正在开发中!','status'=>1));
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