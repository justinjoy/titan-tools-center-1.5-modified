<?php
/**
 * Titan Notice Handler
 */

defined( 'ABSPATH' ) || exit;

define('TITAN_TOOLS_CENTER_FREE_URL',__('https://www.titanthemes.net/products/titan-tools-center','titan-tools-center'));
define('TITAN_TOOLS_CENTER_PREMIUM_DOCUMENTATION',__('https://preview.titanthemes.net/documentation/titan-tools-center-pro/','titan-tools-center'));
define('TITAN_TOOLS_CENTER_SUPPORT',__('https://wordpress.org/support/theme/titan-tools-center/','titan-tools-center'));
define('TITAN_TOOLS_CENTER_REVIEW',__('https://wordpress.org/support/theme/titan-tools-center/reviews/#new-post','titan-tools-center'));
define('TITAN_TOOLS_CENTER_BUY_NOW',__('https://www.titanthemes.net/products/tools-wordpress-theme','titan-tools-center'));
define('TITAN_TOOLS_CENTER_DOC_URL',__('https://preview.titanthemes.net/documentation/titan-tools-center-lite/','titan-tools-center'));
define('TITAN_TOOLS_CENTER_LIVE_DEMO',__('https://preview.titanthemes.net/titan-tools-center/','titan-tools-center'));
define('TITAN_TOOLS_CENTER_BUNDLE',__('https://www.titanthemes.net/products/wordpress-theme-bundle','titan-tools-center'));

/**
 * Admin Hook
 */
function titan_tools_center_admin_menu_page() {
    $titan_tools_center_theme = wp_get_theme( get_template() );

    add_theme_page(
        $titan_tools_center_theme->display( 'Name' ),
        $titan_tools_center_theme->display( 'Name' ),
        'manage_options',
        'titan-tools-center',
        'titan_tools_center_do_admin_page'
    );
}
add_action( 'admin_menu', 'titan_tools_center_admin_menu_page' );

/**
 * Enqueue getting started styles and scripts
 */
function titan_widgets_backend_enqueue() {
    wp_enqueue_style(
        'titan-getting-started',
        get_template_directory_uri() . '/about-theme/about-theme.css'
    );

    // Enqueue your admin notice JS
    wp_enqueue_script(
        'titan-admin-notice',
        get_template_directory_uri() . '/about-theme/admin-notice-script.js',
        array('jquery'), // dependencies
        '1.0',
        true
    );

    // Pass PHP data to JS
    wp_localize_script(
        'titan-admin-notice',
        'titan_tools_center',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('titan_tools_center_nonce'),
        )
    );
}
add_action( 'admin_enqueue_scripts', 'titan_widgets_backend_enqueue' );


/**
 * Class Titan_Notice_Handler
 */
class Titan_Notice_Handler {

    public static $nonce;

    /**
     * Empty Constructor
     */
    public function __construct() {
        // Activation notice
        add_action( 'switch_theme', array( $this, 'flush_dismiss_status' ) );
        add_action( 'admin_init', array( $this, 'getting_started_notice_dismissed' ) );
        add_action( 'admin_notices', array( $this, 'titan_theme_info_welcome_admin_notice' ), 3 );
        add_action( 'wp_ajax_titan_tools_center_dismissable_notice', array( $this, 'ajax_dismiss_notice' ) );
        add_action( 'wp_ajax_titan_getting_started', array( $this, 'titan_getting_started' ) );
    }

