<?php
/*
 * Author:Naieem Mahmud Supto
 */

/* Creating freebies custom post type */

register_post_type('freebies', array(
    'labels' => array(
        'name' => __('Freebies', 'qode'),
        'singular_name' => __('Freebie', 'qode'),
        'add_item' => __('New Freebie', 'qode'),
        'add_new_item' => __('Add New Freebie', 'qode'),
        'edit_item' => __('Edit Freebie', 'qode')
    ),
    'public' => true,
    'show_in_menu' => true,
    'rewrite' => array('slug' => 'freebies'),
    'menu_position' => 4,
    'show_ui' => true,
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'supports' => array('author', 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'comments')
        )
);

/* Create Freebies Categories */

$labels = array(
    'name' => __('Freebies Categories', 'qode'),
    'singular_name' => __('Freebies Category', 'qode'),
    'search_items' => __('Search Freebies Categories', 'qode'),
    'all_items' => __('All Freebies Categories', 'qode'),
    'parent_item' => __('Parent Freebies Category', 'qode'),
    'parent_item_colon' => __('Parent Freebies Category:', 'qode'),
    'edit_item' => __('Edit Freebies Category', 'qode'),
    'update_item' => __('Update Freebies Category', 'qode'),
    'add_new_item' => __('Add New Freebies Category', 'qode'),
    'new_item_name' => __('New Freebies Category Name', 'qode'),
    'menu_name' => __('Freebies Categories', 'qode'),
);

register_taxonomy('freebie_category', array('freebies'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'freebies-category'),
    'show_admin_column' => true
));

/**
 * Adds a meta box to the post editing screen
 */
function prfx_custom_meta() {
    add_meta_box('prfx_meta', __('Image gallery', 'qode'), 'prfx_meta_callback', 'freebies', 'normal', 'high');
    add_meta_box('free_category', __('Extra Options', 'qode'), 'category_callback', 'freebies', 'normal', 'high');
}

add_action('add_meta_boxes', 'prfx_custom_meta');

/*
 * Outputs the content of the meta box
 */

function prfx_meta_callback($post) {
    wp_nonce_field(basename(__FILE__), 'freebies_nonce');
    $prfx_stored_meta = get_post_meta($post->ID);
    $image = get_post_meta($post->ID, "image", true);
    //print_r($image);
    ?>
    <p class="clone">
        <input type="button" id="meta-image-button" class="button meta-image-button"
               value="<?php _e('Choose or Upload an Image', 'qode') ?>"/>
    </p>
    <div class="show_image">
        <?php
        $img_c = 0;
        if (is_array($image)) {
            if (count($image) > 0) {
                foreach ($image as $val) {
                    echo "<div class='image_container'><input type='hidden' name='image[" . $img_c . "]' value='" . $val . "'> <img src='" . $val . "'><span class='remove'><a href='#' class='expand'>&#10005</a></span></div>";
                    $img_c = $img_c + 1;
                }
            }
        }
        ?>
    </div>


    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            jQuery(".remove").live('click', function (e) {
                e.preventDefault();
                jQuery(this).parent().remove();
            });

            var img_count =<?php echo $img_c; ?>;
            jQuery('.meta-color').wpColorPicker();
            var meta_image_frame;

            // Runs when the image button is clicked.
            jQuery('.meta-image-button').click(function (e) {

                // Prevents the default action from occuring.
                e.preventDefault();

                // If the frame already exists, re-open it.
                if (meta_image_frame) {
                    meta_image_frame.open();
                    return;
                }

                // Sets up the media library frame
                meta_image_frame = wp.media({
                    title: meta_image.title,
                    button: {text: meta_image.button},
                    library: {type: 'image'}
                });

                // Runs when an image is selected.
                meta_image_frame.on('select', function () {

                    // Grabs the attachment selection and creates a JSON representation of the model.
                    var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

                    // Sends the attachment URL to our custom image input field.
                    //$('#meta-image').val(media_attachment.url);
                    jQuery(".show_image").append("<div class='image_container'><input type='hidden' name='image[" + img_count + "]' value='" + media_attachment.url + "'> <img src='" + media_attachment.url + "'><span class='remove'><a href='#' class='expand'>&#10005</a></span></div>");
                    img_count = img_count + 1;
                });
                // Opens the media library frame.
                meta_image_frame.open();
            });
        });
    </script>
    <?php
}

