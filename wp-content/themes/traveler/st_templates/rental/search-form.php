<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Rental search form
 *
 * Created by ShineTheme
 *
 */
$st_style_search="style_2";
$object=new STRental();
$fields=$object->get_search_fields();
?>
<h3><?php st_the_language('rental_search_for_vacation_rentals')?></h3>
<form role="search" method="get" class="search main-search" action="">
    <input type="hidden" name="s" value="">
    <input type="hidden" name="post_type" value="st_rental">
    <input type="hidden" name="layout" value="<?php echo STInput::get('layout') ?>">
    <input type="hidden" name="style" value="<?php echo STInput::get('style') ?>">
    <?php echo TravelHelper::get_input_multilingual_wpml() ?>
    <div class="row">
        <?php
        if(!empty($fields))
        {
            foreach($fields as $key=>$value)
            {
                $name=$value['name'];
                $size='4';
                if($st_style_search=="style_1")
                {
                    $size=$value['layout_col'];
                }else
                {
                    if($value['layout_col2'])
                    {
                        $size=$value['layout_col2'];
                    }
                }
                ?>
                <div class="col-md-<?php echo esc_attr($size);
                ?>">
                    <?php echo st()->load_template('rental/elements/search/field_'.esc_html($name),false,array('data'=>$value)) ?>
                </div>
            <?php
            }
        }?>
    </div>
    <?php 
        $option = st()->get_option('allow_rental_advance_search');
        $fields=st()->get_option('rental_advance_search_fields');
        $st_direction = !empty($st_direction) ? $st_direction : "horizontal";
        $field_size = !empty($field_size) ? $field_size : "lg";
        if($option=='on' and !empty($fields)):?>
            <div class="search_advance">
                <div class="expand_search_box form-group form-group-<?php echo esc_attr($field_size);?>">
                    <span class="expand_search_box-more"> <i class="btn btn-primary fa fa-plus mr10"></i><?php echo __("Advanced Search",'traveler') ; ?></span>
                    <span class="expand_search_box-less"> <i class="btn btn-primary fa fa-minus mr10"></i><?php echo __("Advanced Search",'traveler') ; ?></span>
                </div>
                <div class="view_more_content_box row">
                    <?php
                        if(!empty($fields))
                        { 
                            foreach($fields as $key=>$value)
                            {
                                if(!isset($value['name'])) continue;
                                $name=$value['name'];
                                $size='4';
                                if(!empty($st_style_search) and  $st_style_search=="style_1")
                                {
                                    $size=$value['layout_col'];
                                }else
                                {
                                    if($value['layout_col2'])
                                    {
                                        $size=$value['layout_col2'];
                                    }
                                }

                                if(!empty($st_direction) and  $st_direction!='horizontal'){
                                    $size='x';
                                }

                                $size_class = " col-md-".$size." col-lg-".$size. " col-sm-12 col-xs-12 " ;
                                ?>
                                <div class="<?php echo esc_attr($size_class); ?>">
                                    <?php echo st()->load_template('rental/elements/search/field_'.esc_html($name),false,array('data'=>$value,'field_size'=>$field_size,'location_name'=>'location_name')) ?>
                                </div>
                            <?php 
                            }
                        }?>
                </div>
            </div>
        <?php endif ;?>
    <button class="btn btn-primary btn-lg" type="submit"><?php st_the_language('search_for_rental')?></button>
</form>
