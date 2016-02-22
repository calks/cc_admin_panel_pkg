
	{$form->render()}

	{*<form action="{$form_action}" method="POST" enctype="multipart/form-data">
		<nav class="navbar navbar-default object-edit-form">
		
			<h4 class="navbar-text">
	            {if $action == 'add'}
	                Добавление страницы
	            {else}
	                Редактирование страницы
	            {/if}			
			</h4>
	
			<div class="col-sm-4 pull-right">
				<input type="button" class="btn btn-default navbar-btn pull-right back-button" onclick="javascript:window.location.href='{$back_link}'" name="back" value="Вернуться назад">				
				<input type="submit" class="btn btn-primary navbar-btn pull-right save-button" name="save" value="Сохранить">
			</div>		
	    	
	    </nav>
		
		<div class="form-group">
			<label class="control-label">Родитель</label>
			{$form->render('parent_id')} 
		</div>
		<div class="form-group">	
			<label class="control-label">Тип</label>
			{$form->render('link_type')}
		</div>
		<div class="form-group st-form-line type-page_itself">	
			<label class="control-label">URL</label>
			{if $object->protected}
				{$object->url}
			{else}
				{$form->render('url')}
			{/if}
		</div>

		<div class="form-group st-form-line type-alias">	
			<label class="control-label">Ссылка на существующую страницу</label>
			{$form->render('open_link')}
		</div>

		<div class="form-group">
			<label class="control-label">Активна?</label>
			{$form->render('active')}  
		</div>

		<div class="form-group">
			<label class="control-label">Раздел или страница?</label>
			{$form->render('category')}
		</div>
		
		<div class="form-group">
			<label class="control-label">Название в меню</label>
			{$form->render('title')} 
		</div>

		<div class="form-group st-form-line type-page_itself">
			<label class="control-label">Заголовок</label>
			{$form->render('meta_title')}
		</div>
		
		<div class="form-group st-form-line type-page_itself">
			<label class="control-label">Контент</label>
			{$form->render('content')}
		</div>

		<div class="form-group st-form-line">
			<label class="control-label">Отображать в меню</label>
			{$form->render('menu')}
		</div>
		
		<div class="form-group st-form-line type-page_itself">
			<label class="control-label">Открывать в новом окне</label>
			{$form->render('open_new_window')}
		</div>

		<div class="form-group st-form-line type-page_itself">
			<label class="control-label">Meta Description</label>
			{$form->render('meta_desc')}
		</div>
		
		<div class="form-group st-form-line type-page_itself">
			<label class="control-label">Meta Keywords</label>
			{$form->render('meta_key')}
		</div>

				
		<div class="form-group">								
			<input type="submit" class="btn btn-primary navbar-btn save-button" name="save" value="Сохранить">
			<input type="button" class="btn btn-default navbar-btn back-button" onclick="javascript:window.location.href='{$back_link}'" name="back" value="Вернуться назад">
		</div>


		{$form->render('id')}
		{$form->render('seq')}
		{$form->render('language_id')}
		<input type="hidden" name="action" value="{$action}">

	</form>*}
        

        
        
  