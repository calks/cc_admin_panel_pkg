<?php

	class adminPanelPkgEntityFormField extends coreBaseFormField {
		
		protected $entity_name;
		protected $row_errors;
		
		public function __construct($field_name) {
			parent::__construct($field_name);
		}
		
		
		public function SetFromPost($POST) {
			$post_data = Request::getFieldValue($this->field_name, $POST);
				
			$value = Application::getEntityInstance($this->entity_name);
			
			if ($post_data) {
				$row = $this->getRow($value);
				$row->loadFromRequest($POST);
				$row->updateObject($value);
			}
				
			$this->SetValue($value);
		}
		
		
		public function getAsHtml() {
			$value = $this->GetValue();
			$row = $this->getRow($value);
						
			$smarty_namespace = $this->getResourceType() . $this->getResourceName();
			$smarty = Application::getSmarty($smarty_namespace);
			
			$smarty->assign('row', $row);
			
			$item_template_path = $this->findEffectiveSubresourcePath('layout', 'item', null, 'tpl');
			return $smarty->fetch($item_template_path);
		}
		
		
		protected function getRow($value) {
			
			if (!$value) {
				$this->SetValue(Application::getEntityInstance($this->entity_name));
				$value = $this->GetValue();
			}				
			
			if (Application::resourceExists('list_row', $value->getResourceName())) {
				$row_name = $value->getResourceName();
			}
			else {
				$row_name = 'base';
			}
				
			$row = Application::getResourceInstance('list_row', $row_name);
			$row->setFieldsCommonName($this->getFieldName());
			$row->initWithEntityFields($value);
				
			return $row;
		}
		
		public function isMalformed() {
			$row = $this->getRow($this->GetValue());
			$row->validate();
			$this->row_errors = $row->getErrors();			
			return !empty($this->row_errors);
		}
		
		
		public function getFormatErrors() {
			
			$out = array();
			foreach ($this->row_errors as $fieldname=>$field_errors) {
				$out[] = implode(', ', $field_errors);
				
			}
			
			return $out;
		}
		
		
	}
		
		
		