<?php

	class adminPanelPkgDocumentForm extends coreEntityEditForm {
	
		protected function getFieldList($entity) {
			$fields = parent::getFieldList($entity);
			
			$content_fields = array(
				'title',
				'content',
				'meta_title',                    
				'meta_desc',
				'meta_key',
				'language_id'
			);
			
			return array_merge($fields, $content_fields);
		}
		
		
		public function initWithEntityFields($entity) {
			parent::initWithEntityFields($entity);
		
			$link_type_options = array(
				'page_itself' => $entity->gettext('page itself'),
				'alias' => $entity->gettext('page alias')
			);
			
			$type_field = coreFormElementsLibrary::get('radio', 'link_type');
			$type_field->setOptions($link_type_options);
			$this->addField($type_field);
			$type_field->setValue($entity->open_link ? 'alias' : 'page_itself');
		
			
			$this->setFieldCaption('link_type', $entity->gettext('Link type'));
			
			$this->setFieldsOrder(array(
				'parent_id',
				'category',
				'title',
				'is_active',
				'open_new_window',
				'menu',
				'link_type',
				'url',
				'open_link'
			));		
		}
		
		protected function getEntityName() {
			return 'document';
		}

		
		public function validate() {
			$link_type = $this->getValue('link_type');
			
			if ($link_type == 'page_itself') {
				$this->makeFieldOptional('open_link');
				$this->makeFieldReqiured('url');
				
				$url = $this->getValue('url');				
				if ($url) {
					$document = Application::getEntityInstance($this->getEntityName());
					$existing = $document->loadToUrl($url);
					if ($existing && $existing->id != $this->getValue('id')) {
						$this->setFieldError('url', $this->gettext('This URL slug is already used for another page'));
					}
				}
			}
			elseif($link_type == 'alias') {
				$this->makeFieldOptional('url');
				$this->makeFieldReqiured('open_link');
			}
			
			parent::validate();
			
		
		}
		
	
	}