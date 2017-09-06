<?php

/*
 * Plugin Name: Icons Manager
 * Description: Manages FontAwesome, IcoMoon and other icons
 * Version:     1.0.0
 * Author:
 * License:     GPL-2.0+
 */

if ( ! class_exists( 'SL_Icon_Manager' ) ) {
	class SL_Icon_Manager {
			
		static $_instance = null, $dir_name = 'sl_icons', $upload_dir = array(), $icon_styles = array();
		       

		public static function getInstance() {
			if (null === static::$_instance) {
				static::$_instance = new static();
			}

			return static::$_instance;
		}		

		public static function init() {

			global $wp_filesystem;

			if (empty($wp_filesystem)) {
				require_once (ABSPATH . '/wp-admin/includes/file.php');
				WP_Filesystem();
			}

			$upload_dir = wp_get_upload_dir();

            self::$upload_dir['path'] = $upload_dir['basedir'];
			self::$upload_dir['url'] = $upload_dir['baseurl'];
			self::$upload_dir['tempdir'] = trailingslashit(  self::$upload_dir['path'] ) . self::$dir_name . '/temp_files';

			$icon_folders = scandir( self::$upload_dir['path'] . '/' . self::$dir_name );
			if ( $icon_folders && count ( $icon_folders ) > 0 ) {
				foreach ( $icon_folders as $icon_folder ) { 
					if ( $icon_folder != '.' && $icon_folder != '..' ) {
						$css_files = glob( self::$upload_dir['path'] . '/' . self::$dir_name . '/' . $icon_folder . '/*.css' );
						$css_file = false;
						foreach ( $css_files as $css ) {
							if ( !$css_file && ( basename( $css ) == 'style.css' || basename( $css ) == 'styles.css' || basename( $css ) == $icon_folder . '.css' ) ) {
								self::$icon_styles[$icon_folder]['dir'] = $css; $css_file = true;
							}
						    self::$icon_styles[$icon_folder]['url'][] = self::$upload_dir['url'] . '/' . self::$dir_name . '/' . $icon_folder . '/' . basename( $css );
						}
					}
				}
            }

			add_action('admin_menu', 'SL_Icon_Manager::icon_manager_submenu' );
			add_action('wp_enqueue_scripts', 'SL_Icon_Manager::enqueue_icon_styles' );
			add_action('admin_enqueue_scripts', 'SL_Icon_Manager::enqueue_icon_styles' );

			add_action( 'wp_ajax_sl_add_zipped_font', 'SL_Icon_Manager::add_zipped_font' );
			add_action( 'wp_ajax_sl_remove_zipped_font', 'SL_Icon_Manager::remove_zipped_font' );
			
			
			add_action( 'init', 'SL_Icon_Manager::vc_icon_manager_init' );

		}

		function icon_manager_submenu() {
			$sub_page = add_submenu_page( 'tools.php', __( 'Icon Manager', "sl_menu" ), __( 'Icon Manager', "sl_menu" ), 'activate_plugins', 'icon-manager-dashboard', 'SL_Icon_Manager::icons_table' );
			add_action('admin_enqueue_scripts', 'SL_Icon_Manager::enqueue_admin_scripts', 1);
		}

		function enqueue_admin_scripts() {

			wp_enqueue_script( 'sl_icons_admin_media', plugin_dir_url( __FILE__ ) . 'admin/js/admin.js', array( 'jquery' ) );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_media();
			wp_enqueue_style( 'sl_icons_admin_css', plugin_dir_url( __FILE__ ) . 'admin/css/admin.css' );

		}

		public static function enqueue_icon_styles() {
			foreach ( self::$icon_styles as $name => $style_array ) {
				foreach ( $style_array['url'] as $style ) {
                    wp_enqueue_style( 'sl_icons_admin_' . $name, $style );
				}
			}
		}

		public static function icons_table() {
			$out = '
			<div id="sl_icons_wrap"><h1>' . __( 'Icon Fonts Manager', 'sl_icons' ) .
				'</h1><a href="#sl_iconsset_upload" id="sl_icons_upload" class="button button-secondary button-small">' . __( 'Upload New Icons', 'sl_icons' ) . '</a>
			 <div id="sl_icons_uploader_msg"></div>';

            if ( count ( self::$icon_styles ) > 0 ) {
				$out .= '<div id="sl_icons_admin_box">';
				$out .= '<div id="sl_admin_icons_search">' . __( 'Search by icon name', 'sl_icons' ) .': <input type="text" placeholder="Search"></div>';
				foreach ( self::$icon_styles as $font_name => $style ) {
					$out .= self::get_icons_font_html( $font_name, $style['dir'] );
				}
				$out .= '</div>';
			}
			else {
				$out .= '
				<div class="no_fonts">
					<p>' . __( 'No font icons uploaded. Upload some font icons to display here.', 'sl_icons' ) . '</p>
				</div>';
			}
			$out .= '</div>';

			echo $out;
		}		
		

        public static function get_icons_font_html( $font_name, $css_file, $view = null ) {

            $font_info = sl_get_font_info( $css_file );
			if ( $font_info && count ( $font_info ) > 0 ) {
				if ( !$view ) 
                    $out .= self::get_icons_for_admin_panel( $font_name, $font_info['prefix'], $font_info['icons'] );
                else 
                    $out .= call_user_function( $view, $font_name, $font_info['prefix'], $font_info['icons'] );					
			}
			else $out = '';

			return $out;

		}
		
		
		public function get_icons_for_admin_panel( $font_name, $prefix, $icons ) {
			
			$n = count ( $icons[0] );
			$out = '<div id="' . $font_name . '-icons" class="sl_icons_block">';
			$out .= '<div class="sl_icons_service_block">
						<h3>' . $font_name . '<span class="icons-count">' . $n . ' icons</span></h3>
						<button class="button button-secondary button-small remove_mega_icons_set" data-delete="' . $font_name . '" data-title="Delete This Icon Set">Delete Icon Set</button>
					</div>';
			$out .= '<div id="' . $font_name . '-icons_table" class="sl_all_icons_container">';
			for ( $i = 0; $i < $n; $i++ ) {
				$out .= '
				<span class="sl_icon_container available_data">
					<i class="' . $prefix . $icons[1][$i] . '" data-unicode="' . $icons[2][$i] . '"></i>
				</span>';
			}
			$out .= '</div></div>';

            return $out; 
 
		}
		
		
		public function get_icons_for_menu_item($menu_item_meta) {

			$out = '';		
            if ( count ( self::$icon_styles ) > 0 ) {	
				$out .=                 '<div id="sl_icons_search"><input id="icon_search" type="text" placeholder="icon search"></div>';
				$out .=                 '<div id="selected_icon" item_id="' . $_POST['data']['menu_item_id'] . '">' . '<span class="' . $menu_item_meta['icon'] . '" aria-hidden="true"></span><input type="hidden" value="' . $menu_item_meta['icon'] . '" name="settings[icon]"><input type="hidden" value="' . $menu_item_meta['unicode'] . '" name="settings[unicode]"><a id="remove_icon" href="#">remove icon</a></div>';
				$out .=                 '<div id="sl_icons">';
				    foreach ( self::$icon_styles as $font=>$font_paths ) {
						
						$font_info = sl_get_font_info( $font_paths['dir'] );

						if ( $font_info ) {
							$out .= '<div class="icons_table_title">' . $font . '</div>';
							$out .= '<div id="' . $font . '-icons_table" class="sl_all_icons_container">';
							$n = count ( $font_info['icons'][0] );
							for ( $i = 0; $i < $n; $i++ ) {
								$out .= '<span class="sl_icon_container">
											<i class="' . $font_info['prefix'] . $font_info['icons'][1][$i] . '" data-unicode="' . $font_info['icons'][2][$i] . '"></i>
										</span>';									
							}
							$out .= '</div>';
						}
                    }								
            }
            else { $out = '<div class="menu_item_has_no_icons">There ara no available icons sets now</div>'; }
            return $out;			
		}		
		
		

		public static function add_zipped_font( $path = null ) {

			if ( $path ) { $path = $path; }
			else { $attachment = $_POST['values']; $path = realpath( get_attached_file( $attachment['id'] ) ); }

			$unzipped   = self::unzip( $path, array( '\.eot', '\.svg', '\.ttf', '\.woff', '\.json', '\.css' ) );

			if ( !$path ) {
				if ( self::$font_name == 'unknown' ) {
					self::delete_folder( $self::upload_dir['tempdir'] );
					die( __( 'Was not able to retrieve the Font name from your Uploaded Folder', 'sl_menu' ) );
				}
				die( __( 'font_added:', 'sl_menu' ) . self::$font_name );
            }

        }

		public static function unzip( $zipfile, $filter ) {

			global $wp_filesystem;

			if ( is_dir( self::$upload_dir['tempdir'] ) ) self::delete_folder( self::$upload_dir['tempdir'] );
            $tempdir = self::create_folder( self::$upload_dir['tempdir'], false );

			if ( ! $tempdir ) {
				die( __( 'Wasn\'t able to create temp folder', 'sl_menu' ) );
			}

			if ( class_exists( 'ZipArchive' ) ) {
				$zip = new ZipArchive; $font_name = false;
				if ( $zip->open( $zipfile ) ) {
					for ( $i = 0; $i < $zip->numFiles; $i++ ) {
						$get_font_name = false;
						$file = $zip->getNameIndex( $i );
						$remove = true;
						if (preg_match('/' . implode('|',$filter) . '/', $file)) { $remove = false; }
						if ( substr( $file, - 1 ) == '/' || ! empty( $remove ) ) { continue; }
					    $fp  = $zip->getStream( $file );
						$path = self::$upload_dir['tempdir'] . '/temp';
						if ( !is_dir( $path ) ) mkdir( $path );
						$filepath = $path . '/' . basename( $file );
						$ofp = fopen( $filepath, 'w' );
						if ( ! $fp ) { die( __( 'Unable to extract the file.', 'sl_menu' ) ); }
						while ( ! feof( $fp ) ) { fwrite( $ofp, fread( $fp, 8192 ) ); }
						fclose( $fp );
						fclose( $ofp );
					}
					$zip->close();
					$unzipped = glob( $path . '/*.*' );
					if ( $unzipped ) {
						$result_dir = self::$upload_dir['tempdir'] . '/result';
						mkdir( $result_dir ); $font_name = false;
						foreach ( $unzipped as $file ) {
							if ( $file != '.' && $file != '..' ) {
                                $content = $wp_filesystem->get_contents( $file );
								$info = sl_get_font_info( $file );
								if ( is_array( $info ) && $info['font_name'] ) {
									$font_name = $info['font_name'];
									$content = str_replace( '../fonts', './fonts', $content );
									$content = str_replace( './Flaticon', './fonts/Flaticon', $content );
									$wp_filesystem->put_contents( $result_dir . '/' . basename( $file ), $content );
								}
								else {
									if ( !is_dir( $result_dir . '/fonts' ) ) mkdir ( $result_dir . '/fonts' );
									$wp_filesystem->put_contents( $result_dir . '/fonts/' . basename( $file ), $content );
								}
							}
						}
                        if ( $font_name ) {
							$rename = rename ( self::$upload_dir['tempdir'] . '/result', self::$upload_dir['path'] . '/' . self::$dir_name . '/' . $font_name );
                        }
                        else {
                        }
                        self::delete_folder( self::$upload_dir['tempdir'] . '/result' );
                        self::delete_folder( self::$upload_dir['tempdir'] . '/temp' );	
                        self::delete_folder( self::$upload_dir['tempdir'] );						
					}
					else {
						die( __( "Wasn't able to work with Zip Archive", 'sl_menu' ) );
					}
				}
			} else {
				die( __( "Wasn't able to work with Zip Archive", 'sl_menu' ) );
			}

			return true;

		}
		
		public function remove_zipped_font( $path = null ) {
			if ( ! $path ) $path = self::$upload_dir['path'] . '/' . self::$dir_name . '/' . $_POST['font'];
			$files = glob($path . '/*');
			foreach ($files as $file) {
				is_dir($file) ? self::remove_zipped_font($file) : unlink($file);
			}
			rmdir($path);
			return;
		}		


		public function create_folder( &$folder ) {

			$created = wp_mkdir_p( trailingslashit( $folder ) );
			@chmod( $folder, 0777 );

			return $created;

		}

		public function delete_folder( $new_name ) {
			if ( is_dir( $new_name ) ) {
				$objects = scandir( $new_name );
				foreach ( $objects as $object ) {
					if ( $object != "." && $object != ".." ) {
						unlink( $new_name . "/" . $object );
					}
				}
				reset( $objects );
				rmdir( $new_name );
			} else {
				//echo $new_name . ' no found<br/>';
			}
		}
		
		
		
		
		
		public static function vc_icon_manager_init() {
			
			if ( function_exists ( vc_add_shortcode_param ) )
			    vc_add_shortcode_param( 'icons_set', array( $this, 'icon_manager_field'), plugin_dir_url( __FILE__ ) . 'admin/js/icon_field.js?version=' . time() );
			
			if(function_exists("vc_map")) {
			
				vc_map( array(
					'name'        => __( 'Icons Choice', 'sell' ),
					'base'        => 'icon_manager',
					'description' => __( 'Provides Icons Choice', 'sell' ),
					'category'    => __( 'Tools', 'sell' ),
					'icon'        => '',
					'params'      => array(							
						array(
							'type'			=> 'icons_set',
							'heading'		=> __( 'Icon Sets', 'sell' ),
							'param_name'	=> 'sl_icon',
							'description'	=> __( '', 'sell' ),
							'value'         => ''
						)
					)
				));
				
			}					
			
		}
		
		public function icon_manager_field( $settings, $value ) {
					
			$out = '<div class="icon_manager_block">'; $icons = '';
			
			$out .= '<input name="' . esc_attr( $settings['param_name'] ) . '" class="icon_manager_input wpb_vc_param_value wpb-textinput ' .
					 esc_attr( $settings['param_name'] ) . ' ' .
					 esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';	

			if ( $settings['param_name'] == 'sl_icon' ) {
				if ( count ( self::$icon_styles ) > 0 ) {
					$icons = '<div class="icon_manager_display">';
					$out .= '<select name="font_set" class="icons_set_select">';
					if ( !$selected ) $out .= '<option value="all" selected="selected">all</option>'; else $out .= '<option value="all">all</option>';
					foreach ( self::$icon_styles as $font => $paths ) {
						$font_info = sl_get_font_info( $paths['dir'] );
						if ( $font_info && count ( $font_info['icons'] ) > 0 ) {
							$icons .= '<div id="' . $font . '-icons" class="icons_manager_block">';
							$icons .= '<div class="icons_table_title">' . $font . '</div>';
							$n = count ( $font_info['icons'][0] );
							for ( $i = 0; $i < $n; $i++ ) {
								$class = $font_info['prefix'] . $font_info['icons'][1][$i];
								$icons .= '<span class="icon_manager_container"><i class="'. $class . '" data-unicode="' . $font_info['icons'][2][$i] . '"></i></span> ';
						        if ( $class == $selected['font'] ) $marked = ' selected'; else $marked = '';
							}
							$out .= '<option value="' . $font . '"' . $marked .'>' . $font . '</option>';
							$icons .= '</div>';
						}
					}				
					$out .= '</select>';
					$icons .= '</div>';
				}
				else $out .= '<div class="no_icons">' . __( 'You have loaded any Icon Fonts yet', 'sell' ) . '</div>';
			}

			$selected_icon = '<span class="icon_manager_container"><i class="'. $value . '"></i></span> ';
			$out .= '<div class="icon_manager_selected_icon">' . $selected_icon . '</div>';

			$out .= '<div id="search_' . $settings['param_name'] . '" class="icon_manager_search_box"><input type="text" placeholder="icons search"><div class="sl_icons_loader"></div></div>';				
			
			$out .= '</div>';			
			
			$out .= '<div class="icon_manager_display">' . $icons . '</div>';
			
			return $out;			
			
		}

	}
}


