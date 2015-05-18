# Image Upload

Simple HTML5 Image Upload. Images are saved within the entity;

## Form

```php
    class ImageFormType extends \PM\Bundle\ToolBundle\Form\ImageFormType
    {

        /**
         * @param FormBuilderInterface $builder
         * @param array                $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            parent::buildForm($builder, $options);
    
    
            $builder
                //->add(...)
                ->add('save', 'submit', array(
                    'label' => 'Save'
                ));
        }
    
        /**
         * @return string
         */
        public function getName()
        {
            return "image";
        }

    }
```

## JavaScript

Add the JavaScript to the edit template. It will initialize itself and import the stylesheet. jQuery is required.

```twig
    <script type="text/javascript" src="{{ asset("bundles/pmtool/pm-imageupload/js/pm-imageupload.jquery.js") }}"></script>
```

## Use Image

To embed the image inside a twig template, call the getContent() method.

```twig
    <img src="{{ image.content }}" />
```

## Get Image using specific Dimension

Comming soon

