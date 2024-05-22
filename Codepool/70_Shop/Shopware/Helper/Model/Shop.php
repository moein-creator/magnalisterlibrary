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
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

class ML_Shopware_Helper_Model_Shop {

    /**
     * @return \Shopware\Models\Shop\Locale|null
     * @throws Exception
     */
    public function getLocalOfBackendUser() {
        try {
            // since Shopware 5.7+
            $locale = Shopware()->Locale()->toString();
        } catch (\Exception $e) {
            $locale = Shopware()->Container()->get('shopware.locale')->toString();
        }

        $localeModel = Shopware()->Models()->getRepository('\Shopware\Models\Shop\Locale')
            ->findOneBy(['locale' => $locale]);

        return $localeModel;
    }

    public function getI18nSnippets($namespace) {
        $queryBuilder = Shopware()->Models()->createQueryBuilder();
        $result = $queryBuilder
            ->select('snippet.name,snippet.value')
            ->from('Shopware\Models\Snippet\Snippet', 'snippet')
            ->where("snippet.namespace = '".$namespace."' And snippet.localeId = ".$this->getLocalOfBackendUser()->getId())
            ->getQuery()
            ->getArrayResult();

        $valueDescription = array();
        foreach ($result as $row) {
            $valueDescription[$row['name']] = $row['value'];
        }

        return $valueDescription;
    }

    /**
     * Translate given values of like payment or order status to user language
     *
     * @param $namespace
     * @param $data [id, name, description]
     * @return array
     */
    public function getTranslatedValues($namespace, $data) {
        $translations = $this->getI18nSnippets($namespace);

        $result = array();

        foreach ($data as $row) {
            if (!array_key_exists('name', $row)) {
                throw new MagnaException('Wrong implementation of this function::'.__CLASS__.'::'.__FUNCTION__, 1675156915);
            }
            $i18nIndex = $row['name'];
            $result[$row['id']] = isset($translations[$i18nIndex]) ? $translations[$i18nIndex] : $row['description'];
        }

        return $result;
    }
}

