<?php

/**

 * [Laike System] Copyright (c) 2020 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */


class viewAction extends PluginAction {


	public function getDefaultView() {

        return View :: INPUT;

	}



	public function execute(){


		return;

	}



	public function getRequestMethods(){

		return Request :: POST;

	}


}

?>