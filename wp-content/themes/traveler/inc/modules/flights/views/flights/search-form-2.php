<?php

/**

 * Created by wpbooking.

 * Developer: nasanji

 * Date: 6/26/2017

 * Version: 1.0

 */

$flight_search_fields = st()->get_option('flight_search_fields','');



$title = esc_html__('Search for flights', 'traveler');



if(!empty($title)){

    ?>

    <h3><?php echo esc_attr($title); ?></h3>

<?php } ?>

<?php

if(!empty($flight_search_fields) && is_array($flight_search_fields)){

    $flight_type = STInput::get('flight_type',false)?STInput::get('flight_type',false):'return';

    ?>

    <form action="#" method="get" class="st-flight-search-form">

        <div class="tabbable st-flight-search <?php echo esc_attr($flight_type); ?>">

            <input type="hidden" name="flight_type" value="<?php echo esc_attr($flight_type); ?>">

            <ul class="nav nav-pills nav-sm nav-no-br mb10" id="myTab">

                <li class="<?php echo ($flight_type!='one_way'?'active':'')?>"><a href="#flight-search-1" data-toggle="tab"><?php echo esc_html__('Round Trip', 'traveler')?></a>

                </li>

                <li class="one_way <?php echo ($flight_type=='one_way'?'active':'')?>"><a href="#flight-search-1" data-toggle="tab"><?php echo esc_html__('One Way', 'traveler')?></a>

                </li>

            </ul>

            <div class="tab-content">

                <div class="tab-pane fade in active" id="flight-search-1">

                    <div class="row">

                        <?php foreach($flight_search_fields as $key => $val){

                            $col = 'col-md-6';

                            if(!empty($val['layout_col'])){

                                $col = 'col-md-'.$val['layout_col'];

                            }

                            $val['field_size'] = 'no';

                            echo '<div class="'.$val['flight_field_search'].' '.$col.'">';

                            echo st_flight_load_view('search/fields/'.$val['flight_field_search'],false, array('data' => $val));

                            echo '</div>';

                        } ?>

                    </div>

                </div>

            </div>

        </div>

        <input class="btn btn-primary mt10" type="submit" value="<?php echo esc_html__('Search for Flights', 'traveler');?>" />

    </form>

<?php } ?>