    /**
     * Display an admin notice linking to the about page
     */
    public function titan_theme_info_welcome_admin_notice() {
        $current_screen = get_current_screen();
        $titan_tools_center_theme = wp_get_theme();

        if ( is_admin() && ! get_user_meta( get_current_user_id(), 'gs_notice_dismissed' ) && $current_screen->base != 'appearance_page_titan-tools-center' ) {
            echo '<div class="updated notice notice-success is-dismissible notice-get-started-class admin-notice" data-notice="getting_started_notice">';
            echo '<div class="admin-notice-content">';
            echo '<p><strong>' . sprintf( esc_html__( 'Welcome! Thank you for choosing %1$s.', 'titan-tools-center' ), esc_html( $titan_tools_center_theme->get( 'Name' ) ) ) . '</strong></p>';
            echo '<p class="plugin-notice">' . esc_html__( 'Thank you for choosing this theme. Use the Get Started guide to quickly set up and personalize your website.', 'titan-tools-center' ) . '</p>';
            echo '<div class="titan-buttons">';
            echo '<p><a href="' . esc_url( admin_url( 'themes.php?page=titan-tools-center' ) ) . '" class="titan-install-plugins button button-primary">' . sprintf( esc_html__( 'Get started with %s', 'titan-tools-center' ), esc_html( $titan_tools_center_theme->get( 'Name' ) ) ) . '</a></p>';
            echo '<p><a href="' . esc_url( TITAN_TOOLS_CENTER_BUY_NOW ) . '" class="button button-secondary" target="_blank">' . esc_html__( 'GO FOR PREMIUM', 'titan-tools-center' ) . '</a></p>';
            echo '<p><a href="' . esc_url( TITAN_TOOLS_CENTER_BUNDLE ) . '" class="button button-bundle" target="_blank">' . esc_html__( 'GET BUNDLE', 'titan-tools-center' ) . '</a></p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="admin-notice-image">';
            echo '<img class="notice_img" width="100%" src="' . esc_url( get_template_directory_uri() . '/assets/images/notice.png' ) . '">';
            echo '</div>';
            echo '<a href="#" class="getting-started-notice-dismiss">Dismiss</a>';
            echo '</div>';
        }
    }

    public function getting_started_notice_dismissed() {
        if ( isset( $_GET['gs-notice-dismissed'] ) ) {
            // Check nonce for security
            check_admin_referer( 'titan_tools_center_nonce' );
            add_user_meta( get_current_user_id(), 'gs_notice_dismissed', 'true' );
        }
    }

    public function flush_dismiss_status() {
        delete_user_meta( get_current_user_id(), 'gs_notice_dismissed' );
    }

    public function ajax_dismiss_notice() {
        check_ajax_referer( 'titan_tools_center_nonce', 'nonce' );

        if ( isset( $_POST['type'] ) && $_POST['type'] === 'getting_started_notice' ) {
            add_user_meta( get_current_user_id(), 'gs_notice_dismissed', 'true' );
        }
        wp_die();
    }

}

new Titan_Notice_Handler();

/**
 * Render admin page.
 *
 * @since 1.0.0
 */
