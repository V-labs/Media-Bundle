VlabsMediaBundle
================

Installation
------------

Type command

```
composer require vlabs/media-bundle
```

Update your .env with a VLABS_MEDIA_BASE_URL
```
#.env
###> vlabs/media-bundle ###
VLABS_MEDIA_BASE_URL=http://localhost
###< vlabs/media-bundle ###
```

Add the minimal configuration that makes the bundle work

```yaml
#config/packages/vlabs_media.yml
vlabs_media:
    default_base_url: '%env(PUBLIC_URL)%'
```

Add doctrine config for media class
```yaml
#config/packages/doctrine.yml
doctrine:
    orm:
        vlabs_media_vendor:
            type: xml
            dir: "%kernel.project_dir%/vendor/vlabs/media-bundle/config/mapping"
            prefix: Vlabs\MediaBundle\Entity
            alias: VlabsMediaBundle
            is_bundle: false
```

Update form theme with templates for media field if needed

```yaml
#config/packages/twig.yml
twig:
    form_themes:
        - '@VlabsMedia/Form/theme.html.twig'
```
You cant also copy/modify js and css file as needed and use them in yout templates :
- media-bundle/assets/media.css
- media-bundle/assets/media.js
- media-bundle/assets/media_collection.js
```html
<script type="application/javascript" defer src="{{ asset('bundles/media.js') }}"></script>
<script type="application/javascript" defer src="{{ asset('bundles/media_collection.js') }}"></script>
<script type="text/javascript">
    window.addEventListener("DOMContentLoaded", (event) => {
        MediaType.init(document)
        MediaCollectionType.init(document)

        document.querySelector('.media-collection').addEventListener('addentry', function(e){
            MediaType.initMedia(e.detail.entryNode)
        })
    })
</script>
```


Usage
------------

Create an entity wich extends `Vlabs\MediaBundle\Entity\Media` and provide an unique value for `getKey()`. This value will be used to resolve which flysystem config to use

```php
//App/Entity/ProfilePicture.pĥp

use Vlabs\MediaBundle\Entity\Media;

class ProfilePicture extends Media
{
    /**
     * @return string
     */
    public function getKey(): string
    {
        return 'profile_picture';
    }
}
```

If you use a collection of media you should add index-by="id" on inverseSide

```xml
<!--config/mapping/Profile.orm.xml-->
<one-to-one
    field="profilePicture"
    target-entity="App\Entity\ProfilePicture"
    orphan-removal="true"
>
    <cascade>
        <cascade-all/>
    </cascade>
</one-to-one>
<!--OR-->
<one-to-many
    field="profilePictures"
    target-entity="App\Entity\ProfilePicture"
    mapped-by="profile"
    orphan-removal="true"
    index-by="id"
>
    <cascade>
        <cascade-all/>
    </cascade>
</one-to-many>
```

Create vichuploader mapping for your entity
```xml
<!--App/Entity/ProfilePicture.xml-->
<vich_uploader class="App\Entity\ProfilePicture">
    <field
        mapping="profile_picture"
        name="mediaFile"
        filename_property="filename"
    />
</vich_uploader>
```

Create a flystem adapter and a mount point to create a folder for your upload, for example :

```yaml
#config/packages/oneup_flysystem.yaml
oneup_flysystem:
    adapters:
        uploads_adapter:
            local:
                directory: "%kernel.project_dir%/public/uploads"
                permissions:
                    file:
                        public: 0664
                        private: 0600
                    dir:
                        public: 0775
                        private: 0700
    filesystems:
        profile_picture_fs:
            adapter: uploads_adapter
            mount: profile_picture
            disable_asserts: true
```

Modify vich_uploader config for loading vich mapping
```yaml
#config/packages/vich_uploader.yaml
vich_uploader:
    db_driver: orm
    storage: flysystem
    metadata:
        auto_detection: false
        directories:
            #Where your vich mapping files are located
            - { path: "%kernel.project_dir%/src/Entity", namespace_prefix: App\Entity } 
    mappings:
        profile_picture: # should be the same as the getKey() of your entity
            #prefix used in front of filename (in most case the same as adapter directory without /public)
            uri_prefix: /uploads 
            upload_destination: profile_picture # should be the same as mount value of the flysystem to use
            namer: Vich\UploaderBundle\Naming\SmartUniqueNamer
```
Modify vlabs_media config to handle your new entity
```yaml
#config/packages/vlabs_media.yaml
vlabs_media:
    resize:
        profile_picture:
            base_url: "%env(VLABS_MEDIA_BASE_URL)%"
            queuing: sync #Define wich queue you want to use (@see Vlabs\MediaBundle\Queuing)
            filesystem: profile_picture # should be the same as mount value of the flysystem to use
            thumbs: # configure multiple resizes as needed
                thumb:
                    uri_prefix: /thumb
                    size: { width: 480, height: 480 }
                    mode: inset
                thumb_large:
                    uri_prefix: /thumb_large
                    size: { width: 1024, height: 768 }
                    mode: inset
```
