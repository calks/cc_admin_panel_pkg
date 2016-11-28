

	function EntityListField(container_selector, field_type, field_name, entity_name) {
		var me = this;
		me.container = jQuery(container_selector);
		if (me.container.length == 0) {
			throw 'EntityListField: container ' + container_selector + ' not found';
		}
		
		me.item_list = me.container.find('.item-list:first');
		
		me.entity_name = entity_name;
		me.field_type = field_type;
		me.field_name = field_name;
		me.next_index = me.item_list.children().length;
		
		me.container.children('.add-item').on('click', function(event){			
			event.preventDefault();
			me.addItem();
		});
		
		
		me.container.children().on('click', '.btn.remove, .btn.move-lower, .btn.move-upper', function(event){			
			event.preventDefault();
			
			var item = jQuery(this).parents('.item:first');
			var button = jQuery(event.target);
			if (button.is('span')) {
				button = button.parent();				
			}
			
			if (button.is('.remove')) {
				me.removeItem(item);
			}
			else if (button.is('.move-lower')) {
				me.changeItemPosition(item, 1);
			}
			else if (button.is('.move-upper')) {
				me.changeItemPosition(item, -1);
			}
		});

		
		me.updateNumbers();
				
	}
	
	
	EntityListField.prototype = {
			
		animation_speed: 300,
			
		addItem: function() {
			var me = this;
			
			App.blockUI();
			
			App.ajaxRequest(
				current_module_ajax_endpoint,
				'post',
				{
					ajax: 1,
					action: 'get_empty_entity_list_item',
					field_type: me.field_type,
					field_name: me.field_name,
					entity_name: me.entity_name,
					index: me.next_index
				},
				function(response) {
					App.unblockUI();
					
					var item_html = response.item_html || null;
					if (item_html) {
						var new_item = jQuery(item_html);
						new_item.hide().appendTo(me.item_list).slideDown(me.animation_speed, function(){
							me.updateNumbers();
							me.next_index++;
						});
					}					
				}	
			);
		},
		
		
		removeItem: function(item) {
			var me = this;
			item.slideUp(me.animation_speed, function(){
				item.remove();
				me.updateNumbers();
			});
			
		},
		
		
		changeItemPosition: function(item, diff) {
			var me = this;
			
			var sibling = diff>0 ? item.next() : item.prev();			
			if (sibling.length == 0) return;
			
			var item_height = item.outerHeight(true);
			var sibling_height = sibling.outerHeight(true);
			
			item.find('.actions .move-upper, .actions .move-lower').addClass('hidden');
			sibling.find('.actions .move-upper, .actions .move-lower').addClass('hidden');
			
			item.css({
				position: 'relative'				
			}).animate({
				top: diff>0 ? sibling_height : (-1)*sibling_height
			}, me.animation_speed);
			
			
			sibling.css({
				position: 'relative'				
			}).animate({
				top: diff>0 ? (-1)*item_height : item_height
			}, me.animation_speed, function(){
				item.stop().remove().css({top: 0});
				sibling.css({top: 0});
				if (diff > 0) {
					item.insertAfter(sibling);					
				}
				else {
					item.insertBefore(sibling);
				}
				me.updateNumbers();
			});
			
			
		},

	
	
		updateNumbers: function() {
			var me = this;
			
						
			var items_count = me.item_list.children().length;
			var item_number = 1;
			
			me.item_list.children().each(function(){
				var item = jQuery(this);
				
				var is_first = item_number==1;
				var is_last = item_number==items_count;
				item.find('.number span').html(item_number);
				
				item_number++;
				
				
				item.find('.actions .move-upper, .actions .move-lower').addClass('hidden');
				if (!is_first) {
					item.find('.actions .move-upper').removeClass('hidden');
				}
				if (!is_last) {
					item.find('.actions .move-lower').removeClass('hidden');
				}
				
				var seq_field = item.find('[name$="[seq]"]');
				if (seq_field.length != 0) seq_field.val(item_number);
				
			});
			
		}
			
			
	}
	