function titan_tools_center_do_admin_page() { 
    $titan_tools_center_theme = wp_get_theme(); ?>
    <div class="titan-tools-center-themeinfo-page--wrapper">
        <div class="free&pro">
            <div id="titan-tools-center-admin-about-page-1">
                <div class="theme-detail">
                   <div class="titan-tools-center-admin-card-header-1">
                    <div class="titan-tools-center-header-left">
                        <h2>
                            <?php echo esc_html( $titan_tools_center_theme->Name ); ?> <span><?php echo esc_html($titan_tools_center_theme['Version']);?></span>
                        </h2>
                        <p>
                            <?php
                            echo wp_kses_post( apply_filters( 'titan_theme_description', esc_html( $titan_tools_center_theme->get( 'Description' ) ) ) );
                        ?>
                        </p>
                    </div>
                    <div class="titan-tools-center-header-right">
                        <div class="titan-tools-center-pro-button">
                            <a href="<?php echo esc_url( TITAN_TOOLS_CENTER_BUY_NOW ); ?>" class="titan-tools-center-button button-primary" target="_blank" rel="noreferrer">
                                <?php esc_html_e( 'UPGRADE TO PREMIUM', 'titan-tools-center' ); ?>
                            </a>
                        </div>
                    </div>
                </div>   
                </div>   
                <div class="titan-tools-center-features">
                    <div class="titan-tools-center-features-box">
                        <h3><?php esc_html_e( 'PREMIUM DEMONSTRATION', 'titan-tools-center' ); ?></h3>
                        <p><?php esc_html_e( 'Effortlessly create and customize your website by arranging text, images, and other elements using the Gutenberg editor, making web design easy and accessible for all skill levels.', 'titan-tools-center' ); ?></p>
                        <a href="<?php echo esc_url( TITAN_TOOLS_CENTER_LIVE_DEMO ); ?>" class="titan-tools-center-button button-secondary-1" target="_blank" rel="noreferrer">
                                <?php esc_html_e( 'DEMONSTRATION', 'titan-tools-center' ); ?>
                            </a>
                    </div>
                    <div class="titan-tools-center-features-box">
                        <h3><?php esc_html_e( 'REVIEWS', 'titan-tools-center' ); ?></h3>
                        <p><?php esc_html_e( 'We would be happy to hear your thoughts and value your evaluation.', 'titan-tools-center' ); ?></p>
                        <a href="<?php echo esc_url( TITAN_TOOLS_CENTER_REVIEW ); ?>" class="titan-tools-center-button button-secondary-1" target="_blank" rel="noreferrer">
                                <?php esc_html_e( 'REVIEWS', 'titan-tools-center' ); ?>
                            </a>
                    </div>
                    <div class="titan-tools-center-features-box">
                        <h3><?php esc_html_e( '24/7 SUPPORT', 'titan-tools-center' ); ?></h3>
                        <p><?php esc_html_e( 'Please do not hesitate to contact us at support if you need help installing our lite theme. We are prepared to assist you!', 'titan-tools-center' ); ?></p>
                        <a href="<?php echo esc_url( TITAN_TOOLS_CENTER_SUPPORT ); ?>" class="titan-tools-center-button button-secondary-1" target="_blank" rel="noreferrer">
                            <?php esc_html_e( 'SUPPORT', 'titan-tools-center' ); ?>
                        </a>
                    </div>
                    <div class="titan-tools-center-features-box">
                        <h3><?php esc_html_e( 'THEME INSTRUCTION', 'titan-tools-center' ); ?></h3>
                        <p><?php esc_html_e( 'If you need assistance configuring and setting up the theme, our tutorial is available. A fast and simple method for setting up the theme.', 'titan-tools-center' ); ?></p>
                        <a href="<?php echo esc_url( TITAN_TOOLS_CENTER_DOC_URL ); ?>" class="titan-tools-center-button button-secondary-1" target="_blank" rel="noreferrer">
                                <?php esc_html_e( 'DOCUMENTATION', 'titan-tools-center' ); ?>
                            </a>
                    </div>
                </div>   
            </div>
            <div id="titan-tools-center-admin-about-page-2">
                <div class="titan-tools-center-pro-button bundle">
                    <div class="bundle-content">
                        <h3 class="bundle-head"><?php esc_html_e( 'Get All Themes In Single Pack', 'titan-tools-center' ); ?></h3>
                        <p class="bundle-para"><?php esc_html_e( 'From customization to optimization, this bundle gives you the flexibility to create stunning websites.', 'titan-tools-center' ); ?></p>
                        <a href="<?php echo esc_url( TITAN_TOOLS_CENTER_BUNDLE ); ?>" class="titan-tools-center-button bundle" target="_blank" rel="noreferrer">
                            <?php esc_html_e( 'BUY THEME BUNDLE', 'titan-tools-center' ); ?>
                        </a>
                    </div>
                    <div class="bundle-image">
                        <img class="img_bundle" width="100%" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/bundle.png' ); ?>" alt="<?php esc_attr_e('logo', 'titan-tools-center'); ?>">
                    </div>
                </div> 
                <div class="theme-detail">
                   <div class="titan-tools-center-admin-card-header-1">
                        <div class="titan-tools-center-header-left-pro"> 
                            <h2><?php esc_html_e( 'The premium version of this theme will be available for you to enhance or unlock our premium features.', 'titan-tools-center' ); ?></h2>
                        </div>
                        <div class="titan-tools-center-header-right-2">
                            <div class="titan-tools-center-pro-button">
                                <a href="<?php echo esc_url( TITAN_TOOLS_CENTER_BUY_NOW ); ?>" class="titan-tools-center-button button-primary-1 buy-now" target="_blank" rel="noreferrer">
                                    <?php esc_html_e( 'GO TO PREMIUM', 'titan-tools-center' ); ?>
                                </a>
                            </div>
                            <div class="titan-tools-center-pro-button">
                                <a href="<?php echo esc_url( TITAN_TOOLS_CENTER_LIVE_DEMO ); ?>" class="titan-tools-center-button button-primary-1 pro-demo" target="_blank" rel="noreferrer">
                                    <?php esc_html_e( 'PREMIUM DEMO', 'titan-tools-center' ); ?>
                                </a>
                            </div>
                            <div class="titan-tools-center-pro-button">
                                <a href="<?php echo esc_url( TITAN_TOOLS_CENTER_PREMIUM_DOCUMENTATION ); ?>" class="titan-tools-center-button button-primary-1 buy-now" target="_blank" rel="noreferrer">
                                    <?php esc_html_e( 'PRO DOCUMENTATION', 'titan-tools-center' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="titan-tools-center-admin-card-header-2">
                        <img class="img_responsive" style="width: 100%;" src="<?php echo esc_url( $titan_tools_center_theme->get_screenshot() ); ?>" />
                    </div>  
                </div>    
            </div>
        </div>
    </div>
<?php } ?>