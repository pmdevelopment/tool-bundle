<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 20.10.2015
 * Time: 14:34
 */

namespace PM\Bundle\ToolBundle\Framework\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class SendMailFormType
 *
 * @package PM\Bundle\ToolBundle\Framework\Form
 */
class SendMailFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sender', EmailType::class, [
                'label'       => 'mail_sender',
                'constraints' => new Email(),
            ])
            ->add('recipient', EmailType::class, [
                'label'       => 'mail_recipient',
                'constraints' => new Email(),
            ])
            ->add('subject', TextType::class, [
                'label'       => 'mail_subject',
                'constraints' => new NotBlank(),
            ])
            ->add('message', TextareaType::class, [
                'label'       => 'mail_message',
                'constraints' => new NotBlank(),
                'attr'        => [
                    'rows' => $options['message_rows'],
                ],
            ])
            ->add('send', SubmitType::class, [
                'label' => 'button.send',
            ]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'message_rows' => 10,
            ]
        );
    }


}