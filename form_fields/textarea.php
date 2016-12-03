<?php

	class adminPanelPkgTextareaFormField extends coreTextareaFormField {
		public function render() {
			$this->addClass('form-control');
			return parent::render();
		}
	}