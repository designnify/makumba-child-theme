<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Makumba Child Theme' );
define( 'CHILD_THEME_URL', 'http://designnify.com/' );
define( 'CHILD_THEME_VERSION', '1.0' );

//* Enqueue Styles and Scripts
add_action( 'wp_enqueue_scripts', 'genesis_sample_scripts' );
function genesis_sample_scripts() {

	//* Add Google Fonts
	wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700|Questrial|Didact Gothic|Pacifico|Open Sans|PT Sans Narrow', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'google-fonts' );
    
	//* Remove default CSS
	wp_dequeue_style( 'genesis-sample-theme' );

	/* Add compiled CSS
	wp_register_style( 'genesis-sample-styles', get_stylesheet_directory_uri() . '/style' . $minnified . '.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'genesis-sample-styles' );*/

	/* Add compiled JS
	wp_enqueue_script( 'genesis-sample-scripts', get_stylesheet_directory_uri() . '/js/project-min' . $minnified . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );*/

}

//* Activate the use of Dashicons
 add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );
function load_dashicons_front_end() {
wp_enqueue_style( 'dashicons' );
}

//* Enqueue scripts for Responsive menu
add_action( 'wp_enqueue_scripts', 'enqueue_responsive_menu_script' );
function enqueue_responsive_menu_script() {
 
	wp_enqueue_script( 'my-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/project-min.js', array( 'jquery' ), '1.0.0' );
 
}

//* Remove query string from static files
function remove_cssjs_ver( $src ) {
 if( strpos( $src, '?ver=' ) )
 $src = remove_query_arg( 'ver', $src );
 return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 2-column footer widgets
add_theme_support( 'genesis-footer-widgets', 2 );

//* Enable shortcodes in widgets
add_filter('widget_text', 'do_shortcode');

//* Add new tumbnail image sizes for post excerpts
add_image_size( 'post-image-medium', 380, 190, TRUE );
add_image_size( 'post-image-large', 600, 300, TRUE );

//* Display Post Featured Image Before Title in post page 
add_action( 'genesis_entry_header', 'single_post_featured_image', 15 );
function single_post_featured_image() {
	if ( ! is_singular( 'post' ) )
		return;
	
	$img = genesis_get_image( array( 'format' => 'html', 'size' => genesis_get_option( 'image_size' ), 'attr' => array( 'class' => 'post-image' ) ) );
	printf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), $img );
	
}

//* Edit the read more link text
add_filter( 'excerpt_more', 'custom_read_more_link');
add_filter('get_the_content_more_link', 'custom_read_more_link');
add_filter('the_content_more_link', 'custom_read_more_link');
function custom_read_more_link() {
return '<br><div class="more-link"><a class="more-link" href="' . get_permalink() . '" rel="nofollow">LUE LISÄÄ &#9658;</a></div></br>';
}

//* Customize the credits
add_filter( 'genesis_footer_creds_text', 'sp_footer_creds_text' );
function sp_footer_creds_text() {
	echo '<div class="creds"><p>';
	echo 'Copyright &copy; ';
	echo date('Y');
	echo ' &middot; Makumba &middot; All rights reserved &middot; Built by <a href="http://designnify.com/" target="blank">Designnify - Mauricio Alvarez</a>';
	echo '</p></div>';
}

//* Remove page titles from specific pages
add_action( 'get_header', 'remove_titles_from_pages' );
function remove_titles_from_pages() {
    if ( is_page(array(palvelut, aikataulu_hinnat, opettajat, tanssit, yhteystiedot) ) ) {
        remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    }
}

//* Featured image & Page title
//* Adding option to use featured image (if it exists) as background image
 
add_image_size( 'featured-banner', 2000, 1292, TRUE ); // creates a featured image size for the banner
 
function makumba_child_featured_img() {
	if ( is_page(array(palvelut, uutiset, aikataulu_hinnat, opettajat, tanssit, yhteystiedot) ) && has_post_thumbnail() ) { // checks post has thumbnail
	// gets URL for that image
	$background = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'featured-banner' ); 
	if (is_array($background)) {   
    // echo the output
	echo '<section class="entry-background" style="background-size:cover; -moz-background-size: cover; -webkit-background-size: cover; background-position: 50% 0px; background: url(';
	echo $background[0];
	echo ') no-repeat fixed #fff;"><div class="wrap">';
		genesis_do_post_title();
	echo '</div></section>'; 
	}}
	else { // if no featured image, adds class to use default image
	return;
}}
 
