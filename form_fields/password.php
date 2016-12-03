<?php

	class adminPanelPkgPasswordFormField extends corePasswordFormField {
		public function render() {
			$this->addClass('form-control');
			return parent::render();
		}		
	}