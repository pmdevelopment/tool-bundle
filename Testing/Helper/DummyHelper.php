<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 16.12.2016
 * Time: 16:31
 */

namespace PM\Bundle\ToolBundle\Testing\Helper;

/**
 * Class DummyHelper
 *
 * @package PM\Bundle\ToolBundle\Testing\Helper
 */
class DummyHelper
{
    const VALUE_STRING = 'Stark Industries';
    const VALUE_STRING_SLUG = 'stark-industries';

    const VALUE_STRING_USERNAME = 'tony@stark.com';
    const VALUE_STRING_NAME = 'Tony';
    const VALUE_STRING_SURNAME = 'Stark';

    const VALUE_INTEGER = 1312;

    const VALUE_NOT_EXISTING_ID = 1337;

    const VALUE_IP_V4 = '127.0.0.1';

    const VALUE_FQDN = 'stark.com';
    const VALUE_FQDN_2 = 'stark-and-pepper.com';

    const VALUE_PATH = '/suits/mark-4';

    const VALUE_URI = 'http://pepper.de/welcome.html';
    const VALUE_URI_2 = 'http://pepper.de/hello.html';
}