<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/metagauss
 * @since      1.0.0
 *
 * @package    Blog_Posts_Organizer
 * @subpackage Blog_Posts_Organizer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blog_Posts_Organizer
 * @subpackage Blog_Posts_Organizer/admin
 * @author     Metagauss <support@metagauss.com>
 */
class Blog_Posts_Organizer_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blog-posts-organizer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blog-posts-organizer-admin.js', array( 'jquery' ), $this->version, false );

	}


        // Add custom meta box
	public function bpo_org_add_custom_meta_box() {
		add_meta_box( 'bpo_org_custom_title', 'Custom Post Title', array( $this, 'bpo_org_box_callback' ), 'post', 'side' );
	}


	public function bpo_org_box_callback( $post ) {
		wp_nonce_field( 'atw_save_custom_title_data', 'atw_custom_title_meta_box_nonce' );
		$value = get_post_meta( $post->ID, '_atw_custom_title', true );
		?>
            <label for="atw_custom_title"><?php esc_html_e( 'Custom Title:', 'blog-posts-organizer-browse-sidebar-blog-categories-and-blog-titles-easily' ); ?></label>
            <input type="text" id="atw_custom_title" name="atw_custom_title" value="<?php echo esc_attr( $value ); ?>" size="25" />
            <?php
	}

        // Save custom field data
	public function bpo_org_save_custom_title_data( $post_id ) {
        if ( !isset( $_POST['atw_custom_title_meta_box_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atw_custom_title_meta_box_nonce'] ) ), 'atw_save_custom_title_data' ) ) {
                    return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( isset( $_POST['post_type'] ) && 'post' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		if ( isset( $_POST['atw_custom_title'] ) ) {
                    $custom_title = sanitize_text_field( wp_unslash( $_POST['atw_custom_title'] ) );
                    update_post_meta( $post_id, '_atw_custom_title', $custom_title );
		}
	}


	// Add icon field to category edit screen
	public function bpo_org_add_category_icon_field( $term ) {
		$icon  = get_term_meta( $term->term_id, 'category_icon', true );
		$order = get_term_meta( $term->term_id, 'atw_category_order', true );
		$show  = get_term_meta( $term->term_id, 'atw_category_show', true );
		?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="category_icon"><?php esc_html_e( 'Category Icon', 'blog-posts-organizer-browse-sidebar-blog-categories-and-blog-titles-easily' ); ?></label></th>
        <td>
            <input type="text" name="category_icon" id="category_icon" value="<?php echo esc_attr( $icon ); ?>" />
            <p class="description"><?php esc_html_e( 'Enter the URL of the icon.', 'blog-posts-organizer-browse-sidebar-blog-categories-and-blog-titles-easily' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="atw_category_order"><?php esc_html_e( 'Order', 'blog-posts-organizer-browse-sidebar-blog-categories-and-blog-titles-easily' ); ?></label></th>
        <td>
            <input type="number" name="atw_category_order" id="atw_category_order" value="<?php echo esc_attr( $order ); ?>" />
            <p class="description"><?php esc_html_e( 'Set the order for this category.', 'blog-posts-organizer-browse-sidebar-blog-categories-and-blog-titles-easily' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="atw_category_show"><?php esc_html_e( 'Show in Accordion', 'blog-posts-organizer-browse-sidebar-blog-categories-and-blog-titles-easily' ); ?></label></th>
        <td>
            <input type="checkbox" name="atw_category_show" id="atw_category_show" value="1" <?php checked( $show, '1' ); ?> />
            <p class="description"><?php esc_html_e( 'Show this category in the accordion.', 'blog-posts-organizer-browse-sidebar-blog-categories-and-blog-titles-easily' ); ?></p>
        </td>
    </tr>
		<?php
                wp_nonce_field( 'atw_save_category_icon_field_nonce', 'atw_category_nonce' );
	}


    // Save icon field value
    public function bpo_org_save_category_icon_field( $term_id ) {
        // Verify nonce
		if ( !isset( $_POST['atw_category_nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atw_category_nonce'] ) ), 'atw_save_category_icon_field_nonce' ) ) {
			return;
		}
		if ( isset( $_POST['atw_category_show'] ) ) {
				update_term_meta( $term_id, 'atw_category_show', '1' );
		} else {
				update_term_meta( $term_id, 'atw_category_show', '0' );
		}

        if ( isset( $_POST['category_icon'] ) ) {
            update_term_meta( $term_id, 'category_icon', sanitize_url( wp_unslash( $_POST['category_icon'] ) ) );
        }
		if ( isset( $_POST['atw_category_order'] ) ) {
            update_term_meta( $term_id, 'atw_category_order', intval( wp_unslash( $_POST['atw_category_order'] ) ) );
        }

    }



}
