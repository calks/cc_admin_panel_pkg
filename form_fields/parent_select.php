<?php

	class adminPanelPkgParentSelectFormField extends coreParentSelectFormField {
		public function getAsHtml() {
			$this->addClass('form-control');
			return parent::getAsHtml();
		}
	}