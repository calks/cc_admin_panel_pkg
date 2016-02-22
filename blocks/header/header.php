<?php

	class adminPanelPkgHeaderBlock extends coreBaseBlock {
		
		public function render() {
			
			$user_session = Application::getUserSession();
			if (!$user_session->userLogged()) return $this->terminate();

			$smarty = Application::getSmarty();
			
			$smarty->assign('top_menu', Application::getBlock('menu'));
					
			$smarty->assign('user_logged', $user_session->getUserAccount());
			$smarty->assign('logout_link', Application::getSeoUrl('/login/logout'));
			$smarty->assign('site_logo', coreResourceLibrary::getStaticPath('/img/site_logo.jpg'));
			
			
			$template_path = $this->getTemplatePath();
			return $smarty->fetch($template_path);
			
		}
		
	}