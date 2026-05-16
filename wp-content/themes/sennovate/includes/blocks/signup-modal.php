<?php
function custom_signup_footer_content() {
    global $post;
    $selected_post_id = get_option('custom_signup_options_field');

    ?>
    <div id="SignupModal" class="modal signup-modal" style="display: none;">
        <div class="modal-content">
            <?php 
            if ($selected_post_id) {
                $post = get_post($selected_post_id);
                if ($post && $post->post_type === 'wp_block') {
                    echo apply_filters('the_content', $post->post_content);
                }
            }
            ?>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'custom_signup_footer_content');