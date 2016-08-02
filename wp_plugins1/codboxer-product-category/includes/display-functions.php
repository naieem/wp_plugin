<?php

/*********************************
Display functions for information output
*********************************/
class codboxer_category_widget extends WP_Widget {

	// constructor
	function codboxer_category_widget() {
		parent::WP_Widget(false, $name = __('List Product Categories', 'codboxer') );
	}
	// widget form creation
	function form($instance) {
	// Check values
	if( $instance) {
		 $title = esc_attr($instance['title']);
	} else {
		 $title = '';
	}
	?>
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'codboxer'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<?php
	}
	// update widget
	function update($new_instance, $old_instance) {
		  $instance = $old_instance;
		  // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		 return $instance;
	}

	// widget display
	function widget($args, $instance) {
		extract( $args );
		// these are the widget options
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		echo "<h4 class='product_category_title'>".$title."</h4>";
		echo"<ul class='product-categories custom_cat'>";
		$args = array(
		'show_option_all'    => '',
		'orderby'            => 'name',
		'order'              => 'ASC',
		'style'              => 'list',
		'show_count'         => 1,
		'hide_empty'         => 0,
		'use_desc_for_title' => 1,
		'feed'               => '',
		'feed_type'          => '',
		'feed_image'         => '',
		'exclude'            => '',
		'exclude_tree'       => '',
		'include'            => '',
		'hierarchical'       => 1,
		'title_li'           => __( '' ),
		'show_option_none'   => __( '' ),
		'number'             => null,
		'echo'               => 1,
		'depth'              => 0,
		'current_category'   => 0,
		'pad_counts'         => 0,
		'taxonomy'           => 'product_cat',
		'walker'             => null
		);
		wp_list_categories( $args );
		echo "</ul>";
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("codboxer_category_widget");'));
?>