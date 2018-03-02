<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 02.03.2018
 * Time: 10:42
 */

namespace PM\Bundle\ToolBundle\Composer;

use Composer\Script\Event;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use PM\Bundle\ToolBundle\Components\Helper\ComposerHelper;

/**
 * Class ScriptHandler
 *
 * @package PM\Bundle\ToolBundle\Composer
 */
class ScriptHandler
{
    /**
     * Clears the Symfony cache.
     *
     * @param Event $event
     */
    public static function setup(Event $event)
    {
        $consoleDir = ComposerHelper::getConsoleDir();
        if (null === $consoleDir) {
            throw new \RuntimeException('Console not found');
        }

        /* Doctrine Migrations */
        if (true === class_exists(DoctrineMigrationsBundle::class)) {
            ComposerHelper::write($event, 'DoctrineMigrationsBundle exist. Start migrations.');

            ComposerHelper::executeCommand($event, $consoleDir, 'doctrine:migrations:migrate', 600);
        }
    }
}