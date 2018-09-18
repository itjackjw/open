﻿<?php

/**

 * [Laike System] Copyright (c) 2018 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
class IndexInputView extends SmartyView {
    public function execute() {
		$request = $this->getContext()->getRequest();
		$this->setAttribute("name",$request->getAttribute("name"));
		$this->setAttribute("list",$request->getAttribute("list"));
		$this->setAttribute("software",$request->getAttribute("software"));
		$this->setTemplate("index.tpl");
    }
}
?>
