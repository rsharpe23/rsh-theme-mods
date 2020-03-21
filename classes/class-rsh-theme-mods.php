<?php

class RSh_Theme_Mods {
  public static function initialize() {
    add_action( 'admin_enqueue_scripts', array( 'RSh_Theme_Mods', 'admin_enqueue_scripts' ) );
    add_action( 'admin_menu', array( 'RSh_Theme_Mods', 'admin_menu' ) );
  }

  public static function admin_enqueue_scripts() {
    wp_enqueue_style( 
      'rsh-theme-mods', 
      self::get_uri( '/main.css' )
    );
  
    wp_enqueue_script( 
      'rsh-theme-mods',
      self::get_uri( '/main.js' ),
      array( 'jquery' ), false, true 
    );
  }

  public static function admin_menu() {
    $page_title = __( 'Импорт/экспорт данных темы' );

    $management_page = new RSh_Management_Page( array(
      'title'      => $page_title, 
      'menu_title' => $page_title, 
      'menu_slug'  => 'rsh-import-export',
    ) );

    $management_page->load();
  }

  public static function get_uri( $file_path = '' ) {
    $plugin_name = strtolower( __CLASS__ );
    $plugin_name = str_replace( '_', '-', $plugin_name );
    return plugins_url( $plugin_name . $file_path );
  }
}