/**
 * Outputs the content of the meta box
 */
function category_callback($post) {
    wp_nonce_field(basename(__FILE__), 'category_nonce');
    $prfx_stored_meta = get_post_meta($post->ID);
    $image = get_post_meta($post->ID, "category_image", true);
    $name = get_post_meta($post->ID, "category_name", true);
    $file_name = get_post_meta($post->ID, "file_name", true);
    //print_r($image);
    ?>
    <p class="clone">
        <label for="meta-color" class="prfx-row-title"><?php _e('Downloadable File Name', '') ?></label>
        <input type="text" class="file_name" style="width: 100%;" name="file_name" value="<?php echo $file_name; ?>"
               placeholder="Enter file url"/><br>
        <input type="button" id="file_upload" class="button"
               value="<?php _e('Choose or Upload an Image', 'qode') ?>"/>
    </p>

    <p class="clone">
        <label for="meta-color" class="prfx-row-title"><?php _e('Sub Category Name', '') ?></label>
        <input type="text" class="" name="category_name" value="<?php echo $name; ?>"
               placeholder="Enter Sub Category Name"/>
    </p>

    <p class="clone">
        <label for="meta-color" class="prfx-row-title"><?php _e('Category Image', '') ?></label>
        <input type="button" id="meta-image-button-category" class="button"
               value="<?php _e('Choose or Upload an Image', 'qode') ?>"/>
    </p>
    <div class="show_image_category">
        <?php
        if ($image != '') {
            echo "<div class='image_container'><input type='hidden' name='category_image' value='" . $image . "'>
         <img src='" . $image . "'><span class='remove'><a href='#' class='expand'>&#10005</a></span></div>";
        }
        ?>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#meta-image-button-category').click(function (e) {

                // Prevents the default action from occuring.
                e.preventDefault();
                var meta_image_frame;
                // If the frame already exists, re-open it.
                if (meta_image_frame) {
                    meta_image_frame.open();
                    return;
                }

                // Sets up the media library frame
                meta_image_frame = wp.media({
                    title: meta_image.title,
                    button: {text: meta_image.button},
                    //library: {type: 'image'}
                });

                // Runs when an image is selected.
                meta_image_frame.on('select', function () {

                    // Grabs the attachment selection and creates a JSON representation of the model.
                    var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

                    // Sends the attachment URL to our custom image input field.
                    //$('#meta-image').val(media_attachment.url);
                    //alert(media_attachment.url);
                    jQuery(".show_image_category").html("<div class='image_container'><input type='hidden' name='category_image' value='" + media_attachment.url + "'> <img src='" + media_attachment.url + "'><span class='remove'><a href='#' class='expand'>&#10005</a></span></div>");
                    //img_count = img_count + 1;
                });
                // Opens the media library frame.
                meta_image_frame.open();
            });

            jQuery('#file_upload').click(function (e) {

                // Prevents the default action from occuring.
                e.preventDefault();
                var meta_image_frame;
                // If the frame already exists, re-open it.
                if (meta_image_frame) {
                    meta_image_frame.open();
                    return;
                }

                // Sets up the media library frame
                meta_image_frame = wp.media({
                    title: meta_image.title,
                    button: {text: meta_image.button},
                    //library: {type: 'image'}
                });

                // Runs when an image is selected.
                meta_image_frame.on('select', function () {

                    // Grabs the attachment selection and creates a JSON representation of the model.
                    var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

                    // Sends the attachment URL to our custom image input field.
                    jQuery('.file_name').val(media_attachment.url);

                    //$(".show_image_category").html("<div class='image_container'><input type='hidden' name='category_image' value='" + media_attachment.url + "'> <img src='" + media_attachment.url + "'><span class='remove'><a href='#' class='expand'>&#10005</a></span></div>");
                    //img_count = img_count + 1;
                });

                // Opens the media library frame.
                meta_image_frame.open();

            });
        });
    </script>
    <?php
}

