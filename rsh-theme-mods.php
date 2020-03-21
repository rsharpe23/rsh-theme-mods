<?php
/**
 * Plugin Name: RSh-ThemeMods
 * Plugin URI:  https://
 * Description: Simple plugin for import/export current theme mods.
 * Author:      Roman Sharpe
 * Author URI:  https://
 * Version:     1.0
 * Text Domain: rsh
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}

define( 'RSH_THEME_MODS_DIR', plugin_dir_path( __FILE__ ) );

require_once RSH_THEME_MODS_DIR . 'classes/class-rsh-nonce.php';

require_once RSH_THEME_MODS_DIR . 'interfaces/interface-rsh-model.php';
require_once RSH_THEME_MODS_DIR . 'classes/models/class-rsh-model.php';
require_once RSH_THEME_MODS_DIR . 'classes/models/class-rsh-import-export.php';

require_once RSH_THEME_MODS_DIR . 'interfaces/interface-rsh-templ.php';
require_once RSH_THEME_MODS_DIR . 'classes/templates/class-rsh-templ.php';
require_once RSH_THEME_MODS_DIR . 'classes/templates/class-rsh-import-export-templ.php';

require_once RSH_THEME_MODS_DIR . 'classes/class-rsh-templ-factory.php';
require_once RSH_THEME_MODS_DIR . 'classes/class-rsh-management-page.php';
require_once RSH_THEME_MODS_DIR . 'classes/class-rsh-theme-mods.php';

RSh_Theme_Mods::initialize();


// function get_page_url() {
//   $args = array( 'page' => 'rsh-import-export' );
//   return add_query_arg( $args, admin_url( 'options-general.php' ) );
// }

function rsh_display_page() {
  $result = '';
  $nonce = 'rsh-theme-mods-secret';
  $action = $_POST['action'];

  if ( $action ) {
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], $nonce ) ) {
      return;
    }

    if ( $action === 'import' ) {
      $result = stripslashes( $_POST['rsh-data'] );
      $result = wp_kses_post( $result );

      if ( trim( $result ) ) {
        $data = json_decode( $result, true );
        $data = wp_parse_args( $data, get_theme_mods() );
        $theme = get_option( 'stylesheet' );

	      if ( update_option( "theme_mods_$theme", $data ) ) {
          echo 'Данные были успешно импортированы!';
        }
      }
      // ===
    } elseif ( $action === 'export' ) {
      $result = wp_json_encode( get_theme_mods(), JSON_UNESCAPED_UNICODE );
      $result = stripslashes( $result );
    }
  }

  $tab_index = 0;
  $active_tab_index = empty( $action ) || $action === 'import' ? 0 : 1;

  $tab_links = array( 
    '#rsh_import' => 'Импорт', 
    '#rsh_export' => 'Экспорт' 
  );
?>
  <div class="wrap">
    <h1>Импорт/экспорт данных темы</h1>

    <ul class="rsh-tabs">
      <?php foreach ( $tab_links as $href => $text ) : ?>
        <li class="rsh-tab<?php echo $tab_index === $active_tab_index ? ' active' : ''; ?>">
          <a href="<?php echo esc_url( $href ); ?>" class="rsh-tab-link"><?php esc_html_e( $text ); ?></a>
        </li>
        <?php $tab_index++; ?>
      <?php endforeach; ?>
    </ul>

    <div class="rsh-tab-area">
      <div id="rsh_import" class="rsh-tab-pane">
        <form action="<?php esc_url( get_page_url() ); ?>" method="post" class="form">
          <?php wp_nonce_field( $nonce ); ?>
          <input type="hidden" name="action" value="import">
          <p>Импортируйте данные, вставив сюда скопированный текст и нажав кнопку "Импортировать".</p>
          <p><textarea name="rsh-data" cols="70" rows="20"><?php echo wp_kses_post( $result ); ?></textarea></p>
          <p><input type="submit" value="Импортировать" class="button button-primary"></p>
        </form>
      </div>

      <div id="rsh_export" class="rsh-tab-pane">
        <?php if ( $action === 'export' ) : ?>
          <p>Скопируйте эти данные, и сохраните в текстовый файл.<br>
            Позже Вы сможете импортировать их в соответсвующей вкладке.</p>
          <p><textarea name="rsh-data" cols="70" rows="20"><?php echo wp_kses_post( $result ); ?></textarea></p>
        <?php else : ?>
          <form action="<?php esc_url( get_page_url() ); ?>" method="post" class="form">
            <?php wp_nonce_field( $nonce ); ?>
            <input type="hidden" name="action" value="export">
            <p>Экспортируйте данные, нажав на кнопку.</p>
            <p><input type="submit" value="Экспортировать" class="button button-primary"></p>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php
}

// function rsh_admin_menu() {
//   add_options_page( 
//     __( 'Импорт/экспорт данных темы' ), 
//     __( 'Импорт/экспорт данных темы' ), 
//     'manage_options', 
//     'rsh-import-export', 
//     'rsh_display_page' 
//   );
// }
// add_action( 'admin_menu', 'rsh_admin_menu' );

// function rsh_admin_enqueue_scripts() {
//   wp_enqueue_style( 
//     'rsh-tabs', 
//     plugins_url( 'rsh-theme-mods/css/rsh-tabs.css' ) 
//   );

//   wp_enqueue_script( 
//     'rsh-tabs', 
//     plugins_url( 'rsh-theme-mods/js/rsh-tabs.js' ), 
//     array( 'jquery' ), false, true 
//   );
// }
// add_action( 'admin_enqueue_scripts', 'rsh_admin_enqueue_scripts' );





// global $wpdb;
// $result = $wpdb->query( "DELETE FROM wp_posts WHERE post_type='customize_changeset'" );
// var_dump( $result );

// <div class="wrap">
//   <h1>Импорт/экспорт данных темы</h1>

//   <ul class="rsh-tabs">
//     <li class="rsh-tab active">
//       <a href="#rsh_import" class="rsh-tab-link">Импорт</a>
//     </li>

//     <li class="rsh-tab">
//       <a href="#rsh_export" class="rsh-tab-link">Экспорт</a>
//     </li>
//   </ul>

//   <div class="rsh-tab-area">
//     <div id="rsh_import" class="rsh-tab-pane active">
//       <form action="/" method="post" class="form">
//         <input type="hidden" name="action" value="import">
//         <p>Импортируйте данные, вставив сюда скопированный текст и нажав кнопку "Импортировать".</p>
//         <p><textarea name="rsh-data" cols="70" rows="20"></textarea></p>
//         <p><input type="submit" value="Импортировать" class="button button-primary"></p>
//       </form>
//     </div>

//     <div id="rsh_export" class="rsh-tab-pane">
//       <form action="/" method="post" class="form">
//         <input type="hidden" name="action" value="export">
//         <p>Экспортируйте данные, нажав на кнопку.</p>
//         <p><input type="submit" value="Экспортировать" class="button button-primary"></p>
//       </form>

//       <form action="/" class="form">
//         <p>Скопируйте эти данные, и сохраните в текстовый файл.<br>
//           Позже Вы сможете импортировать их в соответсвующей вкладке.</p>
//         <p><textarea name="rsh-data" cols="70" rows="20"></textarea></p>
//       </form>
//     </div>
//   </div>
// </div>