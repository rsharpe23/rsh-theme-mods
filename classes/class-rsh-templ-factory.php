<?php

class RSh_Templ_Factory {
  public static function create( $slug, $extra = array() ) {
    // Для проверки class_exists и создания объекта класса 
    // из переменной - регистр символом не обязателен
    $model_class_name = str_replace( '-', '_', $slug );
    
    if ( class_exists( $model_class_name ) ) {
      $class_name = "{$model_class_name}_templ";

      if ( class_exists( $class_name ) ) {
        return new $class_name( new $model_class_name( $extra ) );
      }
    }

    return false;
  }
}