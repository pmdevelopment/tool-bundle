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
        /*
         * Select Host
         */

        /*
         * Select external database (default: database_name)
         */

        /*
         * Select local database (default: database_name)
         */

        /*
         * mysqldump database_name > Y-m-d_H-i-s_unique.sql
         */

        /*
         * scp Y-m-d_H-i-s_unique.sql  /backup/Y-m-d_H-i-s_unique.sql
         */

        /*
         * create database database_name_Ymdhis
         * if exists: ask for drop
         */

        /*
         * mysql -D database_name_Ymdhis < /backup/Y-m-d_H-i-s_unique.sql
         */

        /**
         * Success! New database name:
         */

        return null;
    }
}