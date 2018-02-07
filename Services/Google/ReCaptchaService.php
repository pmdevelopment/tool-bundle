<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 07.02.2018
 * Time: 12:45
 */

namespace PM\Bundle\ToolBundle\Services\Google;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class ReCaptchaService
 *
 * @package PM\Bundle\ToolBundle\Services\Google
 */
class ReCaptchaService
{
    const URI_BASE_V2 = 'https://www.google.com/recaptcha/api/';
    const URI_VERIFY_V2 = 'siteverify';

    /**
     * Is Valid
     *
     * @param string  $secret
     * @param Request $request
     *
     * @return bool
     */
    public function isValid($secret, Request $request)
    {
        $captcha = $request->get('g-recaptcha-response');
        if(null === $captcha || true === empty($captcha)){
            return false;
        }

        $guzzle = $this->getClient();

        $result = $guzzle->request(Request::METHOD_POST, self::URI_VERIFY_V2, [
            'form_params' => [
                'secret'   => $secret,
                'response' => $captcha,
                'remoteip' => $request->getClientIp(),
            ]
        ]);

        $bodyContents = $result->getBody()->getContents();
        if (true === empty($bodyContents)) {
            return false;
        }

        $jsonContents = json_decode($bodyContents, true);
        if (false === isset($jsonContents['success']) || true !== $jsonContents['success']) {
            return false;
        }

        return true;
    }

    /**
     * Get Client
     *
     * @return Client
     */
    private function getClient()
    {
        return new Client(
            [
                'base_uri' => self::URI_BASE_V2,
            ]
        );
    }
}