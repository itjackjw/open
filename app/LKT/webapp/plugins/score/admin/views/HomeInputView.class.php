﻿<?php

/**

 * [Laike System] Copyright (c) 2020 laiketui.com

 * Laike is not a free software, it under the license terms, visited http://www.laiketui.com/ for more details.

 */
class HomeInputView extends PluginInputView {
    public function execute() {
        $request = $this->getContext()->getRequest();
		$this->setTemplate('index.tpl');
    }
}
?>
