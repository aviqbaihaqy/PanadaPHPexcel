$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
$(function() {
    $(window).bind("load resize", function() {
        console.log($(this).width())
        if ($(this).width() < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    });
        
	$(".dataTable").each(function(){
		var datatable = $(this);
		// SEARCH - Add the placeholder for Search and Turn this into in-line formcontrol
		var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
		search_input.attr('placeholder', 'Search')
		search_input.addClass('form-control input-small')
		search_input.css('width', '200px')

		// SEARCH CLEAR - Use an Icon
		var clear_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] a');
		clear_input.html('<i class="icon-remove-circle icon-large"></i>')
		clear_input.css('margin-left', '5px')

		// LENGTH - Inline-Form control
		var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
		length_sel.addClass('form-control input-small')
		length_sel.css('width', '75px')

		// LENGTH - Info adjust location
		var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_info]');
		length_sel.css('margin-top', '18px')
	});
})