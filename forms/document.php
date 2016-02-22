<?php

	class adminPanelPkgDocumentForm extends coreEntityEditForm {
	
		protected function getFieldList($entity) {
			$fields = parent::getFieldList($entity);
			
			$content_fields = array(
				'language_id',
				'title',
				'content',
				'meta_title',                    
				'meta_desc',
				'meta_key'
			);
			
			return array_merge($fields, $content_fields);			
		}
	
	}