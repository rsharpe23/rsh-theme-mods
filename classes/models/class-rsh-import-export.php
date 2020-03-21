<?php

class RSh_Import_Export extends RSh_Model {
  protected function process() {
    $result = array( 'tab_index' => 0 );

    if ( isset( $_POST['action'] ) ) {
      $result = wp_parse_args( $this->{$_POST['action']}(), $result );
    }

    return $result;
  }

  private function import() {
    if ( ! RSh_Nonce::verify( 'rsh-import' ) ) {
      return array();
    }

    $theme_mods_text = stripslashes( $_POST['theme-mods-text'] );
    $theme_mods_text = wp_kses_post( $theme_mods_text );

    if ( ! trim( $theme_mods_text ) ) {
      return array(
        'success' => false,
        'message' => __( 'Введите данные' ),
      );
    }

    $theme_mods = json_decode( $theme_mods_text, true );

    if ( ! $theme_mods ) {
      return array( 
        'success'    => false,
        'message'    => __( 'Некорректные данные' ),
        'error_text' => $theme_mods_text,
      );
    }

    $theme_mods = wp_parse_args( $theme_mods, get_theme_mods() );

    $option_name = get_stylesheet();
    $option_name = "theme_mods_{$option_name}";

    if ( ! update_option( $option_name, $theme_mods ) ) {
      return array();
    }

    return array( 
      'success' => true,
      'message' => __( 'Данные успешно обновлены' ),
    );
  }

  private function export() {
    $result = array( 'tab_index' => 1 );

    if ( ! RSh_Nonce::verify( 'rsh-export' ) ) {
      return $result;
    }

    // Также удаляет пробелы
    $site_url = sanitize_text_field( $_POST['site-url'] ); 
    // Удаляет слэш в конце строки, он если есть
    $site_url = rtrim( $site_url, ' /' ); 

    $theme_mods_text = wp_json_encode( 
      get_theme_mods(), 
      JSON_UNESCAPED_UNICODE 
    );

    if ( ! empty( $site_url ) ) {
      $theme_mods_text = str_replace( 
        addcslashes( site_url(), '/' ), 
        addcslashes( $site_url, '/' ), 
        $theme_mods_text 
      );
    }

    // Дополнительное использование здесь stripslashes 
    // для $theme_mods_text приведет к некорректным json-данным
    return wp_parse_args( array( 
      'theme_mods_text' => $theme_mods_text 
    ), $result );
  }
}