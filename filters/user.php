<?php

	class adminPanelPkgUserFilter extends coreBaseFilter {
				
		function add_fields() {
        	$user = Application::getEntityInstance('user');        	
        	        	
        	$this->addField(coreFormElementsLibrary::get('text', 'search_keyword'));
        	$this->setFieldCaption('search_keyword', $this->gettext('Name or Email'));
        	$this->addField(coreFormElementsLibrary::get('checkbox_collection', 'search_role_id')->setOptions($user->getRoleSelect()));
        	$this->setFieldCaption('search_role_id', $this->gettext('Role'));
        }
        
        function set_params(&$params) {
        	parent::set_params($params);
        	
            $db = Application::getDb();

            $user = Application::getEntityInstance('user');
            $table = $user->getTableName();
                        
            $keyword = trim($this->getValue('search_keyword'));
            if($keyword) {
            	$skeyword = addslashes($keyword);
            	$params['where'][] = "(
					$table.first_name LIKE '%$keyword%' OR 
					$table.first_name LIKE '%$keyword%' OR 
					$table.email LIKE '%$keyword%' OR 
					$table.login LIKE '%$keyword%'
            	)";
            }
                        
            $role_id = $this->getValue('search_role_id');
            
            if ($role_id) {
            	
            	foreach ($role_id as &$r) $r = "'" . addslashes($r) . "'";
            	$coupling_table = $user->getRolesCouplingTableName();
            	$role_id = implode(',', $role_id);
            	$params['from'][] = "
            		INNER JOIN $coupling_table ON $coupling_table.user_id=$table.id AND $coupling_table.role_id IN($role_id)
            	";
            	$params['group_by'][] = "$table.id";
            }
        }
		
	}