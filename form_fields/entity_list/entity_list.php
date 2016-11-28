<?php

	class adminPanelPkgEntityListFormField extends coreBaseFormField {
		
		protected $entity_name;
		protected $row_errors;
		
		public function __construct($field_name) {
			parent::__construct($field_name);
			$this->SetValue(array());
			$js_path = $this->findEffectiveSubresourcePath('static', 'entity_list', null, 'js');
			$page = Application::getPage();
			$page->addScript($js_path);
		}
		
		
		public function SetFromPost($POST) {
			$post_data = Request::getFieldValue($this->field_name, $POST);
			
			$value = array();
			
			if ($post_data) {
				foreach ($post_data as $index => $item_data) {					
					$item = Application::getEntityInstance($this->entity_name);
					$row = $this->getRow($item, $index);
					$row->loadFromRequest($POST);
					$row->updateObject($item);
					$value[$index] = $item;
				}
			}
			
			$this->SetValue($value);			
		}
		
		
		public function getAsHtml() {			
			$value = $this->GetValue();
			if (!$value) {
				$value = array(
					Application::getEntityInstance($this->entity_name)
				);
			}
			
			$items = array();
			
			$smarty_namespace = $this->getResourceType() . $this->getResourceName();
			$smarty = Application::getSmarty($smarty_namespace);
			
			foreach ($value as $index=>$entity) {
				$items[] = $this->getItemHtml($entity, $index); 
			}

			$smarty->assign('items', $items);
			$smarty->assign('field', $this);
			$list_template_path = $this->findEffectiveSubresourcePath('layout', 'list', null, 'tpl');
			
			$field_id = 'entity_list_' . md5(uniqid());
			$smarty->assign('field_id', $field_id);			
			$smarty->assign('field_type', $this->getResourceName());
			$smarty->assign('field_name', $this->getFieldName());
			$smarty->assign('entity_name', $this->getEntityName());
			
			return $smarty->fetch($list_template_path);
		}
		
		
		protected function getRow($entity, $index) {
			if (Application::resourceExists('list_row', $entity->getResourceName())) {
				$row_name = $entity->getResourceName();			
			}
			else {
				$row_name = 'base';
			}
			
			$row = Application::getResourceInstance('list_row', $row_name);
			$row->setFieldsCommonName($this->getFieldName() . "[$index]");
			$row->initWithEntityFields($entity);

			return $row;
		}
		
		
		public function getItemHtml($entity, $index) {
			$smarty_namespace = $this->getResourceType() . $this->getResourceName();
			$smarty = Application::getSmarty($smarty_namespace);
			
			$row = $this->getRow($entity, $index);
			$smarty->assign('row', $row);
			$smarty->assign('seq_field_available', $row->hasField('seq'));
			$smarty->assign('row_number', $index+1);
			
			$item_template_path = $this->findEffectiveSubresourcePath('layout', 'item', null, 'tpl');
			return $smarty->fetch($item_template_path);
		}
		
		
		public function isMalformed() {
			$out = false;
			foreach ($this->value as $index=>$entity) {
				$row = $this->getRow($entity, $index);
				$row->validate();
				$row_errors = $row->getErrors();
				$this->row_errors[$index] = $row_errors;
				$out |= !empty($row_errors);
			}
			
			return $out;						
		}
		
		public function getFormatErrors() {
			$out = array();
			$item_number = 1;
			foreach ($this->row_errors as $index=>$errors) {
				if ($errors) {
					$errors_plain = array();
					foreach ($errors as $field_name=>$field_errors) {
						$errors_plain = array_merge($errors_plain, $field_errors);
					}
					
					$errors_str = implode(', ', $errors_plain);
					$out[] = "($item_number) $errors_str";
					$item_number++;
				}				
			} 
			
			return $out;
		}
		
	
	}
	
	
	
	