<?php

/**
 * [Laike System] Copyright (c) 2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */

class addGoodsAction extends PluginAction
{


    public function getDefaultView()
    {
        $request = $this->getContext()->getRequest();
        $m = $request->getParameter('m');
        if ($m != '') {
            $this->$m();
            exit;
        }
        return View::INPUT;
    }

    //查询商品
    public function pro_query()
    {

    }

    public function execute()
    {
        return;
    }

    public function baocun()
    {

    }


    public function getRequestMethods()
    {

        return Request::NONE;
    }
}
