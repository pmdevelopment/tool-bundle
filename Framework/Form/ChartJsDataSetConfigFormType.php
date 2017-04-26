<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 26.04.2017
 * Time: 09:36
 */

namespace PM\Bundle\ToolBundle\Framework\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ChartJsDataSetConfigFormType
 *
 * @package PM\Bundle\ToolBundle\Framework\Form
 */
class ChartJsDataSetConfigFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lineTension', NumberType::class, [
                'label'       => 'label.chartjs.line_tension',
                'attr'        => [
                    'help_text' => 'placeholder.chartjs.line_tension',
                ],
                'constraints' => new NotBlank(),
            ])
            ->add('pointRadius', NumberType::class, [
                'label'       => 'label.chartjs.point_radius',
                'constraints' => new NotBlank(),
            ])
            ->add('pointHoverRadius', NumberType::class, [
                'label'       => 'label.chartjs.point_radius_hover',
                'constraints' => new NotBlank(),
            ])
            ->add('pointHitRadius', NumberType::class, [
                'label'       => 'label.chartjs.point_radius_hit',
                'constraints' => new NotBlank(),
            ])
            ->add('pointBorderColor', TextType::class, [
                'label'       => 'label.chartjs.point_border_color',
                'constraints' => new NotBlank(),
            ])
            ->add('pointHoverBorderColor', TextType::class, [
                'label'       => 'label.chartjs.point_hover_border_color',
                'constraints' => new NotBlank(),
            ])
            ->add('pointHoverBackgroundColor', TextType::class, [
                'label'       => 'label.chartjs.point_hover_background_color',
                'constraints' => new NotBlank(),
            ])
            ->add('borderColor', TextType::class, [
                'label'       => 'label.chartjs.border_color',
                'constraints' => new NotBlank(),
            ])
            ->add('borderWidth', NumberType::class, [
                'label'       => 'label.chartjs.border_width',
                'constraints' => new NotBlank(),
            ])
            ->add('backgroundColor', TextType::class, [
                'label'       => 'label.chartjs.background_color',
                'constraints' => new NotBlank(),
            ])
            ->add('save', SubmitType::class, [
                'label' => 'button.save',
            ]);
    }

}