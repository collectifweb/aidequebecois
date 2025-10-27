<?php
/** 
 * Helper functions to be used by eventon or its addons
 * front-end only
 *
 * @version 4.8.1
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class evo_helper{	
	public $options2, $opt2;
	public function __construct(){
		$this->opt2 = get_option('evcal_options_evcal_2'); 		
	}

	// Process permalink appends
		public function process_link($link,  $var, $append, $force_par = false){
			if(strpos($link, '?')=== false ){

				if($force_par){
					if( substr($link,-1) == '/') $link = substr($link,0,-1);
					$link .= "?".$var."=".$append;
				}else{
					if( substr($link,-1) == '/') $link = substr($link,0,-1);
					$link .= "/".$var."/".$append;
				}
				
			}else{
				$link .= "&".$var."=".$append;
			}
			return $link;
		}
	// process urls to complete url @4.8.1
		public function _process_url($url){
			return strpos($url, 'http') === false ? 'https://'. $url : $url;
		}

	// sanitization
		// @4.5.5
		public function sanitize_xss($value){
			return str_replace(array(
				'alert(', 'onanimationstart', 'onclick'
			), '', $value);
		}
		// @+ 4.0.3
		public function sanitize_array($array){
			return $this->recursive_sanitize_array_fields($array);
		}
		public function recursive_sanitize_array_fields($array){
			if(is_array($array)){
				$new_array = array();
				foreach ( $array as $key => $value ) {
		        	if ( is_array( $value ) ) {
		        		$key = sanitize_title($key);
		            	$new_array[ $key ] = $this->recursive_sanitize_array_fields($value);
		        	}
		        	else {
		            	$new_array[ $key ] = sanitize_text_field( $value );
		        	}
	    		}

	    		return $new_array;
	    	}else{
	    		return sanitize_text_field( $array );	    		
	    	}
		}	
		// @ 4.7.2
		// returned sanitized $_POST
		public function sanitize_post(){
			return $this->recursive_sanitize_array_fields($_POST);
		}

		// check ajax submissions for sanitation and nonce verification
		// @+3.1
		public function process_post($array, $nonce_key='', $nonce_code='', $filter = true){
			$array = $this->sanitize_array( $array);

			if( !empty($nonce_key) && !empty($nonce_code)){

				if( !wp_verify_nonce( $array[ $nonce_key], $nonce_code ) ) return false;
			}

			if($filter)	$array = array_filter( $array );
			return $array;
		}	

		// sanitize html content @u 4.6.1
			function sanitize_html($content){
				if( !EVO()->cal->check_yn('evo_sanitize_html','evcal_1')) return $content;

				if( is_array($content)) return $content;

				//return wp_kses_post( $content );

				return wp_kses( $content, apply_filters('evo_sanitize_html', array( 
				    'a' => array(
			            'href' => array(),
			            'title' => array()
			        ),
			        'br' => array(),
			        'p' => array(),
			        'i' => array(),
			        'b' => array(),
			        'u' => array(),              
			        'ul' => array(),
			        'li' => array(),
			        'em' => array(),
			        'strong' => array(),
			        'span' => array(
			            'class' => array(),
			            'style' => array(),
			        ),
			        'font' => array(
			            'color' => array()
			        ),
			        'img' => array(
			            'src'      => true,
			            'srcset'   => true,
			            'sizes'    => true,
			            'class'    => true,
			            'id'       => true,
			            'width'    => true,
			            'height'   => true,
			            'alt'      => true,
			            'title'    => true,
			            'align'    => true,
			            'style'    => true,
			            'data-*'   => true, // Allow any data attributes
			        ),
				) ) );
			}
			function sanitize_html_for_eventtop( $content ){
				return wp_kses( $content, apply_filters('evo_sanitize_html_eventtop',
					array( 				    
				    'i' => array(),
				    'b' => array(),
				    'u' => array(),			    
				    'br' => array(),
				    'em' => array(),
				    'strong' => array(),
				    'img' => array(
				    	'src'      => true,
				        'srcset'   => true,
				        'sizes'    => true,
				        'class'    => true,
				        'id'       => true,
				        'width'    => true,
				        'height'   => true,
				        'alt'      => true,
				        'align'    => true,
				    ),
				) ) );
			}


		// sanitize unix
		function sanitize_unix( $unix){
			$t = explode('Z', $unix);
			$u = explode('T', $t[0]);

			$a = (int)$u[0];
			$b = isset($u[1]) ? (int)$u[1]:0;

			if(strlen($a)<6) $a = sprintf('%06d', $a);
			if(strlen($b)<6) $b = sprintf('%06d', $b);

			return $a.'T'. $b .'Z';
		}


	// Create posts 
		function create_posts($args){
			$DATA_Store = new EVO_Data_Store();
			return $DATA_Store->create_new( $args );
		}

		function create_custom_meta($post_id, $field, $value){
			add_post_meta($post_id, $field, $value);
		}

	// check if post exist for a ID
	// @+ 3.0
		function post_exist($ID, $post_status = 'publish'){
			global $wpdb;

			$post_id = $ID;
			$post_exists = $wpdb->get_row(
				$wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE id = %d AND post_status = %s", $post_id, $post_status)
			, 'ARRAY_A');
			return $post_exists? $post_exists['ID']: false;
		}
			
	// Eventon Settings helper
		function get_html($type, $args){
			switch($type){
				case 'email_preview':
					ob_start();
					echo '<div class="evo_email_preview"><p>Headers: '.$args['headers'][0].'</p>';
					echo '<p>To: '.$args['to'].'</p>';
					echo '<p>Subject: '.$args['subject'].'</p>';
					echo '<div class="evo_email_preview_body">'.$args['message'].'</div></div>';
					return ob_get_clean();
				break;
			}
		}

	// ADMIN & Frontend Helper
		// @updated 4.5.7
		public function send_email($args){
			$defaults = array(
				'html'=>'yes',
				'preview'=>'no',
				'to'=>'',
				'bcc'=>'', // @s 4.5.5 support array() or single
				'from'=>'',
				'from_name'=>'','from_email'=>'',
				'header'=>'',
				'subject'=>'',
				'message'=>'',
				'type'=>'',// bcc
				'attachments'=> array(),
				'return_details'=>false,
				'reply-to' => ''
			);

			$args = wp_parse_args( $args, $defaults );
			//extract($args);

			if($args['html']=='yes'){
				add_filter( 'wp_mail_content_type',array($this,'set_html_content_type'));
				add_filter( 'wp_mail_charset', array($this,'change_mail_charset') );
			}

			$headers = '';

			if(!empty($args['header'])){
				$headers = $args['header'];
			}else{
				$headers = array();
				if(empty($args['from_email'])){
					$headers[] = 'From: '.$args['from'];
				}else{
					$headers[] = 'From: '.(!empty($args['from_name'])? $args['from_name']:'') .' <'.
						$args['from_email'] . '>';
				}
			}	


			// add reply to into headers // @2.8.6
				if(!empty($args['reply-to']) && isset($args['reply-to'])){

					if( is_array($headers)){
						$headers[] = 'Reply-To: '. $args['reply-to'];
					}else{
						$headers .= 'Reply-To: '. $args['reply-to'];
					}
				}

			// email type as html
				if($args['html']=='yes'){
					if( is_array($headers)){
						$headers[] = 'Content-Type: text/html; charset=UTF-8';	
					}else{
						$headers .= 'Content-Type: text/html; charset=UTF-8';	
					}
				}

			$return = '';	

			if($args['preview']=='yes'){
				$return = array(
					'to'=>$args['to'],
					'subject'=>$args['subject'],
					'message'=>$args['message'],
					'headers'=>$headers
				);
			
			// bcc version of things everything
			}else if(!empty($args['type']) && $args['type']=='bcc' ){
				if(is_array($args['to']) ){
					foreach($args['to'] as $EM){
						$headers[] = "Bcc: ".$EM;
					}
				}else{
					$headers[] = "Bcc: ".$args['to'];
				}

				$return = wp_mail($args['from'], $args['subject'], $args['message'], $headers, $args['attachments']);	
			}else{

				// if bcc emails are provided add those to header @4.5.7
					if( !empty( $args['bcc'])){
						if(is_array( $args['bcc'] ) ){
							foreach( $args['bcc'] as $bcc_email){
								$headers[] = "Bcc: ".$bcc_email;
							}
						}else{	$headers[] = "Bcc: ". $args['bcc'];	}
					}

				// Send email using wp_mail()
				$return = wp_mail($args['to'], $args['subject'], $args['message'], $headers, $args['attachments']);
			}

			if($args['html']=='yes'){
				remove_filter( 'wp_mail_content_type', array($this,'set_html_content_type') );
			} 

			if($args['return_details']){
				// get the errors
				$ts_mail_errors = array();
				if(!$return){
					global $ts_mail_errors;
					global $phpmailer;

					if (!isset($ts_mail_errors)) $ts_mail_errors = array();

					if (isset($phpmailer)) {
						$ts_mail_errors[] = $phpmailer->ErrorInfo;
					}
				}
				return array('result'=>$return, 'error'=>$ts_mail_errors);
			}else{
				return $return;
			}
			
		}	
		
		// set a custom encoding character type for emails
		function change_mail_charset( $charset ) {

			$encoding = EVO()->cal->get_prop('_evo_email_encode','evcal_1');

			if( !$encoding) return $charset;
			if( $encoding == 'def') return $charset;
			if( $encoding == 'utf8') return 'UTF-8';
			if( $encoding == 'utf16') return 'UTF-16';
		}
	 
		function set_html_content_type() {
			return 'text/html';
		}
		function set_charset_type() {
			return 'utf8';
		}

		// GET email body with eventon header and footer for email included
		public function get_email_body_content($message='', $outside = true){
			
			ob_start();
			if($outside) echo EVO()->get_email_part('header');
			echo !empty($message)? $message:'';
			if($outside) echo EVO()->get_email_part('footer');
			return ob_get_clean();
		}

	// YES NO Button
		function html_yesnobtn($args=''){
			return EVO()->elements->yesno_btn( $args );
		}

	// tool tips
		function tooltips($content, $position='', $handleClass=false, $echo = false){
			return EVO()->elements->tooltips( $content, $position, $echo, $handleClass);
		}
		function echo_tooltips($content, $position=''){
			$this->tooltips($content, $position='',true);
		}

	// ICS - date time processing
		public function get_ics_format_from_unix($unix, $separate = true){
			$enviro = new EVO_Environment();

			$unix = $unix - $enviro->get_UTC_offset();

			if( !$separate) return $unix;


			$new_timeT = date("Ymd", $unix);
			$new_timeZ = date("Hi", $unix);

			return $new_timeT.'T'.$new_timeZ.'00Z';
		}


		// Escape ICS text
			function esc_ical_text( $text='' ) {
				
			    $text = str_replace("\\", "", $text);
			    $text = str_replace("\r", "\r\n ", $text);
			    $text = str_replace("\n", "\r\n ", $text);
			    $text = str_replace(",", "\, ", $text);
			    $text = EVO()->calendar->helper->htmlspecialchars_decode($text);
			    return $text;
			}

	// template locator
	// pass: paths array, file name, default template with full path and file
		function template_locator($paths, $file, $template){
			foreach($paths as $path){
				if(file_exists($path.$file) ){	
					$template = $path.$file;
					break;
				}
			}				
			if ( ! $template ) { 
				$template = AJDE_EVCAL_PATH . '/templates/' . $file;
			}

			return $template;
		}	
	// Humanly readable time
	// @+ 4.6.9
		function get_human_time($time){

			$output = '';
			$minFix = $hourFix = $dayFix = 0;

			$day = $time/(60*60*24); // in day
			$dayFix = floor($day);
			$dayPen = $day - $dayFix;
			if($dayPen > 0)
			{
				$hour = $dayPen*(24); // in hour (1 day = 24 hour)
				$hourFix = floor($hour);
				$hourPen = $hour - $hourFix;
				if($hourPen > 0)
				{
					$min = $hourPen*(60); // in hour (1 hour = 60 min)
					$minFix = floor($min);
					$minPen = $min - $minFix;
					if($minPen > 0)
					{
						$sec = $minPen*(60); // in sec (1 min = 60 sec)
						$secFix = floor($sec);
					}
				}
			}
			$str = "";
			if($dayFix > 0)
				$str.= $dayFix . " " . ( $dayFix > 1 ? evo_lang('Days') : evo_lang('Day') );
			if($hourFix > 0)
				$str.= ' '. $hourFix . ' '. ( $hourFix > 1 ? evo_lang('Hours') : evo_lang('Hour') );
			if($minFix > 0)
				$str.= ' '. $minFix . ' '. ( $minFix > 1 ? evo_lang('Minutes') : evo_lang('Minute') );
			//if($secFix > 0)	$str.= $secFix." sec ";
			return $str;
		}

	// Woocommerce related
		function convert_to_currency($price, $symbol = true){		

			extract( apply_filters( 'wc_price_args', wp_parse_args( array(), array(
		        'ex_tax_label'       => false,
		        'currency'           => '',
		        'decimal_separator'  => wc_get_price_decimal_separator(),
		        'thousand_separator' => wc_get_price_thousand_separator(),
		        'decimals'           => wc_get_price_decimals(),
		        'price_format'       => get_woocommerce_price_format(),
		    ) ) ) );

			$sym = $symbol? html_entity_decode(get_woocommerce_currency_symbol($currency)):'';

			$negative = $price < 0;
			$price = floatval($negative? $price *-1: $price);
			$price = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

			

			if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
		        $price = wc_trim_zeros( $price );
		    }

		    $return = ( $negative ? '-' : '' ) . sprintf( $price_format, $sym, $price );

		    if ( $ex_tax_label && wc_tax_enabled() ) {
		        $return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
		    }


			return $return;
		}
		function get_price_format_data(){
			return array(
				'currencySymbol'=>get_woocommerce_currency_symbol(),
				'thoSep'=> get_option('woocommerce_price_thousand_sep'),
				'curPos'=> get_option('woocommerce_currency_pos'),
				'decSep'=> get_option('woocommerce_price_decimal_sep'),
				'numDec'=> get_option('woocommerce_price_num_decimals')
			);
		}

	
	// convert array to data element values - 3.1 / U 4.1
		public function array_to_html_data($array){
			$html = '';
			foreach($array as $k=>$v){
				if( is_array($v)) $v = htmlspecialchars( json_encode($v), ENT_QUOTES);
				$html .= 'data-'. $k .'="'. $v .'" ';
			}
			return $html;
		}

	// Timezones	
		//@4.7.1
		private function __get_evo_timezone_choices($selected_zone, $locale = null ){

		
			return apply_filters(
				'evo_events_timezone_choice',
				wp_timezone_choice( $selected_zone, $locale ),
				$selected_zone,
				$locale
			);
		}

		// return readable list of wp based timezone values @4.7.1
		function get_modified_wp_timezone_list($unix = ''){
			// using WP timezones
			$html = $this->__get_evo_timezone_choices('UTC');

			preg_match_all('/<option value="([^"]+)">/', $html, $matches);
			$tzs = $matches[1];

			$DD = new DateTime( 'now' );

			// if unix is passed adjust according to time present in unix
			if( !empty( $unix ))	$DD->setTimestamp( $unix );

			$updated_zones = array();
			foreach($tzs as $tz_string ){

				if(	strpos($tz_string, 'UTC') !== false ) continue;

				try {
					$DD->setTimezone( new DateTimeZone( $tz_string ));
				}
				catch (Exception $e) {
					// invalid timezone name
					error_log(print_r($e->getMessage(), TRUE));
					continue;
				}

				$updated_zones[ $tz_string ] = '(GMT'. $DD->format('P').') '. $tz_string;
				
			}

			return $updated_zones;
		}

		// @updated 4.5.7
		// $unix value passed to calculate DST for a given date - otherwise DST for now
		function get_timezone_array( $unix = '' , $adjusted = true) {
			return $this->get_modified_wp_timezone_list( $unix );
		}


		// return time offset from saved timezone values @4.5.2
		public function _get_tz_offset_seconds( $tz_key, $unix = ''){

			$DD = new DateTime( 'now' );

			// set passed on tz key
			try {
				$DD->setTimezone( new DateTimeZone( $tz_key ));
			}
			catch (Exception $e) {
				// invalid timezone name
				error_log(print_r($e->getMessage(), TRUE));	
				$DD->setTimezone( new DateTimeZone( 'UTC' ));			
			}

			if( !empty( $unix ))	$DD->setTimestamp( $unix );

			$GMT_value = $DD->format('P');

			// if it is UTC 0
			if(strpos($GMT_value, '+0:') !== false)	return 0;

			// alter
			if(strpos($GMT_value, '+') !== false){
				$ss = str_replace('+', '-', $GMT_value);
			}else{
				$ss = str_replace('-', '+', $GMT_value);	
			}

			// convert to seconds
			sscanf($ss, "%d:%d", $hours, $minutes);

			return $hours * 3600 + $minutes * 60;
		}

		// return GMT value
		function get_timezone_gmt($key, $unix = false){

			$DD = new DateTime();
			if($unix) $DD->setTimestamp($unix);
			$DD->setTimezone( new DateTimeZone( $key ));

			return 'GMT'. $DD->format('P');
		}

}