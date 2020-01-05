<?php
//add_shortcode('qrs', 'qrs_rangeslider');
//add_action('init', 'qrs_create_css_file');
//add_action('wp_enqueue_scripts', 'qrs_scripts');
//add_filter('plugin_action_links', 'qrs_plugin_action_links', 10, 2);

// TODO - activeren of verplaatsen naar admin deel van de plugin
if (is_admin()) require_once (plugin_dir_path(__FILE__) . '../admin/settings.php');

class QuickRangeSlider
{

    public static function qrs_rangeslider($atts)
    {
        extract(shortcode_atts(array('max' => '100', 'min' => '0', 'step' => '10', 'value' => '50'), $atts));
        $style = QuickRangeSlider::qrs_get_stored_style();
        $output = '<div class="range">
    <input type="range" min="' . $min . '" max="' . $max . '" value="' . $value . '" step="' . $step . '" data-rangeslider>
    <div class="slideroutput">';
        if ($style['output-values']) {
            $output .= '<span class="sliderleft">' . $min . '</span>
        <span class="slidercenter"><output></output></span>
        <span class="sliderright">' . $max . '</span>';
        } else {
            $output .= '<span class="outputcenter"><output></output></span>';
        }
        $output .= '</div><div style="clear: both;"></div></div>
    <script>
    jQuery(document).ready(function($){
        $(function() {
        var $document = $(document),selector = \'[data-rangeslider]\',$inputRange = $(selector);
        function valueOutput(element) {var value = element.value,output = element.parentNode.getElementsByTagName(\'output\')[0];output.innerHTML = value;}
        for (var i = $inputRange.length - 1; i >= 0; i--) {valueOutput($inputRange[i]);};
        $document.on(\'change\', selector, function(e) {valueOutput(e.target);});
        $inputRange.rangeslider({polyfill: false,});
        });
    });
    </script>';
        return $output;
    }

    static function qrs_scripts()
    {
        wp_enqueue_style('qrs_style', plugins_url('css/quick-range-slider.css', __FILE__));
        wp_enqueue_style('qrs_custom', plugins_url('css/quick-range-custom.css', __FILE__));
        wp_enqueue_script("jquery-effects-core");
        wp_enqueue_script('qrs_script', plugins_url('js/quick-range-slider.js', __FILE__), array('jquery'), false, true);
    }

    static function qrs_plugin_action_links($links, $file)
    {
        if ($file == plugin_basename(__FILE__)) {
            $qrs_links = '<a href="' . get_admin_url() . 'options-general.php?page=donate/admin/settings.php">' . __('Settings') . '</a>';
            array_unshift($links, $qrs_links);
        }
        return $links;
    }

    static function qrs_create_css_file($update)
    {
        if (function_exists('file_put_contents')) {
            $css_dir = plugin_dir_path(__FILE__) . '/css/quick-range-custom.css';
            $filename = plugin_dir_path(__FILE__);
            if (is_writable($filename) && (!file_exists($css_dir) || !empty($update))) {
                $data = QuickRangeSlider::qrs_generate_css();
                file_put_contents($css_dir, $data, LOCK_EX);
            }
        } else add_action('wp_head', 'qrs_head_css');
    }

    static function qrs_generate_css()
    {
        $style = QuickRangeSlider::qrs_get_stored_style();
        $data = '.rangeslider, .rangeslider__fill {background: ' . $style['slider-background'] . ';}
    .rangeslider__fill {background: ' . $style['slider-revealed'] . ';}
    .rangeslider__handle {background: ' . $style['handle-background'] . ';
    border: 1px solid ' . $style['handle-border'] . ';}';
        return $data;
    }

    static function qrs_head_css()
    {
        $data = '<style type="text/css" media="screen">' . qrs_generate_css() . '</style>';
        echo $data;
    }

    static function qrs_get_stored_style()
    {
        $style = get_option('qrs_style');
        if (!is_array($style)) $style = array();
        $default = QuickRangeSlider::qrs_get_default_style();
        $style = array_merge($default, $style);
        return $style;
    }

    static function qrs_get_default_style()
    {
        $style = array();
        $style['slider-background'] = '#CCC';
        $style['slider-revealed'] = '#00ff00';
        $style['handle-background'] = 'white';
        $style['handle-border'] = '#CCC';
        $style['output-size'] = '';
        $style['output-colour'] = '#009900';
        return $style;
    }
}