add_action( 'genesis_after_header', 'makumba_child_featured_img' );
function remove_makumba_child_featured_img() {
    if ( is_home() ) {
        remove_action( 'genesis_after_header', 'makumba_child_featured_img' );
    }
}

//* Removing emoji code form head
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

//* Clean Markup Text Widget 
add_action('widgets_init', create_function('', 'register_widget("clean_markup_widget");'));
class Clean_Markup_Widget extends WP_Widget {
	function __construct() {
		parent::WP_Widget('clean_markup_widget', 'Clean markup widget', array('description'=>'Simple widget for well-formatted markup &amp; text'));
	}
	function widget($args, $instance) {
		extract($args);
		$markup = $instance['markup'];
		//echo $before_widget;
		if ($markup) echo $markup;
		//echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['markup'] = $new_instance['markup'];
		return $instance;
	}
	function form($instance) {
		if ($instance) $markup = esc_attr($instance['markup']);
		else $markup = __('&lt;p&gt;Clean, well-formatted markup.&lt;/p&gt;', 'markup_widget'); ?>
		<p>
			<label for="<?php echo $this->get_field_id('markup'); ?>"><?php _e('Markup/text'); ?></label><br />
			<textarea class="widefat" id="<?php echo $this->get_field_id('markup'); ?>" name="<?php echo $this->get_field_name('markup'); ?>" type="text" rows="16" cols="20" value="<?php echo $markup; ?>"><?php echo $markup; ?></textarea>
		</p>
<?php }
}


//* Minify HTML
class WP_HTML_Compression
{
	// Settings
	protected $compress_css = true;
	protected $compress_js = true;
	protected $info_comment = true;
	protected $remove_comments = true;

	// Variables
	protected $html;
	public function __construct($html)
	{
		if (!empty($html))
		{
			$this->parseHTML($html);
		}
	}
	public function __toString()
	{
		return $this->html;
	}
	protected function bottomComment($raw, $compressed)
	{
		$raw = strlen($raw);
		$compressed = strlen($compressed);
		
		$savings = ($raw-$compressed) / $raw * 100;
		
		$savings = round($savings, 2);
		
		return '<!--HTML compressed, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
	}
	protected function minifyHTML($html)
	{
		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
		preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
		$overriding = false;
		$raw_tag = false;
		// Variable reused for output
		$html = '';
		foreach ($matches as $token)
		{
			$tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
			
			$content = $token[0];
			
			if (is_null($tag))
			{
				if ( !empty($token['script']) )
				{
					$strip = $this->compress_js;
				}
				else if ( !empty($token['style']) )
				{
					$strip = $this->compress_css;
				}
				else if ($content == '<!--wp-html-compression no compression-->')
				{
					$overriding = !$overriding;
					
					// Don't print the comment
					continue;
				}
				else if ($this->remove_comments)
				{
					if (!$overriding && $raw_tag != 'textarea')
					{
						// Remove any HTML comments, except MSIE conditional comments
						$content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
					}
				}
			}
			else
			{
				if ($tag == 'pre' || $tag == 'textarea')
				{
					$raw_tag = $tag;
				}
				else if ($tag == '/pre' || $tag == '/textarea')
				{
					$raw_tag = false;
				}
				else
				{
					if ($raw_tag || $overriding)
					{
						$strip = false;
					}
					else
					{
						$strip = true;
						
						// Remove any empty attributes, except:
						// action, alt, content, src
						$content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
						
						// Remove any space before the end of self-closing XHTML tags
						// JavaScript excluded
						$content = str_replace(' />', '/>', $content);
					}
				}
			}
			
			if ($strip)
			{
				$content = $this->removeWhiteSpace($content);
			}
			
			$html .= $content;
		}
		
		return $html;
	}
		
	public function parseHTML($html)
	{
		$this->html = $this->minifyHTML($html);
		
		if ($this->info_comment)
		{
			$this->html .= "\n" . $this->bottomComment($html, $this->html);
		}
	}
	
	protected function removeWhiteSpace($str)
	{
		$str = str_replace("\t", ' ', $str);
		$str = str_replace("\n",  '', $str);
		$str = str_replace("\r",  '', $str);
		
		while (stristr($str, '  '))
		{
			$str = str_replace('  ', ' ', $str);
		}
		
		return $str;
	}
}

function wp_html_compression_finish($html)
{
	return new WP_HTML_Compression($html);
}

