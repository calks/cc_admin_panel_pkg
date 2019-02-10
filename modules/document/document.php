<?php

	
	class adminPanelPkgDocumentModule extends adminPanelPkgBaseModule {
		
		protected $language_id = CURRENT_LANGUAGE;
		
		protected function getObjectName() {
			return 'document';
		}
		
		public function getPageTitle() {
			return 'Страницы';
		}
		
		protected function commonLogic($params) {
			$this->language_id = coreRequestLibrary::get('language_id', CURRENT_LANGUAGE);		
			return parent::commonLogic($params);
		}
		
		
		protected function beforeListLoad(&$load_params) {
			$load_params['language_id'] = $this->language_id;
			return parent::beforeListLoad($load_params);
		}
		
		
		protected function taskList($params) { 
			$smarty = Application::getSmarty();
			$smarty->assign('line_template_path', $this->getTemplatePath('list_line'));
			return parent::taskList();				
		}
		
		protected function getPreservedFields() {
			$fields = array('protected');
			if (isset($this->original_objects[0]) && $this->original_objects[0]->protected) $fields[] = 'url';
			return $fields;
		}
		
		protected function loadObjects() {
			parent::loadObjects();
			foreach ($this->objects as $obj) {
				$obj->language_id = $this->language_id;
			}
		}
		
		
		protected function createEmptyObjects() {
			parent::createEmptyObjects();
			foreach ($this->objects as $obj) {
				$obj->language_id = $this->language_id;
			}
		}
		
		
		protected function afterListLoad($list) {			
			foreach($list as $item) {
				$item->moveup_link = Application::getSeoUrl("/{$this->getName()}?action=moveup&amp;ids[]=$item->id");
				$item->movedown_link = Application::getSeoUrl("/{$this->getName()}?action=movedown&amp;ids[]=$item->id");
				$item->edit_link = Application::getSeoUrl("/{$this->getName()}?action=edit&amp;ids[]=$item->id");
				$item->delete_link = Application::getSeoUrl("/{$this->getName()}?action=delete&amp;ids[]=$item->id");
				$this->afterListLoad($item->children);
			}
		}
				
		protected function taskEdit() {
			$page = Application::getPage();
			$page->addScript($this->getStaticFilePath('/type_switch.js'));
			return parent::taskEdit();
		}
		
		protected function updateObjectFromRequest($object) {
			parent::updateObjectFromRequest($object);
			$link_type = $this->form->getValue('link_type');
			if ($link_type == 'page_itself') {
				$object->open_link = '';
			}
			elseif($link_type == 'alias') {
				$object->url = '';
				$object->meta_title = '';
				$object->content = '';
				$object->meta_desc = '';
				$object->meta_key = '';
			}
		}
		
		
		protected function taskMove($params, $direction) {
			$object = $this->objects[0];
			$table = $object->getTableName();
			$db = Application::getDb();			
			if($direction=='up') {
				$sql = "
					SELECT id, seq FROM $table
					WHERE parent_id=$object->parent_id
					AND seq<$object->seq
					ORDER BY seq DESC
					LIMIT 1
				";
			}
			elseif($direction=='down') {
				$sql = "
					SELECT id, seq FROM $table
					WHERE parent_id=$object->parent_id
					AND seq>$object->seq
					ORDER BY seq ASC
					LIMIT 1
				";				
			}
			else return $this->terminate();
			
			$neighbour = $db->executeSelectObject($sql);
			if ($neighbour) {
				$db->execute("
					UPDATE $table SET seq=$object->seq
					WHERE id=$neighbour->id
				");			
				$db->execute("
					UPDATE $table SET seq=$neighbour->seq
					WHERE id=$object->id
				");
				$this->normalizeSeq($object->parent_id);
			}
			
			
			$message = $this->gettext('Order changed');
			Application::stackMessage($message);
			$redirect_url = "/admin/{$this->getName()}?action=list";
			Redirector::redirect($redirect_url);
						
		}
		
		
		public function taskDeleteVersion($params = array()) {
			$language_id = coreRequestLibrary::get('language_id');
			$languages = coreRealWordEntitiesLibrary::getLanguages(null, 'id', 'native_name');
			
			$redirect_url = "/{$this->getName()}?action=list";
			$url_addition = $this->url_addition;
			if ($this->page > 1) $url_addition .= $url_addition ? "&page=$this->page" : "page=$this->page";
			if ($url_addition) $redirect_url .= '&' . $url_addition;
			$redirect_url = Application::getSeoUrl($redirect_url);
			
			if (!array_key_exists($language_id, $languages)) {
				Application::stackError($this->gettext('Unknown language ID'));
				Redirector::redirect($redirect_url);
			}
			
			$language_name = $languages[$language_id];
			
			foreach($this->objects as $obj) {				
				if ($obj->deleteLanguageVersion($language_id)) {
					Application::stackMessage($this->gettext('&laquo;%s&raquo; language version successfully deleted for &laquo;%s&raquo; document', $language_name, $obj->title));
				}
				else {
					Application::stackError($this->gettext('Failed to delete &laquo;%s&raquo; language version for &laquo;%s&raquo; document', $language_name, $obj->title));
				}
			}
			
			Redirector::redirect($redirect_url);
			
		}
		
		
	}
	
	
	
	
	
	
	
	
	