<?php
/*
 Plugin Name: Widget NAVASAN
 Plugin URI: https://github.com/akbarijedi/wp-navasan/
 Description: Latest Forex Rates with navasan.tech API.
 Version: 1.0.0
 Author: NV Team and Developed by Hadi Akbarijedi (WEBSTART Team) to use API
 Author URI: https://webstart.ir
 License: MIT
 */

class WP_Navasan_Widget extends WP_Widget
{

    // Main constructor
    public function __construct()
    {
        parent::__construct(
            'wp_navasan',
            __('WP Navasan API', 'text_domain'),
            array(
                'customize_selective_refresh' => true,
            )
        );
    }

    // show the form in admin pannel
    public function form($instance)
    {

        // Set widget defaults
        $defaults = array(
            'title'    => 'نرخ ارز',
            'show_change'  => true,
            'show_date' => true,
            'select'   => ['usd', 'eur'],
            'amount_change' => [],
            'api' => 'Get from http://navasan.tech'
        );

        // Parse current settings with defaults
        extract(wp_parse_args((array) $instance, $defaults)); ?>

        <?php // Widget Title
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Widget Title', 'text_domain'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('api')); ?>"><?php _e('API Key', 'text_domain'); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('api')); ?>" name="<?php echo esc_attr($this->get_field_name('api')); ?>" value="<?php echo esc_attr($api); ?>">

            <?php // Checkbox
            ?>
        <p>
            <input id="<?php echo esc_attr($this->get_field_id('show_change')); ?>" name="<?php echo esc_attr($this->get_field_name('show_change')); ?>" type="checkbox" value="1" <?php checked('1', $show_change); ?> />
            <label for="<?php echo esc_attr($this->get_field_id('show_change')); ?>"><?php _e('Show Change', 'text_domain'); ?></label>
        </p>

        <?php // Checkbox
        ?>
        <p>
            <input id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" type="checkbox" value="1" <?php checked('1', $show_date); ?> />
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php _e('Show time', 'text_domain'); ?></label>
        </p>

        <?php // Dropdown
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Currencies', 'text_domain'); ?></label>
            <select style="height: 600px;" name="<?php echo $this->get_field_name('select') . '[]'; ?>" id="<?php echo $this->get_field_id('select'); ?>" class="widefat" multiple>
                <?php
                // Your options array
                $options = array_merge(
                    $curs2 = ["usd" => "دلار آمریکا", "eur" => "یورو", "gbp" => "پوند انگلیس", "aed_note" => "درهم امارات", "try" => "لیر ترکیه", "jpy" => "ین ژاپن", "aud" => "دلار استرالیا", "nzd" => "دلار نیوزلند", "cad" => "دلار کانادا", "sgd" => "دلار سنگاپور", "chf" => "فرانک سویس", "pkr" => "روپیه پاکستان", "azn" => "منات آذربایجان"],
                    $curs1 = ["nok" => "کرون نروژ", "sek" => "کرون سوئد", "dkk" => "کرون دانمارک", "kwd" => "دینار کویت", "omr" => "ریال عمان", "rub" => "روبل روسیه", "brl" => "رئال برزیل", "thb" => "بات تایلند", "afn" => "افغانی", "inr" => "روپیه هند", "cny" => "یوان چین", "myr" => "رینگیت مالزی", "gel" => "لاری گرجستان"],
                    $curs3 = ["usd_sherkat" => 'دلار آمریکا شرکت', "usd_shakhs" => 'دلار آمریکا شخص', "aed" => "درهم امارات", 'eur_hav' => 'حواله یورو', 'gbp_hav' => 'حواله پوند', 'hav_cad_cheque' => 'حواله دلار کانادا', 'aud_hav' => 'حواله دلار استرالیا', 'myr_hav' => 'حواله رینگیت', 'cny_hav' => 'حواله یوان', 'try_hav' => 'حواله لیر', 'jpy_hav' => 'حواله ین'],
                    $curs4 = ['btc' => 'بیت کوین', 'eth' => 'اتریوم', 'xrp' => 'ریپل', 'bch' => 'بیت کوین کش', 'ltc' => 'لایت کوین', 'eos' => 'ای او اس', 'bnb' => 'بایننس', 'usdt' => 'تتر', 'pp' => 'دلار پی پال', 'pp' => 'یورو پی پال'],
                    $curs5 = ['mex_usd_sell' => 'دلار  ص.ملی فروش', 'mex_usd_buy' => 'دلار  ص.ملی خرید', 'mex_eur_sell' => 'یورو ص.ملی فروش', 'mex_eur_buy' => 'یورو ص.ملی خرید'],
                    $curs7 = ['xau' => 'اونس طلا', 'usd_xau' => 'اونس طلا به دلار', '18ayar' => 'یک گرم طلا 18 عیار', 'sekkeh' => 'سکه طرح امامی', 'bahar' => 'سکه بهار آزادی', 'nim' => 'سکه نیم', 'rob' => 'سکه ربع', 'abshodeh' => 'مثقال طلای آبشده', 'gerami' => 'سکه گرمی'],
                    $curs6 = ['bub_sekkeh' => 'حباب سکه امامی', 'bub_bahar' => 'حباب بهار آزادی', 'bub_nim' => 'حباب سکه نیم', 'bub_rob' => 'حباب سکه ربع', 'bub_18ayar' => 'حباب گرم طلا 18', 'bub_gerami' => 'حباب سکه گرمی']
                );

                // Loop through options and add each one to the select dropdown
                foreach ($options as $key => $name) {
                    echo '<option value="' . esc_attr($key) . '" id="' . esc_attr($key) . '" ' . (in_array(esc_attr($key), $instance['select']) ? 'selected="selected"' : '') . '>' . $name . '</option>';
                } ?>
            </select>
            <span style="margin-top:4px; display: block;"><?php _e('Press and hold Ctrl to choose multiple currencies', 'text_domain'); ?></span>
        </p>
        <p>

            <hr />
            <label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Change Prices increase OR decrease in TOMAN:', 'text_domain'); ?></label>
            <?php
            $index = 0;
            foreach ($options as $key => $name) {
                if (in_array(esc_attr($key), $instance['select'])) {
                    echo '<br/><input name="' . $this->get_field_name('amount_change') . '[]' . '" value="' . $instance['amount_change'][$index] . '" id="' . $this->get_field_id('amount_change') . '">' . $name . '</input>';
                    $index++;
                }
            }
            ?>

        </p>


    <?php }