function wp_html_compression_start()
{
	ob_start('wp_html_compression_finish');
}
add_action('get_header', 'wp_html_compression_start');

//* Customise the post meta info
add_filter( 'genesis_post_info', 'genesischild_post_info' );
function genesischild_post_info($post_info) {
 if (!is_page()) {
 $post_info = 'Julkaistu <span class="dashicons dashicons-calendar"></span>[post_date] - <span class="dashicons dashicons-admin-users"></span>Tekijä [post_author] [post_comments] [post_edit]';
 return $post_info;
 }
}

//* Customize categories and tags text
add_filter( 'genesis_post_meta', 'ig_entry_meta_footer' );
function ig_entry_meta_footer( $post_meta ) {
	$post_meta = '[post_categories before="Luoka: "] [post_tags before="Tunniste: "]';
	return $post_meta;
}

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'footer-widgets',
	'footer'
) );


//* Register widget areas 
genesis_register_sidebar( array(
	'id'				=> 'home-top',
	'name'			=> __( 'Home Top', 'makumba-child' ),
	'description'	=> __( 'This is home page top widget', 'makumba-child' ),
) );
genesis_register_sidebar( array(
	'id'				=> 'cta-left',
	'name'			=> __( 'CTA Left', 'makumba-child' ),
	'description'	=> __( 'This is home page cta left widget', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );
genesis_register_sidebar( array(
	'id'				=> 'cta-right',
	'name'			=> __( 'CTA Right', 'makumba-child' ),
	'description'	=> __( 'This is home page cta right widget', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );
genesis_register_sidebar( array(
	'id'				=> 'news-home',
	'name'			=> __( 'News Home Page', 'makumba-child' ),
	'description'	=> __( 'This is home page news widget', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );
genesis_register_sidebar( array(
	'id'				=> 'content-left',
	'name'			=> __( 'Content Left', 'makumba-child' ),
	'description'	=> __( 'This is home page content left widget', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h3 class="widgettitle">','after_title'=>'</h3>'
) );
genesis_register_sidebar( array(
	'id'				=> 'content-right',
	'name'			=> __( 'Content Right', 'makumba-child' ),
	'description'	=> __( 'This is home page content right widget', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h3 class="widgettitle">','after_title'=>'</h3>'
) );
genesis_register_sidebar( array(
	'id'				=> 'polttarit',
	'name'			=> __( 'Polttarit', 'makumba-child' ),
	'description'	=> __( 'This is polttarit section in palvelut page', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );
genesis_register_sidebar( array(
	'id'				=> 'yksityistunti',
	'name'			=> __( 'Yksityistunti', 'makumba-child' ),
	'description'	=> __( 'This is yksityistunti section in palvelut page', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );
genesis_register_sidebar( array(
	'id'				=> 'esitykset',
	'name'			=> __( 'Esitykset', 'makumba-child' ),
	'description'	=> __( 'This is esitykset section in palvelut page', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );
genesis_register_sidebar( array(
	'id'				=> 'festival',
	'name'			=> __( 'Festival', 'makumba-child' ),
	'description'	=> __( 'This is Festival and events section in palvelut page', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );
genesis_register_sidebar( array(
	'id'				=> 'teachers1',
	'name'			=> __( 'Teachers 1', 'makumba-child' ),
	'description'	=> __( 'This is teachers section in teachers page', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );
genesis_register_sidebar( array(
	'id'				=> 'teachers2',
	'name'			=> __( 'Teachers 2', 'makumba-child' ),
	'description'	=> __( 'This is teachers section in teachers page', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );
genesis_register_sidebar( array(
	'id'				=> 'teachers3',
	'name'			=> __( 'Teachers 3', 'makumba-child' ),
	'description'	=> __( 'This is teachers section in teachers page', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );
genesis_register_sidebar( array(
	'id'				=> 'teachers4',
	'name'			=> __( 'Teachers 4', 'makumba-child' ),
	'description'	=> __( 'This is teachers section in teachers page', 'makumba-child' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
    'before_title'=>'<h2 class="widgettitle">','after_title'=>'</h2>'
) );

//* Hooks the widgets below header in widget area after header
add_action( 'genesis_after_header', 'makumba_child_home_top_genesis' );
function makumba_child_home_top_genesis() {
if ( ! is_home() )
 return;
	genesis_widget_area('home-top', array(
		'before'	=> '<section class="home-top">',
		'after'		=> '</section>',
		));
}
add_action( 'genesis_after_header', 'makumba_child_cta_genesis' );
function makumba_child_cta_genesis() {
	if ( ! is_home() )
		return;
	if ( ! is_home( 'cta-left' ) || ( 'cta-right' ) ) {
		echo '<section class="cta"><div class="wrap">';
		
		   genesis_widget_area( 'cta-left', array(
		       'before' => '<div class="one-half first cta-left">',
		       'after'		=> '</div>',
		   ) );
	
		   genesis_widget_area( 'cta-right', array(
		       'before' => '<div class="one-half cta-right">',
		       'after'		=> '</div>',
		   ) );
		echo '</div></section>';
	}
}
add_action( 'genesis_after_header', 'makumba_child_news_home_genesis' );
function makumba_child_news_home_genesis() {
if ( ! is_home() )
 return;
	genesis_widget_area('news-home', array(
		'before'	=> '<section class="news-home"><div class="wrap">',
		'after'		=> '</div></section>',
		));
}
add_action( 'genesis_after_header', 'makumba_child_home_content_genesis' );
function makumba_child_home_content_genesis() {
	if ( ! is_home() )
		return;
	if ( ! is_home( 'content-left' ) || ( 'content-right' ) ) {
		echo '<section class="home-content"><div class="wrap">';
		
		   genesis_widget_area( 'content-left', array(
		       'before' => '<div class="one-half first content-left">',
		       'after'		=> '</div>',
		   ) );
	
		   genesis_widget_area( 'content-right', array(
		       'before' => '<div class="one-half content-right">',
		       'after'		=> '</div>',
		   ) );
		echo '</div></section>';
	}
}

add_action( 'genesis_after_header', 'makumba_child_polttarit_genesis' );
function makumba_child_polttarit_genesis() {
if ( ! is_page( 'palvelut' ) )
 return;
	genesis_widget_area('polttarit', array(
		'before'	=> '<section class="polttarit"><div class="wrap">',
		'after'		=> '</div></section>',
		));
}
add_action( 'genesis_after_header', 'makumba_child_yksityistunti_genesis' );
function makumba_child_yksityistunti_genesis() {
if ( ! is_page( 'palvelut' ) )
 return;
	genesis_widget_area('yksityistunti', array(
		'before'	=> '<section class="yksityistunti"><div class="wrap">',
		'after'		=> '</div></section>',
		));
}
add_action( 'genesis_after_header', 'makumba_child_esitykset_genesis' );
function makumba_child_esitykset_genesis() {
if ( ! is_page( 'palvelut' ) )
 return;
	genesis_widget_area('esitykset', array(
		'before'	=> '<section class="esitykset"><div class="wrap">',
		'after'		=> '</div></section>',
		));
}
add_action( 'genesis_after_header', 'makumba_child_festival_genesis' );
function makumba_child_festival_genesis() {
if ( ! is_page( 'palvelut' ) )
 return;
	genesis_widget_area('festival', array(
		'before'	=> '<section class="festival"><div class="wrap">',
		'after'		=> '</div></section>',
		));
}
add_action( 'genesis_after_header', 'makumba_child_teachers1_genesis' );
function makumba_child_teachers1_genesis() {
if ( ! is_page( 'opettajat' ) )
 return;
	genesis_widget_area('teachers1', array(
		'before'	=> '<section class="teachers1"><div class="wrap">',
		'after'		=> '</div></section>',
		));
}
add_action( 'genesis_after_header', 'makumba_child_teachers2_genesis' );
function makumba_child_teachers2_genesis() {
if ( ! is_page( 'opettajat' ) )
 return;
	genesis_widget_area('teachers2', array(
		'before'	=> '<section class="teachers2"><div class="wrap">',
		'after'		=> '</div></section>',
		));
}
add_action( 'genesis_after_header', 'makumba_child_teachers3_genesis' );
function makumba_child_teachers3_genesis() {
if ( ! is_page( 'opettajat' ) )
 return;
	genesis_widget_area('teachers3', array(
		'before'	=> '<section class="teachers3"><div class="wrap">',
		'after'		=> '</div></section>',
		));
}
add_action( 'genesis_after_header', 'makumba_child_teachers4_genesis' );
function makumba_child_teachers4_genesis() {
if ( ! is_page( 'opettajat' ) )
 return;
	genesis_widget_area('teachers4', array(
		'before'	=> '<section class="teachers2"><div class="wrap">',
		'after'		=> '</div></section>',
		));
}