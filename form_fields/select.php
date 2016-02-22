<?php

	class adminPanelPkgSelectFormField extends coreSelectFormField {
		public function getAsHtml() {
			$this->addClass('form-control');
			return parent::getAsHtml();
		}
	}