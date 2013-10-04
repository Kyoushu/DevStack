$(function(){
	
	$('form').each(function(){
		
		var form = $(this);
		
		form.find('[data-prototype]').each(function(){
			
			var collection = $(this);
			
			var label = collection.prev('label').text();
			var fieldCounter = collection.find('> *').length;
			
			var btnAdd = $('<a>', {'href': '#'}).addClass('btn btn-default').text('Add');
			btnAdd.click(function(e){
				e.preventDefault();
				addField();
			});
			
			collection.prepend(btnAdd);
			
			function createField(){
				var html = collection.data('prototype')
						.replace(/__name__/g, fieldCounter);
				fieldCounter++;
				
				var field = $(html)
				
				var btnRemove = $('<a>', {'href': '#'}).addClass('btn btn-default pull-left').text('Remove');
				btnRemove.click(function(e){
					e.preventDefault();
					field.remove();
				});
				
				field.prepend(btnRemove);
				
				return field;
			}
			
			function addField(){
				collection.append( createField() );
			}
			
		});
		
	});
	
});