/**
 * EventON elements
 * @version: 4.8.2
 */
jQuery(document).ready(function($){

const BB = $('body');

// process element interactivity on demand
	$.fn.evo_process_element_interactivity = function(O){
		setup_colorpicker();
		_evo_elm_load_datepickers();

		if( $('body').find('.evoelm_trumbowyg').length > 0 ){
			$('body').find('.evoelm_trumbowyg').each(function(){
				if ( $.isFunction($.fn.trumbowyg) ) {
					$(this).trumbowyg({
						btns: [
							['viewHTML'],
					        ['undo', 'redo'], // Only supported in Blink browsers
					        ['formatting'],
					        ['strong', 'em'],
					        ['link'],
					        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
					        ['unorderedList', 'orderedList'],
					        ['removeformat'],
					        ['fullscreen']
						]
					});
				}
			});	
		}
	}
	// on page load
	$('body').evo_process_element_interactivity();
	// on after elements load
	$('body').on('evo_elm_load_interactivity',function(){
		$(this).evo_process_element_interactivity();
	});

/* interactive wysiwyg 4.6*/
	BB.on('click','.evo_elm_act_on',function(){
		$(this).siblings('.evo_field_container').show();
		$(this).hide();
	});
	BB.on('click','.evo_field_preview',function(){
		$(this).siblings('.evo_field_container').show();
		$(this).hide();
	});

// angle button
	var dragging = false;
	$('body').on('mousedown', '.evo_elm_ang_hold',function(){
		dragging = true
	}).on('mouseup','.evo_elm_ang_hold',function(){
		dragging = false
	}).on('mousemove','.evo_elm_ang_hold',function(e){
		if (dragging) {
			//console.log(e);
			tar = $(this).find('.evo_elm_ang_center');
			var mouse_x = e.offsetX;
            var mouse_y = e.offsetY;
            var radians = Math.atan2(mouse_x - 10, mouse_y - 10);
            var degree = parseInt( (radians * (180 / Math.PI) * -1) + 180 );
			//console.log(degree+ ' '+ mouse_x +' '+mouse_y);

			tar.css('transform', 'rotate(' + degree + 'deg)');
			$(this).siblings('.evo_elm_ang_inp').val( degree +'°');

			$('body').trigger('evo_angle_set',[$(this), degree]);
		}
	}).on('keyup','.evo_elm_ang_inp',function(){
		deg = parseInt($(this).val());
		$(this).val( deg +'°');
		tar.css('transform', 'rotate(' + deg + 'deg)');
		
		$('body').trigger('evo_angle_set',[$(this), deg]);
	});


// Attach an image


// Image Attachment @4.8.1
	var file_frame;	
	var __img_index;
	var __img_obj;
	var __img_box;
	var __img_type;
  
    BB.on('click','.evolm_img_select_trig',function(event) {
    	event.preventDefault();

    	__img_obj = $(this);
    	__img_box = __img_obj.closest('.evo_metafield_image');
    	__img_type = __img_box.hasClass('multi')? 'multi': 'single';

    	if( __img_type == 'single' &&  __img_box.hasClass('has_img') ) return;

    	if( __img_type == 'multi'){
    		__img_index = __img_obj.data('index');

    		// remove image
			if( __img_obj.hasClass('on')){
				__img_obj.css('background-image', '').removeClass('on');
				__img_obj.find('input').val( '' );
				return;
			}
    	}

    	// If the media frame already exists, reopen it.
			if ( file_frame ) {	file_frame.open();	return;			}

		// Create the media frame.
			file_frame = wp.media.frames.downloadable_file = wp.media({
				title: 'Choose an Image', button: {text: 'Use Image',},	multiple: false
			});

    	
		// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				const attachment = file_frame.state().get('selection').first().toJSON();

				if( __img_type == 'single'){
					__img_box.addClass('has_img');
					__img_box.find('input.evo_meta_img').val( attachment.id );
					__img_box.find('.evoelm_img_holder').css('background-image', 'url('+ attachment.url +')');
				}else{
					__img_obj.css('background-image', 'url('+ attachment.url +')').addClass('on');
					__img_obj.find('input').val( attachment.id );
				}

			});

		// Finally, open the modal.
		file_frame.open();
		
    });  
	// remove image
	BB.on('click','.evoel_img_remove_trig',function(){

		const field = $(this).closest('.evo_metafield_image');

		if( !(field.hasClass('has_img') ) ) return;
		
		field.removeClass('has_img');
		field.find('input').val('');
		field.find('button').addClass('chooseimg');
		field.find('.evoelm_img_holder').css('background-image', '' );
	});



