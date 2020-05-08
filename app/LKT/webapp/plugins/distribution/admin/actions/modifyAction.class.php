<?php

/**
 * [Laike System] Copyright (c) 2020 laiketui.com
 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.
 */


class modifyAction extends PluginAction
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

    }

    public function baocun()
    {

    }

    public function getRequestMethods()
    {

        return Request::NONE;
    }
}
