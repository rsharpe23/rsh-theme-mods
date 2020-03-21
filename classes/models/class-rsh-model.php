<?php

abstract class RSh_Model implements iRSh_Model {
  protected $extra;

  public function __construct( $extra = array() ) {
    $this->extra = $extra;
  }

  public function get_data() {
    return wp_parse_args( $this->process(), $this->extra );
  }

  protected abstract function process();
}