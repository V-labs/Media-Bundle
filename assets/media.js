const DEFAULT_COMPONENT_OPTIONS = {
  figureSelector:   '.media',
  removeSelector:   '.media_remove',
  browseSelector:   '.media_browse',
  downloadSelector: '.media_download'
}

const MediaType = {

  eventListeners: [],

  init: function (node = document) {
    let mediaNodes = node.querySelectorAll('[data-component="media"]');

    if (!mediaNodes.length) {
      return;
    }

    this.clearEventListeners();

    mediaNodes.forEach(mediaNode => {
      this.initMedia(mediaNode)
    })
  },

  initMedia: function (mediaNode) {
    const fileInput    = mediaNode.querySelector('input[type="file"]')
    const mediaOptions = Object.assign({}, DEFAULT_COMPONENT_OPTIONS, mediaNode.dataset.componentOptions ? JSON.parse(mediaNode.dataset.componentOptions) : {});
    const browseBtn    = mediaNode.querySelector(mediaOptions.browseSelector)
    const removeBtn    = mediaNode.querySelector(mediaOptions.removeSelector)

    const handleFileChange = (file) => {
      if (file) {
        const isImage = file.type.includes('image')
        let url       = URL.createObjectURL(file);
        if (isImage) {
          mediaNode.querySelector(mediaOptions.downloadSelector).style.backgroundImage = `url(${url})`;
        } else {
          mediaNode.querySelector(mediaOptions.downloadSelector).style.backgroundImage = null;
          mediaNode.querySelector('small').innerHTML = file.name;
        }

        mediaNode.querySelector(mediaOptions.downloadSelector).href          = url;
        mediaNode.querySelector(mediaOptions.downloadSelector).style.display = `flex`;
        browseBtn.style.display                                              = 'none';
        removeBtn.style.display                                              = 'flex';

        mediaNode.dispatchEvent(new CustomEvent('addfile', {
          bubbles:true,
          detail:{
            file: file
          }
        }))
      } else {
        fileInput.value                                                              = null
        mediaNode.querySelector(mediaOptions.downloadSelector).style.backgroundImage = null;
        mediaNode.querySelector(mediaOptions.downloadSelector).style.display         = `none`;
        browseBtn.style.display                                                      = 'flex';
        removeBtn.style.display                                                      = 'none';

        mediaNode.dispatchEvent(new CustomEvent('removefile', {
          bubbles:true,
          detail:{
            file: file
          }
        }))
      }
    }

    let inputAddListener = e => {
      const files = e.target.files
      if (files && files.length) {
        handleFileChange(files[0])
      }
    }

    fileInput.addEventListener('change', inputAddListener)
    this.eventListeners.push({el: fileInput, type: 'change', listener: inputAddListener});

    let removeBtnListener = e => {
      e.preventDefault();
      handleFileChange(null)
      const checkRemove = e.currentTarget.querySelector('input[type="checkbox"]')
      if (checkRemove) {
        checkRemove.checked = true;
      }
    }
    removeBtn.addEventListener('click', removeBtnListener)
    this.eventListeners.push({ el: removeBtn, type: 'click', listener: removeBtnListener });
  },

  clearEventListeners: function () {

    this.eventListeners.map(entry => {
      entry.el.removeEventListener(entry.type, entry.listener)
    })
    this.eventListeners = []
  }
}
