<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.10.2015
 * Time: 14:12
 */

namespace PM\Bundle\ToolBundle\Constants;

/**
 * Class HttpStatusCode
 *
 * @package PM\Bundle\ToolBundle\Constants
 */
class HttpStatusCode
{
    const SWITCHING_PROTOCOLS = 101;
    const PROCESSING = 102;
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NONAUTHORITATIVE_INFORMATION = 203;
    const NO_CONTENT = 204;
    const RESET_CONTENT = 205;
    const PARTIAL_CONTENT = 206;
    const MULTIPLE_CHOICES = 300;
    const MOVED_PERMANENTLY = 301;
    const MOVED_TEMPORARILY = 302;
    const SEE_OTHER = 303;
    const NOT_MODIFIED = 304;
    const USE_PROXY = 305;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const PAYMENT_REQUIRED = 402;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const NOT_ACCEPTABLE = 406;
    const PROXY_AUTHENTICATION_REQUIRED = 407;
    const REQUEST_TIMEOUT = 408;
    const CONFLICT = 408;
    const GONE = 410;
    const LENGTH_REQUIRED = 411;
    const PRECONDITION_FAILED = 412;
    const REQUEST_ENTITY_TOO_LARGE = 413;
    const REQUESTURI_TOO_LARGE = 414;
    const UNSUPPORTED_MEDIA_TYPE = 415;
    const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const EXPECTATION_FAILED = 417;
    const IM_A_TEAPOT = 418;
    const UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const BAD_GATEWAY = 502;
    const SERVICE_UNAVAILABLE = 503;
    const GATEWAY_TIMEOUT = 504;
    const HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * Get All
     *
     * @return array
     */
    public static function getAll()
    {
        return [
            self::SWITCHING_PROTOCOLS             => 'Switching Protocols',
            self::PROCESSING                      => 'Processing',
            self::OK                              => 'OK',
            self::CREATED                         => 'Created',
            self::ACCEPTED                        => 'Accepted',
            self::NONAUTHORITATIVE_INFORMATION    => 'Non-Authoritative Information',
            self::NO_CONTENT                      => 'No Content',
            self::RESET_CONTENT                   => 'Reset Content',
            self::PARTIAL_CONTENT                 => 'Partial Content',
            self::MULTIPLE_CHOICES                => 'Multiple Choices',
            self::MOVED_PERMANENTLY               => 'Moved Permanently',
            self::MOVED_TEMPORARILY               => 'Moved Temporarily',
            self::SEE_OTHER                       => 'See Other',
            self::NOT_MODIFIED                    => 'Not Modified',
            self::USE_PROXY                       => 'Use Proxy',
            self::BAD_REQUEST                     => 'Bad Request',
            self::UNAUTHORIZED                    => 'Unauthorized',
            self::PAYMENT_REQUIRED                => 'Payment Required',
            self::FORBIDDEN                       => 'Forbidden',
            self::NOT_FOUND                       => 'Not Found',
            self::METHOD_NOT_ALLOWED              => 'Method Not Allowed',
            self::NOT_ACCEPTABLE                  => 'Not Acceptable',
            self::PROXY_AUTHENTICATION_REQUIRED   => 'Proxy Authentication Required',
            self::REQUEST_TIMEOUT                 => 'Request Time-out',
            self::CONFLICT                        => 'Conflict',
            self::GONE                            => 'Gone',
            self::LENGTH_REQUIRED                 => 'Length Required',
            self::PRECONDITION_FAILED             => 'Precondition Failed',
            self::REQUEST_ENTITY_TOO_LARGE        => 'Request Entity Too Large',
            self::REQUESTURI_TOO_LARGE            => 'Request-URL Too Long',
            self::UNSUPPORTED_MEDIA_TYPE          => 'Unsupported Media Type',
            self::REQUESTED_RANGE_NOT_SATISFIABLE => 'Requested range not satisfiable',
            self::EXPECTATION_FAILED              => 'Expectation Failed',
            self::IM_A_TEAPOT                     => 'I\'m a teapot',
            self::UNAVAILABLE_FOR_LEGAL_REASONS   => 'Unavailable For Legal Reasons',
            self::INTERNAL_SERVER_ERROR           => 'Internal Server Error',
            self::NOT_IMPLEMENTED                 => 'Not Implemented',
            self::BAD_GATEWAY                     => 'Bad Gateway',
            self::SERVICE_UNAVAILABLE             => 'Service Unavailable',
            self::GATEWAY_TIMEOUT                 => 'Gateway Time-out',
            self::HTTP_VERSION_NOT_SUPPORTED      => 'HTTP Version not supported',
        ];
    }
}