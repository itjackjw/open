<?php

/**
 * [Laike System] Copyright (c) 2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */


class goodsAction extends PluginAction
{

    public function getDefaultView()
    {
        $name = $request->getParameter("username"); // 商品名称
        $pagesize = $request->getParameter('pagesize');
        $status = $request->getParameter('status') ? $request->getParameter('status') : 2;
        $pagesize = $pagesize ? $pagesize : 10;

        // 每页显示多少条数据
        $page = $request->getParameter('page');
        if ($page) {
            $start = ($page - 1) * $pagesize;
        } else {
            $start = 0;
        }
        $data = array();
        //如果名字不为空
        if ($name != '') {
            $data['name'] = "%$name%";
        }

        $appConfig = $this->getAppInfo();
        $img = $appConfig['imageRootUrl'];

        $res = array();
        $res1 = array();
        $res = DistributionModel::selectProAll($data); //查询所有分销商品

        $total = count($res);
        if ($res) {
            $res1 = array_slice($res, $start, $pagesize); //分页
            foreach ($res1 as $key => $value) {
                $pid = $value->pro_id;
                $data = array();
                $sel = "min(price) as price";
                $data['pid'] = $pid;
                $r_s = Configure::selectall($sel, $data); //查询商品对应的商品规格
                if ($r_s) {
                    $res1[$key]->setCustomerAttr("price",  $r_s[0]->price);
                } else {
                    $res1[$key]->setCustomerAttr("price",  '0');
                }
                $res1[$key] = $res1[$key]->getAllAttrs();
                $res1[$key]->imgurl = $img . $res1[$key]->imgurl;
            }
        }

        $pager = new ShowPager($total, $pagesize, $page);
        $url = "index.php?module=pi&p=distribution&c=goods&name=$name&pagesize=" . urlencode($pagesize);
        $pages_show = $pager->multipage($url, $total, $page, $pagesize, $start, $para = '');
        $request->setAttribute("list", $res1);
        $request->setAttribute("name", $name);
        $request->setAttribute("status", $status);
        $request->setAttribute("pages_show", $pages_show);
        return View::INPUT;
    }


    public function execute()
    {

    }

    public function getRequestMethods()
    {
        return Request::NONE;
    }


}