/**
 * Saves the custom meta input
 */
function prfx_meta_save($post_id) {
    // Checks save status
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST['freebies_nonce']) && wp_verify_nonce($_POST['freebies_nonce'], basename(__FILE__))) ? 'true' : 'false';

    // Exits script depending on save status
    if ($is_autosave || $is_revision || !$is_valid_nonce) {
        return;
    }
    //Saving datas
    if (isset($_POST['image'])) {
        update_post_meta($post_id, 'image', $_POST['image']);
    } else {
        delete_post_meta($post_id, 'image');
    }
    if (isset($_POST['category_name'])) {
        update_post_meta($post_id, 'category_name', $_POST['category_name']);
    } else {
        delete_post_meta($post_id, 'category_name');
    }
    if (isset($_POST['category_image'])) {
        update_post_meta($post_id, 'category_image', $_POST['category_image']);
    } else {
        delete_post_meta($post_id, 'category_image');
    }
    if (isset($_POST['file_name'])) {
        update_post_meta($post_id, 'file_name', $_POST['file_name']);
    } else {
        delete_post_meta($post_id, 'file_name');
    }
}

add_action('save_post', 'prfx_meta_save');

/**
 * Adds the meta box stylesheet when appropriate
 */
function prfx_admin_styles() {
    global $typenow;
    //if ($typenow == 'post') {
    wp_enqueue_style('prfx_meta_box_styles', get_stylesheet_directory_uri() . '/css/custom/meta-box-styles.css');
    //}
}

add_action('admin_print_styles', 'prfx_admin_styles');

/**
 * Loads the color picker javascript
 */
function prfx_color_enqueue() {
    global $typenow;
    //if ($typenow == 'post') {
    wp_enqueue_style('wp-color-picker');
    //wp_enqueue_script( 'meta-box-color-js', plugin_dir_url( __FILE__ ) . 'meta-box-color.js', array( 'wp-color-picker' ) );
    //}
}

add_action('admin_enqueue_scripts', 'prfx_color_enqueue');

/**
 * Loads the image management javascript
 */
function prfx_image_enqueue() {
    global $typenow;
    //if ($typenow == 'post') {
    wp_enqueue_media();

    // Registers and enqueues the required javascript.
    wp_register_script('meta-box-image', plugin_dir_url(__FILE__) . 'meta-box-image.js', array('jquery'));
    wp_localize_script('meta-box-image', 'meta_image', array(
        'title' => __('Choose or Upload an Image', 'qode'),
        'button' => __('Use this image', 'qode'),
        'ajaxUrl' => admin_url('admin-ajax.php'),
            )
    );
    wp_enqueue_script('meta-box-image');
    //}
}

add_action('admin_enqueue_scripts', 'prfx_image_enqueue');

/*
 * Custom funciton to get category and title of the freebies
 */
if (!function_exists('getfreebienavigationPostCategoryAndTitle')) {

    /**
     * Function that compares two portfolio options for sorting
     * @param $post
     * @return html of navigation
     */
    function getfreebienavigationPostCategoryAndTitle($post) {
        $html_info = '<span class="post_info">';
        $categories = wp_get_post_terms($post->ID, 'freebie_category');
        $html_info .= '<span class="categories">';
        $k = 1;
        foreach ($categories as $cat) {
            $html_info .= $cat->name;
            if (count($categories) != $k) {
                $html_info .= ', ';
            }
            $k++;
        }
        $html_info .= '</span>';

        if ($post->post_title != '') {
            $html_info .= '<span class="h5">' . $post->post_title . '</span>';
        }
        $html_info .= '</span>';
        return $html_info;
    }

}

/*
 * Visual composer elements Shortcode
 */

add_shortcode('freebie', 'freebie_func');

