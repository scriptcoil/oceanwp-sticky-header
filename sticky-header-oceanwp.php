<?php
/**
 * Plugin Name:			Sticky Header OceanWP
 * Description:			Want a new fresh sticky header like everyone else, with our plugin you can get the best and better sticky header that you ever seen
 * Version:				1.0.8
 * Author:				Oren Hahiashvili
 * Author URI:			https://www.script.co.il
 * Requires at least:	3.5.0
 * Tested up to:		5.8
 * Text Domain: sticky-header-oceanwp
 * Domain Path: /languages/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class sticky_header_oceanwp {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'init', array( $this, 'init_stickyheader' ) );
	}

	/* FRONT HANDLER START */
	function init_stickyheader(){
		
		// LOAD PLUGIN LANGUAGE TRANSLATION FILES
		$this->stickyheader_load_translations();
		
		// LOAD PLUGIN SCRIPTS AND STYLES
		add_action( 'wp_enqueue_scripts', array( $this, 'stickyheader_load_assets' ) );
		
		// OUTPUT BY SCRIPT VALUES STICKY HEADER STYLING TYPE
		add_action( 'wp_head', array( $this, 'stickyheader_output_style' ) );

	}
	
	function stickyheader_output_style(){
		
		echo '<script>var sticky_header_style = "'. get_option('oceanwp_header_style') .'";</script>';
		
	}
	
	function stickyheader_load_assets(){
		
        wp_enqueue_style( 'sticky-header-oceanwp-style', plugin_dir_url( __FILE__ ) . '/style.css', '1.0.0' );
        wp_enqueue_script( 'sticky-header-oceanwp', plugin_dir_url( __FILE__ ) . '/main.js', array( 'jquery' ), '1.0.0' );
		
	}
	
	function stickyheader_load_translations() {
		load_plugin_textdomain( 'sticky-header-oceanwp', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
	}
	/* FRONT HANDLER END */
	
	
	/* ADMIN HANDLER START */
	function admin_menu() {
		add_options_page(
			'Ocean Theme Sticky Header',
			__('Sticky Header', 'sticky-header-oceanwp'),
			'manage_options',
			'sticky-header-oceanwp',
			array(
				$this,
				'settings_page'
			)
		);
	}

	function settings_page() {
	    
		$sticky_header_styles = array(
		    'float',
		    'shrink',
	    );
	    
	    if (!empty($_POST['oceanwp_header_style']) && in_array($_POST['oceanwp_header_style'], $sticky_header_styles)){
	        update_option('oceanwp_header_style', esc_sql($_POST['oceanwp_header_style']));
	        echo '<h2>update success</h2>';
	    }
	    
		$current_style = get_option('oceanwp_header_style');
		if ($current_style == 'fixed') {
			update_option('oceanwp_header_style', 'shrink');
			$current_style = 'shrink';
		}
		
?>
		<h1><?php _e('Select your sticky header style', 'sticky-header-oceanwp'); ?></h1>
		<form method="post">
		    <label><?php _e('Style', 'sticky-header-oceanwp') ?>: <select name="oceanwp_header_style">
		        <?php foreach ($sticky_header_styles as $style): ?>
		        <option<?php echo $style == $current_style ? ' selected' : '' ?>><?php echo $style ?></option>
		        <?php endforeach; ?>
		    </select></select></label>
		    <br><label><button type="submit"><?php _e('Save', 'sticky-header-oceanwp') ?></button></label>
		</form>
		
		<?php
	}
	/* ADMIN HANDLER END */
	
	
}

new sticky_header_oceanwp;
