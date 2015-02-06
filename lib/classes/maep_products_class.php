<?php

    class MaepProducts
    {
        private $_devID;
        private $_appID;
        private $_certID;
        private $_globalID;
        private $_trackingID;
        private $_siteID;
        private $_token;
        private $_catID;
        private $_itemID;
        private $_typeRESP;
        private $_headers;
        private $_xml_body;
        private $_keywords;
        private $_count;
        private $_include_selector;
        private $_page;


        function __construct()
        {
            $this->_appID = get_option('appID');
            $this->_siteID = get_option('maep_lang');
            $this->_devID = get_option('DEVID');
            $this->_certID = get_option('CertID');
            $this->_token = get_option('token');
            $this->_trackingID = get_option('trackingId');
            //$this->_typeRESP = 'JSON';
            $this->_typeRESP = 'XML';

        }

        // cURL запрос на eBay
        public function CallToEbay($url)
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

        public static function mk_file($filename, $content) {
            $url = dirname(__FILE__) . '/logs/'.  $filename;
            $content .= " -- ";
            return file_put_contents($url, $content, FILE_APPEND | LOCK_EX);
        }

        // запрос в виде XML
        public function SendXML($url)
        {
            try
            {
                $connect = curl_init();
                curl_setopt($connect, CURLOPT_URL, $url);
                curl_setopt($connect, CURLOPT_HTTPHEADER, $this->_headers);
                curl_setopt($connect, CURLOPT_RETURNTRANSFER,1);
                curl_setopt($connect, CURLOPT_POST, 1);
                curl_setopt($connect, CURLOPT_POSTFIELDS, $this->_xml_body);
                $result = curl_exec($connect);
                curl_close($connect);

                return $result;
            }
            catch(Exeption $e)
            {
                throw new Exception( "Error cURL call XML body" );
                error_log($e->getMessage());
                return false;
            }
        }

        // информация о подкатегориях выбранной категории
        protected function GetCategoryInfo()
        {
            $url  = "http://open.api.ebay.com/Shopping?";
            $url .= "callname=GetCategoryInfo&appid=" . $this->_appID;
            $url .= "&version=699";
            $url .= "&siteid=" . $this->_siteID;
            $url .= "&CategoryID=" . $this->_catID;
            $url .= "&IncludeSelector=ChildCategories";
            $url .= "&responseencoding=" . $this->_typeRESP;

            return $this->CallToEbay($url);

        }

        // подробная информация о 20 товарах одной категории
        protected function GetMultipleItems()
        {
            $url  = "http://open.api.ebay.com/shopping?";
            $url .= "callname=GetMultipleItems";
            $url .= "&responseencoding=" . $this->_typeRESP;
            $url .= "&appid=" . $this->_appID;
            $url .= "&siteid=" . $this->_siteID;
            $url .= "&version=515";
            $url .= "&IncludeSelector=" . $this->_include_selector;
            $url .= "&ItemID=". $this->_itemID; // строка из 20 или меньше id через запятую
            $url .= "&trackingpartnercode=9";
            $url .= "&trackingid=" . $this->_trackingID;

            return $this->CallToEbay($url);
        }
        // id продуктов по заданной категории
        protected function findItemsByCategory()
        {
            $url  = "http://svcs.ebay.com/services/search/FindingService/v1?";
            $url .= "OPERATION-NAME=findItemsByCategory";
            $url .= "&SERVICE-VERSION=1.0.0";
            $url .= "&X-EBAY-SOA-GLOBAL-ID=" . $this->_globalID;
            $url .= "&SECURITY-APPNAME=" . $this->_appID;
            $url .= "&RESPONSE-DATA-FORMAT=" . $this->_typeRESP;
            $url .= "&REST-PAYLOAD";
            $url .= "&categoryId=" . $this->_catID;
            $url .= "&paginationInput.entriesPerPage=20";
            $url .= "&paginationInput.pageNumber=" . $this->_page;

            return $this->CallToEbay($url);
        }

        // id продуктов по заданной категории c кейвордом
        protected function findCompletedItems()
        {
            $url  = "http://svcs.ebay.com/services/search/FindingService/v1?";
            $url .= "OPERATION-NAME=findCompletedItems";
            $url .= "&SERVICE-VERSION=1.7.0";
            $url .= "&X-EBAY-SOA-GLOBAL-ID=" . $this->_globalID;
            $url .= "&SECURITY-APPNAME=" . $this->_appID;
            $url .= "&RESPONSE-DATA-FORMAT=" . $this->_typeRESP;
            $url .= "&categoryId=" . $this->_catID;
            $url .= "&keywords=" . $this->_keywords ;
            $url .= "&paginationInput.entriesPerPage=20";
            $url .= "&paginationInput.pageNumber=" . $this->_page;

            return $this->CallToEbay($url);
        }

        // Получает весь список подкатегорий категории по ID
        protected function GetCategories()
        {

            $endpoint = "https://api.ebay.com/ws/api.dll";

            $headers = array(
                'Content-Type: text/xml;charset=UTF-8',
                'X-EBAY-API-COMPATIBILITY-LEVEL: 819',
                'X-EBAY-API-DEV-NAME: ' . $this->_devID,
                'X-EBAY-API-APP-NAME: ' . $this->_appID,
                'X-EBAY-API-CERT-NAME :' . $this->_certID,
                'X-EBAY-API-CALL-NAME: GetCategories',
                'X-EBAY-API-SITEID:' . $this->_siteID,
            );
            $body = '<?xml version="1.0" encoding="utf-8"?>
					<GetCategoriesRequest xmlns="urn:ebay:apis:eBLBaseComponents">
					<RequesterCredentials>
					<eBayAuthToken>' . $this->_token . '</eBayAuthToken>
					</RequesterCredentials>
					<CategoryParent>' . $this->_catID . '</CategoryParent>
					<ViewAllNodes>True</ViewAllNodes>
					<DetailLevel>ReturnAll</DetailLevel>
					</GetCategoriesRequest>';

            $this->setHeaders($headers)->setXmlBody($body);

            return $this->SendXML($endpoint);

        }

        // Поиск по кейворду
        protected function findItemsByKeywords()
        {
            $url  = "http://svcs.ebay.com/services/search/FindingService/v1?";
            $url .= "X-EBAY-SOA-GLOBAL-ID=" . $this->_globalID;
            $url .= "&OPERATION-NAME=findItemsByKeywords";
            $url .= "&SERVICE-VERSION=1.0.0";
            $url .= "&SECURITY-APPNAME=" . $this->_appID;
            $url .= "&RESPONSE-DATA-FORMAT=XML";
            $url .= "&REST-PAYLOAD";
            $url .= "&affiliate.networkId=9";
            $url .= "&affiliate.trackingId=" . $this->_trackingID;
            $url .= "&keywords=" . $this->_keywords ;
            $url .= "&paginationInput.entriesPerPage=" . $this->_count;

            return $this->CallToEbay($url);
        }

        // Поиск популярных запросов
        protected function FindPopularSearches()
        {
            $url  = "http://open.api.ebay.com/shopping?";
            $url .= "callname=FindPopularSearches";
            $url .= "&responseencoding=" . $this->_typeRESP;
            $url .= "&appid=" . $this->_appID;
            $url .= "&siteid=" . $this->_siteID;
            $url .= "&version=531";
            $url .= "&CategoryID=" . $this->_catID;

            return $this->CallToEbay($url);
        }

        // Поиск популярных запросов
        protected function FindReviewsAndGuides()
        {
            $url  = "http://open.api.ebay.com/shopping?";
            $url .= "callname=FindReviewsAndGuides";
            $url .= "&responseencoding=" . $this->_typeRESP;
            $url .= "&appid=" . $this->_appID;
            $url .= "&siteid=" . $this->_siteID;
            $url .= "&PageNumber=" . $this->_page;
            $url .= "&version=531";
            $url .= "&CategoryID=" . $this->_catID;

            return $this->CallToEbay($url);
        }


        protected function setCatID($id)
        {
            $this->_catID = $id;
            return $this;
        }

        protected function setItemID($id)
        {
            $this->_itemID = $id;
            return $this;
        }

        protected function setGlobalID($id)
        {
            $this->_globalID = $id;
            return $this;
        }

        protected function setHeaders($string)
        {
            $this->_headers = $string;
            return $this;
        }

        protected function setXmlBody($string)
        {
            $this->_xml_body = $string;
            return $this;
        }

        protected function setKeywords($string)
        {
            $this->_keywords = $string;
            return $this;
        }

        protected function setCount($count)
        {
            $this->_count = $count;
            return $this;
        }

        protected function setIncludeSelector($string)
        {
            $this->_include_selector = $string;
            return $this;
        }

        protected function setNumberPage($number)
        {
            $this->_page = $number;
            return $this;
        }


    }

?>