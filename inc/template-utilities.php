<?php

/* Set the C var in the rows from args */
function csomaster_rows_set_c($args) {

    $c = !empty( $args['c'] ) ? (int) $args['c'] : 0;

    return $c;
}

function placeholder_img($width = 1200, $height = 900) {
    echo 'https://picsum.photos/'.$width.'/'.$height;
}

/**
 * Add functions to handle ACF stuff
 */

function get_acf_value($field, $type = 'sub', $postId = null) {
    if($type === 'sub') {
        return !empty(get_sub_field($field, $postId)) ? get_sub_field($field, $postId) : '';
    } else {
        return !empty(get_field($field, $postId)) ? get_field($field, $postId) : '';
    }

}
function get_acf_image($field, $size = 'full', $type = 'sub', $postId = null, $classes = null) {
    if($type === 'sub') {
	    return get_sub_field($field, $postId) ? wp_get_attachment_image(get_sub_field($field, $postId), $size, 0, array('title'=> '')) : ''; 
    } else {
	    return get_field($field, $postId) ? wp_get_attachment_image(get_field($field, $postId), $size, 0, array('title'=> '', 'class'=> $classes)) : ''; 
    }
}

/* Youtube ID */
function get_youtube_id($url) {

    $url_components = parse_url($url);
    parse_str($url_components['query'], $params);

    return $params['v'];
}

function get_vimeo_id($url) {
    $exploded = explode('?', $url);
    $exploded = explode('/', $exploded[0]);

    $length = count($exploded);

    if($length !== 4) return '';

    return $exploded[$length - 1];
}

/* Args decifer */
function get_args_value($args, $prop) {
    return !empty( $args[ $prop] ) ? $args[ $prop ] : '';
}


/* Handle video URL */
function do_video_field($url, $type) {
    if(empty($url)) return;

    if($type === 'url') {
        return "<video class='video' autoplay muted loop playsinline><source src='$url' type='video/mp4'></video>";
    }
    elseif($type === 'youtube') {
        $id = get_youtube_id($url);
        return "<iframe class='video youtube' id='video' width='100%' height='600' src='https://www.youtube.com/embed/$id?rel=0&modestbranding=1&controls=0&color=009999' title='YouTube video player' frameborder='0' allow='autoplay; accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";

    } elseif($type === 'vimeo') { //347119375 // 107178137

        $id = get_vimeo_id($url);

        return "<iframe class='video vimeo' id='video' width='100%' height='600' src='https://player.vimeo.com/video/$id?title=0&portrait=0&byline=0&color=009999' title='Vimeo video player' frameborder='0' allow='autoplay; clipboard-write; encrypted-media; picture-in-picture' allowfullscreen></iframe>";
    }
    else {
        return $url;
    }
}

/* Helper for wrapping stuff */
function conditionally_output_field($value, $openTag, $closeTag) {
    if(empty($value)) return;

    return $openTag.$value.$closeTag;
}

/* Helpder for dealing with a link CTA */
function do_a_cta($args) {
    if(empty($args['link']) || empty($args['text'])) return false;
    if(empty($args['classes'])) $args['classes'] = 'btn-primary';

    $link = $args['link'];
    $text = $args['text'];
    $classes = $args['classes'];

    $alignment = !empty($args['align']) ? ' align-'.$args['align'] : '';

    $classes .= $alignment;

    if(empty($link) || empty($text)) return false;

    return '<a href="'.$link.'" class="btn '.$classes.'">'.$text.'</a>';
}

function do_gallery_image_logic($image_number, $gallery_count) {
    $grid_cols = "xl-4 lg-5 md-12";
    $grid_start = "xl-1 lg-1 md-1";
    $grid_row_span = '';
    $grid_row = '';


    switch($image_number):
        case 1:
            $grid_cols = "xl-5 lg-5 md-6 sm-6";
            $grid_start = "auto";
            $grid_row = "xl-1 lg-1 md-1 sm-1";
            $grid_row_span = "xl-2 lg-2 md-2 sm-2";
            break;
        case 2:
            $grid_cols = "xl-3 lg-3 md-6 sm-6";
            $grid_start = "auto";
            $grid_row = "auto";
            break;
        case 5:
            $grid_cols = "xl-3 lg-3 md-8 sm-8";
            $grid_start = "auto";
            $grid_row = "auto";
            break;
        case 3:
            $grid_cols = "xl-4 lg-4 md-6 sm-6";
            $grid_start = "auto";
            $grid_row = "auto";
            break;
        case 4:
            $grid_cols = "xl-4 lg-4 md-4 sm-4";
            $grid_start = "auto";
            $grid_row = "auto";
            break;

        case 6:
            $grid_cols = "xl-3 lg-3 md-6 sm-6";
            $grid_start = "auto";
            $grid_row = "";
            break;
        case 7:
            $grid_cols = "xl-4 lg-4 md-6 sm-6";
            $grid_start = "auto";
            $grid_row = "";
            break;
        case 8:
            $grid_cols = "xl-5 lg-5 md-6 sm-6";
            $grid_start = "auto";
            $grid_row = "xl-3 lg-3 md-4 sm-4";
            $grid_row_span = "xl-2 lg-2 md-2 sm-2";
            break;

        case 9:
            $grid_cols = "xl-4 lg-4 md-8 sm-8";
            $grid_start = "auto";
            $grid_row = "";
            break;
        case 10:
            $grid_cols = "xl-3 lg-3 md-4 sm-4";
            $grid_start = "auto";
            $grid_row = "";
            break;
    endswitch;

    return array(
        'grid_col' => $grid_cols,
        'grid_start' => $grid_start,
        'grid_row' => $grid_row,
        'grid_row_span' => $grid_row_span
    );
}



