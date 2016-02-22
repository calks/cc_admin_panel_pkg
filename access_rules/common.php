<?php

	class adminPanelPkgCommonAccessRule extends coreBaseAccessRule {
	
	
		public function accessAllowed($user, $resource, $action) {
			if(!$user) {
				return $resource->getResourceType() == 'module' && $resource->getName() == 'login';
			}
			else {
				return $user->hasRole('admin');
			}
		}
		
	}