// yes no button @4.6.9	
	$('body').on('click','.ajde_yn_btn', function(){

		var obj = $(this);
		var afterstatement = obj.attr('afterstatement');
		var newval = 'yes';
		var key = obj.attr('id');
		
		// yes
		if(obj.hasClass('NO')){
			obj.removeClass('NO');
			obj.siblings('input').val('yes');				
			
			// afterstatment
			if(afterstatement!=''){
				var type = (obj.attr('as_type')=='class')? '.':'#';
				$('body').find(type+afterstatement).show();
			}

		}else{//no
			obj.addClass('NO');
			obj.siblings('input').val('no');
			newval = 'no';

			
			if(afterstatement != ''){
				var type = (obj.attr('as_type')=='class')? '.':'#';
				$('body').find(type+afterstatement).hide();
			}
		}

		//console.log(newval);

		$('body').trigger('evo_yesno_changed',[newval, obj, key, afterstatement]);
	});

	// @since 4.5.2
	$.fn.evo_elm_change_yn_btn = function(val){
		el = this;
		el.val( val );
		if( val == 'no'){
			el.siblings('.evo_elm').addClass('NO');
		}else{
			el.siblings('.evo_elm').removeClass('NO');
		}
	}
	

// yes no button afterstatement hook @4.6.9
	BB.on('evo_yesno_changed', function(event, newval, obj, key, afterstatement){

		if(afterstatement === undefined) return;
		
		if(newval == 'yes'){
			obj.closest('.evo_elm_row').next().show();
		}else{
			obj.closest('.evo_elm_row').next().hide();
		}
	});

// Side panel @4.5.1
	// move the sidepanel to body
		var SP = $('.evo_sidepanel');
		$('.evo_sidepanel').remove();
		BB.append(SP);


// ICON font awesome selector	
	BB.on('click','.evo_icons', function(){

		const el = $(this);
		
		el.evo_open_sidepanel({
			'uid':'evo_open_icon_edit',
			'sp_title':'Edit Icons',
			'content_id': 'evo_icons_data',
			'other_data': el.data('val')
		});
		BB.find('.evo_icons').removeClass('onfocus');
		el.addClass('onfocus');

		BB.find('.evo_settings_icon_box').removeClass('onfocus');
		el.closest('.evo_settings_icon_box').addClass('onfocus');

		return;
	})
	.on('evo_sp_opened_evo_open_icon_edit',function(event, OO){
		BB.evo_run_icon_selector({icon_val : OO.other_data} );
	});

	// when icons sidepanel closed
	BB.on('evo_sp_closed',function(event, SP ){
		if( $(SP).find('.evo_open_icon_edit')){
			BB.find('.evo_settings_icon_box').removeClass('onfocus');
		}
	});		




	$.fn.evo_run_icon_selector = function(options){
		const SP = BB.find('.evo_sp');
		var settings = $.extend({
            icon_val: "",
        }, options );

		var el = SP;
		var icon_on_focus = '';

		el.off('keyup','.evo_icon_search').off('search','.evo_icon_search');;

		var init = function(){
			scrollto_icon();
			icon_on_focus = BB.find('.evo_icons.onfocus');

			// move search to header
			el.find('.evo_icon_search_bar').appendTo( el.find('.evosp_head') );
		}

		var scrollto_icon = function(){
			if( settings.icon_val == '' ) return;
			const icon_in_list = el.find('li[data-v="' +settings.icon_val+ '"]');
				icon_in_list.addClass('selected');
			$('#evops_content').scrollTop( icon_in_list.position().top -100);
		}

		// select an icon
		el.on('click','li',function(){
			icon_on_focus = BB.find('.evo_icons.onfocus');
			var icon = $(this).find('i').data('name');

			el.find('li').removeClass('selected');
			el.find('li[data-v="'+ icon +'"]').addClass('selected');

			var extra_classes = '';
			if( icon_on_focus.hasClass('so')) extra_classes += ' so';

			//console.log(icon);

			icon_on_focus
				.attr({'class':'evo_icons ajde_icons default fa '+icon + extra_classes })
				.data('val', icon)
				.removeClass('onfocus');
			icon_on_focus.siblings('input').val(icon);

			BB.find('.evo_settings_icon_box').removeClass('onfocus');

			el.off('click','li');
			el.evo_close_sidepanel();
		});

		// search icon
		el.on('search','.evo_icon_search',function(){
			el.find('li').show();
			scrollto_icon();
		});
		el.on('keyup', '.evo_icon_search',function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			var typed_val = $(this).val().toLowerCase();

			console.log('e');
			
			el.find('li').each(function(){
				const nn = $(this).data('v');
				const n = nn.substr(3);

				if( typed_val == ''){
					$(this).show();
				}else{
					if( n.includes(typed_val ) ){
						$(this).show();
					}else{
						$(this).hide();
					}
				}				
			});	
		});

		init();
	}

	// remove icon
		$('body').on('click','i.evo_icons em', function(){
			$(this).parent().attr({'class':'evo_icons ajde_icons default'}).data('val','');
			$(this).parent().siblings('input').val('');
		});
	
