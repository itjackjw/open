<?php
/*
 * @Author: your name
 * @Date: 2019-12-05 16:08:08
 * @LastEditTime : 2019-12-20 10:22:50
 * @LastEditors  : Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \LaiKeApp\app\LKT\webapp\modules\distribution\actions\modifyAction.class.php
 */

/**
 * [Laike System] Copyright (c) 2018 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */


require_once(MO_LIB_DIR . '/DBAction.class.php');
require_once(MO_LIB_DIR . '/ServerPath.class.php');
require_once(MO_LIB_DIR . '/ShowPager.class.php');
require_once(dirname(__DIR__) . "/model/DistributionModel.php");

class modifyAction extends LaiKeAction
{


    public function getDefaultView()
    {
        $request = $this->getContext()->getRequest();
        $m = $request->getParameter('m');
        $id = $request->getParameter('id');
        if ($m != '') {
            $this->$m();
            exit;
        }

        return View::INPUT;
    }

    public function execute()
    {
        return;
    }
    public function ajaxmodify()
    {
        $request = $this->getContext()->getRequest();
        $id = $request->getParameter('id');
        $rr = array();
        $data11 = array();
        $data = array();
        if ($id) {
            $data11['id'] = $id;
        }
        $rr = DistributionModel::selectProAll($data11); //查询所有分销商品
        echo json_encode(array('code' => 200, 'data' => $rr));
        exit();
    }
    public function baocun()
    {
        $request = $this->getContext()->getRequest();
        $data = array();

        $leve = $request->getParameter('leve'); //向上返几级
        $leve1 = $request->getParameter('leve1'); //一级佣金比例
        $leve2 = $request->getParameter('leve2');  //二级佣金比例
        $leve3 = $request->getParameter('leve3'); //三级佣金比例
        $leve4 = $request->getParameter('leve4'); //四级佣金比例
        $leve5 = $request->getParameter('leve5'); //五级佣金比例
        $is_show = $request->getParameter('is_show'); //是否显示（0不显示，1热销单品，2.购物车，3.个人中心,4.分销商品显示）

        $data[] = $leve = $leve ? $leve : '0';
        $data[] = $leve1 = $leve1 ? $leve1 : '0'; //一级佣金比例
        $data[] = $leve2 = $leve2 ? $leve2 : '0'; //二级佣金比例
        $data[] = $leve3 = $leve3 ? $leve3 : '0'; //三级佣金比例

        $data[] = $type = $request->getParameter('type'); //佣金发放类型，1 支付成功 2.确认收货
        $data[] = $commissions = $request->getParameter('commissions'); //分销佣金所需手续费
        $data[] = $is_show ? $is_show : '0'; //是否显示（0不显示，1热销单品，2.购物车，3.个人中心，4分销商品显示）
        $data[] = $id = $request->getParameter('id'); //id
        $conn = new LaiketuiDB(); //开启事务
        $db = $conn->connection();
        $db->transaction();
        $r_update =  DistributionModel::updataPro($data, '');
        if ($r_update >= 0) { // 
            $db->commit();
            echo json_encode(array('code' => 200, 'message' => '修改成功!'));
            exit();
        } else {
            $db->rollback();
            echo json_encode(array('code' => 400, 'message' => '未知原因，修改失败!'));
            exit();
        }
    }

    public function getRequestMethods()
    {

        return Request::NONE;
    }
}
