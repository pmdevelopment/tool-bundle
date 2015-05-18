<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 18.05.15
 * Time: 14:56
 */

namespace PM\Bundle\ToolBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ImageFormType
 *
 * @package PM\Bundle\ToolBundle\Form
 */
class ImageFormType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return "image";
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", "text", array(
                'label'       => 'Name:',
                'constraints' => array(new NotBlank())
            ))
            ->add("fileName", "hidden", array(
                'attr' => array('class' => 'pm-imageupload-name')
            ))
            ->add("content", "hidden", array(
                'error_bubbling' => true,
                'constraints'    => array(new NotBlank(array("message" => "Es muss ein Bild hinzugefügt werden."))),
                'attr'           => array('class' => 'pm-imageupload-input')
            ));
    }

}