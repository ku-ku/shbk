<?php

/**
 * Description of Pg_Widget
 *
 * @author lix
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

require_once ABSPATH.'/wp-includes/widgets.php';
class Pg_Widget extends WP_Widget {
        public function __construct() {
            parent::__construct(
                    'my_show_page',
                    'Произвольная страница',
		    array( 'description' => 'Включает любую заданную по "slug" страницу') // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
            echo $args['before_widget'];
            $slug = $instance['slug'];
            $show_title = (1==$instance['title']);
            $full = (1==$instance['full']) ? 1 : 0;
            if ( empty($slug) ){
                $title  = apply_filters( 'widget_title', 'Страница', $instance, $this->id_base );
                echo $args['before_title'] . $title . $args['after_title'];
            } else {
                global $more;
                $old_more = $more;
                $n = 0;
                $qr = new WP_Query(array("name"=>$slug));
                while ( $qr->have_posts() ) {
                    $qr->the_post();
                    if (($n==0)&&($show_title)) {
                        echo $args['before_title'] . get_the_title() . $args['after_title'];
                    }
                    $more = $full;
                    the_content();
                    $n++;
                } 
                if ( $n == 0 ) {
                    echo "no page [".$slug."] found";
                }
                $more = $old_more;
                wp_reset_postdata();
            }
            echo $args['after_widget'];
            
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
            $defaults = array( 'slug' => '', 'title' => 0 );
	    $instance = wp_parse_args( (array) $instance, $defaults );
            $pg = $instance['slug'];
            $chk = $instance['title'];
            $full = $instance['full'];
	?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'slug' ));?>">Страница:</label>&nbsp;
                <input type="text" class="widefat"
                       id="<?php echo esc_attr($this->get_field_id( 'slug' ));?>"
                       name="<?php echo esc_attr($this->get_field_name( 'slug' ));?>" value="<?php echo esc_attr($pg);?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'title' ));?>">Вывести заголовок:</label>&nbsp;
                <input type="checkbox" <?php echo ($chk?'checked="checked"':'');?> class="widefat" 
                       id="<?php echo esc_attr($this->get_field_id( 'title' ));?>" 
                       name="<?php echo esc_attr($this->get_field_name( 'title' ));?>" value="1" />
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id( 'full' ));?>">Вывести весь текст:</label>&nbsp;
                <input type="checkbox" <?php echo ($full?'checked="checked"':'');?> class="widefat" 
                       id="<?php echo esc_attr($this->get_field_id( 'full' ));?>" 
                       name="<?php echo esc_attr($this->get_field_name( 'full' ));?>" value="1" />
            </p>
        <?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
            return array("slug"=>$new_instance['slug'],"title"=>("1" === $new_instance['title'])?1:0,"full"=>("1" === $new_instance['full'])?1:0);
        }
}
