<?php

	class adminPanelPkgEmailFormField extends coreEmailFormField {
		public function render() {
			$this->addClass('form-control');
			return parent::render();
		}
	}