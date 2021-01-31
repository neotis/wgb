<?php
/**
 * Manage seo tags and generator
 * Created by PhpStorm.

 * Date: 2/25/2019
 * Time: 12:00 PM
 * Neotis framework
 */

namespace Neotis\Plugins\Seo;

use Neotis\Core\Http\Header;
use Neotis\Core\Http\Request;
use Neotis\Plugins\Plugins;
use Neotis\Core\Template\Factory as Template;

class Tags extends Plugins
{
    /**
     * Defin base url
     * @var string
     */
    public static string $baseUrl = '';

    /**
     * The main link url
     * @var string
     */
    private string $mainLink = '';

    /**
     * Create main address of url for canonical tag
     * @param $parameters
     */
    public function mainAddress($parameters = [])
    {
        $url = '/';
        if(is_array($parameters)){
            foreach ($parameters as $key => $value) {
                $url .= Request::flashEncode($value) . '/';
            }
        }
        $this->mainLink = $url;
        $this->canonicalUrl();

        return $this;
    }

    /**
     * Define title for seo
     * @param $title
     * @return Tags
     */
    public function title($title)
    {
        Template::vars([
            'seo_title' => $title
        ]);
        return $this;
    }

    /**
     * Name of website
     * @param $name
     * @return Tags
     */
    public function name($name)
    {
        Template::vars([
            'seo_name' => $name
        ]);
        return $this;
    }

    /**
     * Define description for seo
     * @param $description
     * @return Tags
     */
    public function description($description)
    {
        Template::vars([
            'seo_description' => $description
        ]);
        return $this;
    }

    /**
     * Define image for seo
     * @param $image
     * @return Tags
     */
    public function image($image)
    {
        Template::vars([
            'seo_image' => $image
        ]);
        return $this;
    }

    /**
     * List of keys
     * @param $keys
     * @return $this
     */
    public function keys($keys)
    {
        Template::vars([
            'seo_keys' => $keys
        ]);
        return $this;
    }

    /**
     * Define type
     * @param $type
     * @return $this
     */
    public function type($type): Tags
    {
        Template::vars([
            'seo_type' => $type
        ]);
        return $this;
    }

    /**
     * Article structure for data structure
     * @param $title
     * @param $author
     * @param $publisherName
     * @param $logo
     * @param $datePublish
     * @param array $images
     * @param string $authorType
     * @param string $publisherType
     * @return $this
     */
    public function articleStructure($title, $author, $publisherName, $logo, $datePublish, $dateModify, $images = [], $authorType = 'Organization', $publisherType = 'Organization')
    {
        Template::vars([
            'seo_structure_type' => 'article',
            'seo_structure_title' => $title,
            'seo_structure_author' => $author,
            'seo_structure_publisher_name' => $publisherName,
            'seo_structure_images' => $images,
            'seo_structure_logo' => self::$baseUrl . $logo,
            'seo_structure_author_type' => $authorType,
            'seo_structure_publisher_type' => $publisherType,
            'seo_structure_publish_date' => date('Y-m-d\TH:i:s', $datePublish),
            'seo_structure_publish_modify' => date('Y-m-d\TH:i:s', $dateModify),
        ]);
        return $this;
    }

    /**
     * Product structure
     * @param $title
     * @param $description
     * @param $brand
     * @param $ratingCount
     * @param $ratingAverage
     * @param $ratingBest
     * @param $author
     * @param array $images
     * @param string $authorType
     * @return $this
     */
    public function productStructure($title, $description, $brand, $ratingCount, $ratingAverage, $ratingBest, $author, $images = [], $authorType = 'Organization')
    {
        Template::vars([
            'seo_structure_type' => 'product',
            'seo_structure_title' => $title,
            'seo_structure_description' => $description,
            'seo_structure_product_brand' => $brand,
            'seo_structure_product_rating_average' => $ratingAverage,
            'seo_structure_product_rating_best' => $ratingBest,
            'seo_structure_product_aggregate_rating_average' => $ratingAverage,
            'seo_structure_product_aggregate_rating_count' => $ratingCount,
            'seo_structure_images' => $images,
            'seo_structure_author_type' => $authorType,
            'seo_structure_author' => $author,
        ]);
        return $this;
    }

    /**
     * Logo data structure
     * @param $url
     * @param $logo
     * @return Tags
     */
    public function logoStructure($url, $logo)
    {
        Template::vars([
            'seo_logo_structure' => 'logo',
            'seo_logo_structure_url' => $url,
            'seo_logo_structure_title' => $url . $logo,
        ]);
        return $this;
    }

    /**
     * Create canonical url and redirect to main url
     */
    private function canonicalUrl()
    {
        /*if ($this->mainLink !== $_SERVER['REQUEST_URI']) {
            Header::redirect(self::$baseUrl . $this->mainLink);
        }*/
    }

    /**
     * Publish seo information
     */
    public function publish()
    {
        Template::vars([
            'seo_canonical' => self::$baseUrl . $this->mainLink
        ]);
    }
}
