<?php

	class adminPanelPkgLoginModule extends coreLoginModule {
		
		public function run($params=array()) {
			$smarty = Application::getSmarty();
			$smarty->assign('message_stack', Application::getBlock('message_stack'));
			return parent::run($params);		
		}
		
		
		protected function ifLoggedIn() {
			Redirector::redirect($this->back_url ? $this->back_url : Application::getSeoUrl("/"));
		}
		
		protected function onSuccessLogin() {
			Redirector::redirect($this->back_url ? $this->back_url : Application::getSeoUrl("/"));
		}
		
		
	}