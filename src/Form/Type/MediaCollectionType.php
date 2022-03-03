<?php
namespace Vlabs\MediaBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vlabs\MediaBundle\Form\Type\MediaType;
use Vlabs\MediaBundle\MediaInterface;

final class MediaCollectionType extends CollectionType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'entry_type'   => MediaType::class,
            'by_reference' => false,
            'allow_add'    => true,
            'allow_delete' => true,
            'delete_empty' => function (?MediaInterface $media) {
                return null === $media;
            }
        ]);

        $resolver->setDefined([
            'entry_type',
            'delete_empty',
            'by_reference',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'media_collection';
    }
}
