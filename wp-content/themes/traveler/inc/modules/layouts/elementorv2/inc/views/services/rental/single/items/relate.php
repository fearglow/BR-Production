<?php
$search_tax_advance = st()->get_option( 'attribute_search_form_rental', 'rental_types' );
$terms_posts = wp_get_post_terms(get_the_ID(),$search_tax_advance);
$arr_id_term_post = array();
if(is_array($terms_posts) && !empty($terms_posts)){
    foreach($terms_posts as $term_post){
        $arr_id_term_post[] = $term_post->term_id;
    }
}

$args = [
    'posts_per_page' => 4,
    'post_type' => 'st_rental',
    'post_author' => get_post_field('post_author', get_the_ID()),
    'post__not_in' => [$post_id],
    'orderby' => 'rand',
    'tax_query' => array(
        array(
            'taxonomy' => $search_tax_advance,
            'terms' => $arr_id_term_post,
            'field' => 'term_id',
            'operator' => 'IN'
        )
    ),
];
global $post;
$old_post = $post;
$query = new WP_Query($args);

if($query->have_posts()) {
    wp_enqueue_script('owlcarousel');
    wp_enqueue_style('owlcarousel');
    $responsive = [
        '992' => [
            'items' => 4
        ],
        '768' => [
            'items' => 2
        ],
        '0' => [
            'items' => 1
        ]
    ];
?>
<div class="relate-rooms">
    <div class="st-hr"></div>
    <h2 class="st-heading-section"><?php echo esc_html__('Explore other options', 'traveler'); ?></h2>
    <div class="row inner service-list-wrapper service-tour">
        <?php
            if($query->found_posts > 3) {
                echo '<div class="owl-carousel st-owl-slider" data-items="3" data-margin="24" data-responsive="'. esc_attr(json_encode($responsive)) .'">';
                while ($query->have_posts()) {
                    $query->the_post();
                    echo '<div class="item-slide">';
                        echo stt_elementorv2()->loadView('services/rental/loop/grid');
                    echo '</div>';
                }
                wp_reset_postdata();
            echo '</div>';
            } else {
				while ($query->have_posts()) {
					$query->the_post();
					echo '<div class="col-lg-3 col-md-6 col-12 item-service">';
						echo stt_elementorv2()->loadView('services/rental/loop/grid');
					echo '</div>';
				}
            }

        ?>
    </div>
</div>
<?php
}