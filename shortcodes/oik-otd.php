<?php // (C) Copyright Bobbing Wide 2014

/**
 * Return the date in the given format
 *
 * Like date() but makes it easier to code _month() and _day()
 */   
function bw_otd_date( $format="Y", $date=null ) {
  if ( $date ) {
    $date = date( $format, $date );
  } else { 
    $date = date( $format );
  }
  bw_trace2( $date, "date" );
  return( $date );
}

/**
 * Return the year of the date
 */
function bw_otd_year( $date=null ) {
  return( bw_otd_date( "Y", $date ) );
}

/** 
 * Return the month of the date
 */ 
function bw_otd_month( $date=null ) {
  return( bw_otd_date( "n", $date ) );
}

/**
 * Return the day of the date
 */ 
function bw_otd_day( $date=null ) {
  return( bw_otd_date( "j", $date ) );
}

/**
 * Return the base date from which the OTD dates are calculated
 *
 * @param array $atts - shortcode parameters
 * @return string|null - the current post date, selected date or null in format Y-m-d
 *
 * Current post / 
 * Conditional test  Return
 * is_multiple()     null
 */ 
function bw_otd_base_date( $atts ) {
  $base_date = null;
  if ( !is_single() ) {
    
  } else {
    // Single - what is the post type?
    // same as one of the selected post types then we'll use the post's date
    // If NOT in the selection list then we don't use the post's date.
    // So we won't get confused when looking at event calendar entries which are posted on a particular date with a different event date.
    
    // @TODO - use the value of a date field from the selected post type rather than the post date
    
    $post = bw_global_post();
    if ( $post ) {
      if ( false !== array_search( $post->post_type, $atts['post_type'] ) ) { 
        $base_date = bw_otd_year() ;
        $base_date .= bw_format_date( $post->post_date, "-M-d" );
      }  
    }  
  }  
  return( $base_date );
}

/**
 * Display a list heading for the data shown
 *
 * @param array $atts - shortcode parameters
 * @param string $period - the chosen period - which may be a month
 * @param string $date - the actual date being used - including day 
 */
function bw_otd_list_heading( $atts, $period, $date ) {
  if ( isset( $atts['day'] ) ) {
    $heading = bw_format_date( $date, "j F Y" );
  } else {
    $heading = bw_format_date( $date, "F Y" );
  }
  $heading .= "&nbsp;";
  $heading .= $period;
  li( $heading );
}

/**
 * Return the default periods for [bw_otd] shortcode
 * 
 * @TODO - provide a settings page
 */
function bw_otd_default_periods() {
  return( "-1 year,-5 years,-10 years,-25 years,-40 years" );
}

/**
 * Implement [bw_otd] shortcode 
 *
 * @TODO Do we need to cater for posts_per_page? Low priority if at all. Herb 2014/06/15 
 * 
 * @param array $atts array of shortcode parameters
 * @param string $content - not expected
 * @param string $tag - shortcode
 * @return string - generated HTML
 */
function bw_otd( $atts=null, $content=null, $tag=null ) {
  $periods = str_getcsv( bw_array_get( $atts, "periods", bw_otd_default_periods() ) );
  $atts['post_type'] = bw_as_array( bw_array_get( $atts, "post_type", "post" ) );
  $base_date = bw_otd_base_date( $atts );
  $exact = bw_array_get( $atts, "exact", null );
  $exact = bw_validate_torf( $exact );
  //p( bw_format_date( $base_date, "F j" ) ) ;
  sul( bw_array_get( $atts, "class", "bw_otd" ) );
  foreach ( $periods as $period ) {
    //bw_trace2( $period, "period" );
     
    $date = bw_date_adjust( $period, $base_date ); 
    $date = strtotime( $date );
    //bw_trace2( $date, "date" );
    $atts['year'] = bw_otd_year( $date );
    //li( $atts['year']  );
    
    $atts['monthnum'] = bw_otd_month( $date );
    $atts['day'] = bw_otd_day( $date );
    $atts['orderby'] = 'date';
    $atts['order'] = 'DESC'; 
    //bw_get_posts( 
    oik_require( "shortcodes/oik-list.php" );
    bw_push();
    $result = bw_list( $atts );
    
    
    //bw_trace2( $result, "REESULT" );
    bw_pop();
    /*                <ul class="bw_list"></ul>  */
    if ( $result == '<ul class="bw_list"></ul>' ) {
      if ( !$exact ) {
        unset( $atts['day'] );
        bw_push();
        $result = bw_list( $atts );
        bw_pop();
      }
    }
    if ( $result ) {
      bw_otd_list_heading( $atts, $period, $date );
      // li( $period );
      e( $result );
    }  
  } 
  eul();
  return( bw_ret() );
  
}

/**
 * Help hook for [bw_otd] shortcode 
 */  
function bw_otd__help( $shortcode="bw_otd" ) {
  return( __( "Display 'On this day' in history related content ", "oik-dates" ) );
}

/**
 * Syntax hook for [bw_otd] shortcode
 */ 
function bw_otd__syntax( $shortcode="bw_otd" ) {
  $syntax = array( "periods" => bw_skv( bw_otd_default_periods(), "<i>offset1,offset2</i>", "Periods to display" )
                 , "post_type" => bw_skv( "post", "<i>post types</i>", "Post types to select" )
                 , "exact" => bw_skv( "n", "y", "Match day exactly" )
                 );
  $syntax = array_merge( $syntax, _sc_posts() );               
  return( $syntax );
}                 
                 


 

