<?php
/*
Plugin Name: Pegasus Blog Plugin
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: This allows you to show your blog posts on your website with just a shortcode.
Version:     1.0
Author:      Jim O'Brien
Author URI:  https://visionquestdevelopment.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

	function blog_check_main_theme_name() {
		$current_theme_slug = get_option('stylesheet'); // Slug of the current theme (child theme if used)
		$parent_theme_slug = get_option('template');    // Slug of the parent theme (if a child theme is used)

		//error_log( "current theme slug: " . $current_theme_slug );
		//error_log( "parent theme slug: " . $parent_theme_slug );

		if ( $current_theme_slug == 'pegasus' ) {
			return 'Pegasus';
		} elseif ( $current_theme_slug == 'pegasus-child' ) {
			return 'Pegasus Child';
		} else {
			return 'Not Pegasus';
		}
	}

	function pegasus_blog_menu_item() {
		if ( blog_check_main_theme_name() == 'Pegasus' || blog_check_main_theme_name() == 'Pegasus Child' ) {
			//do nothing
		} else {
			//echo 'This is NOT the Pegasus theme';
			add_menu_page(
				"Blog", // Page title
				"Blog", // Menu title
				"manage_options", // Capability
				"pegasus_blog_plugin_options", // Menu slug
				"pegasus_blog_plugin_settings_page", // Callback function
				null, // Icon
				80 // Position in menu
			);
		}
	}
	add_action("admin_menu", "pegasus_blog_menu_item");

	function pegasus_blog_plugin_settings_page() { ?>
	    <div class="wrap pegasus-wrap">
			<h1>Blog Usage</h1>

			<div>
				<h3>Blog Usage 1:</h3>
				<style>
					pre {
						background-color: #f9f9f9;
						border: 1px solid #aaa;
						page-break-inside: avoid;
						font-family: monospace;
						font-size: 15px;
						line-height: 1.6;
						margin-bottom: 1.6em;
						max-width: 100%;
						overflow: auto;
						padding: 1em 1.5em;
						display: block;
						word-wrap: break-word;
					}

					input[type="text"].code {
						width: 100%;
					}
				</style>
				<pre >[blog]</pre>

				<input
					type="text"
					readonly
					value="<?php echo esc_html('[blog]'); ?>"
					class="regular-text code"
					id="my-shortcode"
					onClick="this.select();"
				>
			</div>

			<p style="color:red;">MAKE SURE YOU DO NOT HAVE ANY RETURNS OR <?php echo htmlspecialchars('<br>'); ?>'s IN YOUR SHORTCODES, OTHERWISE IT WILL NOT WORK CORRECTLY</p>

		</div>
	<?php
	}

	/**
	* Proper way to enqueue CSS
	*/
	function pegasus_blog_plugin_styles() {

		wp_register_style( 'blog-plugin-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/pegasus-blog.css', array(), null, 'all' );

		//wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );

	}
	add_action( 'wp_enqueue_scripts', 'pegasus_blog_plugin_styles' );

	/**
	* Proper way to enqueue JS
	*/
	function pegasus_blog_plugin_js() {

		wp_register_script( 'classie-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/classie.js', array( 'jquery' ), null, 'all' );
		wp_register_script( 'pegasus-blog-plugin-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/plugin.js', array( 'jquery' ), null, 'all' );

	} //end function
	add_action( 'wp_enqueue_scripts', 'pegasus_blog_plugin_js' );



	/*~~~~~~~~~~~~~~~~~~~~
		BLOG
	~~~~~~~~~~~~~~~~~~~~~*/

	// [blog url="img-src"]
	function pegasus_blog_func( $atts, $content = null ) {

		$a = shortcode_atts( array(
			'link' => '#',
		), $atts );

		// Defaults
		extract(shortcode_atts(array(
			"the_query" => '',
		), $atts));

		// de-funkify query
		//$the_query = preg_replace('~&#x0*([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $the_query);
		//$the_query = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $the_query);

		$the_query = preg_replace_callback('~&#x0*([0-9a-f]+);~', function($matches){
			return chr( dechex( $matches[1] ) );
		}, $the_query);

		$the_query = preg_replace_callback('~&#0*([0-9]+);~', function($matches){
			return chr( $matches[1] );
		}, $the_query);

		$query_args = array(
			'post_type' => 'post', // Ensure you are querying the correct post type
			'posts_per_page' => -1, // Set the number of posts to retrieve
			//'post_status' => 'publish', // Ensure only published posts are retrieved
			//'category_name' => 'your-category-slug', // Optional: Filter by category
			//'orderby' => 'date', // Optional: Order by date
			//'order' => 'DESC' // Optional: Order descending
		);

		// echo '<pre>';
		// var_dump( $the_query );
		// echo '</pre>';
		// echo '<pre>';
		// var_dump( $query_args );
		// echo '</pre>';
		// Convert query string into array for WP_Query
		parse_str( $the_query, $query_args );
		// echo '<pre>';
		// var_dump( $query_args );
		// echo '</pre>';
		// Create a new WP_Query instance
		$query = new WP_Query( $query_args );

		// echo '<pre>';
		// var_dump( $query );
		// echo '</pre>';

		// Reset and setup variables
		$output = '';
		$temp_title = '';
		$temp_link = '';
		$temp_date = '';
		$temp_pic = '';
		$temp_content = '';
		$the_id = '';

		global $post;
		//global $query_string;

		//$color_chk = "{$a['bkg_color']}";
		//if ($color_chk) { $output .= "<li style='background: {$a['bkg_color']}; '>"; }else{ $output .= "<li>"; }

		// the loop


			// $temp_title = get_the_title($post->ID);
			// $temp_link = get_permalink($post->ID);
			// $temp_date = get_the_date($post->ID);
			// $temp_pic = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
			// $temp_excerpt = wp_trim_words( get_the_excerpt(), 150 );
			// $temp_content = wp_trim_words( get_the_content(), 300 );
			// $the_id = get_the_ID();


			// $terms = get_the_terms( $post->ID, 'category' );

			// if ( $terms && ! is_wp_error( $terms ) ) :
			// 	$links = array();

			// 	foreach ( $terms as $term ) {
			// 		$links[] = $term->name;
			// 	}
			// 	$links = str_replace(' ', '-', $links);
			// 	$tax = join( " ", $links );
			// else :
			// 	$tax = '';
			// endif;


			// $termstwo = get_terms("category");
			// $count = count($termstwo);

			// if ( $count > 0 ) {
			// 	$linkstwo = array();

			// 	foreach ( $termstwo as $termtwo ) {
			// 		$termname = strtolower( $termtwo->name );
			// 		$termname = str_replace( ' ', '-', $termname );
			// 		$linkstwo[] = $termname;
			// 	}

			// 	//$linkstwo = str_replace( ' ', '-', $linkstwo );
			// 	$taxtwo = join( " ", $linkstwo );
			// }



			// output all findings - CUSTOMIZE TO YOUR LIKING
			$output = '';
			//$output .= '<!--PEGASUS BLOG SYSTEM-->';
			$output .= '<div id="cbp-vm" class="cbp-vm-switcher cbp-vm-view-grid">';
			$output .= '<div class="cbp-vm-options">';
			$output .= '<a href="#" class="cbp-vm-icon cbp-vm-grid cbp-vm-selected" data-view="cbp-vm-view-grid">Grid View</a>';
			$output .= '<a href="#" class="cbp-vm-icon cbp-vm-list" data-view="cbp-vm-view-list">List View</a>';
			$output .= '</div>';
			$output .= '<ul id="blog-list" >';

			//$query2 = new WP_Query( array( 'post_type' => array( 'post' ) ) );
			//while ( $query->have_posts() ) : $query->the_post();
			//if (have_posts()) : while (have_posts()) : the_post();
			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();

					$post_id    = get_the_ID();
					$post_title = get_the_title();
					$post_link  = get_permalink();
					$post_thumb = has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'medium') : plugin_dir_url(__FILE__) . '/images/not-available.png';
					$categories = get_the_category();

					$output .= '<li class="blog-item-container">';
					$output .= '<article class="article-' . $post_id . ' block-inner ">';

					$output .= '<!-- output the thumbnail -->';
					if ( has_post_thumbnail() ) {
						$output .= '<a class="cbp-vm-image" href="' . $post_link . '">';
						//$output .= the_post_thumbnail ( 'medium', array ('class' => 'blog-thumbnail ') );
						$output .= '<img src="' . esc_url($post_thumb) . '" alt="' . esc_attr($post_title) . '" class="blog-thumbnail">';
						$output .= '</a>';
					}else{
						$output .= '<a  class="cbp-vm-image" href="' . $post_link . '">';
						$output .= '<img src="' . trailingslashit( plugin_dir_url( __FILE__ ) ) . '/images/not-available.png">';
						$output .= '</a>';
					}//end else and if

					$output .= '<!-- the permalink and title -->';
					$output .= '<a href="' . $post_link . '" alt="' . $post_title . '">';
					$output .= '<h3 class="cbp-vm-title">';
					$output .= $post_title;
					$output .= '</h3> ';
					$output .= '</a>';

					//$output .= '<div class="cbp-vm-price"><i>' . strtolower( $tax ) . ' ' . strtolower( $taxtwo ) . '</i></div>';
					if (!empty($categories)) {
						$output .= '<div class="cbp-vm-price"><i>';
						foreach ($categories as $category) {
							echo esc_html($category->name) . ' ';
						}
						$output .= '</i></div>';
					}

					$output .= '<!-- output the excerpt, and if no excerpt then output content-->';
					$output .= '<div class="blog-content cbp-vm-details">
										<p>thecontent</p>
									</div>';
					$output .= '<!-- output a read more button -->';
					$output .= '<a class="button cbp-vm-icon cbp-vm-add" href="' . $temp_link . '"> Read More </a>';

					//$output = '<div class="clearfix"></div>';
					$output .= '</article>';
					$output .= '</li>';
				}//end while
				//wp_reset_postdata();
			} else {
				echo '<p>No posts found.</p>';
			}

			$output .= '</ul>';
			$output .= '</div>';

		wp_reset_postdata();

		wp_enqueue_style( 'blog-plugin-css' );
		wp_enqueue_script( 'classie-js' );
		wp_enqueue_script( 'pegasus-blog-plugin-js' );

		return $output;

	}
	add_shortcode( 'blog', 'pegasus_blog_func' );
