<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       bertdoornbusch
 * @since      1.0.0
 *
 * @package    Donate
 * @subpackage Donate/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Donate
 * @subpackage Donate/public
 * @author     Bert Doornbusch <bert.doornbusch@gmail.com>
 */
require_once 'quick-range-slider.php';

class Donate_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $qrs;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Donate_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Donate_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/donate-public.css', array(), $this->version, 'all');
        // TODO - uitzoeken of dit naar admin deel van de plugin moet
//        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/settings.css', array(), $this->version, 'all');
//        wp_enqueue_style($this->plugin_name,'qrs_style', plugin_dir_url(__FILE__).'css/quick-range-slider.css', array(), $this->version, 'all');
//        wp_enqueue_style($this->plugin_name,'qrs_custom', plugin_dir_url(__FILE__).'css/quick-range-custom.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Donate_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Donate_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/donate-public.js', array('jquery'), $this->version, false);
        // TODO - uitzoeken of dit hier moet of op een andere plek
        wp_enqueue_script($this->plugin_name,plugin_dir_url(__FILE__).'js/quick-range-slider.js', array('jquery'), $this->version, true);
//        wp_enqueue_script($this->plugin_name,"jquery-effects-core");

    }

    /**
     * Function that shows a donation form.
     *
     * @since   1.0.0
     */
    public function show_donation_form($atts)
    {
        $qrs = new QuickRangeSlider();
        $output = $qrs->qrs_rangeslider($atts);


        return $output;
    }

    public function qrs_scripts() {
        QuickRangeSlider::qrs_scripts();
    }

    public function qrs_create_css_file($update) {
        QuickRangeSlider::qrs_create_css_file($update);
    }

    public function qrs_plugin_action_links($links, $file) {
        QuickRangeSlider::qrs_plugin_action_links($links, $file);
    }

}