function freebie_func($atts, $content = null) { // New function parameter $content is added!
    extract(shortcode_atts(array(
        'title' => '',
                    ), $atts));

    $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
    //echo $foo;
    $args = array('hide_empty=0');
    $terms = get_terms('freebie_category', $args);
    if (!empty($terms) && !is_wp_error($terms)) {
        $count = count($terms);
        $i = 0;
        $term_list = '<ul>';
        $term_list .= '<li class="filter active" data-filter="all"><span>All</span></li>';
        foreach ($terms as $term) {
            $i++;
            $term_list .= '<li class="filter" data-filter="' . $term->name . '"><span>' . $term->name . '</span></li>';
        }
        $term_list .= '</ul>';
    }

    $args = array(
        'post_type' => 'freebies',
        'post_status' => 'publish',
        'posts_per_page' => -1, // you may edit this number
        'orderby' => 'rand'
    );
    $query = new WP_Query($args);
    $freebie_list = '';
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $categories = wp_get_post_terms(get_the_ID(), 'freebie_category');
            $cat_name = get_post_meta(get_the_ID(), "category_name", true);
            $cat_image = get_post_meta(get_the_ID(), "category_image", true);
            $category_html = '';
            $k = 1;
            foreach ($categories as $cat) {
                $category_html .= $cat->name;
                if (count($categories) != $k) {
                    $category_html .= ' ';
                }
                $k++;
            }
            if (has_post_thumbnail()) :
                $thumbnail_uri = get_the_post_thumbnail_url();
            endif;
            $freebie_list .= "<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>";
            $freebie_list .= ' <article class="mix ' . $category_html . '  mix_all" style="display: inline-block; opacity: 1; visibility: visible;">
            <div class="image_holder">
            <a class="portfolio_link_for_touch" href="' . esc_url(get_permalink()) . '" target="_self"><span class="image">
            <img width="570" height="570" src="' . $thumbnail_uri . '" class="attachment-portfolio-square size-portfolio-square wp-post-image"
            alt="8d37bc34075772"></span></a>
            <span class="text_holder">
            <span class="text_outer"><span class="text_inner">
            <span class="feature_holder"></span></span></span></span></div>
            <div class="portfolio_description "><h5 class="portfolio_title">
            <a href="' . esc_url(get_permalink()) . '" target="_self">' . esc_attr(get_the_title()) . '</a></h5><span class="project_category">' . esc_attr(get_the_excerpt()) . '</span>
            </div>
            <div class="portfolio_description" id="download_part">

                <div class="one-third" ><img src="' . $cat_image . '" >
                <span>' . $cat_name . '</span></div>

                <div class="one-third" ><img src="' . get_stylesheet_directory_uri() . '/img/download_logo.jpg" >
                <span>' . downloadcounter(get_the_ID()) . '</span></div>

                <div class="one-third"><a href="' . esc_url(get_permalink()) . '" class="link">View Details</a></div>

            </div>

        </article>';

        endwhile;
    endif;

    return '<div class="projects_holder_outer v3 portfolio_with_space portfolio_standard ">
        <div class="filter_outer">
            <div class="filter_holder">
            <h1 style="margin-bottom: 20px; font-size: 30pt;font-family:\'Montserrat\', sans-serif;">' . $title . '</h1>
                ' . $term_list . '
            </div>
        </div>
        <div class="projects_holder portfolio_main_holder clearfix v3 standard portfolio_square_image  hideItems">
            ' . $freebie_list . '
            <div class="filler"></div>
            <div class="filler"></div>
            <div class="filler"></div>
            <div class="filler"></div>
        </div>
    </div>';
}

/*
 * Visual composer element
 */

add_action('vc_before_init', 'add_new_element');

