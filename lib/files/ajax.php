<?php

    function list_cat()
    {
        $ebay = new MaepCore();
        $id = trim($_REQUEST['id']);
        $result = $ebay->ChildCategoriesByID($id);
        if ($result->Ack != 'Failure')
            foreach ($result->CategoryArray->Category as $category)
                {
                     if($category->LeafCategory == 'false' && $category->CategoryID != $id)
                     {?>
                         <div class="main_list">
                             <h3  class='cat_c'><a class='a_click glyphicon glyphicon-plus btn btn-primary' data-catid='<?php echo $category->CategoryID; ?>'></a><label><input type='checkbox' value="<?php echo $category->CategoryID; ?>" name='maep[]' /><span class='category_name'><?php echo $category->CategoryName; ?></span></label></h3>
                             <div class="<?php echo $category->CategoryID; ?> all_cat"><div style="width: 200px;"><div class="loader loader-<?php echo $category->CategoryID; ?>">Loading...</div></div></div>
                         </div>
                     <?}
                     elseif($category->CategoryID != $id)
                     {
                         echo "<h3 class='one_cat'><label><input type='checkbox' value=" . $category->CategoryID . " name='maep[]' /><span class='category_name'>" . $category->CategoryName . "</span></label></h3>";
                     }elseif($_REQUEST['flag'] == 'custom')
                         echo "<h3 class='one_cat'><label><input type='checkbox' value=" . $category->CategoryID . " name='maep[]' /><span class='category_name'>" . $category->CategoryName . "</span></label></h3>";
                 }
        else
            echo $result->Errors->LongMessage;

        exit;
    }

    add_action('wp_ajax_list_cat', 'list_cat');

function list_cat_ids()
{
    $ebay = new MaepCore();
    $id = trim($_REQUEST['id']);
    $result = $ebay->ChildCategoriesByID($id);
    if ($result->Ack != 'Failure')
        foreach ($result->CategoryArray->Category as $category)
        {
            if($category->LeafCategory == 'false' && $category->CategoryID != $id)
            {?>
                <div class="main_list">
                    <h3  class='cat_c'><a class='a_click glyphicon glyphicon-plus btn btn-primary' data-catid='<?php echo $category->CategoryID; ?>'></a><label><span class='category_name'><?php echo $category->CategoryName; ?></span>( <?php echo $category->CategoryID; ?> )</label></h3>
                    <div class="<?php echo $category->CategoryID; ?> all_cat"><div style="width: 200px;"><div class="loader loader-<?php echo $category->CategoryID; ?>">Loading...</div></div></div>
                </div>
            <?}
            elseif($category->CategoryID != $id)
            {
                echo "<h3 class='one_cat'><label><span class='category_name'>" . $category->CategoryName . "</span>( " . $category->CategoryID . " )</label></h3>";
            }/*elseif($_REQUEST['flag'] == 'custom')
                echo "<h3 class='one_cat'><label><span class='category_name'>" . $category->CategoryName . "</span>(" . $category->CategoryID . ")</label></h3>";*/
        }
    else
        echo $result->Errors->LongMessage;

    exit;
}

