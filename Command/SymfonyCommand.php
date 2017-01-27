<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 07.12.2016
 * Time: 14:45
 */

namespace PM\Bundle\ToolBundle\Command;


use PM\Bundle\ToolBundle\Framework\PMKernel;
use PM\Bundle\ToolBundle\Framework\Utilities\CommandUtility;
use PM\Bundle\ToolBundle\Framework\Utilities\SystemUtility;
use PM\Bundle\ToolBundle\Testing\Config\HelperConfig;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * Class SymfonyCommand
 *
 * @package PM\Bundle\ToolBundle\Command
 */
class SymfonyCommand extends ContainerAwareCommand
{
    const NAME = 'pm:tool:symfony';

    const ACTION_CACHE_CLEAR = 'cache_clear';
    const ACTION_TESTING_CLEAR = 'testing_clear';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Perform tasks for Symfony')
            ->addOption('action', 'a', InputOption::VALUE_OPTIONAL, 'Execute action');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = new SymfonyStyle($input, $output);
        $helper->title('Symfony');

        $choices = [
            self::ACTION_CACHE_CLEAR   => 'Clear custom cache',
            self::ACTION_TESTING_CLEAR => 'Clear testing output folder'
        ];

        $todo = $input->getOption('action');
        if (null === $todo || false === isset($choices[$todo])) {
            $todo = $helper->choice('Select action', $choices);
            $helper->newLine(4);
        }

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
        if (self::ACTION_CACHE_CLEAR === $choice) {
            return $this->actionCacheClear($helper);
        }

        if (self::ACTION_TESTING_CLEAR === $choice) {
            $helper->note('Removes all temporary files generated to save testing output.');

            return $this->actionTestingClear($helper);
        }

        throw new \RuntimeException(sprintf('Unknown choice %s', $choice));
    }

    /**
     * Clear Testing Cache
     *
     * @param SymfonyStyle $helper
     *
     * @return null
     */
    private function actionTestingClear(SymfonyStyle $helper)
    {
        array_map('unlink', glob(sprintf('%s/*.html', HelperConfig::getOutputCacheFolder())));

        return null;
    }

    /**
     * Clear cache
     *
     * @param SymfonyStyle $helper
     *
     * @return null
     */
    private function actionCacheClear(SymfonyStyle $helper)
    {
        $kernel = $this->getContainer()->get('kernel');

        if (!$kernel instanceof PMKernel) {
            $helper->note("Kernel doesn't use PMKernel. Cache should be regular");

            return null;
        }

        $user = SystemUtility::getCurrentUser();
        $dir = $kernel->getBaseTmpDir();

        $dirTillUser = [];
        foreach (explode(DIRECTORY_SEPARATOR, $dir) as $part) {
            if ($part === $user) {
                break;
            }

            $dirTillUser[] = $part;
        }

        $dir = implode(DIRECTORY_SEPARATOR, $dirTillUser);

        $command = sprintf('sudo rm -rf %s', $dir);
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

        return null;
    }

}