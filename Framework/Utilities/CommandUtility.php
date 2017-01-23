<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 01.11.2016
 * Time: 15:56
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CommandUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class CommandUtility
{
    const MAX_STRING_LENGTH = 100;

    /**
     * Get Command Running count.
     *
     * Returns running php process count for given command name.
     *
     * @param string $name e.g. pm:bundle:command
     *
     * @return int
     */
    public static function getCommandRunningCount($name)
    {
        exec(sprintf("ps auxwww|grep '%s'|grep -v grep", $name), $output);

        if (false === is_array($output)) {
            return 1;
        }

        /*
         * Remove Crontab sh calls
         */
        foreach ($output as $lineIndex => $line) {
            if (false !== strpos($line, '/bin/sh -c')) {
                unset($output[$lineIndex]);
            }
        }

        return count($output);
    }

    /**
     * Get Entity Choice
     *
     * @param SymfonyStyle  $helper
     * @param array|mixed[] $entities
     * @param string        $question
     *
     * @return mixed
     */
    public static function getEntityChoice(SymfonyStyle $helper, $entities, $question = 'Please choose')
    {
        $choice = [];
        foreach ($entities as $entity) {
            $choice[$entity->getId()] = strval($entity);
        }

        $selection = $helper->choice($question, $choice);
        $selectionId = array_search($selection, $choice);

        return CollectionUtility::find($entities, $selectionId);
    }

    /**
     * Write Finished Message
     *
     * @param SymfonyStyle $helper
     * @param string       $commandName
     */
    public static function writeFinishedMessage(SymfonyStyle $helper, $commandName)
    {
        $helper->newLine(2);

        $helper->note(
            [
                sprintf('Executed %s', $commandName),
                sprintf('Done within %s seconds', round((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]), 4)),
                date('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * Write associative array as table
     *
     * @param SymfonyStyle $helper
     * @param array        $array
     * @param bool         $flatten
     */
    public static function writeAssociativeArrayTable(SymfonyStyle $helper, $array, $flatten = true)
    {
        if (false === is_array($array)) {
            $helper->error(
                [
                    'Expecting associative array for table',
                    sprintf('Given data for table is %s.', gettype($array)),
                ]
            );
        }

        $rows = [];
        foreach ($array as $index => $value) {
            if (true === is_string($value)) {
                if (self::MAX_STRING_LENGTH < strlen($value)) {
                    $rows = array_merge($rows, self::getRowsFromLongString($index, $value));

                    continue;
                }

                $rows[] = [
                    $index,
                    $value,
                ];

                continue;
            }

            if (true === is_bool($value)) {
                if (true === $value) {
                    $value = 'TRUE';
                } else {
                    $value = 'FALSE';
                }
            } elseif (true === is_array($value)) {
                if (true === $flatten) {
                    $value = sprintf('Array (%d)', count($value));
                } else {
                    $value = json_encode($value);
                }
            } else {
                $value = print_r($value, true);
            }

            $rows[] = [
                $index,
                $value,
            ];
        }

        $helper->table([], $rows);
    }

    /**
     * Cuts string to multiple rows
     *
     * @param string $index
     * @param string $value
     *
     * @return array
     */
    public static function getRowsFromLongString($index, $value)
    {
        $rows = [
            [
                $index,
                substr($value, 0, self::MAX_STRING_LENGTH),
            ]
        ];

        $valueLength = strlen($value);
        $cutOff = self::MAX_STRING_LENGTH;

        while ($cutOff < $valueLength) {
            $rows[] = [
                '',
                substr($value, $cutOff, self::MAX_STRING_LENGTH),
            ];

            $cutOff += self::MAX_STRING_LENGTH;
        }

        return $rows;
    }
}