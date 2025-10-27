<?php
/**
 * Post meta boxes
 * @version 1.0
 */
class evodp_meta_boxes{
	public function __construct(){
		add_action('evotx_event_metabox_end',array($this, 'event_tickets_metabox'), 10, 5);
		add_filter('evotx_save_eventedit_page',array($this, 'event_ticket_save'), 10, 1);
	}

	function event_tickets_metabox($eventid, $epmv, $wooproduct_id, $product_type, $EVENT){
		global $eventon, $evodp;

		$event_edit_allow_dynamic_pricing = apply_filters('evodp_event_edit_enable_dp',true, $EVENT);

		$show_DP = true;

		// Check if repeating event
		if( $EVENT->is_repeating_event()) $show_DP = false; 
		if( empty($wooproduct_id) ) $show_DP = false; 
		if( $product_type != 'simple') $show_DP = false;
		if( !$event_edit_allow_dynamic_pricing) $show_DP = false;
				
		if( $show_DP ):
		
		
		$fnc = new evodp_fnc();
		$HELP = new evo_helper();

		?>
		<tr ><td colspan='2'>
			<p class='yesno_leg_line ' >
				<?php echo eventon_html_yesnobtn(array(
					'id'=>		'_evodp_activate',
					'var'=>		$EVENT->get_prop('_evodp_activate'), 
					'afterstatement'=>'evodp_pricing',
					'input'=>	true,
					'label'=>	__('Enable dynamic ticket pricing options for this event','eventon'),
					'guide'=>	__('This will allow you to set dynamic ticket pricing options.','eventon')
				)); ?>
			</p>
		</td></tr>

		<tr class='innersection' id='evodp_pricing' style='display:<?php echo !$EVENT->check_yn('_evodp_activate') ? 'none':''; ?>'>
			<td style='padding:20px 25px;' colspan='2'>

				<div class='evodp_editor_base'>				

					<p class=''>
						<?php
						EVO()->elements->print_trigger_element(array(
							'extra_classes'=>'',
							'title'=>__('Dynamic Pricing Editor','eventon'),
							'dom_element'=> 'span',
							'uid'=>'evodp_settings',
							'lb_class' =>'config_evodp_settings',
							'lb_title'=>__('Configure Dynamic Pricing Settings','eventon'),	
							'ajax_data'=>array(					
								'eid'=> $EVENT->ID,
								'action'=> 'evodp_load_editor',
							),
						), 'trig_lb');

						?></p>
				</div>
			</td>
		</tr>
		<?php

		else:
			?>
			<tr class='' id='evodp_tr' >
				<td style='padding:5px 25px;' colspan='2'>
					<?php if(!$event_edit_allow_dynamic_pricing):?>
						<p><i><?php _e('NOTE: Dynamic Pricing is not available for current event ticket configurations.', 'eventon'); ?></i></p>
					<?php else:?>	
						<p><i><?php _e('NOTE: Dynamic Pricing is only available for simple ticket product with no repeat instances at the moment. The event ticket basic information must be saved first before configuring dynamic prices.', 'eventon'); ?></i></p>
					<?php endif;?>
				</td>

			</tr>
			<?php
		endif;
	}

	

	// save fields
		function event_ticket_save($array){
			$array[] = '_evodp_activate';
			return $array;
		}
}
new evodp_meta_boxes();