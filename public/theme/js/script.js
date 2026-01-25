$(document).ready(function(e){
	var host = window.location.origin
	var url = window.location.href
	if(url.substr(-1) == '/' || url.substr(-1) == '#'){
		url = url.substring(0, url.length - 1)
	}
	$(".inkubasi-infographic-item[data-id='1']").each(function(){
		count = $(this).find('.inkubasi-infograpic-sub-item').length
		$(this).find('.inkubasi-infograpic-nav').each(function(index,element){
			topPosition = (50 + 50*Math.sin(-0.5 * Math.PI - 2*(1/count)*index*Math.PI)).toFixed(4) + "%";
			leftPosition = (50 - 50*Math.cos(-0.5 * Math.PI - 2*(1/count)*index*Math.PI)).toFixed(4) + "%";
			$(this).css({left:`calc(${leftPosition} - 25px)`,top:`calc(${topPosition} - 25px)`})
		})
		$(this).find('.inkubasi-infograpic-nav-item').each(function(index,element){
			topPosition = (50 + 25*Math.sin(-0.5 * Math.PI - 2*(1/count)*index*Math.PI)).toFixed(4) + "%";
			leftPosition = (50 - 30*Math.cos(-0.5 * Math.PI - 2*(1/count)*index*Math.PI)).toFixed(4) + "%";
			$(this).css({left:`calc(${leftPosition} - 60px)`,top:`calc(${topPosition} - 100px)`})
		})
	})
})
$(document).on('click','.navbar-collapse-button',function(e){
	e.preventDefault()
	$(this).toggleClass('show')
	$('.navbar-menu.navbar-collapse').toggleClass('show')
})
$(document).on('click','.step-inkubasi .step-inkubasi-nav a',function(e){
	e.preventDefault()
	$(".inkubasi-infograpic-sub-item").removeClass('show')
	id = $(this).data('id')

	$('.step-inkubasi').removeClass('active')
	$(".step-inkubasi[data-step='"+id+"']").addClass('active')

	$(".inkubasi-infographic-item[data-id='"+id+"']").addClass('show')
	$(".inkubasi-infographic-item[data-id='"+id+"']").each(function(){
		count = $(this).find('.inkubasi-infograpic-sub-item').length
		$(this).find('.inkubasi-infograpic-nav').each(function(index,element){
			topPosition = (50 + 50*Math.sin(-0.5 * Math.PI - 2*(1/count)*index*Math.PI)).toFixed(4) + "%";
			leftPosition = (50 - 50*Math.cos(-0.5 * Math.PI - 2*(1/count)*index*Math.PI)).toFixed(4) + "%";
			$(this).css({left:`calc(${leftPosition} - 25px)`,top:`calc(${topPosition} - 25px)`})
		})
		$(this).find('.inkubasi-infograpic-nav-item').each(function(index,element){
			topPosition = (50 + 25*Math.sin(-0.5 * Math.PI - 2*(1/count)*index*Math.PI)).toFixed(4) + "%";
			leftPosition = (50 - 30*Math.cos(-0.5 * Math.PI - 2*(1/count)*index*Math.PI)).toFixed(4) + "%";
			$(this).css({left:`calc(${leftPosition} - 80px)`,top:`calc(${topPosition} - 70px)`})
		})
	})
	$(".inkubasi-infographic-item[data-id='"+id+"']").find('.inkubasi-infograpic-sub-item').addClass('show')
})