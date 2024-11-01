<?php
/**
 * Plugin Name:   Simplest Facebook Like
 * Plugin URI:    https://github.com/guhemama/wordpress-simplest-facebook-like
 * Description:   Plugin that adds the Facebook like button to posts.
 * Version:       1.0.0
 * Developer:     Gustavo H. Mascarenhas Machado
 * Developer URI: https://guh.me
 * License:       BSD-3
 *
 * Copyright (c) 2016, Gustavo H. Mascarenhas Machado
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Gustavo H. Mascarenhas Machado nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL GUSTAVO H. MASCARENHAS MACHADO BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

add_filter('the_content', 'sfb_render_button');

add_action('wp_head',   'sfb_generate_meta_tags');
add_action('wp_footer', 'sfb_add_facebook_jssdk');

register_activation_hook(__FILE__, 'sfb_activate');
register_deactivation_hook(__FILE__, 'sfb_deactivate');

if (is_admin()) {
  add_action('admin_init', 'sfb_admin_init');
  add_action('admin_menu', 'sfb_admin_menu');
}

function sfb_activate() {
    add_option('sfb_layout', 'button_count');
    add_option('sfb_action', 'like');
    add_option('sfb_show_faces', true);
    add_option('sfb_width', '150');
    add_option('sfb_share', true);
    add_option('sfb_language_code', 'en_US');
    add_option('sfb_app_id', null);
}

function sfb_deactivate() {
    delete_option('sfb_layout');
    delete_option('sfb_action');
    delete_option('sfb_show_faces');
    delete_option('sfb_width');
    delete_option('sfb_share');
    delete_option('sfb_language_code');
    delete_option('sfb_app_id');
}

function sfb_admin_init() {
  register_setting('sfb', 'sfb_layout');
  register_setting('sfb', 'sfb_action');
  register_setting('sfb', 'sfb_show_faces');
  register_setting('sfb', 'sfb_width', 'intval');
  register_setting('sfb', 'sfb_share');
  register_setting('sfb', 'sfb_language_code');
  register_setting('sfb', 'sfb_app_id');
}

/**
 * Adds the admin options page to the menu
 * @return void
 */
function sfb_admin_menu() {
  add_options_page('Simplest Facebook Like', 'Simplest Facebook Like', 'manage_options', 'sfb', 'sfb_admin_options');
}

/**
 * Loads the admin options page
 * @return void
 */
function sfb_admin_options() {
    require_once dirname(__FILE__) . '/options.php';
}

/**
 * Generates the Facebook button with the necessary attributes
 * @param  string $text The post text
 * @return string
 */
function sfb_render_button($text) {
    global $post;

    $attrs = [];
    $attrs['href']       = get_permalink($post->ID);
    $attrs['layout']     = get_option('sfb_layout');
    $attrs['action']     = get_option('sfb_action');
    $attrs['show-faces'] = get_option('sfb_show_faces');
    $attrs['width']      = get_option('sfb_width');
    $attrs['share']      = get_option('sfb_share');

    $out = '<div class="fb-like"';

    foreach ($attrs as $k => $v) {
        $out .= ' data-' . $k . '="' . $v . '"';
    }

    $out .= '></div>';

    return $out . $text;
}

/**
 * Generates Facebook OpenGraph meta tags for the post
 * @return void
 */
function sfb_generate_meta_tags() {
    global $post;

    // This is required to set some post variables we will use in the tags
    setup_postdata($post);

    echo '<meta property="og:url" content="' . get_permalink($post->ID) . '">';
    echo '<meta property="og:type" content="article">';
    echo '<meta property="og:title" content="' . $post->post_title . '">';
    echo '<meta property="og:description" content="'. get_the_excerpt() . '">';
    echo '<meta property="og:site_name" content="'. get_bloginfo('name') . '">';
}

/**
 * Adds the Facebook JS SDK to the bottom of the page
 * @return void
 */
function sfb_add_facebook_jssdk() {
    $lang  = get_option('sfb_language_code');
    $appId = get_option('sfb_app_id');

    $appIdParam = '';
    if (strlen($appId) > 0) {
      $appIdParam = '&appId=' . $appId;
    }

    ?>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/<?= $lang ?>/sdk.js#xfbml=1&version=v2.6"<?= $appIdParam ?>;
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <?php
}