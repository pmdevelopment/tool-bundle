<?php
namespace PM\Bundle\ToolBundle\Framework\Traits\Repositories;

/**
 * Class SluggableRepositoryTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Services
 */
trait SluggableRepositoryTrait
{

    /**
     * Find One By Slug
     *
     * @param string $slug
     * @param string $fieldName
     *
     * @return mixed
     */
    public function findOneBySlug($slug, $fieldName = 'slug')
    {
        return $this->findOneBy(
            [
                $fieldName => $slug,
                'deleted'  => false,
            ]
        );
    }

}