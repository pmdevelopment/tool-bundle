<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.01.2017
 * Time: 10:10
 */

namespace PM\Bundle\ToolBundle\Command;


use PM\Bundle\ToolBundle\Framework\Events\CronEvent;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CronCommand
 *
 * @package PM\Bundle\ToolBundle\Command
 */
class CronCommand extends ContainerAwareCommand
{
    const NAME = 'pm:tool:cron';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Execute Cronjobs')
            ->addOption('repeat', 'r', InputOption::VALUE_OPTIONAL, 'Repeated interval for this cron. Leave empty to get choice.')
            ->addOption('target', 't', InputOption::VALUE_OPTIONAL, 'Target for specific cronjob. Optional.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = new SymfonyStyle($input, $output);
        $helper->title('Cron');

        $helper->warning('Work in progress!');

        $repeatAvailable = [
            CronEvent::REPEATED_DAILY_MORNING => 'Daily (Morning, 06:00)',
        ];

        $target = $input->getOption('target');
        $repeat = $input->getOption('repeat');

        if (null === $target) {
            if (null === $repeat || false === isset($repeatAvailable[$repeat])) {
                $repeat = $helper->choice('Repeated', $repeatAvailable);
            }

            $helper->section($repeatAvailable[$repeat]);
        } else {
            $helper->section(sprintf('Executing target %s', $target));
        }


        $event = new CronEvent($repeat, $helper, $target);
        $this->getContainer()->get('event_dispatcher')->dispatch(CronEvent::NAME, $event);
    }

}