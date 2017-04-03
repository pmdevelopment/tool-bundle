<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 03.04.2017
 * Time: 13:59
 */

namespace PM\Bundle\ToolBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ConfigFormType
 *
 * @package PM\Bundle\ToolBundle\Form
 */
class ConfigFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', TextType::class, [
                'label'       => 'label.value',
                'constraints' => new NotBlank(),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'button.save',
            ]);
    }


}