<?php

/*
 * Shortcode definition
 * @link
 * @since 1.0
 *
 * @package catlinks-widget
 * @subpackage catlinks-widget
*/


/*
  The class realizes a functionality of the widget

*/
class Catlinks_Widget extends WP_Widget {

  /*
    Creates a widget
  */
  public function __construct() {
    parent::__construct(
      'an_catlinks_widget', // ID of the widget
      esc_html__('Categories Links Widget', 'an_catlinks_wiget'), // Widget title
      array(
        'description' => esc_html__('The widget allow to insert a block of the iconic links for 3 categories', 'an_catlinks_wiget'),
      )
    );

    // enqueue scripts for media library
    add_action( 'admin_enqueue_scripts', array( $this, 'allow_media_upload' ) );
    // enqueue enqueue styles
    add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    // customize styles
    if (!is_admin()) {
      add_action('wp_head',array($this, 'customize_css'));
    }
  }

  /*
    include media library scripts & color picker
  */

  public function allow_media_upload() {
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' ); // color picker
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script('catlinks-media-upload', plugin_dir_url(__FILE__) . 'js/catlinks.js', array( 'jquery' )) ;
  }



  // include plugin's styles

  public function enqueue_styles() {
    wp_enqueue_style('catlinks-widget-style',
        plugin_dir_url(__FILE__)."/css/catlinks.css",
        null, null, 'all'
      );
  }

  // css customization

  function customize_css()
  {
    // get current widget settings
    $instances = $this->get_settings();

    // check settings exist
    if (array_key_exists($this->number, $instances)) {

      // then get instance
      $instance = $instances[$this->number];

      ?>
             <style type="text/css">
                <?php
                  if (isset($instance['bg_color'])):
                ?>
                    .an-catlinks-container { background-color: <?php _e($instance['bg_color']); ?>; }
                <?php
                  endif;
                ?>
                <?php
                  if (isset($instance['font_color'])):
                ?>
                    .an-catlinks-element a,
                    .an-catlinks-element a:link,
                    .an-catlinks-element a:visited,
                    .an-catlinks-element a:active,
                    .an-catlinks-element a:hover{ color: <?php _e($instance['font_color']); ?>; }
                <?php
                  endif;
                ?>
             </style>
      <?php
    } // end of get instance if
  } // end of customize css function

  /*
    The front-end part of the widget
  */
  public function widget($args, $instance) {
    extract( $args );

?>
    <div class="an-catlinks-container">
      <div class="an-catlinks-element">
        <img src="<?php _e($instance['image1']); ?>" />
        <a href="<?php _e(get_category_link($instance['cat1'])); ?>">
          <?php _e(get_cat_name($instance['cat1'])); ?>
        </a>
      </div>

      <div class="an-catlinks-element">
        <img src="<?php _e($instance['image2']); ?>" />
        <a href="<?php _e(get_category_link($instance['cat2'])); ?>">
          <?php _e(get_cat_name($instance['cat2'])); ?>
        </a>
      </div>

      <div class="an-catlinks-element">
        <img src="<?php _e($instance['image3']); ?>" />
        <a href="<?php _e(get_category_link($instance['cat3'])); ?>">
          <?php _e(get_cat_name($instance['cat3'])); ?>
        </a>
      </div>
    </div>

<?php
  }

