<?php

	class adminPanelPkgTextFormField extends coreTextFormField {
		public function render() {
			$this->addClass('form-control');
			return parent::render();
		}
	}