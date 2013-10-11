$(function(){
	
	$('form').each(function(){
		
		var form = $(this);
                
		form.find('[data-prototype]').each(function(){
			
			var collection = $(this);
                        
                        collection.on('post_add_field', function(e, field){
                            if(allowDelete){
                                var btnRemove = $('<a>', {'href': '#'}).addClass('btn btn-default').text('Remove');
				btnRemove.click(function(e){
					e.preventDefault();
					field.remove();
				});
                                field.prepend(btnRemove);                                                            
                            }
                        });
                        
                        var allowAdd = collection.data('allow-add');
                        var allowDelete = collection.data('allow-delete');
			
			var label = collection.prev('label').text();
			var fieldCounter = collection.find('> *').length;
			
			var btnAdd = $('<a>', {'href': '#'}).addClass('btn btn-default').text('Add');
			btnAdd.click(function(e){
				e.preventDefault();
				addField();
			});
			
			function createField(){
				var html = collection.data('prototype').replace(/__name__/g, fieldCounter);
				fieldCounter++;
				return $(html);
			}
			
			function addField(){
                                var field = createField();
				collection.append( field );
                                collection.trigger('post_add_field', [field]);
			}
                        
                        // Trigger "post_add_field" event for existing fields
                        collection.find('> *').each(function(){
                            if(!allowAdd) return;
                            var field = $(this);
                            collection.trigger('post_add_field', [field]);
                        });
                        
                        collection.prepend(btnAdd);
			
		});
		
	});
	
});