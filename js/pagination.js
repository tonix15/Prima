var TABLE = {}
TABLE.paginate = function(table, pageLength){
	//Set up paging information
	var $table = $(table);
	var $rows = $table.find('tbody > tr');
	var numPages = Math.ceil($rows.length / pageLength) - 1;
	var current = 0;
	//Set up navigation controls
	var $nav = $table
		.parents('.table-wrapper')
		.find('.wrapper-paging ul');
	var $back = $nav.find('li:first-child a');
	var $next = $nav.find('li:last-child a');
	$nav.find('a.paging-this b').text('Page ' + (current + 1));
	$nav.find('a.paging-this span').text(' of ' + (numPages + 1));
	$back
		.addClass('paging-disabled')
		.click(function(){ pagination('<'); });
	$next.click(function(){ pagination('>'); });
	//Show initial rows
	$rows
		.hide()
		.slice(0, pageLength)
		.show();
	pagination = function(direction){
		reveal = function(current){
			//Reveal the correct rows
			$back.removeClass('paging-disabled');
			$next.removeClass('paging-disabled');
			
			$rows
				.hide()
				.slice(current * pageLength, current * pageLength + pageLength)
				.show();
			
			$nav.find('a.paging-this b').text('Page ' + (current + 1));
		};
		//Move previous and next
		if(direction == '<'){//previous
			if(current > 1){
				reveal(current -= 1);				
			}
			else if(current == 1){
				reveal(current -= 1);
				$back.addClass('paging-disabled');
			}
		}
		else{//next
			if(current < numPages - 1){
				reveal(current += 1);
			}
			else if(current == numPages - 1){
				reveal(current += 1);
				$next.addClass('paging-disabled');
			}
		}
	}
};