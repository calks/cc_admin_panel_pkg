<?php

	class adminPanelPkgRuTranslation extends langPkgRuTranslation {
	
		public function getTranslations() {
			
			
			$parent_translations = parent::getTranslations();
			
			$translations = array(
				'You should login as admin' => 'Вы должны войти под учетной записью администратора',
				'You logged as %s' => 'Вы вошли как %s',
				'Logout' => 'Выйти',
				'Management panel' => 'Панель управления сайтом',
				'%s %d %s out of %d' => '%s %d %s из %d',
				'object' => array(
					'объект', 
					'объекта', 
					'объектов'
				),
				'Showing' => array(
					'Показан', 
					'Показано', 
					'Показано'
				),
				'Required field "%s" is empty' => 'Не заполнено обязательное поле "%s"',
				'Field "%s" contains malformed value' => 'Поле "%s" содержит значение в неправильном формате'
				
			);
			
			
			$translations = array_merge($parent_translations, $translations);		
			
			return $translations;
			
			
		}
		
		
		
	}