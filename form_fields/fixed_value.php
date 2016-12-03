<?php

	class adminPanelPkgFixedValueFormField extends coreFixedValueFormField {
	
		public function render() {
			$this->addClass('form-control-static');
			$attr_string = $this->getAttributesString();			
			return "
				<p $attr_string>$this->value</p>
			";			
		}
		
	}