<?php

	class adminPanelPkgRichEditorFormField extends ck4PkgRichEditorFormField {
		
		public function __construct($name) {
		
			parent::__construct($name);
			$this->allowTags('b', 'i', 'img', 'li', 'div', 'h2', 'h3', 'ul', 'ol');
			$this->enableFeatures('text_align', 'source', 'text_color', 'font_size', 'font_face');
		
		}
	
	}