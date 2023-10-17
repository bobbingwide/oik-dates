# oik-dates 
![banner](assets/oik-dates-banner-772x250.jpg)
* Contributors: bobbingwide
* Donate link: https://www.oik-plugins.com/oik/oik-donate/
* Tags: shortcodes, smart, lazy
* Requires at least: 5.5
* Tested up to: 6.4-beta3
* Stable tag: 0.2.1
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: oik-dates
* Domain Path: /languages/

## Description 
Implements date related field types for oik-fields

# Field types supported 
* date      - stored in format yyyy-mm-dd    10 characters
* time      - stored in format hh:mm

# Field types planned 
* datetime  - stored in format date time     23 characters
* timestamp - stored as UNIX time stamp - e.g. integer

# Shortcodes 

[bw_otd] - On this day

Produces content performing queries based on the current date


Extends [bw_related] by providing a default value for date type post meta field comparison.

# Action and filter hooks 

Follow this code...


  add_action( "oik_pre_theme_field", "oikd8_pre_theme_field" );
  add_action( "oik_pre_form_field", "oikd8_pre_form_field" );
  add_filter( "bw_field_validation_date", "oikd8_field_validation_date", 10, 3 );
  add_filter( "oik_query_field_types", "oikd8_query_field_types" );
  add_filter( "oik_default_meta_value_date", "oikd8_default_meta_value_date", 10, 2 );
  add_action( "oik_loaded", "oikd8_oik_loaded" );




## Installation 
1. Upload the contents of the oik-dates plugin to the `/wp-content/plugins/oik-dates' directory
1. Activate the oik-dates plugin through the 'Plugins' menu in WordPress

## Frequently Asked Questions 
Are there similar implementations?

Yes. The field types are nearly equivalent to the field types implemented by
* HM's Custom Meta Boxes
* Custom Content Type Manager
* PODS

## Which Date and Time pickers does it use? 
# Date picker 
It uses the date picker bundled with WordPress.

# Time picker 

The time picker is the dual licenced ( MIT & GPL ) jquery.timePicker.js by Anders Fajerson and Dennis Burke

https://github.com/perifer/timePicker
http://labs.perifer.se/timedatepicker/
based on
http://www.texotela.co.uk/code/jquery/timepicker/


This is the same as that bundled with

* HM's Custom Meta Boxes
* used by custom-content-type-manager

jQuery UI timePicker is also an option
http://trentrichardson.com/examples/timepicker/
used by

* PODS

Other time pickers are MIT Licence only

* https://github.com/jonthornton/jquery-timepicker/wiki

ACF's time picker is provided as an Add on http://www.advancedcustomfields.com/add-ons/date-time-picker/

## Screenshots 
1.

## Upgrade Notice 
# 0.2.1 
Update for support for PHP 8.1 and PHP 8.2

# 0.2.0 
Tested up to WordPress 5.5.1 with PHP 7.4

# 0.1 
This is prototype code built to discover what could be needed in the post meta project

github.com/ericandrewlewis/wordpress-metadata-ui-api


## Changelog 
# 0.2.1 
* Changed: Support PHP 8.1 and PHP 8.2,https://github.com/bobbingwide/oik-dates/issues/2
* Tested: With WordPress 6.4-beta3 and WordPress Multisite
* Tested: With PHP 8.0, PHP 8.1 and PHP 8.2
* Tested: With PHPUnit 9.6


# 0.2.0 
* Fixed: Correct case of timePicker.css,https://github.com/bobbingwide/oik-dates/issues/1
* Tested: With WordPress 5.5.1 and WordPress Multi Site
* Tested: With PHP 7.4

# 0.1 
* Added: Plugin cloned the "date" field from oik-fields
* Added: jQuery timePicker for the time field
* Added: field types: "time", "datetime", "timestamp"

## Further reading 
If you want to read more about the oik fields then please visit the
[oik-fields](https://www.oik-plugins.com/oik-plugins/oik-fields)

