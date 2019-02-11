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
	
	function pegasus_blog_menu_item() {
		//add_menu_page("Blog", "Blog", "manage_options", "pegasus_blog_plugin_options", "pegasus_blog_plugin_settings_page", null, 99);
		
	}
	add_action("admin_menu", "pegasus_blog_menu_item");

	function pegasus_blog_plugin_settings_page() { ?>
	    <div class="wrap pegasus-wrap">
	    <h1>Blog Usage</h1>
		
		<!--<p>Callout Usage 1: <pre>[callout button="yes" link="http://example.com" external="yes" color="white" link_text="Learn More" background="http://www.wpfreeware.com/themes/html/appstation/img/download_bg.png"] <?php //echo htmlspecialchars('<p>Get your copy now!Suspendisse vitae bibendum mauris. Nunc iaculis nisl vitae laoreet elementum donec dignissim metus sit.</p'); ?>[/callout]</pre></p>
		<p>Callout Usage 2: <pre>[callout button="yes" link="http://example.com" color="black" external="yes" backgroundcolor="#dedede"] <?php //echo htmlspecialchars('<h2>Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Donec sollicitudin molestie malesuada. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Donec sollicitudin molestie malesuada. Nulla porttitor accumsan tincidunt. Nulla porttitor accumsan tincidunt. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.</h2>'); ?>[/callout]</pre></p>
			
		<p style="color:red;">MAKE SURE YOU DO NOT HAVE ANY RETURNS OR <?php //echo htmlspecialchars('<br>'); ?>'s IN YOUR SHORTCODES, OTHERWISE IT WILL NOT WORK CORRECTLY</p>
		-->
		
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

		// query is made
		query_posts($the_query);

		// Reset and setup variables
		$output = '';
		$temp_title = '';
		$temp_link = '';
		$temp_date = '';
		$temp_pic = '';
		$temp_content = '';
		$the_id = '';

		global $post;

		//$color_chk = "{$a['bkg_color']}";
		//if ($color_chk) { $output .= "<li style='background: {$a['bkg_color']}; '>"; }else{ $output .= "<li>"; }

		// the loop
		if (have_posts()) : while (have_posts()) : the_post();

			$temp_title = get_the_title($post->ID);
			$temp_link = get_permalink($post->ID);
			$temp_date = get_the_date($post->ID);
			$temp_pic = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
			$temp_excerpt = wp_trim_words( get_the_excerpt(), 150 );
			$temp_content = wp_trim_words( get_the_content(), 300 );
			$the_id = get_the_ID();


			$terms = get_the_terms( $post->ID, 'category' );

			if ( $terms && ! is_wp_error( $terms ) ) :
				$links = array();

				foreach ( $terms as $term ) {
					$links[] = $term->name;
				}
				$links = str_replace(' ', '-', $links);
				$tax = join( " ", $links );
			else :
				$tax = '';
			endif;


			$termstwo = get_terms("category");
			$count = count($termstwo);

			if ( $count > 0 ) {
				$linkstwo = array();

				foreach ( $termstwo as $termtwo ) {
					$termname = strtolower( $termtwo->name );
					$termname = str_replace( ' ', '-', $termname );
					$linkstwo[] = $termname;
				}

				//$linkstwo = str_replace( ' ', '-', $linkstwo );
				$taxtwo = join( " ", $linkstwo );
			}



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
			//while ( $query2->have_posts() ) : $query2->the_post();

			$output .= '<li class="blog-item-container">';
			$output .= '<article class="article-' . $the_id . ' block-inner ">';

			$output .= '<!-- output the thumbnail -->';
			if ( has_post_thumbnail() ) {
				$output .= '<a class="cbp-vm-image" href="' . $temp_link . '">';
				//$output = the_post_thumbnail ( 'medium', array ('class' => 'blog-thumbnail ') );
				$output .= '</a>';
			}else{
				$output .= '<a  class="cbp-vm-image" href="' . $temp_link . '">';
				$output .= '<img src="' . trailingslashit( plugin_dir_url( __FILE__ ) ) . '/images/not-available.png">';
				$output .= '</a>';
			}//end else and if

			$output .= '<!-- the permalink and title -->';
			$output .= '<a href="' . $temp_link . '" alt="' . $temp_title . '">';
			$output .= '<h3 class="cbp-vm-title">';
			$output .= $temp_title;
			$output .= '</h3> ';
			$output .= '</a>';

			$output .= '<div class="cbp-vm-price"><i>' . strtolower( $tax ) . ' ' . strtolower( $taxtwo ) . '</i></div>';

			$output .= '<!-- output the excerpt, and if no excerpt then output content-->';
			$output .= '<div class="blog-content cbp-vm-details">
								<p>thecontent</p>
							</div>';
			$output .= '<!-- output a read more button -->';
			$output .= '<a class="button cbp-vm-icon cbp-vm-add" href="' . $temp_link . '"> Read More </a>';

			//$output = '<div class="clearfix"></div>';
			$output .= '</article>';
			$output .= '</li>';
			//endwhile;
			//wp_reset_query();

			$output .= '</ul>';
			$output .= '</div>';

		endwhile; else:
			$output .= "nothing found.";
		endif;

		wp_reset_query();

		wp_enqueue_style( 'blog-plugin-css' );
		wp_enqueue_script( 'classie-js' );
		wp_enqueue_script( 'pegasus-blog-plugin-js' );

		return $output;

	}
	add_shortcode( 'blog', 'pegasus_blog_func' );
	