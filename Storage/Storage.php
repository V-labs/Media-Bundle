<?php

namespace Vlabs\MediaBundle\Storage;

use League\Flysystem\MountManager;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;
use Vich\UploaderBundle\Storage\FlysystemStorage;

class Storage extends FlysystemStorage
{
    /** @var MountManager */
    private $mountManager;

    /**
     * Storage constructor.
     *
     * @param PropertyMappingFactory $factory
     * @param                        $registry
     * @param MountManager           $mountManager
     */
    public function __construct(PropertyMappingFactory $factory, $registry, MountManager $mountManager)
    {
        $this->mountManager = $mountManager;

        parent::__construct($factory, $registry);
    }

    public function getFileSystemFromKey($key)
    {
        return $this->mountManager->getFilesystem($key);
    }
}