function add_new_element() {
    vc_map(array(
        "name" => __("Freebie Listing", "qode"),
        "base" => "freebie",
        "class" => "",
        "category" => __("Custom", "qode"),
//'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
//'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
        "params" => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => __("Title", "qode"),
                "param_name" => "title",
                "value" => __("", "qode"),
                "description" => __("Title For the Heading", "qode")
            ),
        /* array(
          "type" => "colorpicker",
          "class" => "",
          "heading" => __("Text color", "qode"),
          "param_name" => "color",
          "value" => '#FF0000', //Default Red color
          "description" => __("Choose text color", "qode")
          ),
          array(
          "type" => "textarea_html",
          "holder" => "div",
          "class" => "",
          "heading" => __("Content", "qode"),
          "param_name" => "content", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
          "value" => __("", "qode"),
          "description" => __("Enter your content.", "qode")
          ) */
        )
    ));
}

/*
 * file size computing
 */

function Size($path) {
    $contents = file_get_contents($path);
    $fp = fopen("1.jpeg", "w");
    file_put_contents("1.jpeg", $contents);
    $bytes = sprintf('%u', filesize('1.jpeg'));

    if ($bytes > 0) {
        $unit = intval(log($bytes, 1024));
        $units = array('B', 'KB', 'MB', 'GB');

        if (array_key_exists($unit, $units) === true) {
            return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
        }
    }

    return $bytes;
}

/*
 * Ajax request handling
 */

add_action('wp_ajax_download', 'download_file');
add_action('wp_ajax_nopriv_download', 'download_file');

function download_file() {
    global $wpdb;
    $post_id = $_POST['id'];
    $email = $_POST['email'];
    $file_path = get_post_meta($post_id, "file_name", true);
    $ret = $wpdb->insert("newletter", array(
        "email" => $email,
        "freebie_id" => $post_id,
        "date_time" => current_time('mysql', 1),
    ));
    if ($email == '') {
        $return = array("return" => $ret, "file_path" => $file_path, "error" => 'Enter Email Address Please');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $return = array("return" => $ret, "file_path" => $file_path, "error" => 'Invalid email format');
    } else {
        $return = array("return" => $ret, "file_path" => $file_path, "error" => '');
        include_once('custom/email-template.php');
        $message = ob_get_contents();
        ob_end_clean();
        $headers = "From: droitlab@droitlab.com \r\n";
        $headers .= "Reply-To: droitlab@droitlab.com \r\n";
        $headers .= "CC: droitlab@droitlab.com \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        //wp_mail($email, "Freebie Download", $message);
        mail($email,"Freebie Download", $message, $headers);
    }

    echo json_encode($return);
    die();
}

add_action('wp_ajax_delete_newsletter', 'deletenewsletter');
add_action('wp_ajax_nopriv_delete_newsletter', 'deletenewsletter');

function deletenewsletter() {
    global $wpdb;
    $post_id = $_POST['id'];
    $ret = $wpdb->delete('newletter', array('ID' => $post_id));
    //echo json_encode($return);
    echo $ret;
    die();
}

add_action('wp_ajax_delete_newsletter_all', 'deletenewsletterall');
add_action('wp_ajax_nopriv_delete_newsletter_all', 'deletenewsletterall');

function deletenewsletterall() {
    global $wpdb;
    $post_ids = $_POST['ids'];
    foreach ($post_ids as $post_id) {
        $ret = $wpdb->delete('newletter', array('ID' => $post_id));
    }
    //print_r($post_ids);
    die();
}

add_filter("wp_mail_content_type", "set_html_content_type");

function set_html_content_type() {
    return "text/html";
}

/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
            __('Newsletter subscriber', 'textdomain'), 'Newsletter', 'manage_options', 'newsletter', 'newsletter_page', '', 6
    );
}

add_action('admin_menu', 'wpdocs_register_my_custom_menu_page');

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */

