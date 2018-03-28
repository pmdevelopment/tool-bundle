<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 02.03.2018
 * Time: 10:44
 */

namespace PM\Bundle\ToolBundle\Components\Helper;

use Composer\Script\Event;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * Class ComposerHelper
 *
 * @package PM\Bundle\ToolBundle\Components\Helper
 */
class ComposerHelper
{
    /**
     * Execute Command
     *
     * @param Event  $event
     * @param string $consoleDir
     * @param string $cmd
     * @param int    $timeout
     */
    public static function executeCommand(Event $event, $consoleDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(static::getPhp(false));
        $phpArgs = implode(' ', array_map('escapeshellarg', static::getPhpArguments()));
        $console = escapeshellarg($consoleDir . '/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php . ($phpArgs ? ' ' . $phpArgs : '') . ' ' . $console . ' ' . $cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) use ($event) {
            $event->getIO()->write($buffer, false);
        });

        if (false === $process->isSuccessful()) {
            throw new \RuntimeException(sprintf("An error occurred when executing the \"%s\" command:\n\n%s\n\n%s", escapeshellarg($cmd), self::removeDecoration($process->getOutput()), self::removeDecoration($process->getErrorOutput())));
        }
    }

    /**
     * Get PHP Path
     *
     * @param bool $includeArgs
     *
     * @return false|string
     */
    public static function getPhp($includeArgs = true)
    {
        $phpFinder = new PhpExecutableFinder();
        if (false === ($phpPath = $phpFinder->find($includeArgs))) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }

    /**
     * Get PHP Arguments
     *
     * @return array
     */
    public static function getPhpArguments()
    {
        $ini = null;
        $arguments = [];

        $phpFinder = new PhpExecutableFinder();
        if (method_exists($phpFinder, 'findArguments')) {
            $arguments = $phpFinder->findArguments();
        }

        if ($env = getenv('COMPOSER_ORIGINAL_INIS')) {
            $paths = explode(PATH_SEPARATOR, $env);
            $ini = array_shift($paths);
        } else {
            $ini = php_ini_loaded_file();
        }

        if (false !== $ini) {
            $arguments[] = '--php-ini=' . $ini;
        }

        return $arguments;
    }

    /**
     * Get Console Dir
     *
     * @return null|string
     */
    public static function getConsoleDir()
    {
        $rootDir = sprintf('%s/../../../../../../../..', __DIR__);

        $paths = [
            'bin',
            'app',
        ];

        foreach ($paths as $path) {
            $fullPath = sprintf('%s/%s', $rootDir, $path);

            if (true === is_dir($fullPath) && true === file_exists(sprintf('%s/console', $fullPath))) {
                return $fullPath;
            }
        }

        return null;
    }

    /**
     * Write
     *
     * @param Event  $event
     * @param string $message
     */
    public static function write(Event $event, $message)
    {
        $event->getIO()->write(sprintf("\n    %s\n", $message));
    }
}