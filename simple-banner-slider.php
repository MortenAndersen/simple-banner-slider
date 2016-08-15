<?php
/*
Plugin Name: Simple Banner Slider
Plugin URI: <a href="http://www.hjemmesider.dk"<br />
Description:</a> Simple Banner Slider - Wordpress Shortcode Options and a Widget view.
Version: 1.01
Author: Hjemmesider.dk
Author URI: http://www.hjemmesider.dk.dk
*/

// Load the plugin's text domain

function hjemmesider_banner_init() {
    load_plugin_textdomain( 'bannerdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/translation' );
}
add_action('plugins_loaded', 'hjemmesider_banner_init');

// Banner Posttype

function hjemmesider_banner_create_posttype() {

    register_post_type('banner', array('labels' => array('name' => __('Banner', 'bannersdomain'), 'singular_name' => __('Banner', 'bannerdomain')), 'public' => true, 'menu_icon' => 'dashicons-format-gallery', 'exclude_from_search' => true, 'publicly_queryable'  => false, 'query_var'  => false, 'taxonomies' => array('category'), 'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'), 'rewrite' => array('slug' => 'banner'),

        ));
}
add_action('init', 'hjemmesider_banner_create_posttype');

// Images

if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    add_image_size('banner_plugin', 9999, 500, true);
}

if( class_exists('acf') ) {

// Widget

/**
 * Adds Hjemmesider_banner_widget widget.
 */
class Hjemmesider_banner_widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct('Hjemmesider_banner_widget',
         // Base ID
        __('Banner', 'bannerdomain'),
         // Name
        array('description' => __('Simple Banner Slider', 'bannerdomain'),)
         // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $query_args = array('post_type' => 'Banner', 'posts_per_page' => 5,);

        // The Query
        $the_query = new WP_Query($query_args);

        // The Loop
        if ($the_query->have_posts()) {
            echo "\r\n" . '<div id="slides"><ul class="banner hjemmesider__liste slides-container">' . "\r\n";
            while ($the_query->have_posts()) {
                $the_query->the_post();

                    {
                        ?>
<?php if( get_field('synlighed') == 'medtxt' ): ?>
<?php if( get_field('banner_url')): ?>
<li style="background-image: url('<?php the_post_thumbnail_url( 'banner_plugin') ?>');">

<div class="banner__text">
<div class="banner__text_content">
<h4><?php the_title() ?></h4>
<?php the_content() ?>
</div>
<a href="<?php the_field('banner_url') ?>">Læs mere</a>
</div>

</li>
<?php else : ?>
<li style="background-image: url('<?php the_post_thumbnail_url( 'banner_plugin') ?>');">
<div class="banner__text">
<div class="banner__text_content">
<h4><?php the_title() ?></h4>
<?php the_content() ?>
</div>
</div>
</li>
<?php endif; ?>
<?php else : ?>
<?php if( get_field('banner_url')): ?>
<li>
<a href="<?php the_field('banner_url') ?>">
<?php the_post_thumbnail( 'banner_plugin') ?>
</a>
</li>
<?php else : ?>
<li>
<?php the_post_thumbnail( 'banner_plugin') ?>

</li>
<?php endif; ?>
<?php endif; ?>
<?php
        }
    }
            echo '</ul></div>' . "\r\n" . "\r\n";

        }
        else {

            echo "\r\n" . '<p><strong>' . __( 'No Banner found', 'bannerdomain' ) . '</strong></p>' . "\r\n";

        }

        /* Restore original Post Data */
        wp_reset_postdata();

        echo $args['after_widget'];
    }


    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Banner', 'bannerdomain');
?>
        <p>
        <label for="<?php
        echo $this->get_field_id('title'); ?>"><?php
        _e('Title:'); ?></label>
        <input class="widefat" id="<?php
        echo $this->get_field_id('title'); ?>" name="<?php
        echo $this->get_field_name('title'); ?>" type="text" value="<?php
        echo esc_attr($title); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }
}

// register Hjemmesider_banner_Widget widget
function register_Hjemmesider_banner_widget() {
    register_widget('Hjemmesider_banner_widget');
}
add_action('widgets_init', 'register_Hjemmesider_banner_widget');



// ACF

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
    'key' => 'group_5666ab5a81aec',
    'title' => 'Banner',
    'fields' => array (
        array (
            'key' => 'field_5666a9604c158',
            'label' => 'Banner url',
            'name' => 'banner_url',
            'type' => 'page_link',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array (
            ),
            'taxonomy' => array (
            ),
            'allow_null' => 1,
            'multiple' => 0,
        ),
        array (
            'key' => 'field_bannertextvis',
            'label' => 'Synlighed',
            'name' => 'synlighed',
            'type' => 'radio',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array (
                'medtxt' => 'Med tekst',
                'udentxt' => 'Uden tekst',
            ),
            'default_value' => array (
            ),
            'other_choice' => 0,
            'save_other_choice' => 0,
            'default_value' => '',
            'layout' => 'vertical',
        ),
    ),
    'location' => array (
        array (
            array (
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'banner',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

endif;

}