    // Update widget settings
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title']    = isset($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
        $instance['api']    = isset($new_instance['api']) ? wp_strip_all_tags($new_instance['api']) : '';
        $instance['show_change'] = isset($new_instance['show_change']) ? 1 : false;
        $instance['show_date'] = isset($new_instance['show_date']) ? 1 : false;
        $instance['select']   = isset($new_instance['select']) ? ($new_instance['select']) : [];
        $instance['amount_change'] = isset($new_instance['amount_change']) ? ($new_instance['amount_change']) : [];

        return $instance;
    }

    public function widget($args, $instance)
    {
        extract($args);

        // options array
        $options = array_merge(
            $curs2 = ["usd" => "دلار آمریکا", "eur" => "یورو", "gbp" => "پوند انگلیس", "aed_note" => "درهم امارات", "try" => "لیر ترکیه", "jpy" => "ین ژاپن", "aud" => "دلار استرالیا", "nzd" => "دلار نیوزلند", "cad" => "دلار کانادا", "sgd" => "دلار سنگاپور", "chf" => "فرانک سویس", "pkr" => "روپیه پاکستان", "azn" => "منات آذربایجان"],
            $curs1 = ["nok" => "کرون نروژ", "sek" => "کرون سوئد", "dkk" => "کرون دانمارک", "kwd" => "دینار کویت", "omr" => "ریال عمان", "rub" => "روبل روسیه", "brl" => "رئال برزیل", "thb" => "بات تایلند", "afn" => "افغانی", "inr" => "روپیه هند", "cny" => "یوان چین", "myr" => "رینگیت مالزی", "gel" => "لاری گرجستان"],
            $curs3 = ["usd_sherkat" => 'دلار آمریکا شرکت', "usd_shakhs" => 'دلار آمریکا شخص', "aed" => "درهم امارات", 'eur_hav' => 'حواله یورو', 'gbp_hav' => 'حواله پوند', 'hav_cad_cheque' => 'حواله دلار کانادا', 'aud_hav' => 'حواله دلار استرالیا', 'myr_hav' => 'حواله رینگیت', 'cny_hav' => 'حواله یوان', 'try_hav' => 'حواله لیر', 'jpy_hav' => 'حواله ین'],
            $curs4 = ['btc' => 'بیت کوین', 'eth' => 'اتریوم', 'xrp' => 'ریپل', 'bch' => 'بیت کوین کش', 'ltc' => 'لایت کوین', 'eos' => 'ای او اس', 'bnb' => 'بایننس', 'usdt' => 'تتر', 'pp' => 'دلار پی پال', 'pp' => 'یورو پی پال'],
            $curs5 = ['mex_usd_sell' => 'دلار  ص.ملی فروش', 'mex_usd_buy' => 'دلار  ص.ملی خرید', 'mex_eur_sell' => 'یورو ص.ملی فروش', 'mex_eur_buy' => 'یورو ص.ملی خرید'],
            $curs7 = ['xau' => 'اونس طلا', 'usd_xau' => 'اونس طلا به دلار', '18ayar' => 'یک گرم طلا 18 عیار', 'sekkeh' => 'سکه طرح امامی', 'bahar' => 'سکه بهار آزادی', 'nim' => 'سکه نیم', 'rob' => 'سکه ربع', 'abshodeh' => 'مثقال طلای آبشده', 'gerami' => 'سکه گرمی'],
            $curs6 = ['bub_sekkeh' => 'حباب سکه امامی', 'bub_bahar' => 'حباب بهار آزادی', 'bub_nim' => 'حباب سکه نیم', 'bub_rob' => 'حباب سکه ربع', 'bub_18ayar' => 'حباب گرم طلا 18', 'bub_gerami' => 'حباب سکه گرمی']
        );

        // Check the widget options
        $title    = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        $api = isset($instance['api']) ?  $instance['api'] : '';
        $select   = isset($instance['select']) ? $instance['select'] : [];
        $amount_change = isset($instance['amount_change']) ? $instance['amount_change'] : [];
        $show_change = !empty($instance['show_change']) ? $instance['show_change'] : false;
        $show_date = !empty($instance['show_date']) ? $instance['show_date'] : false;
        $query = '';


        //      Instance CURL Class and GET PRICE DATA WITH API
        $firstResponse = wp_remote_get('http://api.navasan.tech/latest/?api_key=' . $api);
        $response = json_decode(wp_remote_retrieve_body($firstResponse));


        // WordPress core before_widget hook (always include )
        echo $before_widget;

        // Display the widget
        echo '<div class="widget-text wp_navasan_box">';

        // Display widget title if defined
        if ($title) {
            echo $before_title . $title . $after_title;
        }
    ?>

        <div id='prlist' scoped>
            <table class="price_table">
                <thead>
                    <tr>
                        <td>نام ارز</td>
                        <td>نام کد</td>
                        <td>قیمت به تومان</td>
                        <td>تغییرات نسبت به روز گذشته</td>
                        <td>تاریخ</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 0;
                    foreach ($options as $key1 => $value1) {

                        if (in_array($key1, $select)) {
                            $color = ((int)$response->$key1->change >= 0) ? 'color:green;' : 'color:red;';
                            if ((int)$response->$key1->change > 0)  $icon = '▲';
                            elseif ((int)$response->$key1->change < 0) $icon = '▼';
                            else $icon = '';
                            echo "<tr>";
                            echo "<td>" . $options[$key1] . "</td>";
                            echo "<td>" . strtoupper($key1) . "</td>";
                            echo "<td style='text-alighn:left'>" . number_format(($response->$key1->value +  (int)$amount_change[$index]), 0) . "</td>";
                            echo "<td style='" . $color . "'>" . number_format((int)$response->$key1->change, 0) . " " . $icon . "</td>";
                            echo "<td>" . $response->$key1->date . "</td>";
                            echo "</tr>";
                            $index++;
                        }
                    }

                    ?>
                </tbody>
            </table>

            <style scoped>
                <?php if (!$show_date) { ?>#navasan_table .dat {
                    display: none !important;
                }

                <?php } ?><?php if (!$show_change) { ?>#navasan_table .chg {
                    display: none !important;
                }

                <?php } ?>
            </style>
        </div>

<?php


        echo '</div>';


        // WordPress core after_widget hook (always include )
        echo $after_widget;
    }
}

// Register the widget
function WP_Navasan_register_widget()
{
    register_widget('WP_Navasan_Widget');
}
add_action('widgets_init', 'WP_Navasan_register_widget');
