<?php
if (!defined('ABSPATH')) {
    exit;
}

function cpgi_add_admin_menu() {
    // Ensure WooCommerce is active before adding menu
    if (!cpgi_check_woocommerce_active()) {
        return;
    }

    add_submenu_page(
        'woocommerce',
        __('Custom Product Gallery Image', 'custom-product-gallery-image'),
        __('Product Gallery Image', 'custom-product-gallery-image'),
        'manage_options',
        'cpgi-settings',
        'cpgi_settings_page'
    );
}
add_action('admin_menu', 'cpgi_add_admin_menu', 99);



function cpgi_enqueue_admin_styles($hook) {
    if ($hook !== 'woocommerce_page_cpgi-settings') {
        return;
    }
    wp_enqueue_style('cpgi-admin-style', CPGI_PLUGIN_URL . 'assets/admin-style.css');
}
add_action('admin_enqueue_scripts', 'cpgi_enqueue_admin_styles');

function cpgi_enqueue_admin_scripts($hook) {
    // Load scripts only on our settings page
    if ($hook !== 'woocommerce_page_cpgi-settings') {
        return;
    }

    wp_enqueue_media(); // Load WordPress Media Uploader

    wp_enqueue_script(
        'cpgi-admin-script',
        plugins_url('assets/js/cpgi-admin.js', __FILE__),
        array('jquery'),
        false,
        true
    );
}
add_action('admin_enqueue_scripts', 'cpgi_enqueue_admin_scripts');


function cpgi_settings_page() {
    ?>
    <div class="wrap cpgi-settings-container">
        <h2><?php _e('Custom Product Gallery Image Settings', 'custom-product-gallery-image'); ?></h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('cpgi_settings_group');
            do_settings_sections('cpgi-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function cpgi_register_settings() {
    register_setting('cpgi_settings_group', 'cpgi_custom_image');
    register_setting('cpgi_settings_group', 'cpgi_selected_categories');
    register_setting('cpgi_settings_group', 'cpgi_selected_tags');

    add_settings_section('cpgi_settings_section', __('Settings', 'custom-product-gallery-image'), null, 'cpgi-settings');

    add_settings_field(
        'cpgi_custom_image',
        __('Upload Image', 'custom-product-gallery-image'),
        'cpgi_custom_image_callback',
        'cpgi-settings',
        'cpgi_settings_section'
    );
}
add_action('admin_init', 'cpgi_register_settings');

function cpgi_custom_image_callback() {
    $image_id = get_option('cpgi_custom_image');
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    ?>
    <input type="hidden" id="cpgi_custom_image" name="cpgi_custom_image" value="<?php echo esc_attr($image_id); ?>">
    <button type="button" class="button" id="upload_image_button"><?php _e('Upload Image', 'custom-product-gallery-image'); ?></button>
    <img id="image_preview" src="<?php echo esc_url($image_url); ?>" style="max-width: 200px; display: block; margin-top: 10px;">
    <script>
        jQuery(document).ready(function($) {
            $('#upload_image_button').click(function(e) {
                e.preventDefault();
                var image = wp.media({
                    title: 'Upload Image',
                    multiple: false
                }).open()
                .on('select', function() {
                    var uploaded_image = image.state().get('selection').first();
                    var image_id = uploaded_image.toJSON().id;
                    var image_url = uploaded_image.toJSON().url;
                    $('#cpgi_custom_image').val(image_id);
                    $('#image_preview').attr('src', image_url).show();
                });
            });
        });
    </script>
    <?php
}
