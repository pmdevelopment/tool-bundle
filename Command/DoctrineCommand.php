<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.11.2016
 * Time: 17:07
 */

namespace PM\Bundle\ToolBundle\Command;

use PM\Bundle\ToolBundle\Framework\Traits\Command\HasDoctrineCommandTrait;
use PM\Bundle\ToolBundle\Framework\Utilities\CommandUtility;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * Class DoctrineCommand
 *
 * @package PM\Bundle\ToolBundle\Command
 */
class DoctrineCommand extends ContainerAwareCommand
{
    use HasDoctrineCommandTrait;

    const NAME = 'pm:tool:doctrine';

    const ACTION_DATABASE_IMPORT = 'database_import';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Perform tasks for Doctrine');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = new SymfonyStyle($input, $output);
        $helper->title('Doctrine');

        $choices = [
            self::ACTION_DATABASE_IMPORT => 'Import database from remote host',
        ];

        $todo = $helper->choice('Select action', $choices);

        $helper->newLine(4);
        $helper->section($choices[$todo]);

        $this->executeChoice($helper, $todo);

        CommandUtility::writeFinishedMessage($helper, self::NAME);
    }

    /**
     * Execute Choice
     *
     * @param SymfonyStyle $helper
     * @param string       $choice
     *
     * @return null
     */
    private function executeChoice(SymfonyStyle $helper, $choice)
    {
        if (self::ACTION_DATABASE_IMPORT === $choice) {
            return $this->actionDatabaseImport($helper);
        }

        throw new \RuntimeException(sprintf('Unknown choice %s', $choice));
    }

    /**
     * Database Import
     *
     * @param SymfonyStyle $helper
     *
     * @return null
     */
    private function actionDatabaseImport(SymfonyStyle $helper)
    {
        $timestamp = date('ymdHi');
        /*
         * Select Host
         */
        $remoteHost = $helper->ask('Remote host');

        /*
         * Select external database (default: database_name)
         */
        $remoteDatabase = $helper->ask('Remote database', $this->getContainer()->getParameter('database_name'));

        /*
         * Select local database (default: database_name)
         */
        $localDatabase = $helper->ask('New local database', sprintf('%s_%s', $this->getContainer()->getParameter('database_name'), $timestamp));

        /*
         * mysqldump
         */
        $fileName = sprintf('%s_%s_%s.sql', $remoteDatabase, $timestamp, uniqid());

        $command = sprintf('ssh %s "mysqldump %s > %s"', $remoteHost, $remoteDatabase, $fileName);
        $helper->comment($command);

        $process = new Process($command);
        $process->setTimeout(600);

        $process->run(function ($type, $buffer) use ($helper) {
            if (Process::ERR === $type) {
                $helper->error($buffer);

                if (false !== strpos($buffer, 'Access denied')) {
                    $helper->note('Missing .my.cnf for mysql access?');
                }
            } else {
                $helper->comment($buffer);
            }
        });

        /*
         * scp
         */
        $command = sprintf('scp %s:%s %s/%s', $remoteHost, $fileName, sys_get_temp_dir(), $fileName);
        $helper->comment($command);

        $process = new Process($command);
        $process->setTimeout(300);

        $process->run(function ($type, $buffer) use ($helper) {
            if (Process::ERR === $type) {
                $helper->error($buffer);
            } else {
                $helper->comment($buffer);
            }
        });

        /*
         * create database database_name_ymdHis
         * if exists: ask for drop
         */
        $command = sprintf('mysql -e "CREATE DATABASE %s;"', $localDatabase);
        $helper->comment($command);

        $process = new Process($command);
        $process->run(function ($type, $buffer) use ($helper) {
            if (Process::ERR === $type) {
                $helper->error($buffer);

                if (false !== strpos($buffer, 'Access denied')) {
                    $helper->note('Missing .my.cnf for mysql access?');
                }
            } else {
                $helper->comment($buffer);
            }
        });

        /*
         * mysql -D database_name_ymdhis < /backup/y-m-d_H-i-s_unique.sql
         */
        $command = sprintf('mysql -D %s < %s/%s', $localDatabase, sys_get_temp_dir(), $fileName);
        $helper->comment($command);

        $process = new Process($command);
        $process->setTimeout(3600);

        $process->run(function ($type, $buffer) use ($helper) {
            if (Process::ERR === $type) {
                $helper->error($buffer);
            } else {
                $helper->comment($buffer);
            }
        });

        /**
         * Remove
         */
        $command = sprintf('ssh %s "rm %s"', $remoteHost, $fileName);
        $helper->comment($command);

        $process = new Process($command);
        $process->run(function ($type, $buffer) use ($helper) {
            if (Process::ERR === $type) {
                $helper->error($buffer);
            } else {
                $helper->comment($buffer);
            }
        });

        $command = sprintf('%s/%s', sys_get_temp_dir(), $fileName);
        $helper->comment(sprintf('unlink %s', $command));
        unlink($command);

        /**
         * Success! New database name:
         */
        $helper->success(
            [
                'Created new Database',
                $localDatabase,
            ]
        );

        return null;
    }
}