<?php

class RSh_Import_Export_Templ extends RSh_Templ {
  protected function do_render( $data ) {
    // TODO: Переделать на шаблон Underscore.js

    $tab_index = 0;
    $tab_links = array(
      '#rsh_import' => __( 'Импорт' ),
      '#rsh_export' => __( 'Экспорт' ),
    );

    $alert_class = 'rsh-alert';

    if ( isset( $data['success'] ) ) {
      $alert_class .= $data['success'] ? ' rsh-alert-success' : ' rsh-alert-danger';
    }
  ?>
    <div class="wrap">
      <h1>Импорт/экспорт данных темы</h1>

      <ul class="rsh-tabs">
        <?php foreach ( $tab_links as $href => $text ) : ?>
          <li class="rsh-tab<?php echo $tab_index === $data['tab_index'] ? ' active' : ''; ?>">
            <a href="<?php echo esc_url( $href ); ?>" class="rsh-tab-link"><?php esc_html_e( $text ); ?></a>
          </li>
          <?php $tab_index++; ?>
        <?php endforeach; ?>
      </ul>

      <div class="rsh-tab-area">
        <div id="rsh_import" class="rsh-tab-pane">
          <?php if ( isset( $data['success'] ) ) : ?>
            <div class="<?php echo esc_attr( $alert_class ); ?>">
              <span><?php echo wp_kses_post( $data['message'] ?? '' ); ?></span>
              <button type="button" class="rsh-alert-btn">&times;</button>
            </div>
          <?php endif; ?>

          <form action="<?php echo esc_url( $data['page_url'] ); ?>" method="post" class="rsh-form">
            <?php RSh_Nonce::create( 'rsh-import' ); ?>
            <input type="hidden" name="action" value="import">
            <p>Импортируйте данные, вставив сюда скопированный текст и нажав кнопку "Импортировать".</p>
            <p><textarea name="theme-mods-text" cols="70" rows="20"><?php echo wp_kses_post( $data['error_text'] ?? '' ); ?></textarea></p>
            <p><input type="submit" value="Импортировать" class="button button-primary"></p>
          </form>
        </div>

        <div id="rsh_export" class="rsh-tab-pane">
          <?php if ( isset( $data['theme_mods_text'] ) ) : ?>
            <p>Скопируйте эти данные, и сохраните в текстовый файл.<br>
              Позже Вы сможете импортировать их в соответсвующей вкладке.</p>
            <p><textarea cols="70" rows="20"><?php echo wp_kses_post( $data['theme_mods_text'] ); ?></textarea></p>
          <?php else : ?>
            <form action="<?php echo esc_url( $data['page_url'] ); ?>" method="post" class="rsh-form">
              <?php RSh_Nonce::create( 'rsh-export' ); ?>
              <input type="hidden" name="action" value="export">
              <p>Экспортируйте данные, нажав на кнопку.</p>
              <p><input type="text" name="site-url" placeholder="Адрес импортируемого сайта (необязательно)"></p>
              <p><input type="submit" value="Экспортировать" class="button button-primary"></p>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php
  }
}