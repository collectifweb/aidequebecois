<?php

/*** CIMY USER EXTRA FIELDS ******************************************************************************************************/
function dud_load_cimy_vals($user_id, $dud_options, $user_directory_meta_flds_tmp)
{
		$dud_meta_addr_flds = array();
		$dud_meta_social_flds = array();
				
		$user_directory_addr_1_op    = !empty($dud_options['user_directory_addr_1']) ? $dud_options['user_directory_addr_1'] : "";
		$user_directory_addr_2_op    = !empty($dud_options['user_directory_addr_2']) ? $dud_options['user_directory_addr_2'] : "";
		$user_directory_city_op      = !empty($dud_options['user_directory_city']) ? $dud_options['user_directory_city'] : "";
		$user_directory_state_op     = !empty($dud_options['user_directory_state']) ? $dud_options['user_directory_state'] : "";
		$user_directory_zip_op       = !empty($dud_options['user_directory_zip']) ? $dud_options['user_directory_zip'] : "";
		$user_directory_country_op   = !empty($dud_options['user_directory_country']) ? $dud_options['user_directory_country'] : "";
		
		$ud_facebook_op    = !empty($dud_options['ud_facebook']) ? $dud_options['ud_facebook'] : "";
		$ud_twitter_op     = !empty($dud_options['ud_twitter']) ? $dud_options['ud_twitter'] : "";
		$ud_linkedin_op    = !empty($dud_options['ud_linkedin']) ? $dud_options['ud_linkedin'] : "";
		$ud_google_op      = !empty($dud_options['ud_google']) ? $dud_options['ud_google'] : "";
		$ud_instagram_op   = !empty($dud_options['ud_instagram']) ? $dud_options['ud_instagram'] : "";
		$ud_pinterest_op   = !empty($dud_options['ud_pinterest']) ? $dud_options['ud_pinterest'] : "";
		$ud_youtube_op     = !empty($dud_options['ud_youtube']) ? $dud_options['ud_youtube'] : "";
		$ud_tiktok_op      = !empty($dud_options['ud_tiktok']) ? $dud_options['ud_tiktok'] : "";
		$ud_podcast_op     = !empty($dud_options['ud_podcast']) ? $dud_options['ud_podcast'] : "";
		
	    $values = dynamic_ud_get_cimy_data($user_id, $user_directory_addr_1_op, $user_directory_addr_2_op, $user_directory_city_op,
	 			$user_directory_state_op, $user_directory_zip_op, $user_directory_country_op, $ud_facebook_op, $ud_twitter_op, $ud_linkedin_op, $ud_google_op, 
				$ud_instagram_op, $ud_pinterest_op, $ud_youtube_op, $ud_tiktok_op, $ud_podcast_op, $user_directory_meta_flds_tmp);
	 					
		if($values) 
		{
			foreach ($values as $value)
			{ 
				$meta_name = strtoupper ($value->NAME);
				
				if($value->TYPE === 'avatar') 
					$cimy_avatar_loc = $value->VALUE;	
				
				else if($user_directory_addr_1_op && $meta_name === strtoupper ($user_directory_addr_1_op)) 
					$dud_meta_addr_flds[0] = $value->VALUE;	
					 
				else if($user_directory_addr_2_op && $meta_name === strtoupper ($user_directory_addr_2_op)) 
					$dud_meta_addr_flds[1] = $value->VALUE;	
					  
				else if($user_directory_city_op && $meta_name === strtoupper ($user_directory_city_op)) 
					$dud_meta_addr_flds[2] = $value->VALUE;	
					 
				else if($user_directory_state_op && $meta_name === strtoupper ($user_directory_state_op)) 
					$dud_meta_addr_flds[3] = $value->VALUE;	
					 
				else if($user_directory_zip_op && $meta_name === strtoupper ($user_directory_zip_op)) 
					$dud_meta_addr_flds[4] = $value->VALUE;	
				
				else if($user_directory_country_op && $meta_name === strtoupper ($user_directory_country_op)) 
					$dud_meta_addr_flds[5] = $value->VALUE;	
								
				else if($ud_facebook_op && $meta_name === strtoupper ($ud_facebook_op)) 
					$dud_meta_social_flds[0] = $value->VALUE;	
				
				else if($ud_twitter_op && $meta_name === strtoupper ($ud_twitter_op)) 
					$dud_meta_social_flds[1] = $value->VALUE;
				
				else if($ud_linkedin_op && $meta_name === strtoupper ($ud_linkedin_op)) 
					$dud_meta_social_flds[2] = $value->VALUE;	
				
				else if($ud_google_op && $meta_name === strtoupper ($ud_google_op)) 
					$dud_meta_social_flds[3] = $value->VALUE;	
				
				else if($ud_pinterest_op && $meta_name === strtoupper ($ud_pinterest_op)) 
					$dud_meta_social_flds[4] = $value->VALUE;	
				
				else if($ud_instagram_op && $meta_name === strtoupper ($ud_instagram_op)) 
					$dud_meta_social_flds[5] = $value->VALUE;
				
				else if($ud_youtube_op && $meta_name === strtoupper ($ud_youtube_op)) 
					$dud_meta_social_flds[6] = $value->VALUE;
				
				else if($ud_tiktok_op && $meta_name === strtoupper ($ud_tiktok_op)) 
					$dud_meta_social_flds[7] = $value->VALUE;
				
				else if($ud_podcast_op && $meta_name === strtoupper ($ud_podcast_op)) 
					$dud_meta_social_flds[8] = $value->VALUE;
				
				else
				{					
					for($inc=0; $inc < sizeof($user_directory_meta_flds_tmp); $inc++ ) 
					{
						if($user_directory_meta_flds_tmp[$inc]['field'] 
							&& $meta_name === strtoupper ($user_directory_meta_flds_tmp[$inc]['field'])) 
						{
							$user_directory_meta_flds_tmp[$inc]['value'] 
								= dynamic_ud_format_meta_val($value->VALUE, $dud_options, $user_directory_meta_flds_tmp[$inc]['format'],
								$user_directory_meta_flds_tmp[$inc]['label']); 
								
							//currently Cimy doesn't appear to store arrays but we are ready if/when it does
							/*if(strlen($value->VALUE) > 2 && substr($value->VALUE, 0, 2) === "a:")
								$user_directory_meta_flds_tmp[$inc]['value'] =  implode(", ",(unserialize(stripslashes($value->VALUE))));	// json_decode() ?
							else
								$user_directory_meta_flds_tmp[$inc]['value'] =  dynamic_ud_parse_meta_val(stripslashes($value->VALUE));
							*/
							break;
						}						
					}
				}				 
			}			
		}

		if(!empty($dud_meta_addr_flds))
		{
			$end_of_array = sizeof($user_directory_meta_flds_tmp);
			
			$user_directory_meta_flds_tmp[$end_of_array]['field'] = "CIMY_ADDRESS";
			$user_directory_meta_flds_tmp[$end_of_array]['value'] = $dud_meta_addr_flds;	
		}

		if(!empty($dud_meta_social_flds))
		{
			$end_of_array = sizeof($user_directory_meta_flds_tmp);
			
			$user_directory_meta_flds_tmp[$end_of_array]['field'] = "CIMY_SOCIAL";
			$user_directory_meta_flds_tmp[$end_of_array]['value'] = $dud_meta_social_flds;	
		}			

		return $user_directory_meta_flds_tmp;
}

