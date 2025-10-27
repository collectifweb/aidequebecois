<?php
/**
 * Event Edit Meta box Location
 * @4.8.1
 */

?>
<div class='evcal_data_block_style1'>
	<p class='edb_icon evcal_edb_map'></p>
	<div class='evcal_db_data'>
		<div class='evcal_location_data_section'>										
			<div class='evo_singular_tax_for_event event_location' data-tax='event_location' data-eventid='<?php echo $EVENT->ID;?>'>
			<?php
				echo EVO()->taxonomies->get_meta_box_content( 'event_location' ,$EVENT->ID, __('location','eventon'));
			?>
			</div>									
		</div>										
		<?php

			// if generate gmap enabled in settings
				$gen_gmap = (EVO()->cal->check_yn('evo_gen_map') && !$EVENT->check_yn('evcal_gmap_gen') ) ? true: false;

			// yea no options for location
			foreach(array(
				'evo_access_control_location'=>array('evo_access_control_location',__('Make location information only visible to logged-in users','eventon')),
				'evcal_hide_locname'=>array('evo_locname',__('Hide Location Name from Event Card','eventon')),
				'evcal_gmap_gen'=>array('evo_genGmap',__('Generate Google Map from the address','eventon')),
				'evcal_gmap_link'=>array('evo_gmapL',__('Show link to open address in google map','eventon')),
				'evcal_name_over_img'=>array('evcal_name_over_img',__('Show location information over location image (If location image exist)','eventon')),
			) as $key=>$val){

				$variable_val = $EVENT->get_prop($key)? $EVENT->get_prop($key): 'no';

				if($variable_val == 'no' && $gen_gmap && $key=='evcal_gmap_gen')
						$variable_val = 'yes';

				echo EVO()->elements->get_element(
					array(
						'type'=>'yesno_btn',
						'label'=> $val[1], 'id'=> $key,
						'value'=> $variable_val
					)
				);
			}

			// check google maps API key
			if( !EVO()->cal->get_prop('evo_gmap_api_key','evcal_1')){
				echo "<p class='evo_notice'>".__('Google Maps API key is required for maps to show on event. Please add them via ','eventon') ."<a href='". get_admin_url() .'admin.php?page=eventon#evcal_005'."'>".__('Settings','eventon'). "</a></p>";
			}
		?>									
	</div>
</div>
<?php