// select2 dropdown field - 4.0.3
	if ( $.isFunction($.fn.select2) ){
		$('.ajdebe_dropdown.evo_select2').select2();

		$('body').on('evo_ajax_complete_eventedit_onload', function(event, OO, data, el){
			$('body').find('.ajdebe_dropdown.evo_select2').each(function(){
				$(this).select2();
			});
		});
	}  

// self hosted tooltips
// deprecating
	$('body').find('.ajdethistooltip').each(function(){
		tipContent = $(this).find('.ajdeToolTip em').html();
		toolTip = $(this).find('.ajdeToolTip');
		classes = toolTip.attr('class').split('ajdeToolTip');
		toolTip.remove();
		$(this).append('<em>' +tipContent +'</em>').addClass(classes[1]);
	});

// ELEMENTS
// @updated 4.7.4
// tooltips

	$.fn.evo_elm_show_tooltip = function( passed_content, hide_time ){
		var el = this;

		if( el.hasClass('show')) return;

		var free = el.hasClass('free') ? true: false;

		var content = (passed_content !== undefined) ? passed_content : el.data('d');
		var tooltipbox = $('.evo_tooltip_box');

		// as backup use title atribute for toolt tip content
		if( content === undefined || content == ''){
			content = el.attr('title');
		}


		if( content == '') return;

		var p = el.position();
		
		var cor = getCoords(event.target);

		tooltipbox.removeClass('show L').html( content );
		var box_height = $('.evo_tooltip_box').height();
		var box_width = $('.evo_tooltip_box').width();

		// box left calculation
		var _left = cor.left + 5;

		// if center arrow
		if( el.hasClass('evocenter')){
			_left = _left - parseInt( box_width / 2 ) - 9;
			tooltipbox.addClass('evocenter');
		}

		tooltipbox.css({'top': (cor.top - 55 - box_height - ( free ? 10: 0) ), 'left': _left })
			.addClass('show');

		// left align
		if( el.hasClass('L')){
			tooltipbox.css({'left': (cor.left - box_width - 15) }).addClass('L');			
		}

		// hide tooltip in set time
		if( hide_time !== undefined ){
			setTimeout(function(){
				el.evo_elm_hide_tooltip();
			}, hide_time);	
		}
		el.addClass('show');
	}
	$.fn.evo_elm_hide_tooltip = function(){
		this.removeClass('show');
		$('.evo_tooltip_box').removeClass('show');
		setTimeout(function(){
			$('.evo_tooltip_box').removeClass('L center');
		},200);
	}

	$('body').on('mouseover','.ajdeToolTip, .colorselector, .evotooltip',function(event){
		event.stopPropagation();

		var relatedTarget = event.relatedTarget;
		if( $(relatedTarget).closest('.evotooltip.show').length == 0)
			$(this).evo_elm_show_tooltip();		
	})
	.on('mouseout','.ajdeToolTip, .colorselector, .evotooltip',function(event){	
		event.stopPropagation();
		var relatedTarget = event.relatedTarget;
		if( $(relatedTarget).closest('.evotooltip.show').length == 0)
	    	$(this).evo_elm_hide_tooltip();
	});



	function getCoords(elem) { // crossbrowser version
	    var box = elem.getBoundingClientRect();
	    //console.log(box);

	    var body = document.body;
	    var docEl = document.documentElement;

	    var scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
	    var scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;

	    var clientTop = docEl.clientTop || body.clientTop || 0;
	    var clientLeft = docEl.clientLeft || body.clientLeft || 0;

	    var top  = box.top +  scrollTop - clientTop;
	    var left = box.left + scrollLeft - clientLeft;

	    return { top: Math.round(top), left: Math.round(left) };
	}

