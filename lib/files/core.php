<?php
    function PublishProducts($id, $name_cat, $number = 1, $keywords = '')
    {
        $ebay = new MaepCore();
        try
        {
            if (!empty($id))
            {
                if ($keywords != '')
                    $data_products = $ebay->SearchByKeywordsInCat($id,$keywords,$number);
                else
                    $data_products = $ebay->ListItemsCategoryByID($id, $number);

                if ($data_products)
                {
                    foreach ($data_products->searchResult->item as $product)
                        $ids[] = (string)$product->itemId;
                    if (is_array($ids))
                    {
                        $items = $ebay->ListProductsByIds($ids);
                        if ($items)
                           $result = InsertDB($items, $name_cat);
                        return $result;
                    }
                }
                else
                    return false;
            }
            else
                return false;
        }
        catch(Exception $e)
        {
            error_log($e->getMessage());
            return false;
        }

        unset($ebay);
    }

    function InsertDB($items, $name_cat)
    {
        global $wpdb;
        $table = $wpdb->prefix . "maep_products_info";
        $id_term = term_exists($name_cat);

        if (!$id_term)
        {
            $id_term = wp_insert_term( $name_cat, 'catproducts');
            $id_term = $id_term['term_id'];
        }

        foreach ($items->Item as $item)
        {
            $check = $wpdb->query("SELECT id_product FROM {$table} WHERE `id_product` = '$item->ItemID'");

            if (!$check)
            {
                // вставка поста
                $product = array(
                    'post_content' =>/*MaepCore::ReplaceURL(*/$item->Description/*,$item->ViewItemURLForNaturalSearch)*/,
                    'post_status' => 'publish',
                    'post_title' => $item->Title,
                    'post_type' => 'maep_products',
                    'post_author' => $user_ID
                );
                $id_post = wp_insert_post( $product );
                $out[] = $id_post;
                wp_set_object_terms( $id_post, intval($id_term), 'catproducts' );
                // вставка в базу дополнительного описания
                if ($id_post)
                {

                    $result =  $wpdb->insert($table, array(
                            'id_product' => $item->ItemID,
                            'id_post' => (string)$id_post,
                            'images' => $item->PictureURL,
                            'link' => $item->ViewItemURLForNaturalSearch,
                            'list' => json_encode($item->ItemSpecifics),
                            'price' => $item->CurrentPrice,
                            'methods' => serialize((array)$item->PaymentMethods),
                            'listing_type' => $item->ListingType,
                            'seller' => $item->Seller->PositiveFeedbackPercent,
                            'status' => $item->ListingStatus,
                            'type_cost' => $item->CurrentPrice['currencyID'],
                        ),
                        array('%s','%s','%s','%s','%s','%s','%s','%s','%s')
                    );
                    if (!$result)
                        wp_delete_post($id_post, false);

                }
                else
                    return false;
            }
        }

    }

    function update_track_id($links, $old_id, $new_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . "maep_products_info";
        if (is_array($links))
        {
            foreach ($links as $link) {
                $n_link = str_replace($old_id, $new_id, $link->link);
                $new_link = ",('" . $link->id . "','". $n_link . "')";
            }
            $new_link = substr_replace($new_link, '', 0, 1);
            if($result = $wpdb->query("INSERT INTO {$table} (`id`,`link`) VALUES {$new_link} ON DUPLICATE KEY UPDATE `link` = VALUES(`link`)"))
                return true;
            else
                return false;
        }
    }

    function update_products()
    {
        global $wpdb;
        $ebay = new MaepCore();

        if(!get_option('number_product'))
            add_option('number_product', 0);

        $table = $wpdb->prefix . "maep_products_info";
        $count_prod = $wpdb->query("SELECT * FROM " . $table);
        $from = get_option('number_product');
        $step = 1000;

        if ($count_prod == $from)
            $from = 0;
        elseif($from == 0 && $count_prod >= $step)
        {
            $number = $step;
            update_option('number_product', $number);
        }
        elseif($from == 0 && $count_prod <= $step)
        {
            $number = $count_prod;
            update_option('number_product', 0);
        }
        elseif($from != 0 && $count_prod >= $from + $step)
        {
            $number = $step;
            update_option('number_product', $from + $step);
        }

        elseif($from != 0 && $count_prod < $from + $step)
        {
            $number = $count_prod - $from;
            update_option('number_product', 0);
        }

        $result = $wpdb->get_results("SELECT id_product,id_post FROM $table LIMIT $from,$number", ARRAY_A);
        if ($result)
        {
            foreach($result as $res)
                $ids[$res['id_post']] = $res['id_product'];

            if (is_array($ids))
            {
                $result_ids = array_chunk($ids, 20, true);
                foreach ($result_ids as $product)
                {
                    $array_ids = array_values($product);
                    $result_price = $ebay->ListProductsByIds($array_ids, 'ShippingCosts');

                    if ($result_price)
                       foreach ($result_price->Item as $info_product)
                         {
                             $count++;
                              if ($info_product->ListingStatus != 'Completed')
                              {
                                  // обновляем цену у товара
                                  $mes[] = $info_product->ItemID . ' - ' . $wpdb->update($table, array('price' => $info_product->ConvertedCurrentPrice), array('id_product' => $info_product->ItemID), array('%s'), array('%s'));

                              }
                              else
                              {
                                  // удаляем товар
                                  $id_post = array_search($info_product->ItemID,$product);
                                  if(wp_delete_post( $id_post, false ))
                                      $mes['delete post']++;
                                  $mes['delet info'] = $wpdb->query("DELETE FROM {$table} WHERE id_product = {$info_product->ItemID}");

                                  // догружаем продукты из категори, откуда удалили товар (20 штук)
                                  $name_cat = array_pop(explode(':',$info_product->PrimaryCategoryName));
                                  $id_cat = (string)$info_product->PrimaryCategoryID;
                                  $keywords = trim(get_option('update_key'));
                                  $new_products = PublishProducts($id_cat,$name_cat,1,$keywords);
                              }
                         }
                }

                //MaepCore::mk_file('update_log.txt', 'Info update ( ' . date('d-m-Y') . ' ) - ' . json_encode($mes) . '- end info; Count ' . $count);
                //MaepCore::mk_file('update_log.txt', 'Info update ( ' . date('d-m-Y') . ' Count ' . $count . ' New Products ' . json_encode($new_products) . ' )');
            }
        }
    }

    function Banners($id_comp, $id_cat, $keywords='', $width, $height, $scrolls, $sort, $topseller )
    {
        $trackingId = get_option('trackingId');
        $programId = getCompain();
        if (empty($programId))
            $programId = 1;

        if ($keywords != '')
            $keywords = '&keyword=' . urlencode('('. $keywords .')');
        else
            $keywords = '&keyword=n';
        if ($id_cat != 0)
            $id_cat = '&catId=' . $id_cat ;
        else
            $id_cat = '';
        ?>
            <script type="text/javascript" src='http://adn.ebay.com/files/js/min/jquery-1.6.2-min.js'></script>
            <script type="text/javascript" src='http://adn.ebay.com/files/js/min/ebay_activeContent-min.js'></script>
            <script charset="utf-8" type="text/javascript">
                document.write('\x3Cscript type="text/javascript" charset="utf-8" src="http://adn.ebay.com/cb?programId=<?=$programId?>&campId=<?=$id_comp?>&toolId=10026<?=$keywords.$id_cat?>&sortBy=<?=$sort?>&width=<?=$width?>&height=<?=$height?>&font=1&textColor=000000&linkColor=0000AA&arrowColor=8BBC01&color1=709AEE&color2=[COLORTWO]&format=ImageLink&contentType=TEXT_AND_IMAGE&enableSearch=y&usePopularSearches=n&freeShipping=n&topRatedSeller=<?=$topseller?>&itemsWithPayPal=n&descriptionSearch=n&showKwCatLink=n&excludeCatId=&excludeKeyword=&disWithin=200&ctx=n&autoscroll=<?=$scrolls?>&flashEnabled=' + isFlashEnabled + '&pageTitle=' + _epn__pageTitle + '&cachebuster=' + (Math.floor(Math.random() * 10000000 )) + '">\x3C/script>' );
            </script>
        <?

    }

   // Крон на обновление продуктов
    function update_prod() {
        if ( !wp_next_scheduled( 'update_plugin_product' ) )
              wp_schedule_event( time(), 'twicedaily', 'update_plugin_product');
    }

   add_action('update_plugin_product', 'do_this_day');
   //add_action('init', 'do_this_day');
    function do_this_day() {
        if (get_option('auto_update_product') == 'check')
            update_products();
    }


    function delete_bad_product()
    {
        if ($_REQUEST['bad_product'])
        {
            $from = $_REQUEST['from'];
            $to = $_REQUEST['to'];
            global $wpdb;
            $table = $wpdb->prefix . "maep_products_info";
            $result = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_type = 'maep_products' LIMIT $from,$to");
            if ($result)
                foreach($result as $id)
                {

                    $res = $wpdb->get_results("SELECT id_post FROM $table WHERE `id_post` = $id->ID");
                    if (!$res)
                        $ret[] = wp_delete_post( $id->ID, false );
                }
            var_dump(count($ret));
        }
    }
    //add_action('init', 'delete_bad_product');

    /*
     * Reviews
     * */
    function load_reviews($id, $page=1)
    {

        $rev = new MaepCore();
        $xml =  $rev->GetReviews($id, $page);
        foreach($xml->BuyingGuideDetails->BuyingGuide as $one_news)
        {
            $title = (string) $one_news->Title;
            $urls[$title] = (string) $one_news->URL;
        }
        $urls = array_unique($urls);
        add_review($urls);
    }
    //add_action('init', 'load_reviews');

    function add_review($urls)
    {
        global $wpdb;
        foreach($urls as $title => $url)
        {
            $check = $wpdb->query("SELECT post_title FROM {$wpdb->posts} WHERE `post_title` = '{$title}'");

            if (!$check)
            {
               $html = file_get_html($url);

                foreach($html->find('div[class=guide-content]') as $ht)
                    $ht = (string)$ht;
                unset($html);
                //$description = $ht;

                $review = array(
                    'post_content' => $ht,
                    'post_status' => 'publish',
                    'post_title' => $title,
                    'post_type' => 'reviews_ebay',
                    'post_author' => $user_ID
                );
                unset($ht);

                $result = wp_insert_post( $review );
            }
            else
                $result = 'Review is exists';

        }
    }
?>