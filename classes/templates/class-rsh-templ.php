<?php

abstract class RSh_Templ implements iRSh_Templ {
  protected $model;

  public function __construct( $model ) {
    $this->model = $model;
  }

  public function render() {
    $this->do_render( $this->model->get_data() );
  }

  protected abstract function do_render( $data );
}