<?php
/**
 * Created by PhpStorm.
 * User: HanhDo
 * Date: 2/18/2019
 * Time: 10:30 AM
 */
$bg = '';
if(!empty($background)){
    $background = wp_get_attachment_image_url($background, 'full');
    $class = Assets::build_css('background: url(' . esc_url($background) . ')');
}
?>
<div class="st-offer-new item has-matchHeight">
    <?php
    if($style == 'featured'){
        if(!empty($featured_text))
            echo '<div class="featured-text btn btn-primary">'. esc_attr($featured_text) .'</div>';
    }else{
        if(!empty($icon)){
            $icon = wp_get_attachment_image_url($icon, 'full', true);
            echo '<div class="featured-icon"><img src="'. esc_url($icon) .'"  alt="'.esc_attr($title).'"/></div>';
        }
    }
    if(!empty($title)){
        echo '<h2 class="item-title">'. esc_attr($title) .'</h2>';
    }

    if(!empty($sub_title)){
        echo '<p class="item-sub-title">'. esc_html($sub_title) .'</p>';
    }

    $link = vc_build_link($link);
    if(!empty($link)){
        $target = '';
        if($link['target'] == '_blank')
            $target = 'target="_blank"';
        echo '<a href="'. esc_url($link['url']) .'" class="btn btn-default" '. esc_attr($target) .'>'. esc_attr($link['title']) .'</a>';
    }

    ?>
    <div class="img-cover <?php echo esc_attr($class) ?>"></div>
</div>
