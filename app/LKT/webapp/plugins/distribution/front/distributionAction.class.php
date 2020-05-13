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