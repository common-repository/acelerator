<?php
/*
Plugin Name: Acelerator
Plugin URI: https://www.rationalplanet.com/linux/acelerator-wordpress-plugin.html
Version: 0.1
Description: Enhance any textarea with ace editor
Author: Alexander Missa
Author URI: https://www.rationalplanet.com
*/

if (! defined('ABSPATH')) {
    exit;
}

/**
* @classname Acelerator_Textarea_Enhancer
* @author alec (alecksmart@rationalpalnet.com)
* @version 1.0
*/
class Acelerator_Textarea_Enhancer
{
    public $config;
    public $options;

    public function __construct($config)
    {
        $this->config = $config;
        $this->options = $config->getOptions();
        $this->on_activate();
        $this->set_hooks();
        $this->set_plugin();
    }

    public function on_activate()
    {
        register_activation_hook(__FILE__, array($this, 'set_default_options'));
    }

    public static function set_default_options()
    {
        $old_options = get_option('acelerator_options');
        $default_options = [
            'acelerator_selector_string' => (
                $old_options && isset($old_options['acelerator_selector_string'])
                    ? $old_options['acelerator_selector_string']
                    : 'textarea[name="data_source"]'
                ),
            'acelerator_syntaxes' => (
                $old_options && isset($old_options['acelerator_syntaxes'])
                ? $old_options['acelerator_syntaxes']
                : 'h4:contains(\'CSS Code\')@css|h4:contains(\'Javascript Code\')@javascript|h4:contains(\'PHP Code\')@php'
            ),
            'acelerator_css' => (
                $old_options && isset($old_options['acelerator_css'])
                ? $old_options['acelerator_css']
                : 'height: 400px; font-size: 1em; font-family: \'Operator Mono Book\', \'Source Code Pro\', \'Fira Code\', Inconsolata, Monofur, Monaco, monospace;'
            ),
        ] ;
        update_option('acelerator_options', $default_options);
    }

    public function set_hooks()
    {
        add_filter(sprintf(
                '%splugin_action_links_%s',
                is_multisite() ? 'network_admin_' : '',
                plugin_basename(__FILE__)
            ), array( $this, 'add_plugin_actions_links' ));
    }

    public function add_plugin_actions_links($links)
    {
        return array_merge(
                 array(sprintf('<a href="%soptions-general.php?page=acelerator-setting-admin">Settings</a>', get_admin_url()) ),
                 $links
             );
    }

    public function set_plugin()
    {
        add_action("in_admin_footer", function () {
            $plugindir = plugin_dir_url(__FILE__);
            $css = trim($this->options['acelerator_css']) ? ' '.trim($this->options['acelerator_css']) : '' ?>
<script type="text/javascript">
jQuery(document).ready(function () {
  (function($) {
    var selectorsStr = "<?php echo addslashes($this->options['acelerator_selector_string']); ?>";
    var syntaxStr = "<?php  echo addslashes($this->options['acelerator_syntaxes']); ?>";
    var selectors = selectorsStr.split('|'), syntaxes = syntaxStr.split('|'), currentSyntax = false;
    var hasAreas = false;
    selectors.forEach(function(selector){
        if($(selector).length > 0 && !hasAreas){
            hasAreas = true;
        }
    });
    if(!hasAreas){
        return;
    }
    syntaxDefined = false;
    syntaxes.forEach(function(rule){
        if(!syntaxDefined){
            var check = rule.split('@');
            if($(check[0]).length > 0){
                syntaxDefined = true;
                currentSyntax = check[1];
            }
        }
    });
    var base = '<?php echo $plugindir ?>ace-builds-master/src-min-noconflict/';
    var css = "<?php echo $css ?>";
    $.getScript( base + "ace.js" )
      .done(function( script, textStatus ) {
        var index = 0;
        selectors.forEach(function(selector){
            if($(selector).length <= 0 ){
                return;
            }
            index++;
            var editorname = 'acelerator' + index;
            $(selector).after('<style>#'+ editorname + ' {position: relative;top: 0;right: 0;bottom: 0;left: 0;' + css + '}</style><div id="' + editorname + '"></div>').hide();
            $('#'+ editorname).text($(selector).val());
            var editor = ace.edit(editorname);
                ace.config.set('basePath', base);
                editor.getSession().setUseWorker(false);
                editor.setTheme("ace/theme/monokai");
            if(currentSyntax){
                editor.session.setMode('ace/mode/' + currentSyntax);
                switch(currentSyntax){
                    case 'php':
                        editor.setOption("beautify", true);
                    case 'css':
                    case 'javascript':
                        editor.setOption("enableEmmet", true);
                        editor.setOption("searchbox", true);
                        editor.setOptions({
                            enableBasicAutocompletion: true,
                            enableSnippets: true,
                            enableLiveAutocompletion: true
                        });
                    default:
                        break;
                }
            }
            var form = $(selector).closest("form");
            $(document).on('submit', $(form),function(){
                $(selector).val(editor.getSession().getValue());
            });
        });
      })
      .fail(function( jqxhr, settings, exception ) {});
  })(jQuery);
});
</script>
<?php
        });
    }
}

if (is_admin()) {
    require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'options.php');
    $acelerator_Textarea_Enhancer_Options = new Acelerator_Textarea_Enhancer_Options();
    $acelerator_Textarea_Enhancer = new Acelerator_Textarea_Enhancer($acelerator_Textarea_Enhancer_Options);
}
