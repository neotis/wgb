<?php
/**
 * Created by PhpStorm.
 * Date: 11/22/2018
 * Time: 11:44 AM
 */

namespace Neotis\Core\Template\MetaTags;

use Neotis\Core\Router\Router;

trait Manipulator
{
    /**
     * Store title of page
     * @var string
     */
    private static $metaTitle = "";

    /**
     * Store description of page
     * @var string
     */
    private static $metaDescription = "";

    /**
     * Store picture link of page
     * @var string
     */
    private static $picture = "";


    /**
     * Store video link of page
     * @var string
     */
    private static $video = "";

    /**
     * Store subject of page
     * @var string
     */
    private static $subject = "";

    /**
     * Store pages key words
     * @var string
     */
    private static $metaKeyWords = "";

    /**
     * Store company name
     * @var string
     */
    private static $company = "";

    /**
     * Store email name of website
     * @var string
     */
    private static $email = "";

    /**
     * List of company tags
     * @var string
     */
    private static $companyTags = '';

    /**
     * List of audio tags
     * @var string
     */
    private static $audioTags = '';

    /**
     * Store image tags
     * @var string
     */
    private static $imageTags = '';

    /**
     * Store video tags
     * @var string
     */
    private static $videoTags = '';

    /**
     * Store general tags
     * @var string
     */
    private static $generalTags = '';


    /**
     * Define tags details
     * @param string $title
     * @param string $description
     * @param string $tags
     * @param string $subject
     */
    public static function setBasicTags($title = "", $description = "", $tags = "", $subject = "")
    {
        self::$metaTitle = $title;
        self::$metaDescription = $description;
        self::$metaKeyWords = $tags;
        $tags = '';
        if(!empty($title)){$tags .= '<meta name="og:title" content="'.$title.'"/>';}
        if(!empty($description)){$tags .= '<meta name="og:description" content="'.$description.'"/>';}
        if(!empty($subject)){$tags .= '<meta name="og:subject" content="'.$subject.'"/>';}
        self::$generalTags = $tags;
    }

    /**
     * Define page with video tags
     * @param string $video
     * @param string $height
     * @param string $width
     * @param string $type
     */
    public static function setVideoTags($video = "", $height = "", $width = "", $type = "")
    {
        $tags = '';
        if(!empty($video)){$tags .= '<meta name="og:video" content="'.$video.'"/>';}
        if(!empty($video)){$tags .= '<meta name="og:video:url" content="'.$video.'"/>';}
        if(!empty($height)){$tags .= '<meta name="og:video:height" content="'.$height.'"/>';}
        if(!empty($width)){$tags .= '<meta name="og:video:width" content="'.$width.'"/>';}
        if(!empty($type)){$tags .= '<meta name="og:video:type" content="'.$type.'"/>';}
        self::$videoTags = $tags;
    }

    /**
     * Define image tags of page
     * @param string $image
     * @param string $height
     * @param string $width
     * @param string $type
     * @param string $alt
     * @param $secureUrl
     */
    public static function setImageTags($image = "", $height = "", $width = "", $type = "", $alt = "", $secureUrl)
    {
        $tags = '';
        if(!empty($image)){$tags .= '<meta name="og:image" content="'.$image.'"/>';}
        if(!empty($image)){$tags .= '<meta name="og:image:url" content="'.$image.'"/>';}
        if(!empty($height)){$tags .= '<meta name="og:image:height" content="'.$height.'"/>';}
        if(!empty($width)){$tags .= '<meta name="og:image:width" content="'.$width.'"/>';}
        if(!empty($type)){$tags .= '<meta name="og:image:type" content="'.$type.'"/>';}
        if(!empty($alt)){$tags .= '<meta name="og:image:alt" content="'.$alt.'"/>';}
        if(!empty($secureUrl)){$tags .= '<meta name="og:image:secure_url" content="'.$secureUrl.'"/>';}
        self::$imageTags = $tags;
    }

