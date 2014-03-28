<?php
/*
 * Register Admin Menu
 */
add_action('admin_menu', 'register_mkgd_menu_page');

function register_mkgd_menu_page() {
  add_menu_page('MK Google Directions Settings', 'MK Google Directions', 'add_users', 'mkgdAdminPage', 'mkgd_admin_page', plugins_url('google-distance-calculator/images/mk16.png'), 99);
}

/*
 * Register settings for the plugin
 */
add_action('admin_init', 'mkgd_register_settings');

function mkgd_register_settings() {
  //register our settings
  register_setting('mkgd-settings-group', 'mkgd_destination');
  register_setting('mkgd-settings-group', 'mkgd_latitude');
  register_setting('mkgd-settings-group', 'mkgd_longitude');
  register_setting('mkgd-settings-group', 'mkgd_language');
  register_setting('mkgd-settings-group', 'mkgd_units');
  register_setting('mkgd-settings-group', 'mkgd_width');
  register_setting('mkgd-settings-group', 'mkgd_height');
}

/*
 * Define Options Page
 */

function mkgd_admin_page() {
  $languages = array(
      'ARABIC' => 'ar',
      'BASQUE' => 'eu',
      'BULGARIAN' => 'bg',
      'BENGALI' => 'bn',
      'CATALAN' => 'ca',
      'CZECH' => 'cs',
      'DANISH' => 'da',
      'GERMAN' => 'de',
      'GREEK' => 'el',
      'ENGLISH' => 'en',
      'ENGLISH (AUSTRALIAN)' => 'en-AU',
      'ENGLISH (GREAT BRITAIN)' => 'en-GB',
      'SPANISH' => 'es',
      'BASQUE' => 'eu',
      'FARSI' => 'fa',
      'FINNISH' => 'fi',
      'FILIPINO' => 'fil',
      'FRENCH' => 'fr',
      'GALICIAN' => 'gl',
      'GUJARATI' => 'gu',
      'HINDI' => 'hi',
      'CROATIAN' => 'hr',
      'HUNGARIAN' => 'hu',
      'INDONESIAN' => 'id',
      'ITALIAN' => 'it',
      'HEBREW' => 'iw',
      'JAPANESE' => 'ja',
      'KANNADA' => 'kn',
      'KOREAN' => 'ko',
      'LITHUANIAN' => 'lt',
      'LATVIAN' => 'lv',
      'MALAYALAM' => 'ml',
      'MARATHI' => 'mr',
      'DUTCH' => 'nl',
      'NORWEGIAN' => 'no',
      'POLISH' => 'pl',
      'PORTUGUESE' => 'pt',
      'PORTUGUESE (BRAZIL)' => 'pt-BR',
      'PORTUGUESE (PORTUGAL)' => 'pt-PT',
      'ROMANIAN' => 'ro',
      'RUSSIAN' => 'ru',
      'SLOVAK' => 'sk',
      'SLOVENIAN' => 'sl',
      'SERBIAN' => 'sr',
      'SWEDISH' => 'sv',
      'TAGALOG' => 'tl',
      'TAMIL' => 'ta',
      'TELUGU' => 'te',
      'THAI' => 'th',
      'TURKISH' => 'tr',
      'UKRAINIAN' => 'uk',
      'VIETNAMESE' => 'vi',
      'CHINESE (SIMPLIFIED)' => 'zh-CN',
      'CHINESE (TRADITIONAL)' => 'zh-TW',
  );
  $units = array(
      'Metric (Kilometers & Meters)' => 'metric',
      'Imperial (Miles & Feet)' => 'imperial',
  );
  ?>
  <div class="wrap">
    <h2><img src="<?php echo plugins_url('google-distance-calculator/images/mk32.png'); ?>"/> MK Google Directions Settings</h2>    
    <form method="post" action="options.php">      
      <?php settings_fields('mkgd-settings-group'); ?>
      <?php do_settings_fields('mkgdAdminPage', 'mkgd-settings-group'); ?>
      <table class="widefat">
        <thead>
          <tr>
            <th>MK Google Directions configurations</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <table>
                  
                <tr>
                  <td><label for="destination">Destination: </label></td>
                  <td><input type="text" value="<?php echo get_option('mkgd_destination', 'Lakeside Holiday Park, 102 Corowa Rd, Mulwala NSW 2647, Australia'); ?>" size="33" name="mkgd_destination"></td>
                  <td><small>Use company address for destination address.</small></td>
                </tr>
                <tr>
                  <td><label for="latitude">Latitude: </label></td>
                  <td><input type="text" value="<?php echo get_option('mkgd_latitude', '43.6525'); ?>" size="33" name="mkgd_latitude"></td>
                  <td><small>Default latitudes for the Google Map <a href="http://itouchmap.com/latlong.html" title="Help" target="_blank" rel="nofollow"><strong>Help?</strong></a></small></td>
                </tr>
                <tr>
                  <td><label for="longitude">Longitude: </label></td>
                  <td><input type="text" value="<?php echo get_option('mkgd_longitude', '-79.3816667'); ?>" size="33" name="mkgd_longitude"></td>
                  <td><small>Default longitudes for the Google Map <a href="http://itouchmap.com/latlong.html" title="Help" target="_blank" rel="nofollow"><strong>Help?</strong></a></small></td>
                </tr>
                <tr>
                  <td><label for="language">Language: </label></td>
                  <td>
                    <select name="mkgd_language">
                      <option value="">-- Select --</option>
                      <?php foreach ($languages as $language => $code) { ?>

                        <option <?php echo get_option('mkgd_language') === $code ? 'selected="selected"' : ''; ?> value="<?php echo $code; ?>"><?php echo $language; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                  <td><small>Default language for the Google Map</small></td>
                </tr>
                <tr>
                  <td><label for="language">Unit System: </label></td>
                  <td>
                    <select name="mkgd_units">
                      <option value="">-- Select --</option>
                      <?php foreach ($units as $key => $value) { ?>
                        <option <?php echo get_option('mkgd_units') === $value ? 'selected="selected"' : ''; ?> value="<?php echo $value; ?>"><?php echo $key; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                  <td><small>Default unit system for the Google Map</small></td>
                </tr>
                <tr>
                  <td><label for="map-width">Map Width: </label></td>
                  <td><input type="text" value="<?php echo get_option('mkgd_width', '500'); ?>" size="33" name="mkgd_width"></td>
                  <td><small>Default width for the Google Map</small></td>
                </tr>
                <tr>
                  <td><label for="map-width">Map Height: </label></td>
                  <td><input type="text" value="<?php echo get_option('mkgd_height', '300'); ?>" size="33" name="mkgd_height"></td>
                  <td><small>Default height for the Google Map</small></td>
                </tr>
              </table>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th>MK Google Directions configurations</th>
          </tr>
        </tfoot>
      </table>

      <?php submit_button(); ?>

    </form>
    <table class="widefat">
      <thead>
        <tr>
          <th>Donate to MK Google Directions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <h2>If you like the plugin, you can buy me a beer</h2>
            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7N283YV4KLEQ2" title="Donate" target="_blank"><img src="https://www.paypalobjects.com/en_GB/i/btn/btn_donateCC_LG.gif" alt="Donate" title="Donate" /></a>
          </td>
        </tr>
      </tbody>
      <thead>
        <tr>
          <th>Donate to MK Google Directions</th>
        </tr>
      </thead>
    </table>
  </div><!-- .wrap -->
  <?php
}