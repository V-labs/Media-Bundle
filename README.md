VlabsMediaBundle
================

Installation
------------

Type command

```
composer require vlabs/media-bundle
```

Update AppKernel.php

```
$bundles = [
    ...
    new Oneup\FlysystemBundle\OneupFlysystemBundle(),
    new Vich\UploaderBundle\VichUploaderBundle(),
    new Vlabs\MediaBundle\VlabsMediaBundle(),
];
```

Update config.yml

```
...

vlabs_media:
    default_base_url: http://[cdn_host or other]
```