function sandbox_initialize_theme_options() {

    // First, we register a section. This is necessary since all future options must belong to a
    add_settings_section(
            'general_settings_section', // ID used to identify this section and with which to register options
            'Sandbox Options', // Title to be displayed on the administration page
            'sandbox_general_options_callback', // Callback used to render the description of the section
            'general'                           // Page on which to add this section of options
    );

    // Next, we'll introduce the fields for toggling the visibility of content elements.
    add_settings_field(
            'show_header', // ID used to identify the field throughout the theme
            'Header', // The label to the left of the option interface element
            'sandbox_toggle_header_callback', // The name of the function responsible for rendering the option interface
            'general', // The page on which this option will be displayed
            'general_settings_section', // The name of the section to which this field belongs
            array(// The array of arguments to pass to the callback. In this case, just a description.
        'Activate this setting to display the header.'
            )
    );

    add_settings_field(
            'show_content', 'Content', 'sandbox_toggle_content_callback', 'general', 'general_settings_section', array(
        'Activate this setting to display the content.'
            )
    );

    add_settings_field(
            'show_footer', 'Footer', 'sandbox_toggle_footer_callback', 'general', 'general_settings_section', array(
        'Activate this setting to display the footer.'
            )
    );

    // Finally, we register the fields with WordPress

    register_setting(
            'general', 'show_header'
    );

    register_setting(
            'general', 'show_content'
    );

    register_setting(
            'general', 'show_footer'
    );
}

// end sandbox_initialize_theme_options
add_action('admin_init', 'sandbox_initialize_theme_options');

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */

function sandbox_general_options_callback() {
    echo '<p>Select which areas of content you wish to display.</p>';
}

// end sandbox_general_options_callback

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */

function sandbox_toggle_header_callback($args) {

    // Note the ID and the name attribute of the element match that of the ID in the call to add_settings_field
    $html = '<input type="checkbox" id="show_header" name="show_header" value="1" ' . checked(1, get_option('show_header'), false) . '/>';

    // Here, we'll take the first argument of the array and add it to a label next to the checkbox
    $html .= '<label for="show_header"> ' . $args[0] . '</label>';

    echo $html;
}

// end sandbox_toggle_header_callback

function sandbox_toggle_content_callback($args) {

    $html = '<input type="checkbox" id="show_content" name="show_content" value="1" ' . checked(1, get_option('show_content'), false) . '/>';
    $html .= '<label for="show_content"> ' . $args[0] . '</label>';

    echo $html;
}

// end sandbox_toggle_content_callback

function sandbox_toggle_footer_callback($args) {

    $html = '<input type="checkbox" id="show_footer" name="show_footer" value="1" ' . checked(1, get_option('show_footer'), false) . '/>';
    $html .= '<label for="show_footer"> ' . $args[0] . '</label>';

    echo $html;
}

// end sandbox_toggle_footer_callback
/**
 * Display a custom menu page
 */
