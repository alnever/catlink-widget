<?php
/*
Plugin Name: Categories Links Plugin
Plugin Uri: https://github.com/alnever/gallery-shortcode
Description: Provides a widget to insert some categories links as a block
Version: 1.0
Author: Alex Neverov
Author URI: http://alneverov.ru
License: GPL2
    Copyright 2018 Alex Neverov
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License,
    version 2, as published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

require plugin_dir_path(__FILE__)."catlinks-widget.php";

class Catlinks_Widget_Plugin {
  public function __construct() {
    add_action( 'widgets_init', array($this, 'register_catlinks_widget'));
  }
  public function register_catlinks_widget() {
    register_widget('Catlinks_Widget' );
  }
}

new Catlinks_Widget_Plugin();


 ?>
