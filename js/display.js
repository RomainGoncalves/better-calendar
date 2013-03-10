(function ($) {
	"use strict";
	$(function () {


		function highlightDate(date){
			var dates = new Array() ;
			var i = 0;

			//console.log(date) ;

			$('table#better_calendar_events tbody .event_date').each(function(){
				dates[$(this).text()] = $(this).text();
				i++;
			});

			if((date.getMonth() + 1) < 10){
				var search = (date.getDate()) + "/" + "0" + (date.getMonth() + 1) + "/" + date.getFullYear();
			}
			else{
				var search = (date.getDate()) + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
			}

			var Highlight = dates[search];

            if (dates[search]) {

                return [true, "highlight", Highlight];

            }

            else {

                return [true, '', ''];

            }

		}

		$('#better_calendar').datepicker({
			dateFormat: "dd/mm/yy",
			firstDay: 1,
			minDate: 0,
			beforeShowDay: highlightDate,
			onSelect: selectEvent
		}) ;

		function selectEvent(date){

			var selector = "table#better_calendar_events tbody tr." + date.replace(/\//g, '-') ;

			//Hide all rows desc
			$('table#better_calendar_events tbody tr.row_desc').hide() ;
			$('table#better_calendar_events tbody tr.row_desc').removeClass('show') ;


			if($(selector).next().hasClass('show')){
				
				//Hide event description
				$(selector).next().hide('slow').removeClass('show') ;

			}
			else{
			//console.log($(selector).next().css('display'));

				//Hide event description
				$(selector).next().show('slow').addClass('show') ;
			}

		}
	});
}(jQuery));