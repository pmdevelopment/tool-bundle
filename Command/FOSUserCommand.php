<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.11.2016
 * Time: 16:36
 */

namespace PM\Bundle\ToolBundle\Command;


use FOS\UserBundle\Model\User;
use PM\Bundle\ToolBundle\Framework\Traits\Command\HasDoctrineCommandTrait;
use PM\Bundle\ToolBundle\Framework\Utilities\CommandUtility;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class FOSUserCommand
 *
 * @package PM\Bundle\ToolBundle\Command
 */
class FOSUserCommand extends ContainerAwareCommand
{
    use HasDoctrineCommandTrait;

    const NAME = 'pm:tool:fos-user';

    const ACTION_PASSWORD_REPLACE = 'password_replace';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Perform tasks for FOSUserBundle');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = new SymfonyStyle($input, $output);
        $helper->title('FOSUserBundle');

        $choices = [
            self::ACTION_PASSWORD_REPLACE => 'Replace all passwords (be careful!)',
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
        if (self::ACTION_PASSWORD_REPLACE === $choice) {
            return $this->actionPasswordReplace($helper);
        }

        throw new \RuntimeException(sprintf('Unknown choice %s', $choice));
    }

    /**
     * Password Replace action
     *
     * @param SymfonyStyle $helper
     *
     * @return null
     */
    private function actionPasswordReplace(SymfonyStyle $helper)
    {
        if (false === $helper->confirm('Are you sure? Only use this for development purposes!', false)) {
            return null;
        }

        $userClass = $this->getUserEntityClass();
        if (null === $userClass) {
            throw new \RuntimeException('User entity not found');
        }

        $password = $helper->ask('New password', 'login123');
        $userManager = $this->getContainer()->get('fos_user.user_manager');

        /** @var User $user */
        foreach ($this->getDoctrine()->getRepository($userClass)->findAll() as $user) {
            $user->setPlainPassword($password);
            $userManager->updateUser($user);
        }

        return null;
    }

    /**
     * Get User Entity Class
     *
     * @return null|string
     */
    private function getUserEntityClass()
    {
        /** @var ClassMetadata $meta */
        foreach ($this->getDoctrine()->getManager()->getMetadataFactory()->getAllMetadata() as $meta) {
            $reflectionClass = $meta->getReflectionClass();

            if (true === $this->hasAbstractParent($reflectionClass, User::class)) {
                return $meta->getName();
            }
        }

        return null;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string          $abstractClassName
     *
     * @return bool
     */
    private function hasAbstractParent($reflectionClass, $abstractClassName)
    {
        if (true === $reflectionClass->isAbstract() && $reflectionClass->getName() === $abstractClassName) {
            return true;
        }

        if (false !== $reflectionClass->getParentClass()) {
            return $this->hasAbstractParent($reflectionClass->getParentClass(), $abstractClassName);
        }

        return false;
    }
}