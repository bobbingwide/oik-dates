<?php // (C) Copyright Bobbing Wide 2013-2014

/**
 * Enqueue the debug script if needed otherwise enqueue the minified (packed) one
 *
 * @TODO 2013/12/18 noticed that there's no difference at present! 
 * Note: jquery-ui-datepicker is part of WordPress
 */
if ( !function_exists( "bw_datepicker_enqueue_script" ) ) {
function bw_datepicker_enqueue_script( ) {
  if ( defined('SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true) {
    wp_enqueue_script( 'jquery-ui-datepicker' );
  } else {
    wp_enqueue_script( 'jquery-ui-datepicker' );
  } 
}
} 

/** 
 * Implement bw_form_field_ hook for "date"
 *
 * **?** oik v2.0 was delivered with jquery-ui-1.9.2.custom.css but originally expected jquery.ui.theme.css. How do we manage this in the future? 2013/06/26
 *
 */
if ( !function_exists( "bw_form_field_date" ) ) { 
function bw_form_field_date( $name, $type, $title, $value, $args ) {
  $args['#length'] = bw_array_get( $args, '#length', 10 );
  //wp_enqueue_style( "jquery-ui-datepicker-css", plugin_dir_url( __FILE__). "css/jquery.ui.datepicker.css" );
  wp_enqueue_style( "jquery-ui-datepicker-css", oik_url( "css/jquery.ui.datepicker.css" ) ); 
  //wp_enqueue_style( "jquery-ui-theme-css", plugin_dir_url( __FILE__). "css/jquery.ui.theme.css" );
  wp_enqueue_style( "jquery-ui-theme-css", oik_url( "css/jquery-ui-1.9.2.custom.css" ) );
   
  bw_datepicker_enqueue_script();
  bw_jquery( "#${name}", "datepicker", bw_jkv( "dateFormat : 'yy-mm-dd', changeMonth: true, changeYear: true" ) );
  if ( $value ) {
    $value = bw_format_date( $value );
  }
  bw_form_field_( $name, $type, $title, $value, $args ); 
}
}


/**
 * Enqueue the time picker debug script if needed otherwise enqueue the minified (packed) one
 *
 */
if ( !function_exists( "bw_timepicker_enqueue_script" ) ) {
function bw_timepicker_enqueue_script( ) {
  if ( defined('SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true) {
    wp_enqueue_script( 'jquery-ui-timepicker', oik_url( "jquery/jquery.timePicker.js", "oik-dates"), array( 'jquery' ) );
  } else {
    wp_enqueue_script( 'jquery-ui-timepicker', oik_url( "jquery/jquery.timePicker.min.js", "oik-dates"), array( 'jquery' ) );
  } 
}
} 

/** 
 * Implement bw_form_field_ hook for "time"
 *
 * This version of code works with 
 * @link https://github.com/jonthornton/jquery-timepicker
 */
if ( !function_exists( "bw_form_field_time" ) ) { 
function bw_form_field_time( $name, $type, $title, $value, $args ) {
  $args['#length'] = bw_array_get( $args, '#length', 8 );
  wp_enqueue_style( "jquery-ui-timepicker-css", oik_url( "css/timepicker.css", "oik-dates") );
  bw_timepicker_enqueue_script();
  ///bw_jquery( "#${name}", "timePicker", bw_jkv( "dateFormat : 'hh-ii-ss', changeMonth: true, changeYear: true" ) );
  //bw_jquery( "#${name}", "timepicker" );
  bw_jquery( "#${name}", "timePicker", bw_jkv( "startTime: '00:00', endTime: '23:30', show24Hours: false, separator: ':', step: 30" ) );
  if ( $value ) {
    
    $value2 = bw_format_date( $value, "H:i" );
    bw_trace2( $value2, "value2" );
    $value = $value2;
  }
  bw_form_field_( $name, $type, $title, $value, $args ); 
}
}


/** 
 * If we want to use HM's CMB then we need to use something like this for the jQuery parameters
 

 * Note: Copied timePicker.js from Custom_Meta_Boxes
 * need the official version... 


		jQuery(this).timePicker({
			startTime: "00:00",
			endTime: "23:30",
			show24Hours: false,
			separator: ':',
			step: 30
		});

*/
/** 
class CMB_Time_Field extends CMB_Field {

	public function enqueue_scripts() {

		parent::enqueue_scripts();

		wp_enqueue_style( 'cmb-jquery-ui', trailingslashit( CMB_URL ) . 'css/jquery-ui.css', '1.10.3' );

		wp_enqueue_script( 'cmb-timepicker', trailingslashit( CMB_URL ) . 'js/jquery.timePicker.min.js', array( 'jquery', 'cmb-scripts' ) );
		wp_enqueue_script( 'cmb-datetime', trailingslashit( CMB_URL ) . 'js/field.datetime.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'cmb-scripts' ) );
	}

	public function html() { ?>

		<input <?php $this->id_attr(); ?> <?php $this->boolean_attr(); ?> <?php $this->class_attr( 'cmb_text_small cmb_timepicker' ); ?> type="text" <?php $this->name_attr(); ?> value="<?php echo esc_attr( $this->value ); ?>"/>

	<?php }

}
*/

/**
 * Theme a field of type 'date'
 * 
 * e.g. date_i18n(get_option('date_format') ,strtotime("11/15-1976"));?>
 * 
 * @param string $key - the field name
 * @param string $value - the field value - expected to be in ccyy-mm-dd  rather than a unix timestamp
 * @param string $field - optional
 *
 */
if ( !function_exists( "bw_theme_field_date" ) ) {
function bw_theme_field_date( $key, $value, $field=null ) {
  $date = bw_array_get( $value, 0, $value );
  if ( $date ) {
    $format = get_option( 'date_format' );
    $date = strtotime( $date );
    e( date_i18n( $format, $date ) ); 
  }  
}
}

/**
 * Theme a field of type 'time'
 *
 * @param string $key - the field name
 * @param string $value - the field value - expected to be a formatted time. e.g. 1:00am
 * @param string $field - optional
 */
if ( !function_exists( "bw_theme_field_time" ) ) {
function bw_theme_field_time( $key, $value, $field=null ) {
  $time = bw_array_get( $value, 0, $value );
  if ( $time ) {
    $format = get_option( 'time_format' );
    $utime = strtotime( $time );
    //bw_trace2( $utime, $format );
    e( date_i18n( $format, $utime ) ); 
  }  
}
}