add_action('wp_ajax_list_cat_ids', 'list_cat_ids');

    function count_cat()
    {
        $ids = $_REQUEST['ids'];
        if (is_array($ids))
        {
            $ebay = new MaepCore();
            foreach ($ids as $id) {
                $result = $ebay->ListAllCategoriesByParentID((int)$id);
                foreach ($result->CategoryArray->Category as $category) {
                    if ($category->LeafCategory == 'true')
                    {
                        $count++;
                        $cat_name[] = $category->CategoryName;
                        $new_ids[] = $category->CategoryID;
                    }
                }
            }
            unset($ebay);
            $array['count'] = $count;
            $array['ids'] = $new_ids;
            $array['cat'] = $cat_name;
            $result = json_encode($array);
            echo $result;
            exit();
        }

    }

    add_action('wp_ajax_count_cat', 'count_cat');

    function load_products()
    {
        $id_cat = trim($_REQUEST['ids']);
        $name = trim($_REQUEST['custom_par']);
        if (isset($_REQUEST['keywords']) && trim($_REQUEST['keywords']) != '')
            $keywords = $_REQUEST['keywords'];
        else
            $keywords = '';

        $rep_page = $_REQUEST['perpage'];
        for ($i=1; $i<$rep_page+1; $i++)
            $result[] = PublishProducts($id_cat, $name, $i, $keywords);
        echo json_encode($result);
        sleep(3);
        exit;
    }
    add_action('wp_ajax_load_products', 'load_products');

    // поиск по кейворду
    function search_keyword()
    {
        $ebay = new MaepCore();
        $key = str_replace(' ', '%20', trim($_REQUEST['keywords']));
        $per = trim($_REQUEST['count_search']);

        $result = $ebay->SearchByKeywords($key,$per );
        if ($result->ack == 'Success' && $result->searchResult['count'] != 0){
            echo '<h3 style="text-align: right"><input type="button" id="select_all" value="Select All" class="btn btn-success"/></h3>';
            foreach ($result->searchResult->item as $item) {?>
                <label><p class="search_desc"><input type="checkbox" class="list-item-ids" value="<?php echo $item->itemId; ?>" data-link="<?php echo $item->viewItemURL; ?>" data-idcat="<?php echo $item->primaryCategory->categoryName; ?>" name="ids_item[]"/><img src="<?php echo $item->galleryURL; ?>" width="80" height="80" /><a href="<?php echo $item->viewItemURL; ?>" target="_blank"><?php echo MaepCore::MoreContent($item->title, 11); ?></a><span> - <b><?php echo $item->sellingStatus->currentPrice;?> - <?php echo $item->sellingStatus->currentPrice['currencyId'];?></b></span></p></label>
            <?}
        }
        elseif($result->ack != 'Success')
            echo '<h3 style="text-align: center;" >' . $result->errorMessage->error->message . '</h3>';
        elseif($result->searchResult['count'] == 0)
            echo '<h3 style="text-align: center;">Products ( ' . $key . ' ) not found...</h3>';
        unset($ebay);
        exit();
    }
    add_action('wp_ajax_search_keyword', 'search_keyword');

    // удаление всех товаров
    function DeleteAll()
    {
        global $wpdb;
        $table  = $wpdb->prefix . "maep_products_info";
        $result[] = $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type = 'maep_products'");
        $result[] = $wpdb->query("DELETE FROM $wpdb->terms WHERE term_id IN (SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = 'catproducts' )");
        $result[] = $wpdb->query("DELETE FROM $wpdb->term_taxonomy WHERE term_id NOT IN (SELECT term_id FROM $wpdb->terms)");
        $result[] = $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM $wpdb->term_taxonomy)");
        $result[] = $wpdb->query("TRUNCATE TABLE  $table");
        $result[] = $wpdb->query("OPTIMIZE TABLE $wpdb->terms , $wpdb->term_taxonomy , $wpdb->term_relationships");
        echo "<h2 style='text-align: center;'>All products deleted!</h2>";
        exit;
    }
    add_action('wp_ajax_delete_all', 'DeleteAll');

    // добавление одного товара
    function LoadOneItem()
    {
        $ebay = new MaepCore();
        $ids = $_REQUEST['id_one'];
        $name = $_REQUEST['name_cat'];
        $category = array_combine($ids, $name);
        foreach ($category as $id_prod => $name_cat)
        {
            $detail_item = $ebay->ListProductsByIds(array($id_prod));
            $result[] = InsertDB($detail_item, $name_cat);
        }
        var_dump($result);
        exit;
    }
    add_action('wp_ajax_load_one_item', 'LoadOneItem');

    function PopS()
    {
        $ebay = new MaepCore();
        $id_cat = trim($_REQUEST['id']);
        $find = $ebay->PopularSearches($id_cat);
        $string = $find->PopularSearchResult->RelatedSearches;
        $items = explode(';',$string);
        foreach($items as $item)
            echo '<span class="label label-info pop-s">' . $item . '</span>';
        exit;
    }
    add_action('wp_ajax_list_pop','PopS');

    function upload_reviews()
    {
        $id = trim($_REQUEST['id']);
        $page = (int)$_REQUEST['perpage'];
        if (isset($id))
        {
            load_reviews($id,$page);
            $page++;
            echo $page;
            sleep(1);
        }
        else
            echo "Error - ID is empty";
        exit;
    }
    add_action('wp_ajax_upload_reviews', 'upload_reviews');

    // проверяем количество гайдов
    function check_review($id)
    {
        $id = trim($_REQUEST['id']);
        $rev = new MaepCore();
        $xml =  $rev->GetReviews($id, 1);
        echo $xml->BuyingGuideCount;
        exit;
    }
    add_action('wp_ajax_check_review', 'check_review');

?>