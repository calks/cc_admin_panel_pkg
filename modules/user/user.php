<?php

	
	class adminPanelPkgUserModule extends adminPanelPkgBaseModule {
				
		public function getPageTitle() {
			return 'Users';
		}		
		
		
		protected function updateObjectFromRequest($object) {
			parent::updateObjectFromRequest($object);
			$new_pass = $this->form->getValue('new_pass');
			if ($new_pass) $object->setPassword($new_pass);
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
			$smarty->assign('filter', $filter);			
		}
		
		
	}