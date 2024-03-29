<div class="sidebar-item st-icheck review-score">
    <div class="item-title">
        <label><?php echo esc_html($title); ?></label>
        <i class="fa fa-angle-up" aria-hidden="true"></i>
    </div>
    <div class="item-content">
        <ul>
            <?php
            for ($i = 5; $i > 0; $i--){
                ?>
                <li class="st-icheck-item">
                    <label>
                        <?php
                        for ($j = 1; $j <= 5; $j++){
                            if($j <= $i) {
                                echo '<span class="real-star"><i class="fa fa-star"></i></span>';
                            }else{
                                echo '<span class="fake-star"><i class="fa fa-star"></i></span>';
                            }
                        }
                        ?>
                        <input type="checkbox" name="review_score" value="<?php echo esc_attr($i); ?>" class="filter-item" data-type="star_rate"/>
                        <span class="checkmark fcheckbox"></span>
                    </label>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>