<?php

namespace Vlabs\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Vlabs\MediaBundle\MediaInterface;

final class MediaTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed|void
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @return MediaInterface|null
     */
    public function reverseTransform($value): ?MediaInterface
    {
        if (is_null($value)) {
            return null;
        }

        if (!$value instanceof MediaInterface) {
            throw new TransformationFailedException();
        }

        if (is_null($value->getMediaFile()) && is_null($value->getFilename())) {
            return null;
        }

        if (!is_null($value->getMediaFile())) {
            $value->setFilename(null);
            $value->setMimeType(null);
            $value->setPath(null);
        }

        return $value;
    }
}
