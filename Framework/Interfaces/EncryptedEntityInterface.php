<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 23.03.2017
 * Time: 12:28
 */

namespace PM\Bundle\ToolBundle\Framework\Interfaces;

/**
 * Interface EncryptedEntityInterface
 *
 * @package PM\Bundle\ToolBundle\Framework\Interfaces
 *
 * @deprecated Convert to new Encryption annotation with HasEncryptedFieldsEntityInterface
 */
interface EncryptedEntityInterface
{
    /**
     * Is Encrypted?
     *
     * @return bool
     */
    public function isEncrypted();

    /**
     * Set Encrypted
     *
     * @param bool $encrypted
     *
     * @return $this
     */
    public function setEncrypted($encrypted);
}