//$(document).ready(function(){}); shorthand
$(function(){
	//hide all tab contents first
	$('.tab-contents').hide();
	//set first tab content div by default to be visible
	$('.tab-contents:first').show();
	
	$('.tab-container ul li a').click(function(){
		var currentTab = $(this).attr('href');
		$('.tab-container ul li a').removeClass('current-tab');
		$(this).addClass('current-tab');
		$('.tab-contents').hide();
		$(currentTab).show();
	});
});