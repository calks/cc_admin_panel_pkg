
	App = {
		access_token: null,		
		animation_speed: 100,
		init_complete: null,
		page_container: null,
		messages_container: null,
		templates: {},
		modules: {},
		
		sessionStorageAvailable: function() {
			try {
			    return 'sessionStorage' in window && window['sessionStorage'] !== null;
			} catch (e) {
			    return false;
			}
		},
		
				
		init: function(){
			var me = this;
			jQuery('.message-stack .close').on('click', function(){
				var alert = jQuery(this).parents('.alert');
				alert.animate({opacity: 0}, me.animation_speed, function(){
					alert.slideUp(me.animation_speed, function(){
						alert.remove();
					});
				});			

			});			
			
		},
		
		initComplete: function() {
			var me = this;
			if(me.init_complete === null) {
				if (me.sessionStorageAvailable()){
					me.setAccessToken(sessionStorage.getItem('access_token'));
				}
				me.page_container = jQuery('#page-content');
				me.init_complete = true;
			}
			
			return me.init_complete;
		},
		
		
		setAccessToken: function(new_token) {
			var me = this;
			me.access_token = new_token;
			if (me.sessionStorageAvailable()){
				sessionStorage.setItem('access_token', new_token);
			}
			
		},
		
		
		ajaxRequest: function(ajax_url, request_type, params, success_callback, error_callback, async) {
			var me = this;
			jQuery.ajax({
				url: ajax_url,
				type: request_type,
				dataType: 'json',
				data: params,
				beforeSend: function (request) {
	                if (me.access_token) {
	                	request.setRequestHeader("Access-Token", me.access_token);
	                }
	            },
				success: function(response) {					
					var status = typeof(response.status)!='undefined' ? response.status : 'error';
					
					var messages = typeof(response.messages)!='undefined' ? response.messages : {};
					
					jQuery.each(messages, function(message_idx, message){						
						me.displayMessage(message.type, message.message);						
					});
					
					
					if (status=='success' || status=='ok') {
						if (typeof(success_callback)=='function') {							 
							success_callback(response);							
						}
					} else {
						if (typeof(error_callback)=='function') {
							error_callback(response);
						}
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					me.unblockUI();
					me.displayMessage('error', 'AJAX error.<br> ' + errorThrown);
				}
			});
			
			
		},
		
		showPage: function(page_url) {
			var me = this;
			
			if(!me.initComplete()) return;
						
			me.blockUI();
			
			function ajax_callback(response) {
								
				var redirect = typeof(response.redirect) != 'undefined' ? response.redirect : null;
				
				if (redirect) {
					return me.showPage(redirect);						
				}
				
				var page_module_name = typeof(response.page_module_name) != 'undefined' ? response.page_module_name : null;
				var page_module_src = typeof(response.page_module_src) != 'undefined' ? response.page_module_src : null;
				
				if (page_module_name) {
					me.runPageModule(page_module_name, page_module_src);						
				}
			}
			
			me.ajaxRequest(page_url, 'post', {ajax: 1}, ajax_callback, ajax_callback);
			
		},
		
		
		setPageContent: function(content, binding_logic) {
			var me = this;
			me.page_container.html(content);
			if (typeof(binding_logic) == 'function') {
				binding_logic(me.page_container);				
			}
		},
		
		
		queryApi: function(api_method, request_type, params, success_callback, error_callback) {
			var me = this;
			var api_url = data_api_endpoint + '/' + api_method;
			
			me.ajaxRequest(api_url, request_type, params, function(response){
				var api_data = typeof(response.data) != 'undefined' ? response.data : null;
				success_callback(api_data);				
			}, function(response) {
				if (typeof(error_callback) != 'function') {
					error_callback = function(response) {
						me.unblockUI();
						var errors = typeof(response.errors)!='undefined' ? response.errors : [];
						if (errors.length==0) errors = ['Unknown API error'];
						jQuery.each(errors, function(idx, error_msg){
							if (error_msg == 'You are not logged in') me.showPage('/login')
							me.displayMessage('error', error_msg);
						});												
					}				
				}
				
				error_callback(response);
								
			});			
		},
		
		
		runPageModule: function(page_module_name, page_module_src) {			
			var me = this;			
			if (typeof(me.modules[page_module_name]) == 'object') {
				try {					
					me.modules[page_module_name].run(me.page_container);
				}
				catch (e) {
					me.displayMessage('error', 'Module run failed' + (e.message ? ': ' + e.message : ''));
				}
				
			}
			else {
				jQuery.ajax({
					global: false,
					url: page_module_src,
					dataType: 'script',				
					success: function(){
						me.modules[page_module_name] = new window[page_module_name];						
						me.runPageModule(page_module_name, page_module_src);
					},
					error: function(jqXHR, textStatus, errorThrown) {						
						me.displayMessage('error', 'Failed to load page module.<br> ' + errorThrown);						
					}
				});			
			}
			
		},
		
		
		renderHtml: function(module_name, template_name, template_vars, callback) {
			var me = this;
			me.loadTemplate(module_name, template_name, function(template){
				try {
					var html = template(template_vars);
					callback(html);
				}
				catch (e) {					
					me.displayMessage('error', 'Template rendering failed' + (e.message ? ': ' + e.message : ''));
				}
			});
		},		
		
		
		getUserInfo: function(module_name, onload_callback) {
			var me = this;
			me.ajaxRequest(
				'/' + module_name,
				'post',
				{
					ajax: 1,
					task: 'get_user_info'
				},					
				function(response){
					onload_callback(typeof(response.user_info) != 'undefined' ? response.user_info : null);
				},
				function() {
					onload_callback(null);						
				}
			);
			
		},
		
		
		logout: function() {
			var me = this;
			me.setAccessToken(null);			
			me.showPage('/login');			
		},
		
		
		loadTemplate: function(module_name, template_name, onload_callback) {
			var me = this;
			
			if(typeof(me.templates[module_name]) == 'undefined') me.templates[module_name] = {};
			if(typeof(me.templates[module_name][template_name]) == 'undefined') {
				
				var ajax_url = '/' + module_name + '/get_template' 
				me.ajaxRequest(
					'/' + module_name,
					'post',
					{
						ajax: 1,
						task: 'get_template',
						template_name: template_name
					},
					
					function(response){						
						try {
							var template_src = typeof(response.template) != 'undefined' ? response.template : '';
							me.templates[module_name][template_name] = Handlebars.compile(template_src);
							onload_callback(me.templates[module_name][template_name]);
						}
						catch (e) {							
							me.displayMessage('error', 'Template compilation failed' + (e.message ? ': ' + e.message : ''));
						}
					},
					function() {
						me.displayMessage('error', 'Template load failed');						
					}
				)
				var ajax_url = '/' + module_name + '/get_template';				
			}
			else {
				onload_callback(me.templates[module_name][template_name]);	
			}
			
			
		},
		
			
		blockUI: function() {
			var me = this;
			jQuery('.loader').stop().css({
				zIndex: 998
			}).animate({opacity: 0.8}, me.animation_speed);
		},
		
		
		unblockUI: function() {
			var me = this;
			jQuery('.loader').stop().animate({opacity: 0}, me.animation_speed, function(){
				jQuery('.loader').css({
					zIndex: -1
				});
			});
		},
		
		
		displayMessage: function(message_type, message_text) {
			var me = this;
			if (!me.messages_container) {
				me.messages_container = jQuery('<div />').css({
					position: 'absolute',
					top: 20,
					right: 0,
					zIndex: 999
				}).addClass('container').addClass('col-sm-4').prependTo('body');
			}
			
			
			switch (message_type) {
				case 'success':
					var alert_class = 'alert-success';
					break;
				case 'warning':
					var alert_class = 'alert-warning';
					break;
				case 'error':
					var alert_class = 'alert-danger';
					break;
				default:
					var alert_class = 'alert-info';
			}

			var alert = jQuery('<div />').addClass('alert').addClass(alert_class).html(message_text).css({
				zIndex: 999				
			});
			var close_btn = jQuery('<button />').addClass('close').attr({
				type: 'button'
			}).html('<span aria-hidden="true">&times;</span>').prependTo(alert);
			alert.appendTo(me.messages_container);
			
			function closeAlert() {
				alert.animate({opacity: 0}, me.animation_speed, function(){
					alert.slideUp(me.animation_speed, function(){
						alert.remove();
					});
				});			
			}
			
			close_btn.click(function(e){
				closeAlert();	
				e.preventDefault();
			});
			
			setTimeout(function(){
				closeAlert();				
			}, 3000);
			
		},
		
		
		getFormData: function(form) {
			var data = {};
			
			function setValueByIndex(variable, indexes, value) {
				var index = indexes.shift();
				if (indexes.length == 0) {
					if (index=='') {
						if (typeof(variable[index]) == 'undefined') variable[index] = [];
						variable[index].push(value);
					}
					else {
						variable[index] = value;	
					}
				}
				else {
					if (typeof(variable[index]) == 'undefined') variable[index] = index=='' ? [] : {};
					if (index=='') index = variable[index].length;
					setValueByIndex(variable[index], indexes, value);
				}
			} 
			
			jQuery(form).find('input, textarea, select').each(function(){
				if (jQuery(this).is('[type=button]')) return;
				if (jQuery(this).is('[type=checkbox]') && !jQuery(this).is(':checked')) return;
				if (jQuery(this).is('[type=radio]') && !jQuery(this).is(':checked')) return;
				
				if (jQuery(this).is('textarea')) {
					if (typeof(CKEDITOR) != 'undefined') {
						var textarea_id = jQuery(this).attr('id');
						if (typeof(textarea_id) != 'undefined') {
							textarea_id = textarea_id.toString();
							if (textarea_id && typeof(CKEDITOR.instances[textarea_id])!='undefined') {
								jQuery(this).val(CKEDITOR.instances[textarea_id].getData());
							}
						}
					}
					
				};
							
				var name = jQuery(this).attr('name');
				if (typeof(name) == 'undefined') return;
				var value = jQuery(this).is('.default.labeled_with_title') ? '' : jQuery(this).val();
				
				if (name.indexOf('[') != -1) {				
					var matches = name.match(/([a-z0-9_]+)(\[[a-z0-9_\[\]]*\])/);
					var fieldname = matches[1];
					var indexes_raw = matches[2];
					var imatches = indexes_raw.match(/\[[a-z0-9_]*\]/g);
					var indexes = [];
					jQuery.each(imatches, function(k, v){
						indexes.push(v.substr(1,v.length-2));
					});
					indexes.unshift(fieldname);
					setValueByIndex(data, indexes, value);
				}
				else {
					data[name] = value;	
				}
			});
			
			return data;
		}

			
	}
	
	
	
	
	