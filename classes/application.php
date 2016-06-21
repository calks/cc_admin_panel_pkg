<?php

	class adminPanelPkgApplicationClass extends coreHttpApplicationClass {
		
		
		protected function route() {
			
			Application::loadLibrary('olmi/request');
			Application::loadLibrary('core/router');
								
			$url = ltrim($_SERVER['REQUEST_URI'], '/');

			$user_logged = $this->getUserSession()->getUserAccount();			
			
			Router::setDefaultModuleName($user_logged ? 'user' : 'login');
			
			Router::route($url);			
					
			$this->module_name = Router::getModuleName();
			$this->module_params = Router::getModuleParams();
			
		}
		
		protected function displayPage($page, $content) {
			$html_head = $page->getHtmlHead();
			$smarty = Application::getSmarty();
			
			$smarty->assign('html_head', $html_head);
			$smarty->assign('header', Application::getBlock('header'));
			$smarty->assign('footer', Application::getBlock('footer'));
			$smarty->assign('content', $content);
		
			$template_path = coreResourceLibrary::getTemplatePath('index');
			
			$smarty->display($template_path);
			
		}
		
		protected function preparePage($page) {
			
			$page->setTitle(Application::gettext('Management panel'));
			$page->addMeta(array(
				'name' => 'viewport',
				'content' => 'width=device-width, initial-scale=1'
			));
			$page->addMeta(array(
				'charset' => 'utf-8'
			));
			$page->addStylesheet(coreResourceLibrary::getStaticPath('/bootstrap/css/bootstrap.min.css'));
			$page->addStylesheet(coreResourceLibrary::getStaticPath('/bootstrap/css/bootstrap-theme.min.css'));
			$page->addStylesheet(coreResourceLibrary::getStaticPath('jquery-ui/jquery-ui-bootstrap.css'));
			
			
			$page->addStylesheet(coreResourceLibrary::getStaticPath('/css/admin.css'));
			$page->addScript(coreResourceLibrary::getStaticPath('/js/jquery-1.11.3.min.js'));
			$page->addScript(coreResourceLibrary::getStaticPath('/jquery-ui/jquery-ui.min.js'));
			$page->addScript(coreResourceLibrary::getStaticPath('/bootstrap/js/bootstrap.min.js'));
			
			$page->addScript(coreResourceLibrary::getStaticPath('/js/application.js'));
			$page->addLiteral('
				<script type="text/javascript">
					jQuery(document).ready(function(){
						App.init();
					});
				</script>
			
			');
			
		
		}
		
		public function getFrontApplicationName() {
			return 'front';
		}
		
		
		public function getResourceRouting() {
			
						
			$resource_routing = array();

		    $resource_routing['seo_rule'] = array(	 
				'applications/admin',
				APP_RESOURCE_CONTAINER_PACKAGES, 
				APP_RESOURCE_CONTAINER_CORE
		    );
		    
		    
		    $resource_routing['form_field'] =
		    $resource_routing['entity'] = array(	 
				'applications/admin',
		    	'applications/front',
				APP_RESOURCE_CONTAINER_PACKAGES, 
				APP_RESOURCE_CONTAINER_CORE
		    );

		    
		    $resource_routing['default'] = array(	 
				'applications/admin',		    	
				APP_RESOURCE_CONTAINER_PACKAGES, 
				APP_RESOURCE_CONTAINER_CORE
		    );
		    
			
			/*array_unshift($resource_routing['default'], 'applications/admin');
			array_unshift($resource_routing['default'], 'applications/cli');*/    
			
			return $resource_routing;
		
		}
		
		
	
	
	}