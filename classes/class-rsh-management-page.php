<?php

class RSh_Management_Page {
  protected $args;

  protected $default_args = array(
    'title'      => '',
    'menu_title' => '',
    'capability' => 'manage_options',
    'menu_slug'  => '',
  );

  private $is_loaded = false;

  public function __construct( $args ) {
    $this->args = wp_parse_args( $args, $this->default_args );
  }

  public function load() {
    if ( $this->is_loaded ) {
      return;
    }

    // Создает переменные с названиями равными значениям $key
    foreach ( $this->args as $key => $value ) {
      $$key = $value; 
    }

    add_management_page( 
      $title, 
      $menu_title, 
      $capability, 
      $menu_slug, 
      array( $this, 'render' ) 
    );

    $this->is_loaded = true;
  }

  public function render() {
    $templ = RSh_Templ_Factory::create( 
      $this->args['menu_slug'], 
      array( 'page_url' => $this->get_url() ) 
    );

    $templ->render();
  }

  public function get_url() {
    $query_args = array( 'page' => $this->args['menu_slug'] );
    return add_query_arg( $query_args );
  }
}