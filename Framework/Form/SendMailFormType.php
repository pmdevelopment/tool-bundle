<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 20.10.2015
 * Time: 14:34
 */

namespace PM\Bundle\ToolBundle\Framework\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
     * @inheritDoc
     */
    public function getName()
    {
        return "pm_tool_framework_sendmail";
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("sender", "email", array(
                "label"       => "Absender:",
                "constraints" => array(new Email())
            ))
            ->add("recipient", "email", array(
                "label"       => "Empfänger:",
                "constraints" => array(new Email())
            ))
            ->add("subject", "text", array(
                "label"       => "Betreff:",
                "constraints" => array(new NotBlank())
            ))
            ->add("message", "textarea", array(
                "label"       => "Nachricht:",
                "constraints" => array(new NotBlank()),
                "attr"        => array("rows" => 10)
            ))
            ->add("senden", "submit");
    }


}