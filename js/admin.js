(function ($) {
	"use strict";
	$(function () {
		$('#event_details input#event_details_start_date, #event_details input#event_details_end_date').datepicker({
			dateFormat: "dd/mm/yy",
			firstDay: 1,
			minDate: 0
		}) ;
	});
}(jQuery));