/*=============================================
                BREADCRUMBS
=============================================*/
//  to include in functions.php
function the_breadcrumb()
{
    $showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = '<span class="spacer">/</span>'; // delimiter between crumbs
    $home = 'Home'; // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show

    $before = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span class="current" itemprop="name">'; // tag before the current crumb
    $after = '</span></li>'; // tag after the current crumb

    $liStart = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    $liEnd = '</li>';

    function position($n) {
        return '<meta itemprop="position" content="'.$n.'" />';
    }


    global $post;
    $homeLink = get_bloginfo('url');
    if (is_home() || is_front_page()) {
        if ($showOnHome == 1) {
            echo '<ol class="breadcrumbs-list" id="crumbs" itemscope itemtype="https://schema.org/BreadcrumbList">'.$liStart.'<a itemprop="item" href="' . $homeLink . '" class="current"><span itemprop="name">' . $home . '</span></a>'.position(1).$liEnd.'</ol>';
        }
    } else {
        echo '<ol class="breadcrumbs-list" id="crumbs" itemscope itemtype="https://schema.org/BreadcrumbList">'.$liStart.'<a itemprop="item" href="' . $homeLink . '">' . $home . '</a>'.position(1) . $delimiter . $liEnd.' ';
        
        if (is_category()) {
            $thisCat = get_category(get_query_var('cat'), false);
            $posN = 2;
            if ($thisCat->parent != 0) {
                $posN = 3;
                echo get_category_parents($thisCat->parent, true, ' ' . $delimiter . ' ');
            }
            echo $before . single_cat_title('', false) . '' . position($posN). $after;

        } elseif (is_search()) {
            echo $before . 'Search results for "' . get_search_query() . '"' . position(2). $after;

            /* This is not used */
        } elseif (is_day()) {
            echo $liStart.'<a itemprop="item" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter . $liEnd. ' ';
            echo $liStart.'<a itemprop="item" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $delimiter . $liEnd. ' ';
            echo $before . get_the_time('d') .position(2). $after;

            /* This is not used */
        } elseif (is_month()) {
            echo $liStart.'<a itemprop="item" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter .position(2). $liEnd. ' ';
            echo $before . get_the_time('F') . position(3). $after;

            /* This is not used */
        } elseif (is_year()) {
            echo $before . get_the_time('Y') . position(2). $after;

        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo $liStart.'<a itemprop="item" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>'.$liEnd;
                
                if ($showCurrent == 1) {
                    echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                }
            } else {
                $cat = get_the_category();
                $cat = $cat[0];
                $cats = get_category_parents($cat, true, ' ' . $delimiter . ' ');

                $cats = preg_replace("#^\<a href#", "<a itemprop='item' href", $cats);

                if ($showCurrent == 0) {
                    $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
                }
                echo $liStart. $cats .position(2).$liEnd;
                if ($showCurrent == 1) {
                    echo $before . get_the_title() . position(3) .$after;
                }
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;

        } elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            //echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
            echo $liStart.'<a itemprop="item" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>'.position(2).$liEnd;
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif (is_page() && !$post->post_parent) {
            if ($showCurrent == 1) {
                echo $before . get_the_title() . position(2).$after;
            }
        } elseif (is_page() && $post->post_parent) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a itemprop="item" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }

            $breadcrumbs = array_reverse($breadcrumbs);

            for ($i = 0; $i < count($breadcrumbs); $i++) {
                //echo $liStart.$breadcrumbs[$i].position($i + 2).$liEnd;

                if ($i != count($breadcrumbs)-1) {
                    echo $liStart.$breadcrumbs[$i].position($i + 2). $delimiter . $liEnd;
                    //echo ' ' . $delimiter . ' ';
                } else {
                    echo $liStart.$breadcrumbs[$i].position($i + 2).$liEnd;
                }
            }
            if ($showCurrent == 1) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() .position($i + 2). $after;
            }
        } elseif (is_tag()) {
            echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . 'Articles posted by ' . $userdata->display_name . $after;
        } elseif (is_404()) {
            echo $before . 'Error 404' . $after;
        }
        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ' (';
            }
            echo __('Page') . ' ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                echo ')';
            }
        }
        echo '</ol>';
    }
} // end the_breadcrumb()