<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.06.2016
 * Time: 15:42
 */

namespace PM\Bundle\ToolBundle\Services\Google;

/**
 * Class LinkShortenerService
 *
 * @package PM\Bundle\ToolBundle\Services\Google
 */
class LinkShortenerService
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $url;

    /**
     * GoogleLinkShortener constructor.
     *
     * @param string $token
     * @param string $url
     */
    public function __construct($token, $url)
    {
        $this->token = $token;
        $this->url = $url;
    }

    /**
     * Get Short URL
     *
     * @param string $url
     *
     * @return string
     */
    public function getShortUrl($url)
    {

        $postData = ['longUrl' => $url];
        $jsonData = json_encode($postData);

        $token = $this->getToken();
        $url = $this->getUrl();

        if (true === empty($token)) {
            throw new \LogicException("Missing pm_tool.google_link_shortener.token");
        }

        if (true === empty($url)) {
            throw new \LogicException("Missing pm_tool.google_link_shortener.url");
        }

        $curlObj = curl_init();

        curl_setopt($curlObj, CURLOPT_URL, sprintf("%s?key=%s", $url, $token));
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($curlObj);

        $json = json_decode($response, true);

        curl_close($curlObj);

        if (isset($json['id'])) {
            return $json['id'];
        }

        return 'Api Error: ' . print_r($json, true);
    }


    /**
     * @return string
     */
    private function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    private function getUrl()
    {
        return $this->url;
    }
}