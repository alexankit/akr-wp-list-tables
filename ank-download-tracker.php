<?php
/*
Plugin Name: Ank Download Tracker
Plugin URI: https://github.com/akrana1990
Description:  WordPress Plugin.
Version: 0.1
Author: Ankit Rana
Author URI: http://akrana1990.github.io/
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
?>
<?php
/* No direct access*/
if (!defined('ABSPATH')) exit;

define('ADT_PLUGIN_VER', '0.1');
define('ADT_BASE_FILE', __FILE__);
define('ADT_AJX_ACTION', 'adt_download');

class Ank_Download_Tracker
{

    function __construct()
    {

        add_shortcode('ank_download_tracker', array($this, 'do_shortCode'));

    }

    function do_shortCode($params)
    {

        $params = shortcode_atts(array(
            'file_id' => 0,
            'title' => 'Download'
        ), $params);

        ob_start();
        if ($params['file_id'] == 0) {
            return;
        }


        add_action('wp_footer', array($this, 'add_form_to_footer'), 20);
        wp_enqueue_script('adt-user-script', plugins_url('assets/adt-user.js', __FILE__), array('jquery'), 0.1, true);
        wp_enqueue_style('adt-user-style', plugins_url('assets/adt-user.css', __FILE__), array(), 0.1, true);

        echo '<a class="adt_link" data-id="' . $params['file_id'] . '" href="#">' . $params['title'] . '</a>';

    }


    function add_ajax_url_to_page()
    {
        ?>
        <script type="text/javascript">
            /* <![CDATA[ */
            var _adt_opt = {
                ajax_url: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
                ajax_action: "<?php echo ADT_AJX_ACTION ?>"
            };
            /* ]]> */
        </script>
    <?php

    }

    function add_form_to_footer()
    {
        $this->add_ajax_url_to_page();
        //form html
        ?>
        <form id="adt_form" name="adt_form" method="get" action="" style="display: none">
            <input type="text" name="name">
            <input type="email" name="email" required="">
            <input type="submit" name="submit">
        </form>
    <?php
    }


}


if (is_admin()) {
    /* Load admin part only if we are inside wp-admin */
    require(trailingslashit(dirname(__FILE__)) . "adt-admin.php");
    //init admin class
    global $Ank_Download_Tracker_Admin;
    $Ank_Download_Tracker_Admin = new Ank_Download_Tracker_Admin();
} else {
    /*init front end part*/
    global $Ank_Download_Tracker;
    $Ank_Download_Tracker = new Ank_Download_Tracker();
}