// Select in a row	 
	 $('body').on('click','span.evo_row_select_opt',function(){

	 	var O = $(this);
	 	var P = O.closest('p');
	 	const multi = P.hasClass('multi')? true: false;
				
		if(multi){
			if(O.hasClass('select')){
				O.removeClass('select');
			}else{
				O.addClass('select');
			}

		}else{
			P.find('span.opt').removeClass('select');
			O.addClass('select');
		}

		var val = '';
		P.find('.opt').each(function(){
			if( $(this).hasClass('select')) val += $(this).attr('value')+',';
		});

		val = val.substring(0, val.length-1);

		P.find('input').val( val );		

		$('body').trigger('evo_row_select_selected',[P, $(this).attr('value'), val]);			
	});

// Color picker @+4.5
	setup_colorpicker();
	$('body').on('evo_page_run_colorpicker_setup',function(){
		setup_colorpicker();
	});
	function setup_colorpicker(){
		$('body').find('.evo_elm_color').each(function(){
			var elm = $(this);

			if( typeof elm.ColorPicker ==='function'){
				elm.ColorPicker({
					onBeforeShow: function(){
						$(this).ColorPickerSetColor( '#888888');
					},
					onChange:function(hsb, hex, rgb,el){
						elm.css({'background-color':'#'+hex});		
						elm.siblings('.evo_elm_hex').val( hex );
					},onSubmit: function(hsb, hex, rgb, el) {
						elm.css({'background-color':'#'+hex});		
						elm.siblings('.evo_elm_hex').val( hex );
						$(el).ColorPickerHide();

						var _rgb = get_rgb_min_value(rgb, 'rgb');
						elm.siblings('.evo_elm_rgb').val( _rgb );
					}
				});
			}
		});
	}

	function get_rgb_min_value(color,type){
			
		if( type === 'hex' ) {			
			var rgba = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(color);	
			var rgb = new Array();
			 rgb['r']= parseInt(rgba[1], 16);			
			 rgb['g']= parseInt(rgba[2], 16);			
			 rgb['b']= parseInt(rgba[3], 16);	
		}else{
			var rgb = color;
		}
		
		return parseInt((rgb['r'] + rgb['g'] + rgb['b'])/3);			
	}

	// color picker 2
	$.fn.evo_colorpicker_init = function(opt){
		var el = this;
		var el_color = el.find('.evo_set_color');

		var init = function(){
			el.ColorPicker({		
				color: get_default_set_color(),
				onChange:function(hsb, hex, rgb,el){
					set_hex_values(hex,rgb);
				},
				onSubmit: function(hsb, hex, rgb, el) {
					set_hex_values(hex,rgb);
					$(el).ColorPickerHide();

					// trigger
					$('body').trigger('evo_colorpicker_2_submit', [ el, hex, rgb]);
				}		
			});
		} 			

		var set_hex_values = function(hex,rgb){			
			el.find('.evcal_color_hex').html(hex);
			el.find('.evo_color_hex').val(hex);

			fcl = el.evo_is_hex_dark({hex: hex}) ? '000000':'ffffff';
			el_color.css({'background-color':'#'+hex, 'color':'#'+ fcl });		
			
			// set RGB val
			rgb_val = $('body').evo_rgb_process({ data : rgb, type:'rgb',method:'rgb_to_val'});
			el.find('.evo_color_n').val( rgb_val );
		}
		
		var get_default_set_color = function(){
			var colorraw = el_color.css("background-color");						
			var def_color = el.evo_rgb_process({data: colorraw, method:'rgb_to_hex'});	
			return def_color;
		}

		init();
	}
	$('body').on('evo_eventedit_dom_loaded_evo_color',function(event, val){
		$('body').find('.evo_color_selector').each(function(){
			$(this).evo_colorpicker_init();	
		});					
	});
	
