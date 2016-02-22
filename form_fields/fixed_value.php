<?php

	class adminPanelPkgFixedValueFormField extends coreFixedValueFormField {
	
		public function getAsHtml() {
			$this->addClass('form-control-static');
			$attr_string = $this->getAttributesString();			
			return "
				<p $attr_string>$this->value</p>
			";			
		}
		
	}