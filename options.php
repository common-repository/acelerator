<?php
class Acelerator_Textarea_Enhancer_Options
{
    private $options;

    public function __construct()
    {
        add_action('admin_menu', array( $this, 'add_plugin_page' ));
        add_action('admin_init', array( $this, 'page_init' ));
    }

    public function getOptions()
    {
        $this->options = get_option('acelerator_options');
        return $this->options;
    }

    public function add_plugin_page()
    {
        add_options_page(
            'Settings Admin',
            'Acelerator',
            'manage_options',
            'acelerator-setting-admin',
            array( $this, 'create_admin_page' )
        );
    }

    public function create_admin_page()
    {
        $this->options = get_option('acelerator_options'); ?>
        <div class="wrap">
            <h1>Acelerator Options</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields('acelerator_option_group');
        do_settings_sections('acelerator-setting-admin');
        submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function page_init()
    {
        register_setting(
            'acelerator_option_group',
            'acelerator_options',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'main_section_id',
            'Acelerator Selectors Settings',
            array( $this, 'print_section_info' ),
            'acelerator-setting-admin'
        );

        add_settings_field(
            'acelerator_selector_string',
            'Selectors <em>(all of those become editors when found, separate with "|")</em>',
            array( $this, 'acelerator_selector_string_callback' ),
            'acelerator-setting-admin',
            'main_section_id'
        );

        add_settings_field(
            'acelerator_syntaxes',
            'Syntaxes <em>(stops at first occurence when found, separate with "|")</em>',
            array( $this, 'acelerator_syntaxes_callback' ),
            'acelerator-setting-admin',
            'main_section_id'
        );

        add_settings_field(
            'acelerator_css',
            'Ace editor CSS',
            array( $this, 'acelerator_css_callback' ),
            'acelerator-setting-admin',
            'main_section_id'
        );
    }

    public function sanitize($input)
    {
        $new_input = array();
        if (isset($input['acelerator_selector_string'])) {
            $new_input['acelerator_selector_string'] = sanitize_text_field($input['acelerator_selector_string']);
        }

        if (isset($input['acelerator_syntaxes'])) {
            $new_input['acelerator_syntaxes'] = sanitize_text_field($input['acelerator_syntaxes']);
        }

        if (isset($input['acelerator_css'])) {
            $new_input['acelerator_css'] = sanitize_text_field($input['acelerator_css']);
        }

        return $new_input;
    }

    public function print_section_info()
    {
        print 'Leave as is if you do not know what you are doing...';
    }

    public function acelerator_selector_string_callback()
    {
        printf(
            '<input type="text" id="acelerator_selector_string" name="acelerator_options[acelerator_selector_string]" value="%s" style="width:95%%" />',
            isset($this->options['acelerator_selector_string']) ? esc_attr($this->options['acelerator_selector_string']) : ''
        );
        echo '<p class="description">', __('Default value: <code>textarea[name="data_source"]</code>', 'aceleterator'), "</p>";
    }

    public function acelerator_syntaxes_callback()
    {
        printf(
            '<input type="text" id="acelerator_syntaxes" name="acelerator_options[acelerator_syntaxes]" value="%s" style="width:95%%" />',
            isset($this->options['acelerator_syntaxes']) ? esc_attr($this->options['acelerator_syntaxes']) : ''
        );
        echo '<p class="description">', __('Default value: <code>h4:contains(\'CSS Code\')@css|h4:contains(\'Javascript Code\')@javascript|h4:contains(\'PHP Code\')@php</code>', 'aceleterator'), "</p>";
    }

    public function acelerator_css_callback()
    {
        printf(
            '<input type="text" id="acelerator_css" name="acelerator_options[acelerator_css]" value="%s" style="width:95%%" />',
            isset($this->options['acelerator_css']) ? esc_attr($this->options['acelerator_css']) : ''
        );
        echo '<p class="description">', __('Default value: <code>height: 400px; font-size: 1em; font-family: \'Operator Mono Book\', \'Source Code Pro\', \'Fira Code\', Inconsolata, Monofur, Monaco, monospace;</code>', 'aceleterator'), "</p>";
    }
}