// plus minus changer
	$('body').on('click','.evo_plusminus_change', function(event){

        OBJ = $(this);

        QTY = parseInt(OBJ.siblings('input').val());
        MAX = OBJ.siblings('input').data('max');        
        if(!MAX) MAX = OBJ.siblings('input').attr('max');           

        NEWQTY = (OBJ.hasClass('plu'))?  QTY+1: QTY-1;

        NEWQTY =(NEWQTY <= 0)? 0: NEWQTY;

        // can not go below 1
        if( NEWQTY == 0 && OBJ.hasClass('min') ){    return;    }

        NEWQTY = (MAX!='' && NEWQTY > MAX)? MAX: NEWQTY;

        OBJ.siblings('input').val(NEWQTY);

        if( QTY != NEWQTY) $('body').trigger('evo_plusminus_changed',[NEWQTY, MAX, OBJ]);
       
        if(NEWQTY == MAX){
            PLU = OBJ.parent().find('b.plu');
            if(!PLU.hasClass('reached')) PLU.addClass('reached');   

            if(QTY == MAX)   $('body').trigger('evo_plusminus_max_reached',[NEWQTY, MAX, OBJ]);                 
        }else{            
            OBJ.parent().find('b.plu').removeClass('reached');
        } 
    });

// date time picker @4.5.5
	var RTL = $('body').hasClass('rtl');

	// load date picker libs
	_evo_elm_load_datepickers();
	$('body').on('evo_elm_load_datepickers',function(){
		_evo_elm_load_datepickers();
	});
	$('body').on('click','.evo_dpicker',function(){	
		_evo_elm_load_datepickers( true, $(this).attr('id') );
	});

	function _evo_elm_load_datepickers( call = false, OBJ_id){

		
		$('body').find('.evo_dpicker').each(function(){

			var OBJ = $(this);
			if( OBJ.hasClass('dp_loaded')) return;

			const this_id = OBJ.attr('id');
			var rand_id = OBJ.closest('.evo_date_time_select').data('id');			
			var D = $('body').find('.evo_dp_data').data('d');
			var startDO, endDO;

			// set start and end date objects
			if( OBJ.hasClass('start') ){
				var startDO = OBJ;
				var endDO = $('body').find('.evo_date_time_select.end[data-id="'+rand_id+'"]').find('input.evo_dpicker.end');
			}else{
				var startDO = $('body').find('.evo_date_time_select.start[data-id="'+rand_id+'"]').find('input.evo_dpicker.start');
				var endDO = OBJ;
			}

			//console.log( endDO);

			OBJ.addClass('dp_loaded');

			const d = new Date( OBJ.val() );
			var highlightson = false;

			OBJ.datepicker({
				beforeShow: function( input , inst){
					$(inst.dpDiv).addClass('evo-datepicker');
					//console.log(rand_id);
					//console.log(startDO.val() +' '+ endDO.val());
				},
				beforeShowDay: function(date){

					var dates = [startDO.val(), endDO.val() ];

					// Convert start and end dates to Date objects
			        let startDate = new Date(dates[0]);
			        let endDate = new Date(dates[1]);

			        // If start and end dates are not set, return default
        			if (isNaN(startDate) || isNaN(endDate)) return [true, ''];

        			// if start and end are the same date
					if( new Date(dates[0]).toString() ==  new Date(dates[1]).toString())
						 return [true, ''];	


        			// Check if the current date is the start date
			        if (startDate.toDateString() === date.toDateString()) {
			            highlightson = true;
			        }

			        // Check if the current date is the day *after* the end date
			        let endDatePlusOne = new Date(endDate);
			        endDatePlusOne.setDate(endDatePlusOne.getDate() + 1);

			        if (endDatePlusOne.toDateString() === date.toDateString()) {
			            highlightson = false;
			        }

			        // Highlight if the date is within the range (including across months)
			        if (date >= startDate && date <= endDate) {
			            highlightson = true;
			        }



			        if( highlightson ) return [true, 'highlight','tt'];
			        return [true, ''];
				},
				onChangeMonthYear: function(year,month, inst){
					highlightson = false;
				},
				dateFormat: D.js_date_format,
				firstDay: D.sow,
				numberOfMonths: 2,
				altField: OBJ.siblings('input.alt_date'),
				altFormat: OBJ.siblings('input.alt_date_format').val(),
				isRTL: RTL,
				setDate: d,
				onSelect: function( selectedDate , ooo) {

					//var date = new Date(ooo.selectedYear, ooo.selectedMonth, ooo.selectedDay);
					var date = OBJ.datepicker('getDate');

					$('body').trigger('evo_elm_datepicker_onselect', [OBJ, selectedDate, date, rand_id]);

					// update end time					
					if( OBJ.hasClass('start') ){						
						if(endDO.length>0){
							
							endDO.datepicker( 'setDate', date);
							endDO.datepicker( "option", "minDate", date );
						}
					}
				}
			});


			var id_match = ( ( OBJ_id !== undefined && OBJ_id == this_id ) || OBJ_id === undefined )
				? true: false;

			if( call && id_match ) OBJ.datepicker('show');
		});
	}

	
