<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.04.2018
 * Time: 10:05
 */

namespace PM\Bundle\ToolBundle\Command;

use PM\Bundle\ToolBundle\Components\Helper\DoctrineHelper;
use PM\Bundle\ToolBundle\Components\Helper\OpenSSLHelper;
use PM\Bundle\ToolBundle\Components\Traits\HasDoctrineTrait;
use PM\Bundle\ToolBundle\EventListener\EncryptionSubscriber;
use PM\Bundle\ToolBundle\Framework\Annotations\Encrypted;
use PM\Bundle\ToolBundle\Framework\Annotations\Encryption;
use PM\Bundle\ToolBundle\Framework\Utilities\CryptUtility;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class MigrationCommand
 *
 * @package PM\Bundle\ToolBundle\Command
 */
class MigrationCommand extends ContainerAwareCommand
{
    use HasDoctrineTrait;

    const NAME = 'pm:tool:migration';

    const MIGRATE_ANNOTATION_ENCRYPTION = 'annotation_encryption';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Migrate after major changes for this tool bundle');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = new SymfonyStyle($input, $output);

        $choices = [
            self::MIGRATE_ANNOTATION_ENCRYPTION => 'Switch encrypted annotation to encryption',
        ];

        $choice = $helper->choice('What migration are you looking for?', $choices);

        if (self::MIGRATE_ANNOTATION_ENCRYPTION === $choice) {
            return $this->migrateAnnotationEncryption($helper);
        }

    }

    /**
     * Migrate Annotation Encrypted to Encryption
     *
     * @param SymfonyStyle $helper
     *
     * @return bool
     */
    private function migrateAnnotationEncryption(SymfonyStyle $helper)
    {
        $helper->note('The encrypted annotation uses mcrypt and therefore is deprecated. This migration will identify all entities using the old annotation, decrpyting with the old method and encrypt the value for the encryption annotation. After execution you have to replace the annotation.');

        /** @var \ReflectionProperty[] $todo */
        $todo = [];
        $classesWithoutNewAnnotation = [];

        $annotationsReader = $this->getContainer()->get('annotations.reader');

        /* Get entites */
        $classes = DoctrineHelper::getAllEntityClassNames($this->getDoctrineManager());
        $helper->comment(sprintf('Found %d entities', count($classes)));

        /* Search for annotation */
        foreach ($classes as $className) {
            $properties = DoctrineHelper::getPropertiesByAnnotation($className, Encrypted::class, $annotationsReader);

            if (0 < count($properties)) {
                $todo[$className] = $properties;
            }
        }

        if (0 === count($todo)) {
            $helper->success('Nothing to do.');

            return true;
        }

        $helper->comment(sprintf('%d entities use the encrypted annotation', count($todo)));

        /* Disable Listener */
        $subscriber = DoctrineHelper::getEventListener(EncryptionSubscriber::class, $this->getDoctrineManager());
        if (null === $subscriber) {
            throw new \LogicException('Event Subscriber not found.');
        }

        $this->getDoctrineManager()->getEventManager()->removeEventSubscriber($subscriber);

        $secret = $this->getContainer()->getParameter('pm__tool.configuration.doctrine.encryption');
        $helper->comment(sprintf('Secret: %s', $secret));

        $secretKey = substr(hash('sha256', $secret), 1, 32);

        /* Get Values */
        foreach ($todo as $className => $properties) {
            $helper->section($className);

            $tableHeader = [
                '#',
            ];
            /** @var \ReflectionProperty $property */
            foreach ($properties as $property) {
                $tableHeader[] = $property->getName();

                if (false === DoctrineHelper::hasPropertyAnnotation($property, Encryption::class, $annotationsReader) && false === in_array($className, $classesWithoutNewAnnotation)) {
                    $classesWithoutNewAnnotation[] = $className;
                }
            }

            $tableValues = [];

            $entites = $this->getDoctrine()->getRepository($className)->findAll();
            foreach ($entites as $entity) {
                $tableRow = [
                    $entity->getId(),
                ];

                foreach ($properties as $property) {
                    $tableRow[] = CryptUtility::decrypt($property->getValue($entity), $secretKey);
                }

                $tableValues[] = $tableRow;
            }

            $helper->table($tableHeader, $tableValues);
        }

        /* Warnings for missing new annotation */
        if (0 < count($classesWithoutNewAnnotation)) {
            $helper->warning(
                array_merge(
                    [
                        'The following classes have properties without the new annotation:',
                    ],
                    $classesWithoutNewAnnotation
                )
            );
        }

        /* Are you sure? */
        if (false === $helper->confirm('Are you sure to reencrypt the shown values?', false)) {
            $helper->warning('Cancelled');

            return false;
        }

        /* Encrypt values */
        foreach ($todo as $className => $properties) {
            $entites = $this->getDoctrine()->getRepository($className)->findAll();
            foreach ($entites as $entity) {

                foreach ($properties as $property) {
                    /** @var Encryption|null $annotation */
                    $annotation = DoctrineHelper::getPropertyAnnotation($property, Encryption::class, $annotationsReader);
                    if (null === $annotation) {
                        $cipher = OpenSSLHelper::CIPHER_AES_256_CBC;
                    } else {
                        $cipher = $annotation->cipher;
                    }

                    $value = CryptUtility::decrypt($property->getValue($entity), $secretKey);

                    $property->setValue($entity, OpenSSLHelper::encrypt($value, $secretKey, $cipher));
                }

                $this->getDoctrineManager()->persist($entity);
            }
        }

        $this->getDoctrineManager()->flush();

        $helper->success('Values changed. Please remove the old annotations now.');

        return true;
    }

}