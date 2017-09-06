<?php

/*
 * Plugin Name: CPT
 * Description: Custom Post Types
 * Version:     1.0.0
 * Author:
 * License:     GPL-2.0+
 */
 
    function custom_add_category( $cat_name, $cat_slug, $tax ) {
		$r = wp_insert_term( $cat_name,
		                     $tax,
						     array( 'slug' => $cat_slug )
					       );
	}
 
    add_action( 'admin_init', 'save_attrs' );
	
	function save_attrs() { 
 
 		$attrs = array(
		    'led_engines' => array( 'led_model_code'     => '',
			                        'brand_series'   => '',
							        'power'          => '',
							        'lumen_output'   => '',
							        'beam_angle'     => '',
							        'color_temp'     => '',
							        'led_cri'            => '',
							        'led_binning'    => '' ),
			'luminaires'  => array ( 'lum_model_code'        => '',
			                         'application'       => '',
							         'mounting_type'     => '',
							         'fixture_shape'     => '',
							         'fixture_type'      => '',
							         'bezel_size'        => '',
							         'cut_out_size'      => '',
							         'ip_rating'         => '', 
							         'reflector_finish'  => '',
							         'trim_finish'       => '' ),
			'linear_led'  => array( 'led_series'        => '',
			                        'lighting_type'     => '',
							        'application'       => '',
							        'lumens_m'          => '',
							        'wattage_m'         => '',
							        'leds_m'            => '',
							        'colour_temp'       => '',
							        'lin_cri'               => '', 
							        'ip_rating'         => '' ),
									
			'track_surface' => array( 'model_name'        => '',
			                          'track_cri'               => '',
							          'lumen_output'      => '',
							          'application'       => '',
							          'mounting_type'     => '',
							          'optics'            => '',
							          'dimming_protocol'  => '',
							           'colour_finish'    => '' ),
            'contractor_range' => array ( 'downlights'           => '',
			                              'led_strip'            => '',
							              'led_profile'          => '',
							              'trip_roof'            => '',
							              'led_panel'            => '',
							              'bulk_head'            => '' )									   
		);
		
		update_option( 'cpt_attrs', json_encode( $attrs ) );
		
		foreach( $attrs as $cat => $atts ) {
			$c = get_term_by( 'slug', $cat, 'product_kind' );
            if ( $c ) {
				custom_add_category( $c->name, $c->slug, 'project_kind' );
			}				
		}
		
	}
	
	add_action( 'admin_enqueue_scripts', 'get_scripts' );
	
	function get_scripts() {
		
		wp_enqueue_style( 'cpt_css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/admin.css' );
		
		wp_enqueue_script( 'jquery-ui-core', array('jquery'));	

        wp_enqueue_script( 'cpt_js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/admin.js', array('jquery', 'jquery-ui-core') );		
		
	}
 
 
    add_action( 'init', 'set_post_types' );
 
	function set_post_types(){ 
		register_post_type('product', array(
			'label'  => 'event',
			'labels' => array(
				'name'               => 'product', 
				'singular_name'      => 'product', 
				'add_new'            => 'Add Product', 
				'add_new_item'       => 'Add New Product', 
				'edit_item'          => 'Edit Product', 
				'new_item'           => 'New Product', 
				'view_item'          => 'View Product', 
				'search_items'       => 'Search Products', 
				'not_found'          => 'No Products Found', 
				'not_found_in_trash' => 'No Products in Trash', 
				'parent_item_colon'  => '', 
				'menu_name'          => 'Products', 
			),
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => null,
			'show_ui'             => true,
			'show_in_menu'        => true, 
			'show_in_admin_bar'   => true, 
			'show_in_nav_menus'   => true,
			'show_in_rest'        => null, 
			'rest_base'           => null, 
			'menu_position'       => 40,
			'menu_icon'           => null, 
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'          => array(),
			'has_archive'         => true,
			'rewrite'             => array( 'slug' => 'products', 'with_front' => true ),
			'query_var'           => true,
		) );

		register_post_type('project', array(
			'label'  => 'event',
			'labels' => array(
				'name'               => 'project', 
				'singular_name'      => 'project', 
				'add_new'            => 'Add Project', 
				'add_new_item'       => 'Add New Project', 
				'edit_item'          => 'Edit Project', 
				'new_item'           => 'New Project', 
				'view_item'          => 'View Project', 
				'search_items'       => 'Search Projectss', 
				'not_found'          => 'No Projects Found', 
				'not_found_in_trash' => 'No Projects in Trash', 
				'parent_item_colon'  => '', 
				'menu_name'          => 'Projects', 
			),
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => null,
			'show_ui'             => true,
			'show_in_menu'        => true, 
			'show_in_admin_bar'   => true, 
			'show_in_nav_menus'   => true,
			'show_in_rest'        => null, 
			'rest_base'           => null, 
			'menu_position'       => 40,
			'menu_icon'           => null, 
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'          => array(),
			'has_archive'         => true,
			'rewrite'             => array( 'slug' => 'projects', 'with_front' => true ),
			'query_var'           => true,
		) );
		

		register_taxonomy('product_kind', array('product'), array(
				'label'                 => '', 
				'labels'                => array(
					'name'              => 'Product Categories',
					'singular_name'     => 'kind',
					'search_items'      => 'Search kind',
					'all_items'         => 'All Kinds',
					'parent_item'       => 'Parent Kind',
					'parent_item_colon' => 'Parent Kind:',
					'edit_item'         => 'Edit Kind',
					'update_item'       => 'Update Kind',
					'add_new_item'      => 'Add New Product Kind',
					'new_item_name'     => 'New Product Kind Name',
					'menu_name'         => 'Product Categories',
				),
				'description'           => '', 
				'public'                => true,
				'publicly_queryable'    => true,
				'show_in_nav_menus'     => true, 
				'show_ui'               => true, 
				'show_tagcloud'         => true,
				'show_in_rest'          => null, 
				'rest_base'             => null, 
				'hierarchical'          => true,
				'update_count_callback' => '',
				'rewrite'               => true,
				'query_var'             => 'product_kind', 
				'capabilities'          => array(),
				'meta_box_cb'           => 'post_categories_meta_box', 
				'show_admin_column'     => true, 
				'_builtin'              => false,
				'show_in_quick_edit'    => null, 
			) );
			
		register_taxonomy('project_kind', array('project'), array(
				'label'                 => '', 
				'labels'                => array(
					'name'              => 'Project Kinds',
					'singular_name'     => 'kind',
					'search_items'      => 'Search kind',
					'all_items'         => 'All Kinds',
					'parent_item'       => 'Parent Kind',
					'parent_item_colon' => 'Parent Kind:',
					'edit_item'         => 'Edit Kind',
					'update_item'       => 'Update Kind',
					'add_new_item'      => 'Add New Project Kind',
					'new_item_name'     => 'New Project Kind Name',
					'menu_name'         => 'Project Kind',
				),
				'description'           => '', 
				'public'                => true,
				'publicly_queryable'    => true, 
				'show_in_nav_menus'     => true, 
				'show_ui'               => true, 
				'show_tagcloud'         => true, 
				'show_in_rest'          => null, 
				'rest_base'             => null, 
				'hierarchical'          => true,
				'update_count_callback' => '',
				'rewrite'               => true,
				'query_var'             => 'project_kind', 
				'capabilities'          => array(),
				'meta_box_cb'           => 'post_categories_meta_box', 
				'show_admin_column'     => true, 
				'_builtin'              => false,
				'show_in_quick_edit'    => null, 
			) );


		function product_metabox() {

			global $post;
			
			add_meta_box( __( 'Products', '' ), __( 'Product Meta Data', '' ), 'product_metabox_process', array ( 'product' ), 'normal', 'low' );
			
		}

        add_action( 'add_meta_boxes', 'product_metabox' );	

        function product_metabox_process () {
	
	        global $post, $attrs;	

            $p = $post;

            $product_terms = get_the_terms( $p, 'product_kind' );
			$terms = get_terms( 'product_kind', array(
                        'hide_empty' => false,
                    ) );
            $out = '<div class="product_meta_box tabs">';
            $tab_links = '<ul>'; $tab_content = '<div class="meta_data tabs_box">';			
            
            //if ( $terms && count($terms) > 0 ) {
				foreach ( $terms as $idx => $t ) { 
				    $clickable = ' unclickable ';
				    foreach ( $product_terms as $i => $pt ) {
						if ( $pt->term_id == $t->term_id ) { $clickable = ' clickable '; break; }
					}
				    $tab_links .= '<li><a class="tabs_pointer' . $clickable . '" href="#term-' . $t->term_id . '">' . $t->name . '</a></li>';
				    $tab_content .= '<div id="term-' . $t->term_id . '" class="product_meta tabs_target ' . $t->slug . '"><h3>' .  $t->name . '</h3>';
				    $tab_content .= meta_data( $p, $t );  
                    $tab_content .= '</div>';				
				}
			//}	
            $tab_links .= '</ul>';
            $tab_content .= '</div>';			
					
			$out .= $tab_links . $tab_content . '</div>';
			
			echo $out;
			
	    }
		
		function meta_data( $post , $terms) {
			
			$attrs = json_decode( get_option( 'cpt_attrs' ), true );;
			
			$defaults = $attrs[$terms->slug]; $set = array();
			
			foreach ( $defaults as $name => $val ) {
				$set[$name] = get_post_meta( $post->ID, $name, true );
			}		
			$out = '';
			
			foreach ( $set as $name => $val ) {
				$out .= '<div id="metafield_' . $name . '" class="metafield_box">';
				$out .= '<input id="' . $name . '_input" name="' . $terms->slug . '_meta' . '[' . $name . ']' . '" type="text" value="' . $val . '"/>';
				$out .= '<label for="' . $name . '_input">' . $name . '</label>';
				$out .= '</div>';
			}
			
			return $out;
			
		}	
		
	}
	
	function save_product( $id ) {
	
		$attrs = json_decode( get_option( 'cpt_attrs' ), true );
		$terms = get_the_terms( $p, 'product_kind' );
		foreach ( $attrs as $t_name => $field_set ) {
			foreach ( $field_set as $name => $val ) {
				delete_post_meta( $id, $name );
			}
		}		
		if ( $terms && count( $terms ) > 0 ) {
			foreach ( $terms as $idx => $t_set ) {
				/*foreach ( $attrs[$t_set->slug] as $name => $val ) {
					delete_post_meta( $id, $name );
				}*/
				if ( isset($_POST[$t_set->slug . '_meta']) ) { 
					$data = $_POST[$t_set->slug . '_meta'];
					foreach( $data as $name => $val) {
					    update_post_meta( $id, $name, $val );
					}
				}
			}
		}
	}
	
	add_action( 'save_post_product', 'save_product' );
	
	
	
	
	
	
	
	function project_metabox() {

		global $post;
		
		add_meta_box( __( 'Projects', '' ), __( 'Project Meta Data', '' ), 'project_metabox_process', array ( 'project' ), 'normal', 'low' );
		
	}

	add_action( 'add_meta_boxes', 'project_metabox' );	

	function project_metabox_process () {

		global $post;	

		$p = $post;

		//$terms = get_the_terms( $p, 'project_kind' );
		$terms = get_terms( 'project_kind', array(
					'hide_empty' => false,
				) );
		$out = '<div class="project_meta_box tabs">';
		$tab_links = '<ul>'; $tab_content = '<div class="meta_data tabs_box">';			
		
		//if ( $terms && count($terms) > 0 ) {
			foreach ( $terms as $idx => $t ) { 
				$tab_links .= '<li><a class="tabs_pointer" href="#term-' . $t->term_id . '">' . $t->name . '</a></li>';
				$tab_content .= '<div id="term-' . $t->term_id . '" class="project_meta tabs_target ' . $t->slug . '"><h3>' .  $t->name . '</h3>';
				$tab_content .= meta_data( $p, $t );  
				$tab_content .= '</div>';				
			}
		//}	
		$tab_links .= '</ul>';
		$tab_content .= '</div>';			
				
		$out .= $tab_links . $tab_content . '</div>';
		
		echo $out;
		
	}
		
		
	
	function save_project( $id ) {
	
		$attrs = json_decode( get_option( 'cpt_attrs' ), true );
		$terms = get_the_terms( $p, 'project_kind' );
		if ( $terms && count( $terms ) > 0 ) {
			foreach ( $terms as $idx => $t_set ) {
				foreach ( $attrs[$t_set->slug] as $name => $val ) {
					delete_post_meta( $id, $name );
				}
				if ( isset($_POST[$t_set->slug . '_meta']) ) { 
					$data = $_POST[$t_set->slug . '_meta'];
					foreach( $data as $name => $val) {
					    update_post_meta( $id, $name, $val );
					}
				}
			}
		}
	}
	
	add_action( 'save_post_project', 'save_project' );	
	
	
	
	
	
	
 
 ?>