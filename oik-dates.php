<?php
/**
Plugin Name: oik-dates
Depends: oik base plugin, oik fields
Plugin URI: https://www.oik-plugins.com/oik-plugins/oik-dates
Description: Implements date based field types for oik-fields 
Version: 0.2.1
Author: bobbingwide
Author URI: https://bobbingwide.com/about-bobbing-wide
License: GPL2

    Copyright 2014-2020, 2023 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

/**
 * Implements action "oik_pre_theme_field"
 *
 * The dates field is only required when we intend to actually display the field
 *
 */
function oikd8_pre_theme_field() {
  oik_require( "includes/oik-dates.inc", "oik-dates" );
} 

/**
 * Implements action "oik_pre_form_field"
 *
 * The dates field is only required when we intend to actually to set a new value for the field
 *
 */
function oikd8_pre_form_field() {
  oik_require( "includes/oik-dates.inc", "oik-dates" );
} 

/**
 * Validate a dates field
 *
 * We only check for is_numeric() so that we can support half dates **?** 2013/07/23
 * 
 * @param string $value - the field value
 * @param string $field - the field name
 * @param array $data - array of data about the field   
 */
function oikd8_field_validation_date( $value, $field, $data ) {
  // bw_trace2();
  if ( $value ) {
    $preg_match = preg_match( '!\d{4}-\d{2}-\d{2}!', $value );
    //$numeric = is_numeric( $value );
    if ( !$preg_match ) {
      $text = sprintf( __( "Invalid %s" ), $data['#title'] );     
      bw_issue_message( $field, "non_numeric", $text, "error" );
    }
  }       
  return( $value );   
}

/**
 * Implement "oik_query_field_types" for oik-dates
 * 
 */
function oikd8_query_field_types( $field_types ) {
  $field_types['dates'] = __( "date", 'oik-dates' ); 
  $field_types['time'] = __( "time", 'oik-dates' ); 
  $field_types['timestamp'] = __( "timestamp integer", 'oik-dates' ); 
  return( $field_types );
}

/**
 * Adjust a date using PHP's date_add() function 
 *
 * This function can be used to apply date adjustments such as
 *
 * <pre>
 * +1 year
 * +1 year 6 months
 * +2 years
 * </pre>
 *
 * @use date_interval_create_from_date_string() (PHP 5.3 and above)
 * 
 * @link http://uk3.php.net/manual/en/datetime.formats.relative.php 
 *
 * @param string $adjustment - the date adjustment to apply
 * @param string $date - date to adjust
 * @param string $format - the required format for the new date
 * @return string the new date
 */
if( !function_exists( "bw_date_adjust" ) ) {
function bw_date_adjust( $adjustment="1 year", $date='now', $format='Y-m-d' ) {
  $adate = date_create( $date );
  date_add( $adate, date_interval_create_from_date_string( $adjustment ));
  return( date_format( $adate, $format ) );
}
}

/**
 * Determine a default value given the supplied meta_value and any other fields in $atts
 * 
 * For field type "date" we may want to look at the meta_compare parameter to see if that helps us decide what value to use.
 * 
 * @TODO - complete table
 *
 * Field type  meta_value   return meta_value
 * ----------  -----------  -----------------
 * date        .|now|today  current date format Y-m-d
 * date        0            0
 * date        other        current date adjusted by meta_value
 */
function oikd8_default_meta_value_date( $meta_value, $atts ) {
  switch ( $meta_value ) {
    case null:
    case '.':
    case "now":
    case "today":
      $meta_value = date( "Y-m-d" );
      break;
      
    case 0:
      // 
      break;
    
    default: 
      bw_trace2( $meta_value, "meta_value before" );
     
      $meta_value = bw_date_adjust( $meta_value, 'now', "Y-m-d" );
      
      bw_trace2( $meta_value, "meta_value after" );
      break;
  }
  bw_trace2();   
  return( $meta_value );
}

/**
 * Implement "oik_loaded" action for oik-dates
 *
 */
function oikd8_oik_loaded() {
  bw_add_shortcode( "bw_otd", "bw_otd", oik_path( "shortcodes/oik-otd.php", "oik-dates" ), false );
}  
 
/**
 * Perform initialisation when plugin file loaded 
 *
 * This plugin doesn't really need to do anything until someone requests a "dates" field to be formatted
 * BUT at present there isn't an action to respond to **?** 2013/07/02
 * 
 */
function oikd8_plugin_loaded() {
  add_action( "oik_pre_theme_field", "oikd8_pre_theme_field" );
  add_action( "oik_pre_form_field", "oikd8_pre_form_field" );
  add_filter( "bw_field_validation_date", "oikd8_field_validation_date", 10, 3 );
  add_filter( "oik_query_field_types", "oikd8_query_field_types" );
  add_filter( "oik_default_meta_value_date", "oikd8_default_meta_value_date", 10, 2 );
  add_action( "oik_loaded", "oikd8_oik_loaded" );
}

oikd8_plugin_loaded();  
