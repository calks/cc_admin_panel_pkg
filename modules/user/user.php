<?php

	
	class adminPanelPkgUserModule extends adminPanelPkgBaseModule {
				
		public function getPageTitle() {
			return 'Users';
		}		
		
		protected function getObjectName() {
			return 'user';
		}
		
		protected function createForm($object) {
			parent::createForm($object);
			$this->form->addField(coreFormElementsLibrary::get('text', 'new_pass'));			
			$this->form->setValue('roles', array_keys($object->roles));
		}
		
		protected function updateObjectFromRequest($object) {
			parent::updateObjectFromRequest($object);
			$roles = $this->form->getValue('roles');
			$role_options = $object->getRoleSelect();
			$object->roles = array();
			foreach ($roles as $r) {
				$object->roles[$r] = $role_options[$r]; 
			}
			$new_pass = $this->form->getValue('new_pass');
			if ($new_pass) $object->setPassword($new_pass);
		}
		
		
		protected function beforeObjectSave() {
			parent::beforeObjectSave();
			$new_pass = $this->form->getValue('new_pass');
			if ($new_pass) {
				if (!isset($this->objects[0])) return $this->terminate();			
				$object = $this->objects[0];
				$object->pass = md5($new_pass);				
			} 
		}
		
		protected function taskList() {
			$smarty = Application::getSmarty();
			$user_session = Application::getUserSession();
			$smarty->assign('logged_user_id', $user_session->getUserID());
			
			return parent::taskList();
		}
		
		
		protected function beforeListLoad(&$load_params) {
			$filter = Application::getFilter('user');
			
			$filter->set_params($load_params);
			$smarty = Application::getSmarty();
			$smarty->assign('test', 'test');						
			$smarty->assign('filter', $filter);			
		}
		
		
	}