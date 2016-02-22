<?php

	class adminPanelPkgMenuBlock extends coreBaseBlock {
		
		public function render() {
						
			$menu = array();
			
			$menu['Users'] = Application::getSeoUrl('/user');
			
			$menu['Pages'] = Application::getSeoUrl('/document');
			
			

			/*$menu['OCS'] = array(
				'Клиенты' => Application::getSeoUrl('/ocs_client'),
				'Рекламные каналы' => Application::getSeoUrl('/ocs_channel'),
				'Заявки' => Application::getSeoUrl('/ocs_order')
			);*/		
			
			
			$menu['Settings'] = Application::getSeoUrl('/settings');
			
			$menu['Log out'] = Application::getSeoUrl('/login/logout');
			
			$smarty = Application::getSmarty();
			$smarty->assign('menu', $menu);
			$template_path = $this->getTemplatePath();
			return $smarty->fetch($template_path);
			
		}
		
	}