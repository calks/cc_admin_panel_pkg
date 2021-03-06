<?php

	class adminPanelPkgSettingsModule extends adminPanelPkgBaseModule {
	
		
		protected function taskList($params=array()) { 
			
			$tree = coreSettingsLibrary::getUpToDateTree();
					
			if (coreRequestLibrary::isPostMethod()) {
				coreSettingsLibrary::updateTreeFromPost();
				$errors = coreSettingsLibrary::getErrors();
				if (!$errors) {
					if (!coreSettingsLibrary::saveTree()) {
						Application::stackError($this->gettext('Failed to save settings'));
					}
					else {
						Application::stackMessage($this->gettext('Settins saved successfully'));
						Redirector::redirect(Application::getSeoUrl('/' . $this->getName()));
					}
				}
				else {
					foreach ($errors as $e) {
						Application::stackError($e);
					}
				}
			}
			
			$smarty = Application::getSmarty();
			
			$smarty->assign('tree', $tree);
			$smarty->assign('group_names', coreSettingsLibrary::getGroupNames());			
						
			return parent::taskDefault($params);
		}
		
		public function getPageTitle() {
			return $this->gettext('Settings');
		}
		
		public function getPageSubtitle() {
			return '';
		}
		
		
		public function getPageActions() {
			return array();
		}
		
		
	}