  /*
  The back-end part of the widget
  */
  public function form($instance) {

    // set up background color
    $bg_color = "#212121";
    if (isset($instance['bg_color'])) {
      $bg_color = $instance['bg_color'];
    }
?>
 <div class="an-catlinks-settings-container">
  <p>
    <label for="<?php echo $this->get_field_id( 'bg_color' ); ?>" style="display:block;"><?php _e( 'Background Color:', 'an_catlinks_wiget' ); ?></label>
    <input class="widefat color-picker an-catlist-bg-color-picker"
        id="<?php echo $this->get_field_id( 'bg_color' ); ?>"
        name="<?php echo $this->get_field_name( 'bg_color' ); ?>" type="text" value="<?php echo esc_attr( $bg_color ); ?>" />
  </p>

<?php
  // setup font color
  $font_color = "#ffffff";
  if (isset($instance['font_color'])) {
    $font_color = $instance['font_color'];
  }
?>
  <p>
  <label for="<?php echo $this->get_field_id( 'font_color' ); ?>" style="display:block;"><?php _e( 'Font color:', 'an_catlinks_wiget' ); ?></label>
  <input class="widefat color-picker"
      id="<?php echo $this->get_field_id( 'font_color' ); ?>"
      name="<?php echo $this->get_field_name( 'font_color' ); ?>" type="text" value="<?php echo esc_attr( $font_color ); ?>" />
  </p>

<?php
    // set up 1st category
    $cat = 0;
    if (isset($instance['cat1'])) {
      $cat = $instance['cat1'];
    }
?>
    <!-- 1st category and image -->
    <label for="<?php _e($this->get_field_id('cat1')); ?>"><?php esc_html__("Category 1", "an_catlinks_wiget"); ?></label>
    <select id="<?php _e($this->get_field_id('cat1')); ?>" name="<?php _e($this->get_field_name('cat1')); ?>" class="widefat" width="100%">
      <option value-"0"><?php _e('(not selected)', 'an_catlinks_wiget'); ?></option>
      <?php foreach(get_terms('category','parent=0&hide_empty=0') as $category): ?>
        <option <?php $cat == $category->term_id ? _e("selected") : _e(""); ?> value="<?php _e($category->term_id); ?>">
          <?php _e($category->name); ?>
        </option>
      <?php endforeach; ?>
    </select>

<?php
    // set up image for the first category
    $image = '';
    if(isset($instance['image1']))
    {
        $image = $instance['image1'];
    }
?>

    <p class="an-catlinks-image-demo">
        <label for="<?php _e($this->get_field_name( 'image1' )); ?>"><?php _e( 'Image 1:', 'an_catlinks_wiget' ); ?></label>

        <input name="<?php _e($this->get_field_name( 'image1' )); ?>"
               id="<?php _e($this->get_field_id( 'image1' )); ?>"
               class="widefat" type="text" size="36"
               value="<?php _e(esc_url( $image )); ?>" />
        <img src="<?php _e(esc_url( $image )); ?>" style="max-width: 100%; height: auto; background: <?php _e($bg_color); ?>" />
        <input class="upload_image_button" type="button" value="<?php _e('Upload Image','an_catlinks_wiget'); ?>" />
    </p>

    <!-- 2nd category and image -->
<?php
    $cat = 0;
    if (isset($instance['cat2'])) {
      $cat = $instance['cat2'];
    }
?>
    <label for="<?php _e($this->get_field_id('cat2')); ?>"><?php esc_html__("Category 2", "an_catlinks_wiget"); ?></label>
    <select id="<?php _e($this->get_field_id('cat2')); ?>" name="<?php _e($this->get_field_name('cat2')); ?>" class="widefat" width="100%">
      <option value-"0"><?php _e('(not selected)', 'an_catlinks_wiget'); ?></option>
      <?php foreach(get_terms('category','parent=0&hide_empty=0') as $category): ?>
        <option <?php $cat == $category->term_id ? _e("selected") : _e(""); ?> value="<?php _e($category->term_id); ?>">
          <?php _e($category->name); ?>
        </option>
      <?php endforeach; ?>
    </select>

<?php
    $image = '';
    if(isset($instance['image2']))
    {
        $image = $instance['image2'];
    }
?>

    <p class="an-catlinks-image-demo">
        <label for="<?php _e($this->get_field_name( 'image2' )); ?>"><?php _e( 'Image 2:', 'an_catlinks_wiget' ); ?></label>

        <input name="<?php _e($this->get_field_name( 'image2' )); ?>"
               id="<?php _e($this->get_field_id( 'image2' )); ?>"
               class="widefat" type="text" size="36"
               value="<?php _e(esc_url( $image )); ?>" />
        <img src="<?php _e(esc_url( $image )); ?>" style="max-width: 100%; height: auto;background: <?php _e($bg_color); ?>" />
        <input class="upload_image_button" type="button" value="<?php _e('Upload Image','an_catlinks_wiget'); ?>" />
    </p>

    <!-- 3rd category and image -->

    <?php
        $cat = 0;
        if (isset($instance['cat3'])) {
          $cat = $instance['cat3'];
        }
    ?>
    <label for="<?php _e($this->get_field_id('cat3')); ?>"><?php esc_html__("Category 3", "an_catlinks_wiget"); ?></label>

    <select id="<?php _e($this->get_field_id('cat3')); ?>" name="<?php _e($this->get_field_name('cat3')); ?>" class="widefat" width="100%">
      <option value-"0"><?php _e('(not selected)', 'an_catlinks_wiget'); ?></option>
      <?php foreach(get_terms('category','parent=0&hide_empty=0') as $category): ?>
        <option <?php $cat == $category->term_id ? _e("selected") : _e(""); ?> value="<?php _e($category->term_id); ?>">
          <?php _e($category->name); ?>
        </option>
      <?php endforeach; ?>
    </select>

<?php
    $image = '';
    if(isset($instance['image3']))
    {
        $image = $instance['image3'];
    }
?>

    <p class="an-catlinks-image-demo">
        <label for="<?php _e($this->get_field_name( 'image3' )); ?>"><?php _e( 'Image 3:', 'an_catlinks_wiget' ); ?></label>

        <input name="<?php _e($this->get_field_name( 'image3' )); ?>"
               id="<?php _e($this->get_field_id( 'image3' )); ?>"
               class="widefat" type="text" size="36"
               value="<?php _e(esc_url( $image )); ?>" />
        <img src="<?php _e(esc_url( $image )); ?>" style="max-width: 100%; height: auto;background: <?php _e($bg_color); ?>" />
        <input class="upload_image_button" type="button" value="<?php _e('Upload Image','an_catlinks_wiget'); ?>" />
    </p>
  </div>
  <script>

    (function($) {

        $(document).ready(function() {
          $('.color-picker').wpColorPicker({
            change: function(event, ui) {
              $(this).parent().trigger('change');

              if ($(this).hasClass('an-catlist-bg-color-picker')) {
                $('.an-catlinks-image-demo img').css('background',$(this).val());
              }
            },

            clear: function(event) {
              $(this).parent().trigger('change');

              if ($(this).hasClass('an-catlist-bg-color-picker')) {
                $('.an-catlinks-image-demo img').css('background','transparent');
              }
            }

          });

        });
      }
    )(jQuery);

  </script>

<?php

  } // end of form function

  /*
    Update the instance of the widget
    Here we define custom fields!
  */

  public function update($new_instance, $old_instance) {
    $instance = array();

    $instance['cat1'] = ! empty( $new_instance['cat1'] )  ? intval($new_instance['cat1']) : 0;
    $instance['image1'] = ! empty ($new_instance['image1']) ? $new_instance['image1'] : '';

    $instance['cat2'] = ! empty( $new_instance['cat2'] )  ? intval($new_instance['cat2']) : 0;
    $instance['image2'] = ! empty ($new_instance['image2']) ? $new_instance['image2'] : '';

    $instance['cat3'] = ! empty( $new_instance['cat3'] ) ? intval($new_instance['cat3'])  : 0;
    $instance['image3'] = ! empty ($new_instance['image3']) ? $new_instance['image3'] : '';

    $instance['bg_color'] = ! empty( $new_instance['bg_color'] ) ? $new_instance['bg_color'] : "#212121";
    $instance['font_color'] = ! empty( $new_instance['font_color'] ) ? $new_instance['font_color'] : "#ffffff";
    return $instance;
  }




}


?>
