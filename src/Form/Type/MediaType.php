<?php
namespace Vlabs\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vlabs\MediaBundle\Form\DataTransformer\MediaTransformer;

class MediaType extends AbstractType
{
    const MEDIA_IMAGE = 'image';
    const MEDIA_FILE = 'file';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $mediaFormType = [
            'image' => VichImageType::class,
            'file'  => VichFileType::class
        ][$options['media_type']];


        $attr = [];

        if($options['accept'] !== null){
            $attr['accept'] = $options['accept'];
        }

        $builder
            ->add('mediaFile', $mediaFormType, [
                'required' => false,
                'label'    => false,
                'allow_delete'  => $options['allow_delete'],
                'download_link' => $options['download_link'],
                'attr'          => $attr
            ])
            ->addModelTransformer(new MediaTransformer())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'media_type'    => 'image',
            'data_class'    => null,
            'allow_delete'  => true,
            'download_link' => true,
            'accept'        => null
        ]);
        $resolver->setAllowedTypes('allow_delete', 'boolean');
        $resolver->setAllowedTypes('download_link', 'boolean');
        $resolver->setAllowedTypes('accept', 'string');
        $resolver->setRequired(['media_type','data_class']);
        $resolver->setAllowedValues('media_type', [self::MEDIA_FILE, self::MEDIA_IMAGE]);
        $resolver->setAllowedTypes('data_class', 'string');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars = array_replace($view->vars, [
            'allow_delete' => $options['allow_delete'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'media';
    }
}