function newsletter_page() {
    ?>
    <!-- Create a header in the default WordPress 'wrap' container -->
    <div class="wrap">

        <div id="icon-themes" class="icon32"></div>
        <h2>Subscribers List</h2>
        <?php //settings_errors();
        ?>

        <?php
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'list';
        ?>

        <h2 class="nav-tab-wrapper">
            <a href="?page=newsletter&tab=list"
               class="nav-tab <?php echo $active_tab == 'list' ? 'nav-tab-active' : ''; ?>">List</a>
            <a href="?page=newsletter&tab=others"
               class="nav-tab <?php echo $active_tab == 'others' ? 'nav-tab-active' : ''; ?>">Others</a>
        </h2>
        <?php
        if ($active_tab == 'list') {
            //the_editor($updated_value, $id = 'content', $prev_id = 'title', $media_buttons = true, $tab_index = 2); 
            ?>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
            <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
            <script>
        jQuery(document).ready(function () {
            jQuery(".check").change(function () {
                jQuery(".check1").prop('checked', jQuery(this).prop("checked"));
            });
            jQuery('.newsletter_table').DataTable();

            jQuery(".remove-single").live('click', function (e) {
                e.preventDefault();
                var rem = jQuery(this).parent().parent();
                var id = jQuery(this).attr("data-id");
                jQuery.ajax({
                    data: {action: 'delete_newsletter', id: id},
                    type: 'post',
                    url: ajaxurl,
                    success: function (data) {
                        //alert(data); //should print out the name since you sent it along
                        rem.remove();
                        location.reload()
                    }
                });
            });

            jQuery(".remove-all").live('click', function (e) {
                e.preventDefault();
                //var rem=jQuery(this).parent().parent();
                //var id=jQuery(this).attr("data-id");
                var total = jQuery('input[type=checkbox]:checked').length;
                if (total > 0) {
                    var checkValues = jQuery('input[type=checkbox]:checked').map(function () {
                        return jQuery(this).val();
                    }).get();
                    jQuery.ajax({
                        data: {action: 'delete_newsletter_all', ids: checkValues},
                        type: 'post',
                        url: ajaxurl,
                        success: function (data) {
                            //alert(data); //should print out the name since you sent it along
                            //rem.remove();
                            location.reload()
                        }
                    });
                }
                else {
                    alert("Select Checkbox to Delete");
                }

            });

        });
            </script>
            <style>
                tbody td {
                    padding: 0 10px !important;
                    text-align: center !important;
                }

                .dataTables_info {
                    display: none !important;
                }
            </style>
            <?php
            global $wpdb;
            $sql = "SELECT * FROM newletter order by id desc";
            $result = $wpdb->get_results($sql) or die(mysql_error());
            if (count($result) > 0) {
                ?>
                <button class="btn btn-7 btn-7d btn-icon-only icon-remove remove-all">&nbsp</button>
                <table class="newsletter_table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="check" value="1"></th>
                            <th>Count</th>
                            <th>Email</th>
                            <th>Subscribe Date</th>
                            <th>Downloaded Item</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        if (is_array($result)) {
                            foreach ($result as $results) {
                                echo '<tr>
                        <td><strong><input type="checkbox" class="check1" value="' . $results->id . '"></strong></td>
                        <td>' . $counter . '</td>
                        <td>' . $results->email . '</td>
                            <td>' . download_date($results->date_time) . '</td>
                            <td>' . get_the_title($results->freebie_id) . '</td>
                        <td>
                            <button class="btn btn-7 btn-7d btn-icon-only icon-remove remove-single" data-id="' . $results->id . '">&nbsp</button>
                        </td>
                    </tr>';
                                $counter = $counter + 1;
                            }
                        }
                        ?>

                    </tbody>
                </table>
                <?php
            } else {
                echo "No Subscriber Found In the List";
            }
            //settings_fields('general');
            //do_settings_sections('general');
        } else {
            //settings_fields('sandbox_theme_social_options');
            //do_settings_sections('sandbox_theme_social_options');
        } // end if/else
        ?>

        <?php //submit_button();  ?>

    </div><!-- /.wrap -->
    <?php
}

// end sandbox_theme_display


add_action('wp_head', 'myplugin_ajaxurl');

function myplugin_ajaxurl() {

    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
           var loading = "' . get_stylesheet_directory_uri() . '/custom/loading.gif";
         </script>';
}

function my_enqueue($hook) {
    wp_enqueue_script('post');
    if (user_can_richedit()) {
        wp_enqueue_script('editor');
    }
    add_thickbox();
    wp_enqueue_script('media-upload');
    wp_enqueue_script('word-count');
    wp_enqueue_style('component_style', get_stylesheet_directory_uri() . '/custom/component.css');
}

add_action('admin_enqueue_scripts', 'my_enqueue');

/**
 * Proper way to enqueue scripts and styles.
 */
function wpdocs_theme_name_scripts() {
    wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/custom/custom.css');
    wp_enqueue_style('magnific-style', get_stylesheet_directory_uri() . '/custom//magnific-popup.css');

    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/custom/custom.js');
    wp_enqueue_script('magnific-js', get_stylesheet_directory_uri() . '/custom/jquery.magnific-popup.js');
}

add_action('wp_enqueue_scripts', 'wpdocs_theme_name_scripts');

function download_date($date) {
    $time = strtotime($date);
    return date("l jS \of F Y h:i:s A", $time);
}

function downloadcounter($id) {
    global $wpdb;
    $result = $wpdb->get_var("SELECT COUNT(*) FROM newletter where freebie_id=$id");
    //$result=$wpdb->query($wpdb->prepare("select count(*) from newletter where freebie_id=%d",array($id)));
    return $result;
}
