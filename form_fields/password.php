<?php

	class adminPanelPkgPasswordFormField extends corePasswordFormField {
		public function getAsHtml() {
			$this->addClass('form-control');
			return parent::getAsHtml();
		}		
	}