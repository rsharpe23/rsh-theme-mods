<?php

class RSh_Nonce {
  const NONCE_KEY = 'rsh-nonce-key';

  public static function create( $name = '_wpnonce' ) {
    wp_nonce_field( self::NONCE_KEY, $name );
  }

  public static function verify( $name = '_wpnonce' ) {
    if ( empty( $_POST[ $name ] ) ) {
      return false;
    }

    return wp_verify_nonce( $_POST[ $name ], self::NONCE_KEY );
  }
}