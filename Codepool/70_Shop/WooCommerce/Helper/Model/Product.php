<?php
/*
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                          m a g n a l i s t e r
 *                                      boost your Online-Shop
 *
 * -----------------------------------------------------------------------------
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

class ML_WooCommerce_Helper_Model_Product {

    protected $productVariationCache = array();

    /**
     * The structure of the necessary data for displaying in prepare items, as well as for filters.
     *
     * @return ML_Database_Model_Query_Select
     */
    public function getProductSelectQuery() {
        global $wpdb;

        $query = MLDatabase::factorySelectClass()
            ->select(array(
                'DISTINCT p.ID',
                'p.post_title AS name',
                /*
                 * If in future we want to sort product list by ean or mpn we can use these code
                 *  "(SELECT t1.name FROM $wpdb->term_relationships tr1
                 INNER JOIN $wpdb->term_taxonomy tt1 ON tt1.term_taxonomy_id = tr1.term_taxonomy_id
                 INNER JOIN $wpdb->terms t1 ON tt1.term_id = t1.term_id
                 WHERE tr1.object_id = p.ID AND tt1.taxonomy = 'pa_mpn' LIMIT 1) AS mpn",
                          "(SELECT t1.name FROM $wpdb->term_relationships tr1
                 INNER JOIN $wpdb->term_taxonomy tt1 ON tt1.term_taxonomy_id = tr1.term_taxonomy_id
                 INNER JOIN $wpdb->terms t1 ON tt1.term_id = t1.term_id
                 WHERE tr1.object_id = p.ID AND tt1.taxonomy = 'pa_ean' LIMIT 1) AS ean",*/

            ))
            ->from($wpdb->posts, 'p')
            ->join("$wpdb->postmeta details2 ON details2.post_id = p.ID AND meta_key='_sku'", ML_Database_Model_Query_Select::JOIN_TYPE_LEFT);

        $query = $query->where("p.post_type = 'product' and post_status!='trash'");

        return $query;
    }


    /**
     * Get article by id
     *
     * @param string $ordernumber
     *
     * @return WC_Product
     */
    public function getTranslatedInfo($ordernumber) {
        /** @var WC_Product $product */
        $product = new WC_Product($ordernumber);

        return $product;
    }

    /**
     * use sConfigurator::getDefaultPrices
     *
     * @param type $detailId
     *
     * @return type
     */
    public function getDefaultPrices($detailId, $sUserGroup = null) {
        $product = wc_get_product($detailId);

        return $product->get_price();
    }

    /**
     * return all variants related to this product id
     *  it doesn't matter if there are disabled or have no stock...
     *
     * @param int $iProductId
     *
     * @return array
     */
    public function getProductDetails($iProductId) {
        $product = new WC_Product_Variable($iProductId);

        /*
         * !!! IMPORTANT !!! - Cant use "get_available_variations" Function - because there are filters
         *      - "Hide out of stock variations if 'Hide out of stock items from the catalog' is checked."
         *      - "Filter 'woocommerce_hide_invisible_variations' to optionally hide invisible variations (disabled variations and variations with empty price)."
         */
        //$productVariations = $product->get_available_variations();
        $productVariations = $this->getAvailableVariationsFromWooCommerceProductVariable($product);

        if (count($productVariations) > 0) {
            foreach ($productVariations as $key => $variation) {
                if (empty($variation['attributes'])) {
                    unset($productVariations[$key]);
                }
            }
        }
        return $productVariations;
    }

    /**
     * Replaces WC_Product_Variable -> get_available_variations()
     *
     * !!! IMPORTANT !!! - Cant use "get_available_variations" Function - because there are filters
     *      - "Hide out of stock variations if 'Hide out of stock items from the catalog' is checked."
     *      - "Filter 'woocommerce_hide_invisible_variations' to optionally hide invisible variations (disabled variations and variations with empty price)."
     *
     * @param $product WC_Product_Variable
     * @return array
     */
    public function getAvailableVariationsFromWooCommerceProductVariable($product) {
        if ($product->get_id() > 0
            && array_key_exists($product->get_id(), $this->productVariationCache)
            && !empty($this->productVariationCache[$product->get_id()])
        ) {
            return $this->productVariationCache[$product->get_id()];
        }

        $available_variations = array();

        foreach ($product->get_children() as $child_id) {
            $variation = wc_get_product($child_id);

            // Hide out of stock variations if 'Hide out of stock items from the catalog' is checked.
            if (!$variation || !$variation->exists()) {
                continue;
            }

            $available_variations[] = $product->get_available_variation($variation);
        }
        $productVariations = array_values(array_filter($available_variations));

        // Set variation to runtime cache
        $this->productVariationCache[$product->get_id()] = $productVariations;

        return $productVariations;
    }

    /**
     * Gets variation properties
     * [
     *   {
     *       pa_color: {
     *           0: "black",
     *           1: "blue"
     *       },
     *       pa_size: {
     *           0: "xl",
     *           1: "m"
     *       }
     *   }
     * ]
     *
     * @param $iArticleId
     *
     * @return array
     */
    public function getProperties($iArticleId) {
        /** @var WC_Product_Variable $product */
        $product = new WC_Product_Variable($iArticleId);
        $aProperties = $product->get_variation_attributes();

        return $aProperties;
    }

    /**
     * Returns featured product image by product id
     *
     * @param $productId
     *
     * @return string
     */
    public function getFeaturedWCProductImage($productId) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($productId), 'single-post-thumbnail');

        if (!$image) {

            return '';
        }

        return $image[0];
    }

    /**
     * Return array of product images by product id
     *
     * @param $productId
     *
     * @return array
     */
    public function getWCProductImages($productId) {
        /** @var WC_Product $product */
        $product = new WC_product($productId);
        $attachmentIds = $product->get_gallery_image_ids();

        if (empty($attachmentIds)) {

            return array();
        }

        $imgUrls = array();
        foreach ($attachmentIds as $attachmentId) {
            $imgUrls[] = wp_get_attachment_url($attachmentId);
        }

        return $imgUrls;
    }
}
