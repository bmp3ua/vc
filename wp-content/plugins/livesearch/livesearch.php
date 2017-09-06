<?php

/*
 * Plugin Name: Live Search
 * Description: Ajax Live Search
 * Version:     1.0.0
 * Author:
 * License:     GPL-2.0+
 */

if ( ! class_exists( 'LiveSearch' ) ) {
	class LiveSearch {
		
		public static function init() {

			global $wp_filesystem;
			
			add_action('admin_menu', 'LiveSearch::livesearch_submenu' );
			
            add_action( 'wp_enqueue_scripts', 'LiveSearch::enqueue_livesearch' );
			add_action( 'wp_ajax_index_search_data', 'LiveSearch::index_search_data' );
            add_action( 'wp_ajax_livesearch', 'LiveSearch::search' );
            add_action( 'wp_ajax_nopriv_livesearch', 'LiveSearch::search' );
			add_action( 'wp_enqueue_scripts', 'LiveSearch::customajax_data', 99 );
		
		}
		
		public static function customajax_data() {
			wp_localize_script('livesearch_front_js', 'customajax', 
				array(
					'url' => admin_url('admin-ajax.php')
				)
			); 			
		}
		
		public static function livesearch_submenu() {
			$sub_page = add_submenu_page( 'tools.php', __( 'Index Search Data', "" ), __( 'Index Search Data', "" ), 'activate_plugins', 'livesearch-dashboard', 'LiveSearch::dashboard' );
			add_action('admin_enqueue_scripts', 'LiveSearch::enqueue_admin_scripts', 1);
		}

        public static function enqueue_admin_scripts() {
			wp_enqueue_style( 'livesearch_admin_css', plugin_dir_url( __FILE__ ) . 'css/admin.css' );
			wp_enqueue_script( 'livesearch_admin_js', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ) );
        }			
		
		public static function enqueue_livesearch() {
			wp_enqueue_script( 'livesearch_front_js', plugin_dir_url( __FILE__ ) . 'js/livesearch.js', array( 'jquery' ) );
		}
		
        public static function dashboard() {
			
			$out  = '<div class="ls_box w-row">';
			$out .= '<div class="button_box w-col"><button id="index_data" class="btn" value="">Index Search Data</button></div>';
			$out .= '</div>';
			
			echo $out;
			
        }

        public static function index_search_data() {
			
			global $wpdb;
			
			$args = array(
                'public'   => true,
                '_builtin' => false
            );
			
			$pts = get_post_types( $args, 'names' );
			foreach ( $pts as $pt ) {
				$query = "
					SELECT DISTINCT($wpdb->postmeta.meta_key) 
					FROM $wpdb->posts 
					LEFT JOIN $wpdb->postmeta 
					ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
					WHERE $wpdb->posts.post_type = '%s' 
					AND $wpdb->postmeta.meta_key != '' 
					AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' 
					AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'
				";
                $meta_keys = $wpdb->get_col($wpdb->prepare($query, $pt));
                var_dump($meta_keys);				
			}
			
			wp_die();			
			
			
        }		
		
		public static function search() {
			
			global $wpdb;
			$attrs = json_decode( get_option( 'cpt_attrs' ), true );
			
			$s = $_POST['content'];
			
			//$_POST['pt'] = 'product';
			
			if ( isset($_POST['pt']) ) $pt_clause = " AND post_type='" . $_POST['pt'] . "'"; else $pt_clause = '';
			
            $query = 
			"SELECT DISTINCT p.ID FROM $wpdb->posts as p INNER JOIN $wpdb->postmeta as pm
            ON ( p.ID = pm.post_id ) WHERE (p.post_title LIKE '%" . $s . "%' OR pm.meta_value LIKE '%" . $s . "%')" . $pt_clause;            			
			$post_result = $wpdb->get_results( $query, ARRAY_A );
			var_dump($post_result);
				
			
			wp_die();
			
		}
		
	}
}

LiveSearch::init();