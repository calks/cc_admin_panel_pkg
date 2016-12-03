<?php

	class adminPanelPkgParentSelectFormField extends coreParentSelectFormField {
		public function render() {
			$this->addClass('form-control');
			return parent::render();
		}
	}