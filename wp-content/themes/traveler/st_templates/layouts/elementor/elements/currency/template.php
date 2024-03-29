<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-11-2018
 * Time: 1:35 PM
 * Since: 1.0.0
 * Updated: 1.0.0
 */
?>
<?php
if (function_exists('icl_get_languages')) {
    $langs = icl_get_languages('skip_missing=0');
} else {
    $langs = [];
}
if (!empty($langs)) {
    ?>
    <div class="st-languages form-group">
        <label class="d-block f14 c-grey font-normal"><?php echo esc_html__('Languages', 'traveler'); ?></label>
        <select name="language" class="form-select f14 select2-languages">
            <?php
            foreach ($langs as $key => $value) {
                ?>
                <option value="<?php echo esc_attr($value['native_name']) ?>" <?php if ($value['active'] == 1) echo 'selected' ?>
                        data-target="<?php echo esc_url($value['url']) ?>"><?php echo esc_html($value['native_name']) ?></option>
                <?php
            }
            ?>
        </select>
    </div>
    <?php
}
?>
<?php
$currency = TravelHelper::get_currency();
$current_currency = TravelHelper::get_current_currency();
?>
<div class="st-currencies form-group <?php if ($langs) echo 'mt30'; ?> <?php echo $layout_style?>">
    <label class="d-block f14 c-grey font-normal"><?php echo esc_html__('Currencies', 'traveler'); ?></label>
    <select name="currency" class="form-select f14 select2-currencies">
        <?php
        if (!empty($currency)) {
            foreach ($currency as $key => $value) {
                ?>
                <option <?php selected($value['name'], $current_currency['name']) ?>
                    value="<?php echo esc_attr($value['name']); ?>"
                    data-target="<?php echo esc_url(add_query_arg('currency', $value['name'])) ?>">
                    <?php echo esc_html($value['name']); ?>
                </option>
            <?php
            }
        }
        ?>
    </select>
</div>