// time picker
	$('body').on('change','.evo_timeselect_only',function(){
		var P = $(this).closest('.evo_time_edit');
		var min = 0;

		min += parseInt(P.find('._hour').val() ) *60;
		min += parseInt(P.find('._minute').val() );

		P.find('input').val( min );
	});

// Upload data files
// @version 4.6.9
	$('body').on('click','.evo_data_upload_trigger',function(event){
		if( event !== undefined ){
			event.preventDefault();
			event.stopPropagation();
		}
		OBJ = $(this);

		const upload_box = OBJ.closest('.evo_data_upload_holder').find('.evo_data_upload_window');
		upload_box.show();

		const msg_elm = upload_box.find('.msg');
		msg_elm.hide();		
	});

	$('body').on('click','.upload_settings_button',function(event){
		//event.preventDefault();
		OBJ = $(this);

		const upload_box = OBJ.closest('.evo_data_upload_window');

		// show form
		upload_box.show();

		const msg_elm = upload_box.find('.msg');
		const form = upload_box.find('form');
		var fileSelect = upload_box.find('input');
		const acceptable_file_type = fileSelect.data('file_type');
		msg_elm.hide();
		
		// when form submitted
		$(form).one('submit',function(event){
			
			event.preventDefault();
			msg_elm.html('Processing').show();

			var files = fileSelect.prop('files');

			if( !files ){
			 	msg_elm.html('Missing File.'); return;
			}
			
			var file = files[0];

			if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
		      	alert('The File APIs are not fully supported in this browser.');
		      	return;
		    }

		    if( file === undefined ){
		    	msg_elm.html('Missing File.'); return;
		    }
		    if( file.name.indexOf( acceptable_file_type ) == -1 ){
		  		msg_elm.html('Only accept '+acceptable_file_type+' file format.');
		  	}else{
		  		var reader = new FileReader();
			  	reader.readAsText(file);

	            reader.onload = function(reader_event) {
	            	$('body').trigger('evo_data_uploader_submitted', [reader_event, msg_elm, upload_box]);
	            };
	            reader.onerror = function() {
	            	msg_elm.html('Unable to read file.');
	            };
	        }	

	        return false;		
		});
		return true;
	});

	// close upload window
	$('body').on('click','.evo_data_upload_window_close',function(){
		$(this).parent().hide();
	});

