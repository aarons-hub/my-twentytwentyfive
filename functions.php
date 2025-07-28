<?php
    function custom_enquene_scripts() {
        wp_enqueue_script( 'jquery' , get_stylesheet_directory_uri() . '/js/jquery-3.7.0.min.js', [], '1.0', true);
        wp_enqueue_script( 'owl-carousel' , get_stylesheet_directory_uri() . '/js/owl-carousel.js', [], '1.0', true);
        wp_enqueue_script( 'jquery-basic-table' , get_stylesheet_directory_uri() . '/js/jquery.basictable.min.js', [], '1.0', true);
        wp_enqueue_script( 'customjs' , get_stylesheet_directory_uri() . '/js/custom.js', [], '1.0', true);
    }
    add_action( 'wp_enqueue_scripts', 'custom_enquene_scripts' );


    // function wpb_add_google_fonts() {
    //     wp_enqueue_style('wpb-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap', false);
    // }
    // add_action('wp_enqueue_scripts', 'wpb_add_google_fonts');


    // Google Analytics
    function add_google_analytics() {
        $ga_script = "window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'G-VSG959XWRE');";

        echo wp_get_inline_script_tag( $ga_script );

        echo wp_get_script_tag( array(
            'async' => true,
            'src' => 'https://www.googletagmanager.com/gtag/js?id=G-VSG959XWRE'
        ) );

    }
    add_action( 'wp_head', 'add_google_analytics', 1 );



    
    function custom_enqueue_style() {
        wp_enqueue_style('custom-style', get_stylesheet_uri() ); 
    }
    add_action( 'wp_enqueue_scripts', 'custom_enqueue_style' );




    // Admin style
    function my_admin_theme_style() {
        wp_enqueue_style('my-admin-style', get_stylesheet_directory_uri() . '/css/custom-admin.css');
    }
    add_action('admin_enqueue_scripts', 'my_admin_theme_style');




    // Add excerpt field to admin
    add_post_type_support('page', 'excerpt');



    function pageinfo() {
        wp_enqueue_script( 'customjs', get_template_directory_uri() . '/js/custom.js', [], '1.0', true );
    
        $siteurl = get_site_url();
        $path = get_stylesheet_directory_uri();
        $current_page_id = get_the_ID();
        $parent_page_id = wp_get_post_parent_id( get_the_ID() );
    
        wp_localize_script( 'customjs', 'param', array(
            'siteurl' => $siteurl,
            'path' => $path,
            'current_page_id' => $current_page_id,
            'parent_page_id' => $parent_page_id
        ) );
    }
    add_action( 'wp_enqueue_scripts', 'pageinfo' );




    function site_breadcrumb_shortcode() {
        $breadcrumb = '<ul class="breadcrumb">';
        
        // Add the home link
        $breadcrumb .= '<li class="home"><a href="' . home_url() . '">Home</a></li>';
        
        // Get the current post or page
        $post = get_queried_object();
        
        if ($post) {
            // Get the post ancestors
            $ancestors = get_post_ancestors($post);
            
            // Reverse the order to start from the root ancestor
            $ancestors = array_reverse($ancestors);
            
            // Add each ancestor to the breadcrumb
            foreach ($ancestors as $ancestor) {
                $ancestor_title = get_the_title($ancestor);
                $ancestor_link = get_permalink($ancestor);
                $breadcrumb .= '<li><a href="' . $ancestor_link . '">' . $ancestor_title . '</a></li>';
            }
            
            // Add the current post or page to the breadcrumb
            $current_title = get_the_title($post);
            $breadcrumb .= '<li>' . $current_title . '</li>';
        }
        
        $breadcrumb .= '</ul>';
        
        return $breadcrumb;
    }
    add_shortcode('site_breadcrumb', 'site_breadcrumb_shortcode');
    



    function landing_page_tiles_shortcode() {
        global $post;
    
        // Get the direct child pages of the current page
        $children = get_children(array(
            'post_parent' => $post->ID,
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
    
        // Sort child pages based on menu_order
        uasort($children, function ($a, $b) {
            return $a->menu_order - $b->menu_order;
        });
    
        // Start the unordered list
        $list = '<ul class="landing-page-tiles">';
    
        // Loop through the children and generate list items
        foreach ($children as $child) {
            // Get the child page details
            $child_title = get_the_title($child);
            $child_link = get_permalink($child);
            $child_excerpt = get_the_excerpt($child);
            $child_content = wp_strip_all_tags($child->post_content);
            $child_featured_image = get_the_post_thumbnail_url($child);
    
            // Trim the excerpt to a certain number of words
            $excerpt_length = 20;
            $child_excerpt = wp_trim_words($child_excerpt, $excerpt_length);
    
            // Trim the content to a certain number of words
            $content_length = 30;
            $child_content = wp_trim_words($child_content, $content_length);
    
            // Count the number of child pages for the current page
            $child_count = count(get_children(array(
                'post_parent' => $child->ID,
                'post_status' => 'publish',
                'post_type' => 'page',
            )));
    
            // Build the list item HTML
            $list_item = '<li>';
    
            // Add featured image if available with hyperlink
            if ($child_featured_image) {
                $list_item .= '<a href="' . $child_link . '"><img src="' . $child_featured_image . '" alt="' . $child_title . '"></a>';
            }
            // Add page title and link
            $list_item .= '<h3><a href="' . $child_link . '">' . $child_title . '</a></h3>';
    
            // Add excerpt if available, otherwise use trimmed content
            if ($child_excerpt) {
                $list_item .= '<p>' . $child_excerpt . '</p>';
            } else {
                $list_item .= '<p>' . $child_content . '</p>';
            }
    
        // Display the number of child pages if there are any
        if ($child_count > 0) {
            $list_item .= '<div class="child-page-counter">' . $child_count . '</div>';
        }
            // Close the list item
            $list_item .= '</li>';
            // Append the list item to the unordered list
            $list .= $list_item;
        }
        // Close the unordered list
        $list .= '</ul>';
    
        return $list;
    }
    add_shortcode('landing_page_tiles', 'landing_page_tiles_shortcode');
    


    function combined_navigation_shortcode() {
        global $post;
        
        $parent_title = get_the_title($post->post_parent); // Get the parent page title
        $parent_link = get_permalink($post->post_parent); // Get the parent page link
        
        if ( is_page() && $post->post_parent ) {
            $childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0' );
        } else {
            $childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->ID . '&echo=0' );
        }
        
        if ( $childpages ) {
            $string = '<ul class="quicklaunch">';
            
            // Append parent page title as a list item with a link
            $string .= '<li><h3><a href="' . $parent_link . '">' . $parent_title . '</a></h3></li>';
            
            // Append the child pages
            $string .= $childpages;
            
            $string .= '</ul>';
        }
        
        return $string;
    }
    add_shortcode('combined_navigation', 'combined_navigation_shortcode');
    
    


    function products_shortcode() {
        $parent_id = 403; // ID of the parent page
        
        // Get the direct child pages of the specified parent
        $children = get_children(array(
            'post_parent' => $parent_id,
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
    
        // Sort child pages based on menu_order
        uasort($children, function($a, $b) {
            return $a->menu_order - $b->menu_order;
        });
    
        // Start the unordered list
        $list = '<ul class="landing-page-tiles">';
    
        // Loop through the children and generate list items
        foreach ($children as $child) {
            // Get the child page details
            $child_title = get_the_title($child);
            $child_link = get_permalink($child);
            $child_excerpt = get_the_excerpt($child);
            $child_content = wp_strip_all_tags($child->post_content);
            $child_featured_image = get_the_post_thumbnail_url($child);
    
            // Trim the excerpt to a certain number of words
            $excerpt_length = 20;
            $child_excerpt = wp_trim_words($child_excerpt, $excerpt_length);
    
            // Trim the content to a certain number of words
            $content_length = 30;
            $child_content = wp_trim_words($child_content, $content_length);
    
            // Build the list item HTML
            $list_item = '<li>';
    
            // Add featured image if available with hyperlink
            if ($child_featured_image) {
                $list_item .= '<a href="' . $child_link . '"><img src="' . $child_featured_image . '" alt="' . $child_title . '"></a>';
            }
    
            // Add page title and link
            $list_item .= '<h3><a href="' . $child_link . '">' . $child_title . '</a></h3>';
    
            // Add excerpt if available, otherwise use trimmed content
            if ($child_excerpt) {
                $list_item .= '<p>' . $child_excerpt . '</p>';
            } else {
                $list_item .= '<p>' . $child_content . '</p>';
            }
    
            // Close the list item
            $list_item .= '</li>';
    
            // Append the list item to the unordered list
            $list .= $list_item;
        }
    
        // Close the unordered list
        $list .= '</ul>';
    
        return $list;
    }
    add_shortcode('products', 'products_shortcode');




    function footer_products_shortcode() {
        // Get the "products" page by slug
        $parent_slug = 'products';
        $parent = get_page_by_path($parent_slug);
        
        if (!$parent) {
            return ''; // If the parent page doesn't exist, return an empty string
        }
        
        // Get the direct child pages of the "products" page
        $children = get_children(array(
            'post_parent' => $parent->ID,
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
    
        // Sort child pages based on menu_order
        uasort($children, function($a, $b) {
            return $a->menu_order - $b->menu_order;
        });
    
        // Start the unordered list
        $list = '<ul class="footer-products">';
    
        // Loop through the children and generate list items
        foreach ($children as $child) {
            // Get the child page details
            $child_title = get_the_title($child);
            $child_link = get_permalink($child);
    
            // Build the list item HTML
            $list_item = '<li>';
            $list_item .= '<a href="' . $child_link . '">' . $child_title . '</a>';
            $list_item .= '</li>';
    
            // Append the list item to the unordered list
            $list .= $list_item;
        }
    
        // Close the unordered list
        $list .= '</ul>';
    
        return $list;
    }
    add_shortcode('footer_products', 'footer_products_shortcode');





    function grouped_acf_file_links_shortcode($atts) {
        $atts = shortcode_atts(array(
            'link' => 'link_one', // Default to link_one if no 'link' attribute is provided
        ), $atts, 'grouped_acf_file_links');
    
        $links = array(
            'link_one',
            'link_two',
            'link_three',
            'link_four',
            // Add more links here if needed
        );
    
        // Check if the provided link is one of the allowed links
        if (!in_array($atts['link'], $links)) {
            return 'Invalid link attribute. Allowed values are: ' . implode(', ', $links);
        }
    
        $output = '';
        if ($atts['link'] === 'link_one') {
            $field_value = get_field($atts['link']);
    
            if ($field_value) {
                $output .= '<h2 class="downloads">Downloads</h2>';
            }
        }
    
        $output .= '<ul class="downloads-list">';
        foreach ($links as $link) {
            $field_value = get_field($link);
    
            if ($field_value) {
                $file_url = $field_value['url'];
                $file_title = $field_value['title'];
                $output .= '<li><a target="_blank" href="' . $file_url . '">' . $file_title . '</a></li>';
            }
        }
        $output .= '</ul>';
    
        return $output;
    }
    add_shortcode('grouped_acf_file_links', 'grouped_acf_file_links_shortcode');





    // Front page carousel - company logos posttype
    function front_page_logos_post_type() {
        register_post_type( 'front-page-logos',
            // WordPress CPT Options Start
            array(
                'labels' => array(
                    'name' => __( 'Front page logos' ),
                    'singular_name' => __( 'Front page logos' )
                ),
                'has_archive' => true,
                'public' => true,
                'rewrite' => array('slug' => 'front-page-logos'),
                'menu_icon' => 'dashicons-embed-photo',
                // 'show_in_rest' => true, Only use this if you want the block editor
                'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes' ),
            )
        );
    }
    add_action( 'init', 'front_page_logos_post_type' );
    
    
    
    function front_page_logos_shortcode($atts) {
        $atts = shortcode_atts( array(
            'count' => 30, // Number of logos to display (default: 30)
        ), $atts );
    
        $args = array(
            'post_type' => 'front-page-logos',
            'posts_per_page' => $atts['count'],
            'orderby' => 'menu_order',
            'order' => 'ASC',
        );
    
        $query = new WP_Query( $args );
    
        if ( $query->have_posts() ) {
            $output = '<ul id="front-page-logos" class="owl-carousel owl-theme front-page-logos">';
            while ( $query->have_posts() ) {
                $query->the_post();
                $company_logo = get_field( 'company_logo' );
                $company_url = get_field( 'company_url' );
                if ( $company_logo && $company_url ) {
                    $output .= '<li class="item"><a href="' . esc_url( $company_url ) . '" target="_blank"><img src="' . esc_url( $company_logo['url'] ) . '" alt="' . esc_attr( get_the_title() ) . '"></a></li>';
                } elseif ( $company_logo ) {
                    $output .= '<li class="item"><img src="' . esc_url( $company_logo['url'] ) . '" alt="' . esc_attr( get_the_title() ) . '"></li>';
                }
            }
            $output .= '</ul>';
            wp_reset_postdata();
            return $output;
        }
    
        return ''; // Return empty string if no logos found
    }
    add_shortcode( 'front_page_logos', 'front_page_logos_shortcode' );
    




    // Front page carousel posttype
    function front_page_carousel_post_type() {
        register_post_type( 'front-page-carousel',
            array(
                'labels' => array(
                    'name' => __( 'Front page carousel' ),
                    'singular_name' => __( 'Front page carousel' )
                ),
                'has_archive' => true,
                'public' => true,
                'rewrite' => array('slug' => 'front-page-carousel'),
                'menu_icon' => 'dashicons-embed-photo',
                'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes' ),
            )
        );
     }
     add_action( 'init', 'front_page_carousel_post_type' );



    
    
    function front_page_carousel_shortcode($atts) {
        $atts = shortcode_atts(array(
            'count' => 10, // Number of items to display (default: 10)
            'link_text' => 'Read more', // Default link text if not specified in the shortcode
        ), $atts);
    
        $args = array(
            'post_type' => 'front-page-carousel',
            'posts_per_page' => $atts['count'],
            'orderby' => 'menu_order',
            'order' => 'ASC',
        );
    
        $query = new WP_Query($args);
    
        if ($query->have_posts()) {
            $output = '<div id="front-page-carousel" class="front-page-carousel owl-theme owl-carousel">';
            while ($query->have_posts()) {
                $query->the_post();
                $carousel_title = get_the_title();
                $carousel_image = get_field('carousel_image');
                $carousel_link = get_field('carousel_link'); // Retrieve the carousel_link field
                $link_text = isset($carousel_link['title']) ? $carousel_link['title'] : $atts['link_text']; // Use the title of carousel_link if available, otherwise use the default link_text
    
                if ($carousel_title) {
                    $output .= '<div class="item">';
                    if ($carousel_image) {
                        $output .= '<img src="' . esc_url($carousel_image['url']) . '" alt="' . esc_attr($carousel_title) . '">';
                    }
                    $output .= '<div class="carousel-content">';
                    $output .= '<h1>' . $carousel_title . '</h1>';
                    if ($carousel_link && $carousel_link['url']) {
                        $output .= '<a href="' . esc_url($carousel_link['url']) . '" target="_self">' . esc_html($link_text) . '</a>';
                    }
                    $output .= '</div>'; // Close the carousel-content div
                    $output .= '</div>'; // Close the item div
                }
            }
            $output .= '</div>';
            wp_reset_postdata();
            return $output;
        }
    
        return ''; // Return empty string if no carousel items found
    }
    add_shortcode('front_page_carousel', 'front_page_carousel_shortcode');  



function autosearch_custom_shortcode() { //Custom autocomplete form
    ob_start();
    ?>
    <div id="autosearch-custom">
        <input type="text" name="keyword" id="keyword" placeholder="Search our products" onkeyup="fetch()"><button id="clearButton" class="clear-btn" onclick="clearData()">Clear</button>
        <ul id="datafetch" class="hide"></ul>
    </div>
    <?php
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('autosearch_custom', 'autosearch_custom_shortcode');

add_action( 'wp_footer', 'ajax_fetch' );
function ajax_fetch() {
?>
<script type="text/javascript">
    function fetch(){
            var inputValue = jQuery('#keyword').val();
    
            if (inputValue.length > 1) {

                //jQuery('body').append('<div id="datafetch-overlay"></div>');

                jQuery('#datafetch').removeClass('hide');

                jQuery.ajax({
                    url: `<?php echo admin_url('admin-ajax.php'); ?>`,
                    type: 'post',
                    data: { action: 'data_fetch', keyword: jQuery('#keyword').val() },
                    success: function(data) {
                        jQuery('#datafetch').html( data );
                    }
                });

            } else {
                jQuery('#datafetch').addClass('hide');
            }
            if (inputValue.length < 2) {
                jQuery('#datafetch').empty();
            }

            setTimeout(function() { // Remove empty li tags
                jQuery('#datafetch li').filter(function() {
                    return jQuery.trim(jQuery(this).text()) === '';
                }).remove();
            }, 1000);
    }

    function clearData() {
        jQuery('#datafetch').empty().addClass('hide');
        jQuery('#keyword').val('');
    }
</script>
<?php
}
add_action('wp_ajax_data_fetch', 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch', 'data_fetch');
function data_fetch() { // Ajax function
    $args = array(
        'posts_per_page' => -1,
        's' => esc_attr($_POST['keyword']),
        'post_type' => 'page',
        'post_status' => 'publish', // Exclude private pages
    );

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post();
            if (!is_page_private(get_the_ID())) : // Exclude private pages
                ?>
                <li class="post-item">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php the_post_thumbnail('thumbnail'); ?>
                            </a>
                        </div>
                    <p><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></p>
                    <?php endif; ?>
                </li>
            <?php endif;
        endwhile;
        wp_reset_postdata();
    endif;

    die();
}
function is_page_private($post_id) {
    $post_status = get_post_status($post_id);
    return $post_status === 'private';
}
?>