    /**
     * Define audio tags of page
     * @param string $audio
     * @param string $title
     * @param string $artist
     * @param string $album
     * @param string $type
     */
    public static function setAudioTags($audio = "", $title = "", $artist = "", $album = "", $type = "")
    {
        $tags = '';
        if(!empty($audio)){$tags .= '<meta name="og:audio" content="'.$audio.'"/>';}
        if(!empty($title)){$tags .= '<meta name="og:audio:title" content="'.$title.'"/>';}
        if(!empty($artist)){$tags .= '<meta name="og:audio:artist" content="'.$artist.'"/>';}
        if(!empty($album)){$tags .= '<meta name="og:audio:album" content="'.$album.'"/>';}
        if(!empty($type)){$tags .= '<meta name="og:audio:type" content="'.$type.'"/>';}
        self::$audioTags = $tags;
    }

    /**
     * Define company address and details tags
     * @param string $company
     * @param string $email
     * @param string $phone_number
     * @param string $fax_number
     * @param string $latitude
     * @param string $longitude
     * @param string $street_address
     * @param string $locality
     * @param string $region
     * @param string $postal_code
     * @param string $country_name
     * @return string
     */
    public static function companyTags($company = "", $email = "", $phone_number = "", $fax_number = "", $latitude = "", $longitude = "", $street_address = "", $locality = "", $region = "", $postal_code = "", $country_name = "")
    {
        self::$company = $company;
        self::$email = $email;
        $tags = '';
        if(!empty($phone_number)){$tags .= '<meta name="og:phone_number" content="'.$phone_number.'"/>';}
        if(!empty($fax_number)){$tags .= '<meta name="og:fax_number" content="'.$fax_number.'"/>';}
        if(!empty($latitude)){$tags .= '<meta name="og:latitude" content="'.$latitude.'"/>';}
        if(!empty($longitude)){$tags .= '<meta name="og:longitude" content="'.$longitude.'"/>';}
        if(!empty($street_address)){$tags .= '<meta name="og:street_address" content="'.$street_address.'"/>';}
        if(!empty($locality)){$tags .= '<meta name="og:locality" content="'.$locality.'"/>';}
        if(!empty($region)){$tags .= '<meta name="og:region" content="'.$region.'"/>';}
        if(!empty($postal_code)){$tags .= '<meta name="og:postal_code" content="'.$postal_code.'"/>';}
        if(!empty($country_name)){$tags .= '<meta name="og:country_name" content="'.$country_name.'"/>';}
        self::$companyTags = $tags;
    }


    /**
     * Return string of needed tags
     */
    public static function getMetaTagsString()
    {
        $tags = '';
        if(!empty(self::$metaKeyWords)){$tags .= '<meta name="keywords" content="'.self::$metaKeyWords.'"/>';}
        if(!empty(self::$metaDescription)){$tags .= '<meta name="description" content="'.self::$metaDescription.'"/>';}
        if(!empty(self::$metaTitle)){$tags .= '<meta name="subject" content="'.self::$metaTitle.'"/>';}
        if(!empty(self::$company)){$tags .= '<meta name="copyright" content="'.self::$company.'"/>';}
        if(!empty(Router::getLanguage())){$tags .= '<meta name="language" content="'.Router::getLanguage().'"/>';}
        if(!empty(Router::getClassification())){$tags .= '<meta name="Classification" content="'.Router::getClassification().'"/>';}
        if(!empty(Router::getAuthor())){$tags .= '<meta name="author" content="'.Router::getAuthor().'"/>';}
        if(!empty(self::$email)){$tags .= '<meta name="reply-to" content="'.self::$email.'"/>';}
        if(!empty(self::$company)){$tags .= '<meta name="owner" content="'.self::$company.'"/>';}
        if(!empty(Router::getCategory())){$tags .= '<meta name="category" content="'.Router::getCategory().'"/>';}
        if(!empty(Router::getCoverage())){$tags .= '<meta name="coverage" content="'.Router::getCoverage().'"/>';}
        if(!empty(Router::getDistribution())){$tags .= '<meta name="distribution" content="'.Router::getDistribution().'"/>';}
        if(!empty(Router::getRevisitAfter())){$tags .= '<meta name="revisit-after" content="'.Router::getRevisitAfter().'"/>';}
        $tags .= '
        <meta name="robots" content="index,follow" />
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />';
        $tags .= self::$companyTags;
        $tags .= self::$imageTags;
        $tags .= self::$videoTags;
        $tags .= self::$audioTags;
        $tags .= self::$generalTags;
        return $tags;
    }
}
