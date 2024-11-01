<div class="wrap">
  <h2>Simplest Facebook Like</h2>

  <form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
    <?php settings_fields('sfb'); ?>

    <table class="form-table">
      <tr valign="top">
        <th scope="row">Layout:</th>
        <td>
          <select name="sfb_layout">
            <option value="standard" <? if (get_option('sfb_layout') == 'standard') echo 'selected' ?>>standard</option>
            <option value="box_count" <? if (get_option('sfb_layout') == 'box_count') echo 'selected' ?>>box_count</option>
            <option value="button_count" <? if (get_option('sfb_layout') == 'button_count') echo 'selected' ?>>button_count</option>
            <option value="button" <? if (get_option('sfb_layout') == 'button') echo 'selected' ?>>button</option>
          </select>
        </td>
      </tr>

      <tr valign="top">
        <th scope="row">Action type:</th>
        <td>
          <select name="sfb_action">
            <option value="like" <? if (get_option('sfb_action') == 'like') echo 'selected' ?>>Like</option>
            <option value="recommend" <? if (get_option('sfb_action') == 'recommend') echo 'selected' ?>>Recommend</option>
          </select>
        </td>
      </tr>

      <tr valign="top">
        <th scope="row">Width (px):</th>
        <td><input type="text" name="sfb_width" value="<?php echo get_option('sfb_width'); ?>" /></td>
      </tr>

      <tr valign="top">
        <th scope="row">Show friends faces:</th>
        <td>
          <select name="sfb_show_faces"><? var_dump(get_option('sfb_show_faces')) ?>
            <option value="true" <? if (get_option('sfb_show_faces') == 'true') echo 'selected' ?>>Yes</option>
            <option value="false" <? if (get_option('sfb_show_faces') == 'false') echo 'selected' ?>>No</option>
          </select>
        </td>
      </tr>

      <tr valign="top">
        <th scope="row">Show share button:</th>
        <td>
          <select name="sfb_share">
            <option value="true" <? if (get_option('sfb_share') == 'true') echo 'selected' ?>>Yes</option>
            <option value="false" <? if (get_option('sfb_share') == 'false') echo 'selected' ?>>No</option>
          </select>
        </td>
      </tr>

      <tr valign="top">
        <th scope="row">Language code (<a href="https://www.facebook.com/translations/FacebookLocales.xml" target="_blank">View all codes</a>):</th>
        <td><input type="text" name="sfb_language_code" value="<?php echo get_option('sfb_language_code'); ?>" /></td>
      </tr>

      <tr valign="top">
        <th scope="row">App ID (optional):</th>
        <td><input type="text" name="sfb_app_id" value="<?php echo get_option('sfb_app_id'); ?>" /></td>
      </tr>
    </table>

    <input type="hidden" name="action" value="update" />

    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
  </form>
</div>