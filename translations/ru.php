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
				'Field "%s" contains malformed value' => 'Поле "%s" содержит значение в неправильном формате',
				'This URL slug is already used for another page' => 'Введенный URL уже используется для другой страницы',
				'Unknown language ID' => 'Передан неизвестный ID языка',
				'&laquo;%s&raquo; language version successfully deleted for &laquo;%s&raquo; document' => 'Перевод на язык &laquo;%s&raquo; для страницы &laquo;%s&raquo; удален',
				'Failed to delete &laquo;%s&raquo; language version for &laquo;%s&raquo; document' => 'Не удалось удалить перевод на язык &laquo;%s&raquo; для страницы &laquo;%s&raquo;',
				'Object saved successfully' => 'Объект сохранен',
				'Order changed' => 'Порядок изменен',
				'Settins saved successfully' => 'Настройки сохранены',
				'Edit' => 'Изменить',
				'Delete' => 'Удалить',
				'Save' => 'Сохранить',
				'Apply' => 'Применить',				
				'Reset' => 'Сбросить',
				'Back' => 'Назад',
				'Actions' => 'Действия',
				'Settings' => 'Настройки',
				'User list' => 'Список пользователей',
				'Page list' => 'Список страниц',
				'Add page' => 'Добавить страницу',
			);
			
			
			$translations = array_merge($parent_translations, $translations);		
			
			return $translations;
			
			
		}
		
		
		
	}