<?php

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
require_once(MO_LIB_DIR . '/DBAction.class.php');
require_once(dirname(__DIR__) . "/model/DistributionModel.php");

class delAction extends LaiKeAction
{

    public function getDefaultView()
    {
        $request = $this->getContext()->getRequest();
        // 接收信息
        $id = intval($request->getParameter('id')); // 插件id
        $data = array();
        $data[] = $recycle = '1';
        $data[] = $id;

        $res =  DistributionModel::delOrder($data);
        if ($res > 0) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script type='text/javascript'>" .
                "alert('删除成功！');" .
                "location.href='index.php?module=distribution&action=index';</script>";
        } else {
            header("Content-type:text/html;charset=utf-8");
            echo "<script type='text/javascript'>" .
                "alert('删除失败！');" .
                "location.href='index.php?module=distribution&action=index';</script>";
        }
        return;
    }

    public function execute()
    {
    }

    public function getRequestMethods()
    {
        return Request::NONE;
    }
}
