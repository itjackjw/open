<?php

/**
 * [Laike System] Copyright (c) 2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */


class relationshipAction extends PluginAction
{

    public function getDefaultView()
    {
        $request = $this->getContext()->getRequest();
        $status = $request->getParameter("status"); // 订单ID
        $username = $request->getParameter("username"); // 用户名字
        $start_time = $request->getParameter("starttime"); // 开始时间
        $end_time = $request->getParameter("group_end_time"); // 结束时间
        $wx_id = $request->getParameter("wx_id"); // 推荐人ID

        $pagesize = $request->getParameter('pagesize');
        $pageto = $request->getParameter('pageto');
        $pagesize = $pagesize ? $pagesize : 10;
        // 每页显示多少条数据
        $page = $request->getParameter('page');
        if ($page) {
            $start = ($page - 1) * $pagesize;
        } else {
            $start = 0;
        }
        $data = array();
        $res1 = array();
        $dd = array();
        //如果名字不为空
        if ($username != '') {
            $data['username'] = $username;
        }
        if ($start_time) {
            $data['start_time'] = $start_time;
        }
        //订单ID
        if ($end_time) {
            $data['end_time'] = $end_time;
        }
        if ($wx_id  || $wx_id == '0') {
            $data['Referee'] = $wx_id;
        }

        $res = Users::SelectUser($data);
        $total = count($res);

        if ($res) {
            $res1 = array_slice($res, $start, $pagesize); //分页
            foreach ($res1 as $key => $value) {

                $dd['user_id'] =  $value->referee;

                $rr = Users::SelectUser($dd); //查询上级信息
                $R_user_name = $rr ? $rr[0]->user_name : '';
                $R_user_id = $rr ? $rr[0]->user_id : '';
                $res1[$key]->setCustomerAttr("R_user_name",  $R_user_name);
                $res1[$key]->setCustomerAttr("R_user_id",  $R_user_id);
                $res1[$key] = $res1[$key]->getAllAttrs();
            }
        }
        $pager = new ShowPager($total, $pagesize, $page);
        $url = "index.php?module=distribution&action=relationship&status=$status&username=$username&start_time=$start_time&end_time=$end_time&pagesize=" . urlencode($pagesize);
        $pages_show = $pager->multipage($url, $total, $page, $pagesize, $start, $para = '');
        $request->setAttribute("list", $res1);
        $request->setAttribute("status", $status);
        $request->setAttribute("start_time", $start_time);
        $request->setAttribute("end_time", $end_time);
        $request->setAttribute("username", $username);
        $request->setAttribute("pages_show", $pages_show);
        return View::INPUT;
    }


    public function execute()
    {
    }

    public function getRequestMethods()
    {
        return Request::POST;
    }
}
