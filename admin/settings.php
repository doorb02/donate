<?php
//add_action('admin_menu', 'qrs_page_init');
//add_action('admin_notices', 'qrs_admin_notice' );
//add_action('admin_enqueue_scripts', 'qrs_settings_scripts');
class Settings
{

    static function qrs_settings_scripts()
    {
        QuickRangeSlider::qrs_scripts();
        wp_enqueue_style('qrs_settings', plugins_url('css/settings.css', __FILE__));
    }

    static function qrs_page_init()
    {
        add_options_page('Donate', 'Donate', 'manage_options', __FILE__, 'settings::qrs_tabbed_page');
    }

    static function qrs_admin_tabs($current = 'shortcodes')
    {
        $tabs = array('shortcodes' => 'Shortcodes', 'styles' => 'Styling');
        echo '<h2 class="nav-tab-wrapper">';
        foreach ($tabs as $tab => $name) {
            $class = ($tab == $current) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=donate/admin/settings.php&tab=$tab'>$name</a>";
        }
        echo '</h2>';
    }

    static function qrs_tabbed_page()
    {
        echo '<div class="wrap">';
        echo '<h1>Quick Toggle Text</h1>';
        if (isset ($_GET['tab'])) {
            Settings::qrs_admin_tabs($_GET['tab']);
            $tab = $_GET['tab'];
        } else {
            Settings::qrs_admin_tabs('shortcodes');
            $tab = 'shortcodes';
        }
        switch ($tab) {
            case 'styles' :
                Settings::qrs_styles();
                break;
            case 'shortcodes' :
                Settings::qrs_shortcodes();
                break;
        }
        echo '</div>';
    }

    static function qrs_shortcodes()
    {
        $content = '<div class="qrs-options">
		<h2>Using the Plugin</h2>
		<p>The default slider uses a shortcode like this:</p>
		<p><code>[donate]</code></p>
		<p>The slider carries five parameters:</p>
<ul>
<li><strong>Name</strong> - The field name</li>
<li><strong>Max</strong> - The maximum value.</li>
<li><strong>Min</strong>  - The minimum value</li>
<li><strong>Step</strong> - The size of the step between each value</li>
<li><strong>Value</strong> -  - The initial value</li>
</ul>
<p>You can set these in the shortcode like this:</p>
<p><code>[donate name="Value of Product" max=500 min=100 step=50 value=300]</code></p>
		<p>For more information on using shortcodes see the <a href="http://codex.wordpress.org/Shortcode" target="blank">WordPress support page</a></p>
		</div>
		<div class="qrs-options">
		<h2>Slider Test</h2>';
        $atts = array('name'=> 'Howdy user', 'max' => '500', 'min' => '100', 'step' => '50', 'value' => '300');
        $content .= QuickRangeSlider::qrs_rangeslider($atts);
        $content .= '</div>';
        echo $content;
    }

    static function qrs_styles()
    {
        if (isset($_POST['Submit'])) {
            $options = array('slider-background', 'slider-revealed', 'handle-background', 'handle-border', 'output-size', 'output-colour', 'output-values'
            );
            foreach ($options as $item) {
                $style[$item] = stripslashes($_POST[$item]);
            }
            update_option('qrs_style', $style);
            QuickRangeSlider::qrs_create_css_file('update');
            Settings::qrs_admin_notice("The slider styles have been updated.");
        }
        if (isset($_POST['Reset'])) {
            delete_option('qrs_style');
            QuickRangeSlider::qrs_create_css_file('update');
            Settings::qrs_admin_notice("The slider styles have been reset.");
        }
        $style = QuickRangeSlider::qrs_get_stored_style();
        print_r($style);
        $content = '<div class="qrs-options">
		<form method="post" action="">
		<table>
		<tr>
<td colspan="2"><h2>Slider</h2></td>
</tr>
<tr>
<td>Normal Background</td>
<td><input type="text" style="width:6em" label="input-border" name="slider-background" value="' . $style['slider-background'] . '" /></td>
</tr>
<tr>
<td>Revealed Background</td>
<td><input type="text" style="width:6em" label="input-border" name="slider-revealed" value="' . $style['slider-revealed'] . '" /></td>
</tr>
<tr>
<td colspan="2"><h2>Handle</h2></td>
</tr>
<tr>
<td>Background</td>
<td><input type="text" style="width:6em" label="input-border" name="handle-background" value="' . $style['handle-background'] . '" /></td>
</tr>
<tr>
<td>Border colour</td>
<td><input type="text" label="input-border" name="handle-border" value="' . $style['handle-border'] . '" /></td>
</tr>

<tr>
<td colspan="2"><h2>Output</h2></td>
</tr>
<tr>
<td>Size</td>
<td><input type="text" style="width:3em" label="input-border" name="output-size" value="' . $style['output-size'] . '" /></td>
</tr>
<tr>
<td>Colour</td>
<td><input type="text" style="width:6em" label="input-border" name="output-colour" value="' . $style['output-colour'] . '" /></td>
</tr>
<tr>
<td></td>
<td><input type="checkbox" style="margin: 0; padding: 0; border: none;" name="output-values" ' . $style['output-values'] . ' value="checked" /> Show max and min values<td>
<tr>
</table>
<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Save Changes" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the styles?\' );"/></p>
</form>
</div>
<div class="qrs-options">
<h2>Slider Test</h2>';
        $atts = array('max' => '500', 'min' => '100', 'step' => '50', 'value' => '300');
        $content .= QuickRangeSlider::qrs_rangeslider($atts);
        $content .= '</div>';
        echo $content;
    }

    static function qrs_admin_notice($message)
    {
        if (!empty($message)) echo '<div class="updated"><p>' . $message . '</p></div>';
    }

}