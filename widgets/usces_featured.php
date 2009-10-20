<?php

/**
 * Welcart_featured Class
 */
class Welcart_featured extends WP_Widget {
    /** constructor */
    function Welcart_featured() {
        parent::WP_Widget(false, $name = 'Welcartお勧め商品');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {	
		global $usces;
        extract( $args );
        $title = $instance['title'] == '' ? 'Welcartお勧め商品' : $instance['title'];
        $icon = $instance['icon'] == '' ? 1 : (int)$instance['icon'];
		//if($icon == 1) $before_title = '<div class="widget_title"><img src="' . USCES_PLUGIN_URL . '/images/osusume.png" alt="' . $title . '" width="24" height="24" />';
		if($icon == 1) $before_title .= '<img src="' . USCES_PLUGIN_URL . '/images/osusume.png" alt="' . $title . '" width="24" height="24" />';
        ?>
              <?php echo $before_widget; ?>
                  <?php echo $before_title
                      . wp_specialchars($title)
                      . $after_title; ?>
					  
		<ul class="ucart_featured_body ucart_widget_body"><li>
			<?php
			$offset = usces_posts_random_offset(get_posts('category='.usces_get_cat_id( 'itemreco' )));
			$myposts = get_posts('numberposts=1&offset='.$offset.'&category='.usces_get_cat_id( 'itemreco' ));
			foreach($myposts as $post) :
				$post_id = $post->ID;
			?>
				<div class="thumimg"><a href="<?php echo get_permalink($post_id); ?>"><?php usces_the_itemImage($number = 0, $width = 150, $height = 150, $post ); ?></a></div>
				<div class="thumtitle"><a href="<?php echo get_permalink($post_id); ?>" rel="bookmark"><?php echo $usces->getItemName($post_id); ?>&nbsp;(<?php echo $usces->getItemCode($post_id); ?>)</a></div>
			<?php endforeach; ?>
		</li></ul>
				  
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = $instance['title'] == '' ? 'Welcartお勧め商品' : esc_attr($instance['title']);
		$icon = $instance['icon'] == '' ? 1 : (int)$instance['icon'];
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('icon'); ?>">アイコンの示数: <select class="widefat" id="<?php echo $this->get_field_id('icon'); ?>" name="<?php echo $this->get_field_name('icon'); ?>"><option value="1"<?php if($icon == 1){echo ' selected="selected"';} ?>>表示する</option><option value="2"<?php if($icon == 2){echo ' selected="selected"';} ?>>表示しない</option></select></label></p>
        <?php 
    }

}
?>