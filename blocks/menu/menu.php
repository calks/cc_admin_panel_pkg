<?php

	class adminPanelPkgMenuBlock extends coreBaseBlock {
		
		public function render() {
			
			$smarty = Application::getSmarty();
			$smarty->assign('menu', $this->getMenu());
			$template_path = $this->getTemplatePath();
			return $smarty->fetch($template_path);
			
		}
		
		
		protected function getMenu() {
			
			$menu = array();
			
			$menu['Users'] = Application::getSeoUrl('/user');
			$menu['Pages'] = Application::getSeoUrl('/document');
			$menu['Settings'] = Application::getSeoUrl('/settings');
			$menu['Log out'] = Application::getSeoUrl('/login/logout');
			
			return $menu;		
		}
		
	}