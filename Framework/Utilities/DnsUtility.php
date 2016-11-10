<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 10.11.2016
 * Time: 11:13
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class DnsUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class DnsUtility
{
    const RECORD_TYPE_CNAME = 'CNAME';
    const RECORD_TYPE_A = 'A';
    const RECORD_TYPE_AAAA = 'AAAA';
    const RECORD_TYPE_MX = 'MX';
    const RECORD_TYPE_SRV = 'SRV';
    const RECORD_TYPE_TXT = 'TXT';
    const RECORD_TYPE_SOA = 'SOA';
    const RECORD_TYPE_NS = 'NS';
    const RECORD_TYPE_PTR = 'PTR';
    const RECORD_TYPE_HINFO = 'HINFO';
    const RECORD_TYPE_RP = 'RP';
    const RECORD_TYPE_TLSA = 'TLSA';

    /**
     * Get Types
     *
     * @return array
     */
    public static function getRecordTypes()
    {
        return [
            self::RECORD_TYPE_A,
            self::RECORD_TYPE_AAAA,
            self::RECORD_TYPE_CNAME,
            self::RECORD_TYPE_HINFO,
            self::RECORD_TYPE_MX,
            self::RECORD_TYPE_NS,
            self::RECORD_TYPE_PTR,
            self::RECORD_TYPE_SOA,
            self::RECORD_TYPE_SRV,
            self::RECORD_TYPE_TLSA,
            self::RECORD_TYPE_TXT,
        ];
    }

    /**
     * Get Types with Priority
     *
     * @return array
     */
    public static function getRecordTypesWithPriority()
    {
        return [
            self::RECORD_TYPE_SRV,
            self::RECORD_TYPE_MX,
        ];
    }

    /**
     * Get Record Type Form Choice Options
     *
     * @param string $label
     *
     * @return array
     */
    public static function getRecordTypeFormChoiceOptions($label = 'Typ:')
    {
        return [
            'label'             => $label,
            'choices'           => array_combine(self::getRecordTypes(), self::getRecordTypes()),
            'choices_as_values' => true,
            'required'          => true,
            'constraints'       => [
                new NotBlank(),
            ],
        ];
    }

}