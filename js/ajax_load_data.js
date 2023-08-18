(function($){	
	$.fn.loaddata = function(options) {// Settings
		var settings = $.extend({ 
			loading_gif_url	: "../assets/img/ajax-loader.gif", //url to loading gif
			end_record_text	: 'No more records found!', //no more records to load
			data_url 		: 'fetch_pages.php', //url to PHP page
			start_page 		: 1 //initial page
		}, options);
		
		var el = this;	
		loading  = false; 
		end_record = false;
		console.log(settings);
		contents(el, settings); //initial data load
		
		$(window).scroll(function() { //detact scroll
			if($(window).scrollTop() + $(window).height() >= $(document).height()){ //scrolled to bottom of the page
				contents(el, settings); //load content chunk 
			}
		});		
	}; 
	//Ajax load function
	function contents(el, settings){
		var load_img = $('<img/>').attr('src',settings.loading_gif_url).addClass('loading-image'); //create load image
		var record_end_txt = $('<div/>').text(settings.end_record_text).addClass('end-record-info'); //end record text
		load_img = $('<div class="text-center" />').append(load_img);
		if(loading == false && end_record == false){
			loading = true; //set loading flag on
			el.append(load_img); //append loading image
			$.post( settings.data_url, {'page': settings.start_page}, function(data){ //jQuery Ajax post
				if(data.trim().length == 0){ //no more records
					el.append(record_end_txt); //show end record text
					load_img.remove(); //remove loading img
					end_record = true; //set end record flag on
					return; //exit
				}
				loading = false;  //set loading flag off
				load_img.remove(); //remove loading img 
				el.append(data);  //append content 
				settings.start_page ++; //page increment
			})
		}
	}
})(jQuery);