// Show a snackbar message for eventON
	$.fn.evo_snackbar = function(opt){
		var defaults = { 
			'message':'',
			'classnames':'',
			'visible_duration':5000,
		}; var OO = $.extend({}, defaults, opt);

		// Create the snackbar element if it doesn't exist
	    if ($('.evo_elms #evo_snackbar').length === 0) {
	        $('.evo_elms').append('<div id="evo_snackbar"></div>');
	    }

	    var snackbar = $('#evo_snackbar');

	    // Set the message and additional classnames
	    snackbar.html(OO.message);
	    setTimeout(function() {
	    	snackbar.attr('class', 'show ' + OO.classnames); // Reset classes
	    },100);

	    // Hide the snackbar after the specified duration
	    setTimeout(function() {
	        snackbar.addClass('hide').removeClass('show');	        
	    }, OO.visible_duration);

	}

// lightbox select @updated 4.7.2
	$('body').on('click','.evo_elm_lb_field input',function(event){
		const O = $(this);
		const elm_row = O.closest('.evo_elm_row');

		$('body').find('.evo_elm_lb_on').removeClass('evo_elm_lb_on');
		O.addClass('evo_elm_lb_on');

		extra_class = '';

		POS = O.offset();
		pos_top = POS.top;
		pos_left = POS.left;

		// if menu to show above
		if( $(window).height() < ( POS.top + 220 ) ){
			extra_class = 'above';

			pos_top = pos_top - 260;
		}

		const list = O.closest('.evo_elm_lb_fields').data('d');
		const setvals = O.closest('.evo_elm_lb_fields').data('v');
		//console.log(list);

		lbhtml = "<div class='evo_elm_lb_window "+extra_class+"'><div class='eelb_in'><div class='eelb_i_i'>";

		// check if list has values
		if (typeof list === 'object' && list !== null && typeof list !== 'undefined') {

			$.each( list, function(index, val){
				select = setvals.includes(index) ? 'select':'';
				lbhtml += "<span class='"+select+"' value='"+index+"'>"+val+"</span>";
			});
		}else{
			lbhtml += "<span class='' value='all'>--</span>";
		}
		lbhtml += "</div></div></div>";

		const elm2 = $('body').find('.evo_elms2');

		elm2.html( lbhtml );

		elm2.find('.eelb_in').css({'top':pos_top,'left':pos_left});
		elm2.find('.evo_elm_lb_window').addClass('show');
		
	});

	// close lightbox
		$(window).on('click', function(event) {
			if( !($(event.target).hasClass('evo_elm_lb_field_input')) )
				$('body').find('.evo_elm_lb_window').removeClass('show above').fadeOut(300);
		});
		

	// selecting options in lightbox select field
	$('body')
		.on('click','.eelb_in span',function(){
			const field = $('body').find('.evo_elm_lb_on');
			
			if($(this).hasClass('select')){
				$(this).removeClass('select');
			}else{
				$(this).addClass('select');
			}

			var V = '', Vo = []; 

			$(this).parent().find('span.select').each(function(index){
				V += $(this).attr('value')+',';
				Vo.push( $(this).attr('value') );
			});

			field.val( V ).trigger('change');
			field.closest( '.evo_elm_lb_fields' ).data('v', Vo);

			console.log(Vo);

			$('body').trigger('evo_elm_lb_option_selected',[ $(this), V]);
		})
		.on('click','.evo_elm_lb_window',function(event){
			if( event !== undefined ){
				event.preventDefault();
				event.stopPropagation();
			}
		})
	;

});