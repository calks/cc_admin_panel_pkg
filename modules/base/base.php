<?php


	class adminPanelPkgBaseModule extends coreBaseModule {
		
		protected $action;
		protected $ids;
		protected $objects;
		protected $original_objects;
		protected $errors;
		protected $form;
		protected $url_addition='';
		protected $page;
		protected $pagenav;
		protected $total_objects;
		
		protected $object_count_str = 'Список объектов пуст';
		protected $links = array();
		
		public function run($params=array()) {			
						
			$this->commonLogic($params);
			
			$terminated = $this->action == 'error';
			if (!$terminated) {
			
				$this->action = coreRequestLibrary::get('action', 'list');
				$this->ids = coreRequestLibrary::get('ids');
				$this->errors = array();
				$this->page = (int)coreRequestLibrary::get('page');
	
							
				if (!$this->ids) $this->ids = array();
				if ($id = (int)coreRequestLibrary::get('id')) $this->ids[] = $id;
				
				foreach ($this->ids as &$id) $id = (int)$id;
				
				$method_name = 'task' . ucfirst(coreNameUtilsLibrary::underscoredToCamel($this->action));
				if (!method_exists($this, $method_name)) return $this->terminate();			
				
				$this->loadObjects();
				
				call_user_func(array($this, $method_name), $params);
				
				foreach ($this->errors as $e) {
					Application::stackError($e);
				}
			}
			
			$smarty = Application::getSmarty();

			$smarty->assign('action', $this->action);
			$smarty->assign('app_img_dir', Application::getSiteUrl()."/applications/".Application::getApplicationName() . '/static/img');
			$smarty->assign('message_stack', Application::getBlock('message_stack'));
			$smarty->assign('module', $this);
						
			$template_path = $this->getTemplatePath($this->action);			
			return $smarty->fetch($template_path);
		}
		
		
		protected function loadObjects() {
			if ($this->ids) {				
				$ids = implode(',', $this->ids);
				
				$obj = Application::getEntityInstance($this->getObjectName());
				$obj_table = $obj->getTableName();				
				$load_params['where'][] = "$obj_table.id IN($ids)";
				$this->beforeListLoad($load_params);				
				$this->objects = $obj->load_list($load_params);

				$this->addLinks($this->objects);
				$this->afterListLoad($this->objects);
				
				$this->original_objects = array();
				foreach ($this->objects as $k=>$o) {					
					$this->original_objects[$k] = clone $o;	
				}
			}
			else {
				$this->objects = array();
				$this->original_objects = array();
			}
		}
		
		
		protected function getObjectName() {
			return $this->getResourceName();
		}
		
		protected function getObjectsPerPageCount() {
			return 20;
		}		
		
		protected function addLinks(&$list) {
			if(!$list) return;
			$url_addition = $this->url_addition;
			if ($this->page > 1) $url_addition .= $url_addition ? "&page=$this->page" : "page=$this->page"; 
			$url_addition = $url_addition ? '&amp;' . str_replace('&', '&amp;', $url_addition) : '';
			 
			foreach ($list as $item) {
				$edit_link = "/{$this->getName()}?action=edit&ids[]=$item->id" . $url_addition;
				$delete_link = "/{$this->getName()}?action=delete&ids[]=$item->id" . $url_addition;
				$moveup_link = "/{$this->getName()}?action=moveup&ids[]=$item->id" . $url_addition;
				$movedown_link = "/{$this->getName()}?action=movedown&ids[]=$item->id" . $url_addition;
				
				$item->edit_link = Application::getSeoUrl($edit_link);
				$item->delete_link = Application::getSeoUrl($delete_link);
				$item->moveup_link = Application::getSeoUrl($moveup_link);
				$item->movedown_link = Application::getSeoUrl($movedown_link);
				
				if (isset($item->children)) {
					$this->addLinks($item->children);
				}
				
			}
			
		}
		
		protected function taskList() {
			
			$load_params['mode'] = 'admin';			
			$this->beforeListLoad($load_params);
			
			$obj = Application::getEntityInstance($this->getObjectName());
						
			if ($this->page<1) $this->page = 1;
			$this->total_objects = $obj->count_list($load_params);
			
			$limit = $this->getObjectsPerPageCount();
			if ($limit) {
				$offset = $limit * ($this->page-1);
				
				if ($offset > $this->total_objects) {
					$this->page = ceil($this->total_objects/$limit);
					$offset = $limit * ($this->page-1);
				}
							
				$load_params['limit'] = $limit;
				$load_params['offset'] = $offset;
			}
			
			$list = $obj->load_list($load_params);
			
			
			$this->afterListLoad($list);
			
			$this->addLinks($list);
						
			$smarty = Application::getSmarty();
			
			$this->pagenav = null;
			
			if ($limit && $this->total_objects>$limit) {				
				$list_link = "/{$this->getName()}?action=list&page=%page%";
				if ($this->url_addition) $list_link .= '&amp;' . str_replace('&', '&amp;', $this->url_addition);
				$this->url_addition .= $this->url_addition ? "&page=$this->page" : "page=$this->page";				
				
				$this->pagenav = Application::getBlock('pagenav');
				
				$this->pagenav->setPageLinkTemplate($list_link);				
				$this->pagenav->setItemsTotal($this->total_objects);
				$this->pagenav->setItemsPerPage($limit);
				$this->pagenav->setCurrentPage($this->page);
				
			}
			
			$add_link = "/{$this->getName()}?action=add";
			if ($this->url_addition) $add_link .= '&amp;' . str_replace('&', '&amp;', $this->url_addition);
			
			
			$add_link = Application::getSeoUrl($add_link);
			
			$smarty->assign('add_link', $add_link);
			$this->links['add'] = $add_link;
			
			
			$group_delete_link = "/{$this->getName()}?action=delete&%ids%";
			if ($this->url_addition) $group_delete_link .= '&amp;' . str_replace('&', '&amp;', $this->url_addition);
			$smarty->assign('group_delete_link', Application::getSeoUrl($group_delete_link));
			
			$this->links['group_delete'] = Application::getSeoUrl($group_delete_link);			
			
			$smarty->assign('entity', $obj);
			$smarty->assign('objects', $list);
			$smarty->assign('pagenav', $this->pagenav);
			
			$obj_count = count($list);
			$this->object_count_str = $this->getObjectCountString($obj_count, $this->total_objects);
			$smarty->assign('count_str', $this->object_count_str);
			
			
			$smarty->assign('page_actions', $this->getPageActions());
		}
		
		protected function getObjectCountString($shown, $total) {
			$shown_str = Application::ngettext('Showing', 'Showing', $shown);
			$obj_count_noun = Application::ngettext('object', 'objects', $shown);						
			return  Application::gettext("%s %d %s out of %d", $shown_str, $shown, $obj_count_noun, $total);
		}
		
		
		protected function createEmptyObjects() {
			$this->objects = array(
				Application::getEntityInstance($this->getObjectName())
			);
		}
		
		protected function taskAdd() {
			$this->createEmptyObjects();
						
			if (array_key_exists('active', get_object_vars($this->objects[0]))) {			
				$this->objects[0]->active = 1;	
			}
			if (array_key_exists('is_active', get_object_vars($this->objects[0]))) {			
				$this->objects[0]->is_active = 1;	
			}
			
			$smarty = Application::getSmarty();
			$edit_template_path = $this->getTemplatePath('edit');
			$smarty->assign('edit_template_path', coreResourceLibrary::getAbsolutePath($edit_template_path));			
			$result = $this->taskEdit();
			$this->normalizeSeq();
			return $result;
		}
		
		protected function taskEdit() {	
			if (!isset($this->objects[0])) return $this->terminate();			
			$object = $this->objects[0];
			
			$this->createForm($object);
			
			if(coreRequestLibrary::isPostMethod()) {
				
				$this->updateObjectFromRequest($object);
				
				$this->validateObject($object);
				
				if (!$this->errors) {
					$this->beforeObjectSave($object);
					//$this->saveImages();
					if (!$this->errors) {												
						if ($object->hasField('seq')) {							
							if (!$object->seq) $object->seq = $this->getSeq();
						}						
						if ($object->save()) {
							
							$this->afterObjectSave($object);
							//if ($this->action == 'add') $this->renameNewObjectImageDir($object->id);
							
							$apply_pressed = (bool)coreRequestLibrary::get('apply');
							if ($apply_pressed) {
								$redirect_url = "/{$this->getName()}?action=edit&ids[]=$object->id";	
							}
							else {
								$redirect_url = "/{$this->getName()}?action=list";
							}
							
							Application::stackMessage($this->gettext('Object saved successfully'));
														
							
							$url_addition = $this->url_addition;						
							if ($this->page > 1) $url_addition .= $url_addition ? "&page=$this->page" : "page=$this->page";
							if ($url_addition) $redirect_url .= '&' . $url_addition;
							Redirector::redirect(Application::getSeoUrl($redirect_url));
						}
						else {
							$this->errors[] = $this->gettext('Failed to save object');
						}
					}										
				}				
			}
			
			$smarty = Application::getSmarty();
			
			
			$form_action = "/{$this->getName()}";
			if ($this->page > 1) $form_action .= "?page=$this->page";
			
			$form_action = Application::getSeoUrl($form_action);
			$smarty->assign('form_action', $form_action);

			
			$url_addition = $this->url_addition;
			if ($this->page > 1) $url_addition .= $url_addition ? "&page=$this->page" : "page=$this->page"; 
			$url_addition = $url_addition ? '&amp;' . str_replace('&', '&amp;', $url_addition) : '';
			
			
			$back_link = "/{$this->getName()}?action=list" . $url_addition;
			$back_link = Application::getSeoUrl($back_link);			
			$this->form->setBackLink($back_link);
			
			$this->links['back'] = $back_link;

			$smarty->assign('form', $this->form);
			$smarty->assign('object', $object);
		}
		
		protected function getSeq() {
			$object = $this->objects[0];
			$table = $object->getTableName();
			$db = Application::getDb();
			return (int)$db->executeScalar("
				SELECT MAX(seq)+1 FROM $table
			");
		}
		
		protected function createForm($object) {			

			$form_class = coreResourceLibrary::getEffectiveClass('form', $this->getObjectName());
			if (!$form_class) {
				$form_class = coreResourceLibrary::getEffectiveClass('form', 'entity_edit');
				
				//throw new coreException("No form class found for {$this->getObjectName()} entity");
				
			}
			$this->form = new $form_class();			
			$this->form->initWithEntityFields($object);
			//$this->form->setHeading($this->action == 'add' ? 'Добавление заявки' : 'Редактирование заявки');
			
			$action_field = coreFormElementsLibrary::get('hidden', 'action');
			$action_field->setValue($this->action);
			$this->form->addField($action_field);
						
			
			$url_addition = $this->url_addition;
			if ($this->page > 1) $url_addition .= $url_addition ? "&page=$this->page" : "page=$this->page"; 
			$url_addition = $url_addition ? '?' . str_replace('&', '&amp;', $url_addition) : '';
			
			$form_action = "/{$this->getName()}$url_addition";
			$form_action = Application::getSeoUrl($form_action);			
			$this->form->setAction($form_action);
			
			$this->form->setMethod('post');			
			
			return $this->form;
		}
		
		protected function updateObjectFromRequest($object) {
			$this->form->LoadFromRequest($_REQUEST);
			$this->form->updateObject($object);

			if ($this->action=='edit') {
				foreach ($this->getPreservedFields() as $f) {
					$object->$f = $this->original_objects[0]->$f;
				}
			}
		}
		
		protected function validateObject($object) {
			$this->form->validate();
			$form_errors = $this->form->getErrors(); 
			foreach ($form_errors as $field_name=>$field_errors) {
				$this->errors = array_merge($this->errors, $field_errors);	
			}
		}
		
		
		protected function taskDelete() {
			$this->beforeObjectDelete();

			$message = count($this->objects)> 1 ? $this->gettext('Objects deleted') : $this->gettext('Object deleted');
			Application::stackMessage($message);
			
			foreach($this->objects as $obj) {				
				$obj->delete();
			}
			$this->afterObjectDelete();
			$this->normalizeSeq();
			
			$redirect_url = "/{$this->getName()}?action=list";
			$url_addition = $this->url_addition;
			if ($this->page > 1) $url_addition .= $url_addition ? "&page=$this->page" : "page=$this->page";
			if ($url_addition) $redirect_url .= '&' . $url_addition;
			
			Redirector::redirect(Application::getSeoUrl($redirect_url));			
		}		

		
		protected function beforeListLoad(&$load_params) {
			
		}
		
		protected function afterListLoad(&$list) {
			
		}
		
		protected function beforeObjectSave(&$object = null) {
			
		}
		
		protected function afterObjectSave(&$object = null) {
			
		}
				
		protected function beforeObjectDelete() {
			
		}
		
		protected function afterObjectDelete() {
			
		}
		
		protected function taskMovedown($params) {
			return $this->taskMove($params, 'down');
		}
		
		protected function taskMoveup($params) {
			return $this->taskMove($params, 'up');
		}
		
		protected function taskMove($params, $direction) {			
			$object = $this->objects[0];
			$table = $object->getTableName();
			$db = Application::getDb();
			$extra_condition = $this->neighbourExtraCondition();
			if ($extra_condition) $extra_condition = " AND $extra_condition ";
			
			$object_seq = (int)$object->seq;
			
			if($direction=='up') {
				$sql = "
					SELECT id, seq FROM $table					
					WHERE seq<$object_seq
					$extra_condition
					ORDER BY seq DESC
					LIMIT 1
				";
			}
			elseif($direction=='down') {
				$sql = "
					SELECT id, seq FROM $table					
					WHERE seq>$object_seq
					$extra_condition
					ORDER BY seq ASC
					LIMIT 1
				";				
			}
			else return $this->terminate();
			//die($sql);
			$neighbour = $db->executeSelectObject($sql);
			
			if ($neighbour) {
				$db->execute("
					UPDATE $table SET seq=$object_seq
					WHERE id=$neighbour->id
				");			
				$db->execute("
					UPDATE $table SET seq=$neighbour->seq
					WHERE id=$object->id
				");				
			}
			
			$this->normalizeSeq();
			
			$message = $this->gettext('Order changed');// . $direction=='up' ? 'выше' : 'ниже';
			Application::stackMessage($message);
			$redirect_url = "/{$this->getName()}?action=list";
			$url_addition = $this->url_addition;
			if ($this->page > 1) $url_addition .= $url_addition ? "&page=$this->page" : "page=$this->page";
			if ($url_addition) $redirect_url .= '&' . $url_addition;
			 
			Redirector::redirect(Application::getSeoUrl($redirect_url));
		}
		
		protected function neighbourExtraCondition() {
			
		}
		
		protected function normalizeSeq() {
			if (!isset($this->objects[0])) return;
			$object = $this->objects[0];			
			if (!$object->hasField('seq')) return;
			
			$table = $object->getTableName();
			$db = Application::getDb();
			
			$db->execute("SET @num=0");
			$db->execute("
				UPDATE `$table`, (
				  SELECT `$table`.*, @num:=@num+1 AS new_seq FROM `$table`
				  ORDER BY seq
				) AS `{$table}_copy`
				SET `$table`.seq = `{$table}_copy`.new_seq
				WHERE `$table`.id = `{$table}_copy`.id
			");
		}
		
		protected function getPreservedFields() {
			return array();			
		}
		
		
		protected function taskError() {
			
		}
		
		
		public function setUrlAddition($url_addition) {
			$this->url_addition = $url_addition;
		}
		
		public function getPageTitle() {
			return ucfirst(str_replace('_', ' ', $this->getObjectName()));
		}
		
		public function getPageSubtitle() {
			switch ($this->action) {
				case 'list':
					return 'Просмотр списка. ' . $this->object_count_str; 
					break;
				case 'add':
					return 'Добавление нового объекта'; 
					break;
				case 'edit':
					return 'Редактирование объекта'; 
					break;
				default:
					return '';	
			}
		}
		
		public function getPageActions() {
			return $this->links;
		}
		
        protected function terminate() {
        	Application::stackError("Произошла ошибка");
        	$this->action = 'error';        	        	
        	return parent::terminate();
        }
        
        
        protected function taskGetEmptyEntityListItem() {
        	if (!$this->isAjax()) {
        		return $this->terminate();
        	}

        	$entity_name = coreRequestLibrary::get('entity_name');        	
        	if (!Application::entityExists($entity_name)) {
        		Application::stackError($this->gettext('Entity "%s" not found', $entity_name));
        	}
        	else {
        		$entity = Application::getEntityInstance($entity_name);
        		$field_type = coreRequestLibrary::get('field_type');
        		$field_name = coreRequestLibrary::get('field_name');
        		
        		$field = coreFormElementsLibrary::get($field_type, $field_name);
        		$field->setEntityName($entity_name);
        		
        		$index = (int)coreRequestLibrary::get('index');
        		
        		$this->response_data['item_html'] = $field->getItemHtml($entity, $index);  
        	}
        	
        	
        	
        	$this->returnResponse();
        
        }
		
		
		
	}
	
	
	
	
	
	