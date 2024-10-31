<?php
function properties_settings_page() {
    // Check if the user has the capability to manage options
    if (!current_user_can('manage_options')) {
        return;
    }

    // Add a submenu page under the 'Property' post type menu
    add_submenu_page(
        'edit.php?post_type=property', // Parent slug
        'Property Settings',             // Page title
        'Property Settings',             // Menu title
        'manage_options',                // Capability
        'properties-settings',           // Menu slug
        'properties_settings_page_html'  // Callback function
    );
}

add_action('admin_menu', 'properties_settings_page');

function properties_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>Property Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('properties_options_group');
            do_settings_sections('properties-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function properties_settings_init() {
    register_setting('properties_options_group', 'properties_field_title');
    register_setting('properties_options_group', 'properties_form_submit');

    add_settings_section(
        'properties_settings_section',
        'Manage Property Types',
        'properties_settings_section_callback',
        'properties-settings'
    );

    add_settings_field(
        'properties_field_title',
        'Title',
        'properties_field_title_render',
        'properties-settings',
        'properties_settings_section'
    );
     add_settings_field(
        'properties_form_submit',
        'Submit Button',
        'properties_form_submit_render',
        'properties-settings',
        'properties_settings_section'
    );
}

add_action('admin_init', 'properties_settings_init');

function properties_settings_section_callback() {
    echo '<p>Here you can manage your property settings.</p>';
}

function properties_field_title_render() {
    $title = get_option('properties_field_title');
    ?>
    <input type='text' name='properties_field_title' value='<?php echo $title; ?>'>
    <?php
}

function properties_form_submit_render() {
    $options = get_option('properties_form_submit'); // Fetch saved option value
    $choices = [
        'bg-white' => 'BG White',
        'bg-blue' => 'BG Blue',
        'bg-dark' => 'BG Dark',
        'bg-red' => 'BG Red',
    ];
    ?>
    <select name='properties_form_submit'>
        <?php foreach ($choices as $value => $label) : ?>
            <option value='<?php echo esc_attr($value); ?>' <?php selected($options, $value); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

