<?php
// Звоним на новости ebay и возвращам просто XML
    function call_news($url)
    {
        try
        {
            $connect = curl_init();// open
            curl_setopt($connect, CURLOPT_URL, $url);
            curl_setopt($connect, CURLOPT_RETURNTRANSFER,1); // возвращаем в переменую
            curl_setopt($connect, CURLOPT_TIMEOUT,15); // таймаут 15 секунд
            $result = curl_exec($connect); // строка ответа от eBay
            $info = curl_getinfo($connect, CURLINFO_HTTP_CODE);

            curl_close($connect);

            //$this::mk_file('log1.txt', $info);

            return $result;
        }
        catch(Exeption $e)
        {
            throw new Exception( "Error cURL call" );
            error_log($e->getMessage());
            return false;
        }
    }

function load_news()
{
    global $wpdb;
    $xml_string = call_news('http://announcements.ebay.com/feed/');
    $xml = new SimpleXMLElement($xml_string,LIBXML_NOCDATA);
    foreach($xml->channel->item as $one_news)
    {
        $news = array(
            'post_content' =>$one_news->description,
            'post_status' => 'publish',
            'post_title' => $one_news->title,
            'post_type' => 'news_ebay',
            'post_author' => $user_ID
        );
        $check = $wpdb->query("SELECT post_title FROM {$wpdb->posts} WHERE `post_title` = '$one_news->title' and `post_type` = 'news_ebay'");
        if (!$check)
            wp_insert_post( $news );
    }

}

// Крон на обновление новостей
function update_news() {
    if ( !wp_next_scheduled( 'update_plugin_news' ) )
        wp_schedule_event( time(), 'daily', 'update_plugin_news');
}

add_action('update_plugin_news', 'do_this_day_news');

function do_this_day_news() {
    if (get_option('update_plugin_news') == 'check')
        update_news();
}

