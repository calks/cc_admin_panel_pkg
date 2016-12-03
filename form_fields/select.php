<?php

	class adminPanelPkgSelectFormField extends coreSelectFormField {
		public function render() {
			$this->addClass('form-control');
			return parent::render();
		}
	}