/*** Builds Cimy Tables Query Based on Dynamic User Directory Key Name Settings ***/
function dynamic_ud_get_cimy_data($id, $user_directory_addr_1_op, $user_directory_addr_2_op, $user_directory_city_op,
	 			$user_directory_state_op, $user_directory_zip_op, $user_directory_country_op, $ud_facebook_op, $ud_twitter_op, $ud_linkedin_op, $ud_google_op, $ud_instagram_op, $ud_pinterest_op, $ud_youtube_op, $ud_tiktok_op, $ud_podcast_op, $user_directory_meta_flds_tmp)
{
	global $wpdb;
	global $dynamic_ud_debug;
	
	if(defined("DUD_CIMY_DATA_TABLE") && defined("DUD_CIMY_FIELDS_TABLE"))
	{
		$ud_sql = "SELECT data.VALUE, efields.NAME, efields.TYPE FROM " . DUD_CIMY_DATA_TABLE . " as data JOIN " . DUD_CIMY_FIELDS_TABLE . 
				" as efields ON efields.id=data.field_id WHERE (";
		
		$was_prev_fld = 0;
		$values = null;
				
		if($user_directory_addr_1_op)   { $ud_sql .= "efields.NAME='". $user_directory_addr_1_op . "'"; $was_prev_fld = 1; }
		if($user_directory_addr_2_op)   { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $user_directory_addr_2_op . "'"; $was_prev_fld = 1; }
		if($user_directory_city_op)     { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $user_directory_city_op . "'"; $was_prev_fld = 1; }
		if($user_directory_state_op)    { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $user_directory_state_op . "'"; $was_prev_fld = 1; }
		if($user_directory_zip_op)      { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $user_directory_zip_op . "'"; $was_prev_fld = 1; }
		if($user_directory_country_op)  { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $user_directory_country_op . "'"; $was_prev_fld = 1; }
		
		if($ud_facebook_op)  { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $ud_facebook_op . "'"; $was_prev_fld = 1; }
		if($ud_twitter_op)   { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $ud_twitter_op . "'"; $was_prev_fld = 1; }
		if($ud_linkedin_op)  { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $ud_linkedin_op . "'"; $was_prev_fld = 1; }
		if($ud_google_op)    { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $ud_google_op . "'"; $was_prev_fld = 1; }
		if($ud_instagram_op) { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $ud_instagram_op . "'"; $was_prev_fld = 1; }
		if($ud_pinterest_op) { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $ud_pinterest_op . "'"; $was_prev_fld = 1; }
		if($ud_youtube_op)   { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $ud_youtube_op . "'"; $was_prev_fld = 1; }
		if($ud_tiktok_op)    { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $ud_tiktok_op . "'"; $was_prev_fld = 1; }
		if($ud_podcast_op)   { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.NAME='" . $ud_podcast_op . "'"; $was_prev_fld = 1; }
		
		foreach ( $user_directory_meta_flds_tmp as $ud_mflds )
		{
			if($ud_mflds['field']) { 
				if($was_prev_fld) { $ud_sql .= " OR "; } 
				$ud_sql .= "efields.NAME='" . $ud_mflds['field'] . "'"; 
				$was_prev_fld = 1; 
			}
		}
		
		if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.TYPE='avatar'";
		
		$ud_sql .= ") AND data.USER_ID = " . $id;
		
		//if($dynamic_ud_debug) { echo "<PRE>Cimy Load Meta Flds SQL:<BR><BR>" . $ud_sql . "<BR><BR></PRE>"; }
				
		return  $wpdb->get_results($ud_sql);
	}
	
	return null;
}

/*** Builds the HTML for displaying the Cimy avatar ***/
function dynamic_ud_get_cimy_avatar($id, $user_login, $atts, $img_style, $cimy_avatar_loc )
{
	if (isset($id)) 
	{			
		$dud_avatar_file_path        = dynamic_ud_before_last ('/', $cimy_avatar_loc);
		$dud_avatar_file_abs_path    = dynamic_ud_between_last ('wp-content', '/', $cimy_avatar_loc);
		$dud_avatar_file_name        = dynamic_ud_between_last ('/', '.', $cimy_avatar_loc);
		$dud_avatar_file_ext         = dynamic_ud_after_last ('.', $cimy_avatar_loc);
		$dud_avatar_thumb_abs_path   = ABSPATH . "wp-content" . $dud_avatar_file_abs_path . "/" . $dud_avatar_file_name . "-thumbnail." . $dud_avatar_file_ext;
		$dud_avatar_thumb_path       = $dud_avatar_file_path . "/" . $dud_avatar_file_name . "-thumbnail." . $dud_avatar_file_ext;
				
		if ($cimy_avatar_loc) 
		{					
			if(file_exists($dud_avatar_thumb_abs_path)) //use the thumbnail if it exists for quicker load
				return "<img alt='' src='{$dud_avatar_thumb_path}' class='avatar " . $img_style  . "' height='96px' width='96px' />";
			else
				return "<img alt='' src='{$cimy_avatar_loc}' class='avatar " . $img_style  . "' height='96px' width='96px' />";
		}
		else 
			return get_avatar($id, '', '', '', $atts);
	}
}

/*** BUDDY PRESS FIELDS ******************************************************************************************************/

function dud_load_bp_vals($user_id, $dud_options, $user_directory_meta_flds_tmp)
{
		$dud_meta_addr_flds = array();
		$dud_meta_social_flds = array();
		
		$user_directory_addr_1_op    = !empty($dud_options['user_directory_addr_1']) ? $dud_options['user_directory_addr_1'] : "";
		$user_directory_addr_2_op    = !empty($dud_options['user_directory_addr_2']) ? $dud_options['user_directory_addr_2'] : "";
		$user_directory_city_op      = !empty($dud_options['user_directory_city']) ? $dud_options['user_directory_city'] : "";
		$user_directory_state_op     = !empty($dud_options['user_directory_state']) ? $dud_options['user_directory_state'] : "";
		$user_directory_zip_op       = !empty($dud_options['user_directory_zip']) ? $dud_options['user_directory_zip'] : "";
		$user_directory_country_op   = !empty($dud_options['user_directory_country']) ? $dud_options['user_directory_country'] : "";
		
		$ud_facebook_op    = !empty($dud_options['ud_facebook']) ? $dud_options['ud_facebook'] : "";
		$ud_twitter_op     = !empty($dud_options['ud_twitter']) ? $dud_options['ud_twitter'] : "";
		$ud_linkedin_op    = !empty($dud_options['ud_linkedin']) ? $dud_options['ud_linkedin'] : "";
		$ud_google_op      = !empty($dud_options['ud_google']) ? $dud_options['ud_google'] : "";
		$ud_instagram_op   = !empty($dud_options['ud_instagram']) ? $dud_options['ud_instagram'] : "";
		$ud_pinterest_op   = !empty($dud_options['ud_pinterest']) ? $dud_options['ud_pinterest'] : "";
		$ud_youtube_op     = !empty($dud_options['ud_youtube']) ? $dud_options['ud_youtube'] : "";
		$ud_tiktok_op      = !empty($dud_options['ud_tiktok']) ? $dud_options['ud_tiktok'] : "";
		$ud_podcast_op     = !empty($dud_options['ud_podcast']) ? $dud_options['ud_podcast'] : "";
		
	    $values = dud_get_bp_data($user_id, $user_directory_addr_1_op, $user_directory_addr_2_op, $user_directory_city_op,
	 			$user_directory_state_op, $user_directory_zip_op, $user_directory_country_op, $ud_facebook_op, $ud_twitter_op, $ud_linkedin_op, $ud_google_op,
				$ud_instagram_op, $ud_pinterest_op, $ud_youtube_op, $ud_tiktok_op, $ud_podcast_op, $user_directory_meta_flds_tmp);
	 					
		if($values) 
		{
			foreach ($values as $value)
			{ 
				$meta_name = strtoupper ($value->name);
								
				if($user_directory_addr_1_op && $meta_name === strtoupper ($user_directory_addr_1_op)) 
					$dud_meta_addr_flds[0] = $value->value;	
					 
				else if($user_directory_addr_2_op && $meta_name === strtoupper ($user_directory_addr_2_op)) 
					$dud_meta_addr_flds[1] = $value->value;	
					  
				else if($user_directory_city_op && $meta_name === strtoupper ($user_directory_city_op)) 
					$dud_meta_addr_flds[2] = $value->value;	
					 
				else if($user_directory_state_op && $meta_name === strtoupper ($user_directory_state_op)) 
					$dud_meta_addr_flds[3] = $value->value;	
					 
				else if($user_directory_zip_op && $meta_name === strtoupper ($user_directory_zip_op)) 
					$dud_meta_addr_flds[4] = $value->value;	
				
				else if($user_directory_country_op && $meta_name === strtoupper ($user_directory_country_op)) 
					$dud_meta_addr_flds[5] = $value->value;	
				
				else if($ud_facebook_op && $meta_name === strtoupper ($ud_facebook_op)) 
					$dud_meta_social_flds[0] = $value->value;	
				
				else if($ud_twitter_op && $meta_name === strtoupper ($ud_twitter_op)) 
					$dud_meta_social_flds[1] = $value->value;
				
				else if($ud_linkedin_op && $meta_name === strtoupper ($ud_linkedin_op)) 
					$dud_meta_social_flds[2] = $value->value;	
				
				else if($ud_google_op && $meta_name === strtoupper ($ud_google_op)) 
					$dud_meta_social_flds[3] = $value->value;	
				
				else if($ud_pinterest_op && $meta_name === strtoupper ($ud_pinterest_op)) 
					$dud_meta_social_flds[4] = $value->value;	
				
				else if($ud_instagram_op && $meta_name === strtoupper ($ud_instagram_op)) 
					$dud_meta_social_flds[5] = $value->value;

				else if($ud_youtube_op && $meta_name === strtoupper ($ud_youtube_op)) 
					$dud_meta_social_flds[6] = $value->value;
				
				else if($ud_tiktok_op && $meta_name === strtoupper ($ud_tiktok_op)) 
					$dud_meta_social_flds[7] = $value->value;
				
				else if($ud_podcast_op && $meta_name === strtoupper ($ud_podcast_op)) 
					$dud_meta_social_flds[8] = $value->value;
				
				else
				{					
					for($inc=0; $inc < sizeof($user_directory_meta_flds_tmp); $inc++ ) 
					{
						if($user_directory_meta_flds_tmp[$inc]['field'] 
							&& $meta_name === strtoupper ($user_directory_meta_flds_tmp[$inc]['field'])) 
						{
							$user_directory_meta_flds_tmp[$inc]['value'] 
								= dynamic_ud_format_meta_val($value->value, $dud_options, $user_directory_meta_flds_tmp[$inc]['format'], $user_directory_meta_flds_tmp[$inc]['label']); 	
							/*if(strlen($value->value) > 2 && substr($value->value, 0, 2) === "a:")
								$user_directory_meta_flds_tmp[$inc]['value'] =  implode(", ",(unserialize(stripslashes($value->value))));
							else
								$user_directory_meta_flds_tmp[$inc]['value'] =  stripslashes(dynamic_ud_parse_meta_val($value->value));*/
							
							break;
						}						
					}
				}											 
			}

			if(!empty($dud_meta_addr_flds))
			{
				$end_of_array = sizeof($user_directory_meta_flds_tmp);
				
				$user_directory_meta_flds_tmp[$end_of_array]['field'] = "BP_ADDRESS";
				$user_directory_meta_flds_tmp[$end_of_array]['value'] = $dud_meta_addr_flds;	
			}	

			if(!empty($dud_meta_social_flds))
			{
				$end_of_array = sizeof($user_directory_meta_flds_tmp);
				
				$user_directory_meta_flds_tmp[$end_of_array]['field'] = "BP_SOCIAL";
				$user_directory_meta_flds_tmp[$end_of_array]['value'] = $dud_meta_social_flds;	
			}			
		}
			
		return $user_directory_meta_flds_tmp;
}

/*** Builds BuddyPress Tables Query Based on Dynamic User Directory Key Name Settings ***/
function dud_get_bp_data($id, $user_directory_addr_1_op, $user_directory_addr_2_op, $user_directory_city_op,
	 			$user_directory_state_op, $user_directory_zip_op, $user_directory_country_op, $ud_facebook_op, $ud_twitter_op, $ud_linkedin_op, $ud_google_op,
				$ud_instagram_op, $ud_pinterest_op, $ud_youtube_op, $ud_tiktok_op, $ud_podcast_op, $user_directory_meta_flds_tmp)
{
	global $wpdb;
	global $dynamic_ud_debug;
	
	if(defined("DUD_BP_PLUGIN_DATA_TABLE") && defined("DUD_BP_PLUGIN_FIELDS_TABLE"))
	{
		$ud_sql = "SELECT data.value, efields.name, efields.type FROM " . DUD_BP_PLUGIN_DATA_TABLE . " as data JOIN " . DUD_BP_PLUGIN_FIELDS_TABLE . 
				" as efields ON efields.id=data.field_id WHERE (";
		
		$was_prev_fld = 0;
		$values = null;
				
		if($user_directory_addr_1_op)   { $ud_sql .= "efields.name='". $user_directory_addr_1_op . "'"; $was_prev_fld = 1; }
		if($user_directory_addr_2_op)   { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $user_directory_addr_2_op . "'"; $was_prev_fld = 1; }
		if($user_directory_city_op)     { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $user_directory_city_op . "'"; $was_prev_fld = 1; }
		if($user_directory_state_op)    { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $user_directory_state_op . "'"; $was_prev_fld = 1; }
		if($user_directory_zip_op)      { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $user_directory_zip_op . "'"; $was_prev_fld = 1; }
		if($user_directory_country_op)  { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $user_directory_country_op . "'"; $was_prev_fld = 1; }
		
		if($ud_facebook_op)  { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $ud_facebook_op . "'"; $was_prev_fld = 1; }
		if($ud_twitter_op)   { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $ud_twitter_op . "'"; $was_prev_fld = 1; }
		if($ud_linkedin_op)  { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $ud_linkedin_op . "'"; $was_prev_fld = 1; }
		if($ud_google_op)    { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $ud_google_op . "'"; $was_prev_fld = 1; }
		if($ud_instagram_op) { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $ud_instagram_op . "'"; $was_prev_fld = 1; }
		if($ud_pinterest_op) { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $ud_pinterest_op . "'"; $was_prev_fld = 1; }
		if($ud_youtube_op)   { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $ud_youtube_op . "'"; $was_prev_fld = 1; }
		if($ud_tiktok_op)    { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $ud_tiktok_op . "'"; $was_prev_fld = 1; }
		if($ud_podcast_op)   { if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.name='" . $ud_podcast_op . "'"; $was_prev_fld = 1; }
		
		foreach ( $user_directory_meta_flds_tmp as $ud_mflds )
		{
			if($ud_mflds['field']) { 
				if($was_prev_fld) { $ud_sql .= " OR "; } 
				$ud_sql .= "efields.name='" . $ud_mflds['field'] . "'"; 
				$was_prev_fld = 1; 
			}
		}
		
		if($was_prev_fld) { $ud_sql .= " OR "; } $ud_sql .= "efields.type='avatar'";
		
		$ud_sql .= ") AND data.user_id = " . $id;
		
		//if($dynamic_ud_debug) { echo "<PRE>BuddyPress Load Meta Flds SQL:<BR><BR>" . $ud_sql . "<BR><BR></PRE>"; }
		
		//echo "<PRE>BuddyPress Load Meta Flds SQL:<BR><BR>" . $ud_sql . "<BR><BR></PRE>";
		
		return  $wpdb->get_results($ud_sql);
	}
	
	return null;
}

/*** S2MEMBER FIELDS ******************************************************************************************************/

function dud_load_s2m_vals($user_id, $dud_options, $user_directory_meta_flds_tmp)
{
	global $wpdb;
	
	$dud_meta_addr_flds = array();
	$dud_meta_social_flds = array();
	
	$user_directory_addr_1_op    = !empty($dud_options['user_directory_addr_1']) ? $dud_options['user_directory_addr_1'] : "";
	$user_directory_addr_2_op    = !empty($dud_options['user_directory_addr_2']) ? $dud_options['user_directory_addr_2'] : "";
	$user_directory_city_op      = !empty($dud_options['user_directory_city']) ? $dud_options['user_directory_city'] : "";
	$user_directory_state_op     = !empty($dud_options['user_directory_state']) ? $dud_options['user_directory_state'] : "";
	$user_directory_zip_op       = !empty($dud_options['user_directory_zip']) ? $dud_options['user_directory_zip'] : "";
	$user_directory_country_op   = !empty($dud_options['user_directory_country']) ? $dud_options['user_directory_country'] : "";
	
	$ud_facebook_op    = !empty($dud_options['ud_facebook']) ? $dud_options['ud_facebook'] : "";
	$ud_twitter_op     = !empty($dud_options['ud_twitter']) ? $dud_options['ud_twitter'] : "";
	$ud_linkedin_op    = !empty($dud_options['ud_linkedin']) ? $dud_options['ud_linkedin'] : "";
	$ud_google_op      = !empty($dud_options['ud_google']) ? $dud_options['ud_google'] : "";
	$ud_instagram_op   = !empty($dud_options['ud_instagram']) ? $dud_options['ud_instagram'] : "";
	$ud_pinterest_op   = !empty($dud_options['ud_pinterest']) ? $dud_options['ud_pinterest'] : "";
	$ud_youtube_op     = !empty($dud_options['ud_youtube']) ? $dud_options['ud_youtube'] : "";
	$ud_tiktok_op      = !empty($dud_options['ud_tiktok']) ? $dud_options['ud_tiktok'] : "";
	$ud_podcast_op     = !empty($dud_options['ud_podcast']) ? $dud_options['ud_podcast'] : "";
	
	$s2m_custom_flds = get_user_meta($user_id, DUD_WPDB_PREFIX . 's2member_custom_fields');
	$s2m_custom_flds = !empty($s2m_custom_flds[0]) ? $s2m_custom_flds[0] : null; //it will always be an array even for single values
	
	if(!empty($s2m_custom_flds)) 
	{
		foreach ($s2m_custom_flds as $key => $value) 
		{ 
			$key = strtoupper ($key);
			
			if($user_directory_addr_1_op && $key === strtoupper($user_directory_addr_1_op)) 
				$dud_meta_addr_flds[0] = $value;	
				 
			else if($user_directory_addr_2_op && $key === strtoupper($user_directory_addr_2_op)) 
				$dud_meta_addr_flds[1] = $value;	
				  
			else if($user_directory_city_op && $key === strtoupper($user_directory_city_op)) 
				$dud_meta_addr_flds[2] = $value;	
				 
			else if($user_directory_state_op && $key === strtoupper($user_directory_state_op)) 
				$dud_meta_addr_flds[3] = $value;	
				 
			else if($user_directory_zip_op && $key === strtoupper($user_directory_zip_op)) 
				$dud_meta_addr_flds[4] = $value;

			else if($user_directory_country_op && $key === strtoupper($user_directory_country_op)) 
				$dud_meta_addr_flds[5] = $value;
			
			else if($ud_facebook_op && $key === strtoupper ($ud_facebook_op)) 
				$dud_meta_social_flds[0] = $value;	
				
			else if($ud_twitter_op && $key === strtoupper ($ud_twitter_op)) 
				$dud_meta_social_flds[1] = $value;
			
			else if($ud_linkedin_op && $key === strtoupper ($ud_linkedin_op)) 
				$dud_meta_social_flds[2] = $value;	
			
			else if($ud_google_op && $key === strtoupper ($ud_google_op)) 
				$dud_meta_social_flds[3] = $value;	
			
			else if($ud_pinterest_op && $key === strtoupper ($ud_pinterest_op)) 
				$dud_meta_social_flds[4] = $value;	
			
			else if($ud_instagram_op && $key === strtoupper ($ud_instagram_op)) 
				$dud_meta_social_flds[5] = $value;
			
			else if($ud_youtube_op && $key === strtoupper ($ud_youtube_op)) 
				$dud_meta_social_flds[6] = $value;
			
			else if($ud_tiktok_op && $key === strtoupper ($ud_tiktok_op)) 
				$dud_meta_social_flds[7] = $value;
			
			else if($ud_podcast_op && $key === strtoupper ($ud_podcast_op)) 
				$dud_meta_social_flds[8] = $value;
			
			else
			{					
				for($inc=0; $inc < sizeof($user_directory_meta_flds_tmp); $inc++ ) 
				{
					if($user_directory_meta_flds_tmp[$inc]['field'] 
						&& $key === strtoupper($user_directory_meta_flds_tmp[$inc]['field'])) 
					{
						$user_directory_meta_flds_tmp[$inc]['value'] 
								= dynamic_ud_format_meta_val($value, $dud_options, $user_directory_meta_flds_tmp[$inc]['format'], $user_directory_meta_flds_tmp[$inc]['label']); 
								
						/*if(is_array($value))
							$user_directory_meta_flds_tmp[$inc]['value'] =  implode(", ", $value);	
						else
							$user_directory_meta_flds_tmp[$inc]['value'] =  stripslashes($value);*/
						
						break;
					}						
				}
			}				 
		}			
	}

	if(!empty($dud_meta_addr_flds))
	{
		$end_of_array = sizeof($user_directory_meta_flds_tmp);
		
		$user_directory_meta_flds_tmp[$end_of_array]['field'] = "S2M_ADDRESS";
		$user_directory_meta_flds_tmp[$end_of_array]['value'] = $dud_meta_addr_flds;	
	}

	if(!empty($dud_meta_social_flds))
	{
		$end_of_array = sizeof($user_directory_meta_flds_tmp);
		
		$user_directory_meta_flds_tmp[$end_of_array]['field'] = "S2M_SOCIAL";
		$user_directory_meta_flds_tmp[$end_of_array]['value'] = $dud_meta_social_flds;	
	}			

	return $user_directory_meta_flds_tmp;
}

function getCountryName($code) {
   
   // Convert input to uppercase to handle case-insensitive codes
    if(is_null($code)) return null;
	
	$code = strtoupper(trim($code));
    
    // Array mapping ISO 3166-1 alpha-2 and alpha-3 codes to country names
    $countries = [
        'AD' => ['name' => 'Andorra', 'alpha3' => 'AND'],
        'AE' => ['name' => 'United Arab Emirates', 'alpha3' => 'ARE'],
        'AF' => ['name' => 'Afghanistan', 'alpha3' => 'AFG'],
        'AG' => ['name' => 'Antigua and Barbuda', 'alpha3' => 'ATG'],
        'AI' => ['name' => 'Anguilla', 'alpha3' => 'AIA'],
        'AL' => ['name' => 'Albania', 'alpha3' => 'ALB'],
        'AM' => ['name' => 'Armenia', 'alpha3' => 'ARM'],
        'AO' => ['name' => 'Angola', 'alpha3' => 'AGO'],
        'AQ' => ['name' => 'Antarctica', 'alpha3' => 'ATA'],
        'AR' => ['name' => 'Argentina', 'alpha3' => 'ARG'],
        'AS' => ['name' => 'American Samoa', 'alpha3' => 'ASM'],
        'AT' => ['name' => 'Austria', 'alpha3' => 'AUT'],
        'AU' => ['name' => 'Australia', 'alpha3' => 'AUS'],
        'AW' => ['name' => 'Aruba', 'alpha3' => 'ABW'],
        'AX' => ['name' => 'Åland Islands', 'alpha3' => 'ALA'],
        'AZ' => ['name' => 'Azerbaijan', 'alpha3' => 'AZE'],
        'BA' => ['name' => 'Bosnia and Herzegovina', 'alpha3' => 'BIH'],
        'BB' => ['name' => 'Barbados', 'alpha3' => 'BRB'],
        'BD' => ['name' => 'Bangladesh', 'alpha3' => 'BGD'],
        'BE' => ['name' => 'Belgium', 'alpha3' => 'BEL'],
        'BF' => ['name' => 'Burkina Faso', 'alpha3' => 'BFA'],
        'BG' => ['name' => 'Bulgaria', 'alpha3' => 'BGR'],
        'BH' => ['name' => 'Bahrain', 'alpha3' => 'BHR'],
        'BI' => ['name' => 'Burundi', 'alpha3' => 'BDI'],
        'BJ' => ['name' => 'Benin', 'alpha3' => 'BEN'],
        'BL' => ['name' => 'Saint Barthélemy', 'alpha3' => 'BLM'],
        'BM' => ['name' => 'Bermuda', 'alpha3' => 'BMU'],
        'BN' => ['name' => 'Brunei Darussalam', 'alpha3' => 'BRN'],
        'BO' => ['name' => 'Bolivia', 'alpha3' => 'BOL'],
        'BQ' => ['name' => 'Bonaire, Sint Eustatius and Saba', 'alpha3' => 'BES'],
        'BR' => ['name' => 'Brazil', 'alpha3' => 'BRA'],
        'BS' => ['name' => 'Bahamas', 'alpha3' => 'BHS'],
        'BT' => ['name' => 'Bhutan', 'alpha3' => 'BTN'],
        'BV' => ['name' => 'Bouvet Island', 'alpha3' => 'BVT'],
        'BW' => ['name' => 'Botswana', 'alpha3' => 'BWA'],
        'BY' => ['name' => 'Belarus', 'alpha3' => 'BLR'],
        'BZ' => ['name' => 'Belize', 'alpha3' => 'BLZ'],
        'CA' => ['name' => 'Canada', 'alpha3' => 'CAN'],
        'CC' => ['name' => 'Cocos (Keeling) Islands', 'alpha3' => 'CCK'],
        'CD' => ['name' => 'Congo, Democratic Republic of the', 'alpha3' => 'COD'],
        'CF' => ['name' => 'Central African Republic', 'alpha3' => 'CAF'],
        'CG' => ['name' => 'Congo', 'alpha3' => 'COG'],
        'CH' => ['name' => 'Switzerland', 'alpha3' => 'CHE'],
        'CI' => ['name' => 'Côte d\'Ivoire', 'alpha3' => 'CIV'],
        'CK' => ['name' => 'Cook Islands', 'alpha3' => 'COK'],
        'CL' => ['name' => 'Chile', 'alpha3' => 'CHL'],
        'CM' => ['name' => 'Cameroon', 'alpha3' => 'CMR'],
        'CN' => ['name' => 'China', 'alpha3' => 'CHN'],
        'CO' => ['name' => 'Colombia', 'alpha3' => 'COL'],
        'CR' => ['name' => 'Costa Rica', 'alpha3' => 'CRI'],
        'CU' => ['name' => 'Cuba', 'alpha3' => 'CUB'],
        'CV' => ['name' => 'Cabo Verde', 'alpha3' => 'CPV'],
        'CW' => ['name' => 'Curaçao', 'alpha3' => 'CUW'],
        'CX' => ['name' => 'Christmas Island', 'alpha3' => 'CXR'],
        'CY' => ['name' => 'Cyprus', 'alpha3' => 'CYP'],
        'CZ' => ['name' => 'Czechia', 'alpha3' => 'CZE'],
        'DE' => ['name' => 'Germany', 'alpha3' => 'DEU'],
        'DJ' => ['name' => 'Djibouti', 'alpha3' => 'DJI'],
        'DK' => ['name' => 'Denmark', 'alpha3' => 'DNK'],
        'DM' => ['name' => 'Dominica', 'alpha3' => 'DMA'],
        'DO' => ['name' => 'Dominican Republic', 'alpha3' => 'DOM'],
        'DZ' => ['name' => 'Algeria', 'alpha3' => 'DZA'],
        'EC' => ['name' => 'Ecuador', 'alpha3' => 'ECU'],
        'EE' => ['name' => 'Estonia', 'alpha3' => 'EST'],
        'EG' => ['name' => 'Egypt', 'alpha3' => 'EGY'],
        'EH' => ['name' => 'Western Sahara', 'alpha3' => 'ESH'],
        'ER' => ['name' => 'Eritrea', 'alpha3' => 'ERI'],
        'ES' => ['name' => 'Spain', 'alpha3' => 'ESP'],
        'ET' => ['name' => 'Ethiopia', 'alpha3' => 'ETH'],
        'FI' => ['name' => 'Finland', 'alpha3' => 'FIN'],
        'FJ' => ['name' => 'Fiji', 'alpha3' => 'FJI'],
        'FK' => ['name' => 'Falkland Islands (Malvinas)', 'alpha3' => 'FLK'],
        'FM' => ['name' => 'Micronesia (Federated States of)', 'alpha3' => 'FSM'],
        'FO' => ['name' => 'Faroe Islands', 'alpha3' => 'FRO'],
        'FR' => ['name' => 'France', 'alpha3' => 'FRA'],
        'GA' => ['name' => 'Gabon', 'alpha3' => 'GAB'],
        'GB' => ['name' => 'United Kingdom', 'alpha3' => 'GBR'],
        'GD' => ['name' => 'Grenada', 'alpha3' => 'GRD'],
        'GE' => ['name' => 'Georgia', 'alpha3' => 'GEO'],
        'GF' => ['name' => 'French Guiana', 'alpha3' => 'GUF'],
        'GG' => ['name' => 'Guernsey', 'alpha3' => 'GGY'],
        'GH' => ['name' => 'Ghana', 'alpha3' => 'GHA'],
        'GI' => ['name' => 'Gibraltar', 'alpha3' => 'GIB'],
        'GL' => ['name' => 'Greenland', 'alpha3' => 'GRL'],
        'GM' => ['name' => 'Gambia', 'alpha3' => 'GMB'],
        'GN' => ['name' => 'Guinea', 'alpha3' => 'GIN'],
        'GP' => ['name' => 'Guadeloupe', 'alpha3' => 'GLP'],
        'GQ' => ['name' => 'Equatorial Guinea', 'alpha3' => 'GNQ'],
        'GR' => ['name' => 'Greece', 'alpha3' => 'GRC'],
        'GS' => ['name' => 'South Georgia and the South Sandwich Islands', 'alpha3' => 'SGS'],
        'GT' => ['name' => 'Guatemala', 'alpha3' => 'GTM'],
        'GU' => ['name' => 'Guam', 'alpha3' => 'GUM'],
        'GW' => ['name' => 'Guinea-Bissau', 'alpha3' => 'GNB'],
        'GY' => ['name' => 'Guyana', 'alpha3' => 'GUY'],
        'HK' => ['name' => 'Hong Kong', 'alpha3' => 'HKG'],
        'HM' => ['name' => 'Heard Island and McDonald Islands', 'alpha3' => 'HMD'],
        'HN' => ['name' => 'Honduras', 'alpha3' => 'HND'],
        'HR' => ['name' => 'Croatia', 'alpha3' => 'HRV'],
        'HT' => ['name' => 'Haiti', 'alpha3' => 'HTI'],
        'HU' => ['name' => 'Hungary', 'alpha3' => 'HUN'],
        'ID' => ['name' => 'Indonesia', 'alpha3' => 'IDN'],
        'IE' => ['name' => 'Ireland', 'alpha3' => 'IRL'],
        'IL' => ['name' => 'Israel', 'alpha3' => 'ISR'],
        'IM' => ['name' => 'Isle of Man', 'alpha3' => 'IMN'],
        'IN' => ['name' => 'India', 'alpha3' => 'IND'],
        'IO' => ['name' => 'British Indian Ocean Territory', 'alpha3' => 'IOT'],
        'IQ' => ['name' => 'Iraq', 'alpha3' => 'IRQ'],
        'IR' => ['name' => 'Iran', 'alpha3' => 'IRN'],
        'IS' => ['name' => 'Iceland', 'alpha3' => 'ISL'],
        'IT' => ['name' => 'Italy', 'alpha3' => 'ITA'],
        'JE' => ['name' => 'Jersey', 'alpha3' => 'JEY'],
        'JM' => ['name' => 'Jamaica', 'alpha3' => 'JAM'],
        'JO' => ['name' => 'Jordan', 'alpha3' => 'JOR'],
        'JP' => ['name' => 'Japan', 'alpha3' => 'JPN'],
        'KE' => ['name' => 'Kenya', 'alpha3' => 'KEN'],
        'KG' => ['name' => 'Kyrgyzstan', 'alpha3' => 'KGZ'],
        'KH' => ['name' => 'Cambodia', 'alpha3' => 'KHM'],
        'KI' => ['name' => 'Kiribati', 'alpha3' => 'KIR'],
        'KM' => ['name' => 'Comoros', 'alpha3' => 'COM'],
        'KN' => ['name' => 'Saint Kitts and Nevis', 'alpha3' => 'KNA'],
        'KP' => ['name' => 'North Korea', 'alpha3' => 'PRK'],
        'KR' => ['name' => 'South Korea', 'alpha3' => 'KOR'],
        'KW' => ['name' => 'Kuwait', 'alpha3' => 'KWT'],
        'KY' => ['name' => 'Cayman Islands', 'alpha3' => 'CYM'],
        'KZ' => ['name' => 'Kazakhstan', 'alpha3' => 'KAZ'],
        'LA' => ['name' => 'Laos', 'alpha3' => 'LAO'],
        'LB' => ['name' => 'Lebanon', 'alpha3' => 'LBN'],
        'LC' => ['name' => 'Saint Lucia', 'alpha3' => 'LCA'],
        'LI' => ['name' => 'Liechtenstein', 'alpha3' => 'LIE'],
        'LK' => ['name' => 'Sri Lanka', 'alpha3' => 'LKA'],
        'LR' => ['name' => 'Liberia', 'alpha3' => 'LBR'],
        'LX' => ['name' => 'Luxembourg', 'alpha3' => 'LUX'],
        'LT' => ['name' => 'Lithuania', 'alpha3' => 'LTU'],
        'LU' => ['name' => 'Luxembourg', 'alpha3' => 'LUX'],
        'LV' => ['name' => 'Latvia', 'alpha3' => 'LVA'],
        'LY' => ['name' => 'Libya', 'alpha3' => 'LBY'],
        'MA' => ['name' => 'Morocco', 'alpha3' => 'MAR'],
        'MC' => ['name' => 'Monaco', 'alpha3' => 'MCO'],
        'MD' => ['name' => 'Moldova', 'alpha3' => 'MDA'],
        'ME' => ['name' => 'Montenegro', 'alpha3' => 'MNE'],
        'MF' => ['name' => 'Saint Martin (French part)', 'alpha3' => 'MAF'],
        'MG' => ['name' => 'Madagascar', 'alpha3' => 'MDG'],
        'MH' => ['name' => 'Marshall Islands', 'alpha3' => 'MHL'],
        'MK' => ['name' => 'North Macedonia', 'alpha3' => 'MKD'],
        'ML' => ['name' => 'Mali', 'alpha3' => 'MLI'],
        'MM' => ['name' => 'Myanmar', 'alpha3' => 'MMR'],
        'MN' => ['name' => 'Mongolia', 'alpha3' => 'MNG'],
        'MO' => ['name' => 'Macao', 'alpha3' => 'MAC'],
        'MP' => ['name' => 'Northern Mariana Islands', 'alpha3' => 'MNP'],
        'MQ' => ['name' => 'Martinique', 'alpha3' => 'MTQ'],
        'MR' => ['name' => 'Mauritania', 'alpha3' => 'MRT'],
        'MS' => ['name' => 'Montserrat', 'alpha3' => 'MSR'],
        'MT' => ['name' => 'Malta', 'alpha3' => 'MLT'],
        'MU' => ['name' => 'Mauritius', 'alpha3' => 'MUS'],
        'MV' => ['name' => 'Maldives', 'alpha3' => 'MDV'],
        'MW' => ['name' => 'Malawi', 'alpha3' => 'MWI'],
        'MX' => ['name' => 'Mexico', 'alpha3' => 'MEX'],
        'MY' => ['name' => 'Malaysia', 'alpha3' => 'MYS'],
        'MZ' => ['name' => 'Mozambique', 'alpha3' => 'MOZ'],
        'NA' => ['name' => 'Namibia', 'alpha3' => 'NAM'],
        'NC' => ['name' => 'New Caledonia', 'alpha3' => 'NCL'],
        'NE' => ['name' => 'Niger', 'alpha3' => 'NER'],
        'NF' => ['name' => 'Norfolk Island', 'alpha3' => 'NFK'],
        'NG' => ['name' => 'Nigeria', 'alpha3' => 'NGA'],
        'NI' => ['name' => 'Nicaragua', 'alpha3' => 'NIC'],
        'NL' => ['name' => 'Netherlands', 'alpha3' => 'NLD'],
        'NO' => ['name' => 'Norway', 'alpha3' => 'NOR'],
        'NP' => ['name' => 'Nepal', 'alpha3' => 'NPL'],
        'NR' => ['name' => 'Nauru', 'alpha3' => 'NRU'],
        'NU' => ['name' => 'Niue', 'alpha3' => 'NIU'],
        'NZ' => ['name' => 'New Zealand', 'alpha3' => 'NZL'],
        'OM' => ['name' => 'Oman', 'alpha3' => 'OMN'],
        'PA' => ['name' => 'Panama', 'alpha3' => 'PAN'],
        'PE' => ['name' => 'Peru', 'alpha3' => 'PER'],
        'PF' => ['name' => 'French Polynesia', 'alpha3' => 'PYF'],
        'PG' => ['name' => 'Papua New Guinea', 'alpha3' => 'PNG'],
        'PH' => ['name' => 'Philippines', 'alpha3' => 'PHL'],
        'PK' => ['name' => 'Pakistan', 'alpha3' => 'PAK'],
        'PL' => ['name' => 'Poland', 'alpha3' => 'POL'],
        'PM' => ['name' => 'Saint Pierre and Miquelon', 'alpha3' => 'SPM'],
        'PN' => ['name' => 'Pitcairn', 'alpha3' => 'PCN'],
        'PR' => ['name' => 'Puerto Rico', 'alpha3' => 'PRI'],
        'PS' => ['name' => 'Palestine, State of', 'alpha3' => 'PSE'],
        'PT' => ['name' => 'Portugal', 'alpha3' => 'PRT'],
        'PW' => ['name' => 'Palau', 'alpha3' => 'PLW'],
        'PY' => ['name' => 'Paraguay', 'alpha3' => 'PRY'],
        'QA' => ['name' => 'Qatar', 'alpha3' => 'QAT'],
        'RE' => ['name' => 'Réunion', 'alpha3' => 'REU'],
        'RO' => ['name' => 'Romania', 'alpha3' => 'ROU'],
        'RS' => ['name' => 'Serbia', 'alpha3' => 'SRB'],
        'RU' => ['name' => 'Russia', 'alpha3' => 'RUS'],
        'RW' => ['name' => 'Rwanda', 'alpha3' => 'RWA'],
        'SA' => ['name' => 'Saudi Arabia', 'alpha3' => 'SAU'],
        'SB' => ['name' => 'Solomon Islands', 'alpha3' => 'SLB'],
        'SC' => ['name' => 'Seychelles', 'alpha3' => 'SYC'],
        'SD' => ['name' => 'Sudan', 'alpha3' => 'SDN'],
        'SE' => ['name' => 'Sweden', 'alpha3' => 'SWE'],
        'SG' => ['name' => 'Singapore', 'alpha3' => 'SGP'],
        'SH' => ['name' => 'Saint Helena, Ascension and Tristan da Cunha', 'alpha3' => 'SHN'],
        'SI' => ['name' => 'Slovenia', 'alpha3' => 'SVN'],
        'SJ' => ['name' => 'Svalbard and Jan Mayen', 'alpha3' => 'SJM'],
        'SK' => ['name' => 'Slovakia', 'alpha3' => 'SVK'],
        'SL' => ['name' => 'Sierra Leone', 'alpha3' => 'SLE'],
        'SM' => ['name' => 'San Marino', 'alpha3' => 'SMR'],
        'SN' => ['name' => 'Senegal', 'alpha3' => 'SEN'],
        'SO' => ['name' => 'Somalia', 'alpha3' => 'SOM'],
        'SR' => ['name' => 'Suriname', 'alpha3' => 'SUR'],
        'SS' => ['name' => 'South Sudan', 'alpha3' => 'SSD'],
        'ST' => ['name' => 'Sao Tome and Principe', 'alpha3' => 'STP'],
        'SV' => ['name' => 'El Salvador', 'alpha3' => 'SLV'],
        'SX' => ['name' => 'Sint Maarten (Dutch part)', 'alpha3' => 'SXM'],
        'SY' => ['name' => 'Syria', 'alpha3' => 'SYR'],
        'SZ' => ['name' => 'Eswatini', 'alpha3' => 'SWZ'],
        'TC' => ['name' => 'Turks and Caicos Islands', 'alpha3' => 'TCA'],
        'TD' => ['name' => 'Chad', 'alpha3' => 'TCD'],
        'TF' => ['name' => 'French Southern Territories', 'alpha3' => 'ATF'],
        'TG' => ['name' => 'Togo', 'alpha3' => 'TGO'],
        'TH' => ['name' => 'Thailand', 'alpha3' => 'THA'],
        'TJ' => ['name' => 'Tajikistan', 'alpha3' => 'TJK'],
        'TK' => ['name' => 'Tokelau', 'alpha3' => 'TKL'],
        'TL' => ['name' => 'Timor-Leste', 'alpha3' => 'TLS'],
        'TM' => ['name' => 'Turkmenistan', 'alpha3' => 'TKM'],
        'TN' => ['name' => 'Tunisia', 'alpha3' => 'TUN'],
        'TO' => ['name' => 'Tonga', 'alpha3' => 'TON'],
        'TR' => ['name' => 'Turkey', 'alpha3' => 'TUR'],
        'TT' => ['name' => 'Trinidad and Tobago', 'alpha3' => 'TTO'],
        'TV' => ['name' => 'Tuvalu', 'alpha3' => 'TUV'],
        'TW' => ['name' => 'Taiwan', 'alpha3' => 'TWN'],
        'TZ' => ['name' => 'Tanzania', 'alpha3' => 'TZA'],
        'UA' => ['name' => 'Ukraine', 'alpha3' => 'UKR'],
        'UG' => ['name' => 'Uganda', 'alpha3' => 'UGA'],
        'UM' => ['name' => 'United States Minor Outlying Islands', 'alpha3' => 'UMI'],
        'US' => ['name' => 'United States', 'alpha3' => 'USA'],
        'UY' => ['name' => 'Uruguay', 'alpha3' => 'URY'],
        'UZ' => ['name' => 'Uzbekistan', 'alpha3' => 'UZB'],
        'VA' => ['name' => 'Holy See', 'alpha3' => 'VAT'],
        'VC' => ['name' => 'Saint Vincent and the Grenadines', 'alpha3' => 'VCT'],
        'VE' => ['name' => 'Venezuela', 'alpha3' => 'VEN'],
        'VG' => ['name' => 'Virgin Islands (British)', 'alpha3' => 'VGB'],
        'VI' => ['name' => 'Virgin Islands (U.S.)', 'alpha3' => 'VIR'],
        'VN' => ['name' => 'Vietnam', 'alpha3' => 'VNM'],
        'VU' => ['name' => 'Vanuatu', 'alpha3' => 'VUT'],
        'WF' => ['name' => 'Wallis and Futuna', 'alpha3' => 'WLF'],
        'WS' => ['name' => 'Samoa', 'alpha3' => 'WSM'],
        'YE' => ['name' => 'Yemen', 'alpha3' => 'YEM'],
        'YT' => ['name' => 'Mayotte', 'alpha3' => 'MYT'],
        'ZA' => ['name' => 'South Africa', 'alpha3' => 'ZAF'],
        'ZM' => ['name' => 'Zambia', 'alpha3' => 'ZMB'],
        'ZW' => ['name' => 'Zimbabwe', 'alpha3' => 'ZWE'],
    ];

    // Check if the code is a 2-character code
    if (strlen($code) == 2 && isset($countries[$code])) {
        return $countries[$code]['name'];
    }

    // Check if the code is a 3-character code
    if (strlen($code) == 3) {
        foreach ($countries as $country) {
            if ($country['alpha3'] === $code) {
                return $country['name'];
            }
        }
    }

    // Return null if the code is invalid
    return null;
}

