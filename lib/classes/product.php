<?php
/**
 *
 */
class Product extends MaepProducts
{
    private $_id;
    private $_product;

    function __construct($id)
    {
        $this->_id = $id;
        $this->setInfo($this->get_all_info());
    }

    public function GetProductsTax($id_tax)
    {
        if (isset($id_tax))
        {
            $args = array(
                'tax_query' => array(
                    array(
                        'taxonomy' => 'catproducts',
                        'terms' => $id_tax
                    )
                )
            );
            $result = new WP_Query( $args );
            $result = $result->posts;
        }else
            $result = false;
        return $result;
    }

    private function get_all_info()
    {
        global $wpdb;
        $id     = $this->_id;
        $post   = get_post($id);
        $table  = $wpdb->prefix . "maep_products_info";
        $result = $wpdb->get_results("SELECT * FROM {$table} WHERE id_post = {$id}");
        $info[] = $post;
        $info[] = $result[0];
        return $info;
    }

    private function setInfo($info)
    {
        $this->_product = $info;
        return $this;
    }

    public function get_cost()
    {
        if (is_object($this->_product[1]))
            return $this->_product[1]->price;
        else
            return false;
    }

    public function get_currency()
    {
        if (is_object($this->_product[1]))
        {
            switch ($this->_product[1]->type_cost) {
                case 'USD':
                    return '$';
                    break;

                case 'EUR':
                    return '€';
                    break;

                case 'GBP':
                    return '£';
                    break;

                case 'CAD':
                    return 'C$';
                    break;

                default:
                    return $this->_product[1]->type_cost;
                    break;
            };
        }

        else
            return false;
    }

    public function get_link()
    {
        if (is_object($this->_product[1]))
            return $this->_product[1]->link;
        else
            return false;
    }

    public function get_title()
    {
        if (is_object($this->_product[0]))
            return $this->_product[0]->post_title;
        else
            return false;
    }

    public function get_thumbnail()
    {
        if (is_object($this->_product[1]))
            return $this->_product[1]->images;
        else
            return false;
    }

    public function get_lists()
    {
        if (is_object($this->_product[1]))
        {
            if($this->_product[1]->list != '')
            {
                $list_array = json_decode($this->_product[1]->list);
                if (is_array($list_array->NameValueList))
                {
                    echo "<ul class='maep_list'>";
                    foreach ($list_array->NameValueList as $value)
                    {

                        if (!is_object( $value->Value ) && !is_array( $value->Value ))
                        {
                            echo "<li class='maep_list_name'>" . $value->Name . ": </li>";
                            echo "<li class='maep_list_value'>" . $value->Value . "</li>";
                        }

                        elseif (is_array($value->Value))
                        {
                            echo "<li class='maep_list_value'>";
                            foreach ($value->Value as $param) {
                                echo $param.', ';
                            }
                            echo '</li>';
                        }
                    }
                    echo "</ul>";
                }
                else echo "none";
            }
        }
        else
            return false;
    }

    public function get_description()
    {
        if (is_object($this->_product[0]))
            return $this->_product[0]->post_content;
        else
            return false;
    }

    public function get_listing_type()
    {
        if (is_object($this->_product[1]))
            return $this->_product[1]->listing_type;
        else
            return false;
    }

    public function get_payment_methods()
    {
        if (is_object($this->_product[1]))
        {
            if($this->_product[1]->methods != '')
            {
                $methods_array = unserialize($this->_product[1]->methods);
                return $methods_array;
            }
        }
        else
            return false;
    }

    public function get_positive_feedback_percent_seller()
    {
        if (is_object($this->_product[1]))
            return $this->_product[1]->seller;
        else
            return false;
    }

    public function get_listing_status()
    {
        if (is_object($this->_product[1]))
            return $this->_product[1]->status;
        else
            return false;
    }
}