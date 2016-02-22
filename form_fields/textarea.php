<?php

	class adminPanelPkgTextareaFormField extends coreTextareaFormField {
		public function getAsHtml() {
			$this->addClass('form-control');
			return parent::getAsHtml();
		}
	}