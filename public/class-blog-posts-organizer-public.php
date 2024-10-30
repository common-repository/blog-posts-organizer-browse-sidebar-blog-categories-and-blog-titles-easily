<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/metagauss
 * @since      1.0.0
 *
 * @package    Blog_Posts_Organizer
 * @subpackage Blog_Posts_Organizer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Blog_Posts_Organizer
 * @subpackage Blog_Posts_Organizer/public
 * @author     Metagauss <support@metagauss.com>
 */
class Blog_Posts_Organizer_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blog_Posts_Organizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blog_Posts_Organizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
                wp_enqueue_style('jquery-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version,'all');
                wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blog-posts-organizer-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blog_Posts_Organizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blog_Posts_Organizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
                 wp_enqueue_script( 'jquery-ui-accordion' );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blog-posts-organizer-public.js', array( 'jquery' ), $this->version, true );
                wp_localize_script(
                    $this->plugin_name,
                    'bpo_org_live_search',
                    array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'bpo_org_live_search_nonce' ),
                    )
                );
	}

	public function register_shortcode() {
		add_shortcode( 'bpo_org_sidebar', array( $this, 'bpo_org_sideboar_shortcode_fun' ) );
		add_shortcode( 'bpo_org_search', array( $this, 'bpo_org_livesearch_shortcode_fun' ) );

	}

	public function shortcode_template_html( $template_name, $content, $attributes = null ) {
		if ( ! $attributes ) {
			$attributes = array();
		}
		ob_start();
                $this->enqueue_scripts();
                $this->enqueue_styles();
		do_action( 'bpo_org_before_' . $template_name, $template_name, $content );
		require 'partials/' . $template_name . '.php';
		do_action( 'bpo_org_after_' . $template_name );
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	public function bpo_org_sideboar_shortcode_fun( $content ) {
		return $this->shortcode_template_html( 'sidebar', $content );
	}

	public function bpo_org_livesearch_shortcode_fun( $content ) {
		return $this->shortcode_template_html( 'livesearch', $content );
	}

	public function bpo_org_live_search() {
            // Verify nonce
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'bpo_org_live_search_nonce' ) ) {
			wp_die( 'Security check failed' );
		}

            $search_query = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';

		 $args = array(
			 's'              => $search_query,
			 'post_status'    => 'publish',
			 'post_type'      => 'post',
			 'posts_per_page' => 10,
		 );

		 $search_results = new WP_Query( $args );

		 if ( $search_results->have_posts() ) {
			 while ( $search_results->have_posts() ) {
				 $search_results->the_post();
					?>
                    <div class="atw-search-result-item">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </div>
                    <?php
			 }
		 } else {
                     ?>
			 <div class="atw-search-no-results"><?php esc_html_e('No results found.','blog-posts-organizer-browse-sidebar-blog-categories-and-blog-titles-easily');?></div>
                     <?php
		 }

		 wp_die();
	}

}
