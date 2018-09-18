<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 18.09.2018
 * Time: 12:14
 */

namespace PM\Bundle\ToolBundle\Components\Traits;


use PM\Bundle\ToolBundle\Components\Interfaces\ResponseServiceInterface;

/**
 * Trait HasResponseServiceTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasResponseServiceTrait
{
    /**
     * @var ResponseServiceInterface
     */
    private $responseService;

    /**
     * @return ResponseServiceInterface
     */
    public function getResponseService()
    {
        if (null === $this->responseService) {
            throw new \LogicException(sprintf('%s missing. Setter not called?', ResponseServiceInterface::class));
        }

        return $this->responseService;
    }

    /**
     * @param ResponseServiceInterface $responseService
     *
     * @return HasResponseServiceTrait
     */
    public function setResponseService($responseService)
    {
        $this->responseService = $responseService;

        return $this;
    }

}