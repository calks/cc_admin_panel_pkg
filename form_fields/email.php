<?php

	class adminPanelPkgEmailFormField extends coreEmailFormField {
		public function getAsHtml() {
			$this->addClass('form-control');
			return parent::getAsHtml();
		}
	}