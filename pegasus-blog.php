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
		add_menu_page("Blog", "Blog", "manage_options", "pegasus_blog_plugin_options", "pegasus_blog_plugin_settings_page", null, 99);
		
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
		
		wp_enqueue_style( 'blog-plugin-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/pegasus-blog.css', array(), null, 'all' );
		
	}
	add_action( 'wp_enqueue_scripts', 'pegasus_blog_plugin_styles' );
	
	/**
	* Proper way to enqueue JS 
	*/
	function pegasus_blog_plugin_js() {
		
		wp_enqueue_script( 'classie-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/classie.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'pegasus-blog-plugin-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/plugin.js', array( 'jquery' ), null, true );
		
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
	
		$output = '';
		$output .= '<!--PEGASUS BLOG SYSTEM-->';
		$output .= '<div id="cbp-vm" class="cbp-vm-switcher cbp-vm-view-grid">';
			$output .= '<div class="cbp-vm-options">';
				$output .= '<a href="#" class="cbp-vm-icon cbp-vm-grid cbp-vm-selected" data-view="cbp-vm-view-grid">Grid View</a>';
				$output .= '<a href="#" class="cbp-vm-icon cbp-vm-list" data-view="cbp-vm-view-list">List View</a>';
			$output .= '</div>';
			$output .= '<ul id="octane-blog-list" >';
				
				//$query2 = new WP_Query( array( 'post_type' => array( 'post' ) ) );
				//while ( $query2->have_posts() ) : $query2->the_post();

					$output .= '<li class="blog-item-container">';
						$output .= '<article class="article-' . the_ID() . ' block-inner ">';
							
							$output .= '<!-- output the thumbnail -->';
							if ( has_post_thumbnail() ) { 
								$output .= '<a class="cbp-vm-image" href="' . the_permalink() . '">';
									//$output = the_post_thumbnail ( 'medium', array ('class' => 'octane-blog-thumbnail ') );
								$output .= '</a>';
							}else{ 
								$output .= '<a  class="cbp-vm-image" href="' . the_permalink() . '">';
									$output .= '<img src="' . trailingslashit( plugin_dir_url( __FILE__ ) ) . '/images/not-available.png">';
								$output .= '</a>';
							}//end else and if
							
							
							
							$output .= '<!-- the permalink and title -->';
							$output .= '<a href="' . the_permalink() . '" alt="' . the_title() . '">';
								$output .= '<h3 class="cbp-vm-title">';
									$output .= the_title(); 
								$output .= '</h3> ';
							$output .= '</a>';

							$output .= '<div class="cbp-vm-price"><i>' . the_category() . '</i></div>';
						
							$output .= '<!-- output the excerpt, and if no excerpt then output content-->';
							$output .= '<div class="octane-blog-content cbp-vm-details">
								<p>thecontent</p>
							</div>';
							$output .= '<!-- output a read more button -->';
							$output .= '<a class="button cbp-vm-icon cbp-vm-add" href="' . the_permalink() . '"> Read More </a>';
						
							//$output = '<div class="clearfix"></div>';
						$output .= '</article>';
					$output .= '</li>';
				//endwhile;
				//wp_reset_query();
				
			$output .= '</ul>';
		$output .= '</div>';

		return $output; 
	}
	add_shortcode( 'blog', 'pegasus_blog_func' );
	