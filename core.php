<?php
    /*
    Plugin Name: Prodotti Woocommerce per chiave di ricerca 
    Description: Aggiunge shortcode che mostra i prodotti Woocommerce per chiave di ricerca e SKU (Codice).
    Author: Domenico Esposito
    Version: 1.0
    Author URI: http://domenicoesposito.it
    */

    defined('ABSPATH') or die('No script kiddies please!');
    define('SPB_DIR', plugin_dir_path(__FILE__));

    class ShowProductsSearch
    {
        public static $columns = 4;
        private static $debug = false;

        public function __construct()
        {
            add_shortcode('ShowProductsSearch', array($this, 'shortcodeBase'));
        }

        public function shortcodeBase($atts)
        {
            add_filter('loop_shop_columns', array('ShowProductsSearch', 'loop_columns'), 999);
            $atts = shortcode_atts(array(
                'columns' => self::$columns,
                'nmb_items' => self::$columns,
                'search' => '',
                'include_sku' => '',
                'exclude_sku' => '',
            ), $atts, 'ShowProductsSearch');

            return $this->shortcodeBase_func($atts);
        }

        private function get_IncludeID($include_sku, $ID_post)
        {

            $this->logs_get_IncludeID($include_sku, $ID_post);

            $query = array(
                'post__not_in' => $ID_post,
                'fields' => 'ids',
                'post_type' => 'product',
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
                'meta_query' => array(
                    array(
                        'key' => '_sku',
                        'value' => $include_sku,
                        'compare' => 'IN',
                    ),
                ),
            );

            return (new WP_Query($query))->posts;
        }

        private function get_SearchID($search, $nmb_items, $exlude_sku)
        {

            $this->logs_get_SearchID($search, $nmb_items, $exlude_sku);

            $query = array(
                's' => $search,
                'fields' => 'ids',
                'post_type' => 'product',
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
                'posts_per_page' => $nmb_items,
                'meta_query' => array(
                    array(
                        'key' => '_sku',
                        'value' => $exlude_sku,
                        'compare' => 'NOT IN',
                    ),
                ),
            );

            return (new WP_Query($query))->posts;
        }

        private function get_products($ID_posts){

            $query = array(
                'post__in' => $ID_posts,
                'posts_per_page' => -1,
                'post_type' => 'product',
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false
            );

            return (new WP_Query($query));
        }

        private function shortcodeBase_func($atts)
        {
            
            self::$columns = $columns = $atts['columns'];

            $exclude_sku = $atts['exclude_sku'];
            $exclude_sku = ($exclude_sku != false) ? explode(", ", $exclude_sku) : array();

            $include_sku = $atts['include_sku'];

            $search = $atts['search'];
            if($search == '')
                return _e("Chiave di ricerca mancante.", 'ShowProductsSearch');

            $nmb_items = $atts['nmb_items'];
            $ID_posts = $this->get_SearchID($search, $nmb_items, $exclude_sku);

            if ($include_sku != false) {
                $ID_include = $this->get_IncludeID(explode(", ", $include_sku), $ID_posts);
                $ID_posts = array_merge($ID_posts, $ID_include);
            }

            $products_complete = $this->get_products($ID_posts);

            return $this->templateProducts($products_complete);
        }

        private function templateProducts($posts)
        {
            if(count($posts) == 0)
                return _e("Nessun prodotto trovato.", 'ShowProductsSearch');

            ob_start();
            ?>

                <div class="woocommerce">
                    <ul class="products columns-<? echo self::$columns; ?>">

                        <?
                            while ($posts->have_posts()) {
                                $posts->the_post();
                                wc_get_template('content-product.php');
                            }
                        ?>

                    </ul>
                </div>

            <?
            $content = ob_get_contents();
            ob_end_clean();

            return $content;
        }

        public static function loop_columns()
        {

            return self::$columns;
        }

        private function logs_get_IncludeID($include_sku, $ID_post)
        {

            if (self::$debug) {
                echo "Include Array";
                echo "<pre>";
                print_r($include_sku);
                echo "</pre>";
            }
        }

        private function logs_get_SearchID($search, $nmb_items, $exlude_sku)
        {

            if (self::$debug) {
                echo "Exclude Array";
                echo "<pre>";
                print_r($exlude_sku);
                echo "</pre>";
            }
        }

    }

    $ShowProductsSearch = new ShowProductsSearch();

    remove_filter('loop_shop_columns', array('ShowProductsSearch', 'loop_columns'), 1000);
?>