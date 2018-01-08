<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.01.2017
 * Time: 10:10
 */

namespace PM\Bundle\ToolBundle\Command;


use PM\Bundle\ToolBundle\Framework\Events\CronEvent;
use PM\Bundle\ToolBundle\Framework\Utilities\CommandUtility;
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
        $helper->title(self::NAME);

        $repeatAvailable = [];

        foreach (CronEvent::getEventNames() as $name) {
            $repeatAvailable[$name] = $this->getContainer()->get('translator')->trans(sprintf('cronjob.%s', $name));
        }

        $target = $input->getOption('target');
        $repeat = $input->getOption('repeat');

        if (null === $target && null === $repeat) {
            $typeChoice = $helper->choice('How to choose cronjob(s)?', [
                'repeat'   => 'All with the same repetition',
                'listener' => 'One single listener',
            ]);

            if ('repeat' === $typeChoice) {
                $repeat = $helper->choice('Choose repetition', $repeatAvailable);
            } else {
                $listeners = $this->getContainer()->get('pm.cronjob')->getListenersWithoutType();
                $targetChoice = $helper->choice('Choose listener', $listeners);

                $target = $listeners[$targetChoice];
            }
        }

        if (null === $target) {
            $helper->section($repeatAvailable[$repeat]);
        } else {
            $helper->section($target);
        }

        $event = new CronEvent($repeat, $helper, $target, $input);

        $this->getEventDispatcher()->dispatch(CronEvent::NAME, $event);

        CommandUtility::writeFinishedMessage($helper, self::NAME);
    }

    /**
     * Get Event Dispatcher
     *
     * @return \Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher
     */
    private function getEventDispatcher()
    {
        return $this->getContainer()->get('event_dispatcher');
    }
}