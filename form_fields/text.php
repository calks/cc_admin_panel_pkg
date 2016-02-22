<?php

	class adminPanelPkgTextFormField extends coreTextFormField {
		public function getAsHtml() {
			$this->addClass('form-control');
			return parent::getAsHtml();
		}
	}