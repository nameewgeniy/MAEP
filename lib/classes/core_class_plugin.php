<?php
    class MaepCore extends MaepProducts
    {


        // подкатегории категории по ID
        public function ChildCategoriesByID($id)
        {
            return $this::ConvertXMLtoObject(parent::setCatID($id)->GetCategoryInfo());
        }

        // популярные запросы по id категории
        public function PopularSearches($id)
        {
            return $this::ConvertXMLtoObject(parent::setCatID($id)->FindPopularSearches());
        }

        // 20 кратких описаний продуктов категории по ID
        public function ListItemsCategoryByID($id, $number)
        {
            return $this::ConvertXMLtoObject(parent::setCatID($id)->setGlobalID(getGlobalID())->setNumberPage($number)->findItemsByCategory());
        }

        // Полное описание 20 или меньше товаров по ID продуктов
        public function ListProductsByIds($ids_array, $include_selector = 'Description,ItemSpecifics,ShippingCosts,Details')
        {
            if (is_array($ids_array))
                $ids_string = implode(',', $ids_array);// тут подумать над ограничением количества продуктов
            if (is_string($ids_string))
                return $this::ConvertXMLtoObject(parent::setItemID($ids_string)->setIncludeSelector($include_selector)->GetMultipleItems());
            else
                return false;
        }

        // Подробный список всех подкатегорний по ID категории
        public function ListAllCategoriesByParentID($id)
        {
            if (is_int($id))
                return  $this::ConvertXMLtoObject(parent::setCatID($id)->GetCategories());
            else
                return false;
        }

        // Поиск по кейворду
        public function SearchByKeywords($keywords, $count = 20)
        {
            if (is_string($keywords))
                return  $this::ConvertXMLtoObject(parent::setGlobalID(getGlobalID())->setKeywords($keywords)->setCount($count)->findItemsByKeywords());
            else
                return false;
        }

        // Поиск по кейворду в категории
        public function SearchByKeywordsInCat($id_cat,$keywords, $perpage)
        {
            $end_keywords = '';
            $keywords = explode(' ', $keywords);
            if (is_array($keywords))
            {

              foreach($keywords as $key)
                    $end_keywords .= '+'.trim($key);
              $keywords = substr_replace($end_keywords,'',0,1);
            }
            if (is_string($keywords))
                return  $this::ConvertXMLtoObject(parent::setGlobalID(getGlobalID())->setKeywords($keywords)->setCatID($id_cat)->setNumberPage($perpage)->findCompletedItems());
            else
                return false;
        }

        // Конвертируем XML в объект
        private static function ConvertXMLtoObject($string_xml)
        {
            try
            {
                return new SimpleXMLElement($string_xml);
            }
            catch(Exception $e)
            {
                throw new Exception('Error convert xml to object');
                error_log($e->getMessage());
                return false;
            }
        }

        // Конвертируем JSON в объект
        private static function ConvertJSONtoObject($string_json)
        {
            try
            {
                return json_decode($string_json);
            }
            catch(Exception $e)
            {
                throw new Exception('Error convert json to object');
                error_log($e->getMessage());
                return false;
            }
        }

        /**
         * Добавляем опции
         * @param array
         * @return boolean
         */
        public function SetOption($options)
        {
            if(is_array($options))
            {
                foreach ($options as $option => $value) {
                    update_option( $option, $value );
                }
                $mes = true;
            }
            else $mes = false;
            return $mes;
        }

        // ограничение выводимых слов
        public static function MoreContent($content = '', $length = 55)
        {
            if($content) {
                $words = explode(' ', $content, $length + 1);
                if(count($words) > $length) {
                    array_pop($words);
                    array_push($words, '...');
                    $content = implode(' ', $words);
                }
                $content = $content;
            }
            return $content;
        }

        public static function update_track_id($links, $old_id, $new_id)
        {
            global $wpdb;
            $table = $wpdb->prefix . "maep_products_info";
            if (is_array($links))
            {
                foreach ($links as $link) {
                    $n_link = str_replace($old_id, $new_id, $link->link);
                    $new_link .= ",('" . $link->id . "','". $n_link . "')";
                }
                $new_link = substr_replace($new_link, '', 0, 1);
                if($result = $wpdb->query("INSERT INTO {$table} (`id`,`link`) VALUES {$new_link} ON DUPLICATE KEY UPDATE `link` = VALUES(`link`)"))
                    return true;
                else
                    return false;
            }
        }

        public static function ReplaceURL($html, $url)
        {
            if ($html != '')
            {
                $pattern = '~(href\s*=\s*(?:"|\')(.*?)(?:"|\'))~';
                $html = preg_replace( $pattern, 'href="' . $url . '" target="_blank"', $html );
            }

            return $html;
        }





    }

?>