function sl_get_font_info( $css_file ) {
	global $wp_filesystem;

	$css = $wp_filesystem->get_contents( $css_file );
	$preg_result = array();
	if ( $css ) {
		preg_match ( '/font-family.?:.?["\']([^"\']+)/', $css, $font_name );
		preg_match('/\[class.?=.?\"([^\"]+)\"\]/', $css, $prefix);
		if ( ( $font_name && $font_name[1] ) && ( $prefix && $prefix[1] ) ) {
			$font_name = strtolower( $font_name[1] );
			$prefix = preg_replace('/\s/', '', $prefix[1]);
			$result = preg_match_all('/' . $prefix . '([^:]+):before\s?\{[^["\']+["\']([^["\']+)/', $css, $icons);
			if ( $result && strpos( $icons[1][0], ']' ) ) { $icons[0] = array_slice( $icons[0], 1 ); $icons[1] = array_slice( $icons[1], 1 ); $icons[2] = array_slice( $icons[2], 1 ); }
		}
		else if ( ( $font_name && $font_name[1] ) && count( $prefix ) == 0 ) {
            $font_name = 'font-awesome';
            $prefix = 'fa fa-';
            $result = preg_match_all('/fa-([^:]+):before\s\{[^\"]+\"([^\"]+)/', $css, $icons);
		}
		else {
            $result = array();
		}	
	}
	if ( count ( $result ) > 0 ) return array( 'font_name' => $font_name, 'prefix' => $prefix, 'icons' => $icons ); else return false;
	
}

SL_Icon_Manager::init();

?>
