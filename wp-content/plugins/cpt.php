<?php

/*
 * Plugin Name: CPT
 * Description: Custom Post Types
 * Version:     1.0.0
 * Author:
 * License:     GPL-2.0+
 */
 
 
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
			
		/*register_taxonomy('project_kind', array('project'), array(
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
			) );*/

		function product_metabox() {

			global $post;
			
			add_meta_box( __( 'Products', '' ), __( 'Product Meta Data', '' ), 'product_metabox_process', array ( 'product' ), 'normal', 'low' );
			
		}

        add_action( 'add_meta_boxes', 'product_metabox' );	

        function product_metabox_process() {
	
	        global $post;	

            $p = $post;

            $terms = get_the_terms( $p, 'product_kind' );
            $out = '<div class="product_meta_box">';			
            //var_dump($terms);	
            if ( $terms && count($terms) > 0 ) {
				foreach ( $terms as $idx => $t ) { 
				$out .= '<div id="term-' . $t->term_id . '" class="product_meta ' . $t->slug . '">';
				$method = 'meta_' . $t->slug;
				$out .= $method( $p, $t ) .  
                $out .= '</div>';				
				}
			}				
					
			$out .= '</div>';
			
			echo $out;
			
	    }
		
		function meta_led_engines( $post , $terms) {
			
			$defaults = array( 'model_code'     => '',
			                   'brand_series'   => '',
							   'power'          => '',
							   'lumen_output'   => '',
							   'beam_angle'     => '',
							   'color_temp'     => '',
							   'cri'            => '',
							   'led_binning'    => '' ); 
			
			$meta = get_post_meta( $post->ID, $terms->slug . '_meta', true );
			$meta = json_decode( $meta, true );
			if ( !$meta ) $meta = array();
			$set = shortcode_atts( $defaults, $meta );
			
			$out = '';
			
			foreach ( $set as $name => $val ) {
				$out .= '<div id="metafield_' . $name . '" class="metafield_box">';
				$out .= '<input id="' . $name . '_input" name="' . $terms->slug . '_meta' . '[' . $name . ']' . '" type="text" value="' . $val . '">';
				$out .= '<label for="' . $name . '_input">' . $name . '</label>';
				$out .= '</div>';
			}
			
			return $out;
			
		}
		
		
		function meta_luminaires( $post , $terms ) {
			
			$defaults = array( 'model_code'        => '',
			                   'application'       => '',
							   'mounting_type'     => '',
							   'fixture_shape'     => '',
							   'fixture_type'      => '',
							   'bezel_size'        => '',
							   'cut_out_size'      => '',
							   'ip_rating'         => '', 
							   'reflector_finish'  => '',
							   'trim_finish'       => '' ); 
			
			$meta = get_post_meta( $post->ID, $terms->slug . '_meta', true );
			$meta = json_decode( $meta, true );
			if ( !$meta ) $meta = array();
			$set = shortcode_atts( $defaults, $meta );
			
			$out = '';
			
			foreach ( $set as $name => $val ) {
				$out .= '<div id="metafield_' . $name . '" class="metafield_box">';
				$out .= '<input id="' . $name . '_input" name="' . $terms->slug . '_meta' . '[' . $name . ']' . '" type="text" value="' . $val . '">';
				$out .= '<label for="' . $name . '_input">' . $name . '</label>';
				$out .= '</div>';
			}
			
			return $out;
			
		}


		function meta_linear_led( $post , $terms ) {
			
			$defaults = array( 'led_series'        => '',
			                   'lighting_type'     => '',
							   'application'       => '',
							   'lumens_m'          => '',
							   'wattage_m'         => '',
							   'leds/m'           => '',
							   'colour_temp'       => '',
							   'cri'               => '', 
							   'ip_rating'         => '' ); 
			
			$meta = get_post_meta( $post->ID, $terms->slug . '_meta', true );
			$meta = json_decode( $meta, true );
			if ( !$meta ) $meta = array();
			$set = shortcode_atts( $defaults, $meta );
			
			$out = '';
			
			foreach ( $set as $name => $val ) {
				$out .= '<div id="metafield_' . $name . '" class="metafield_box">';
				$out .= '<input id="' . $name . '_input" name="' . $terms->slug . '_meta' . '[' . $name . ']' . '" type="text" value="' . $val . '">';
				$out .= '<label for="' . $name . '_input">' . $name . '</label>';
				$out .= '</div>';
			}
			
			return $out;
			
		}	


		function meta_track_surface( $post , $terms ) {
			
			$defaults = array( 'model_name'        => '',
			                   'cri'               => '',
							   'lumen_output'      => '',
							   'application'       => '',
							   'mounting_type'     => '',
							   'optics'            => '',
							   'dimming_protocol'  => '',
							   'colour_finish'     => '' ); 
			
			$meta = get_post_meta( $post->ID, $terms->slug . '_meta', true );
			$meta = json_decode( $meta, true );
			if ( !$meta ) $meta = array();
			$set = shortcode_atts( $defaults, $meta );
			
			$out = '';
			
			foreach ( $set as $name => $val ) {
				$out .= '<div id="metafield_' . $name . '" class="metafield_box">';
				$out .= '<input id="' . $name . '_input" name="' . $terms->slug . '_meta' . '[' . $name . ']' . '" type="text" value="' . $val . '">';
				$out .= '<label for="' . $name . '_input">' . $name . '</label>';
				$out .= '</div>';
			}
			
			return $out;
			
		}
		
		
		function meta_contractor_range( $post , $terms ) {
			
			$defaults = array( 'downlights'           => '',
			                   'led_strip'            => '',
							   'led_profile'          => '',
							   'trip_roof'            => '',
							   'led_panel'            => '',
							   'bulk_head'            => '' ); 
			
			$meta = get_post_meta( $post->ID, $terms->slug . '_meta', true );
			$meta = json_decode( $meta, true );
			if ( !$meta ) $meta = array();
			$set = shortcode_atts( $defaults, $meta );
			
			$out = '';
			
			foreach ( $set as $name => $val ) {
				$out .= '<div id="metafield_' . $name . '" class="metafield_box">';
				$out .= '<input id="' . $name . '_input" name="' . $terms->slug . '_meta' . '[' . $name . ']' . '" type="text" value="' . $val . '">';
				$out .= '<label for="' . $name . '_input">' . $name . '</label>';
				$out .= '</div>';
			}
			
			return $out;
			
		}		
		
		
		
	}
	
	function save_product( $id, $post, $event ) {
		$terms = get_the_terms( $p, 'product_kind' );
		if ( count( $terms) > 0 ) {
			foreach ( $terms as $idx => $t_set ) {
				if ( isset($_POST[$t_set->slug . '_meta']) ) {
					$data = $_POST[$t_set->slug . '_meta'];
					update_post_meta( $id, $t_set->slug . '_meta', json_encode( $data ) );
				}
			}
		}
	}
	
	add_action( 'save_post_product', 'save_product' );
	
	
	
	
	
	
 
 ?>