<?php

	class adminPanelPkgFooterBlock extends coreBaseBlock {
	
		public function render() {
			
			$user_session = Application::getUserSession();
			if (!$user_session->userLogged()) return $this->terminate();

			$smarty = Application::getSmarty();
					
			$smarty->assign('user_logged', $user_session->getUserAccount());
			$smarty->assign('logout_link', Application::getSeoUrl('/login/logout'));
			$smarty->assign('block', $this);
			
			$template_path = $this->getTemplatePath();
			return $smarty->fetch($template_path);
			
		}
		
	
	}