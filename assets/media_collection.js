const MediaCollectionType = {

  eventListeners: [],

  init: function (node = document) {
    this.clearEventListeners();
    this.initCollections(node.querySelectorAll('[data-prototype]'))
  },

  initCollections: function (collections) {
    collections.forEach(collection => {
      this.initCollection(collection)
    })
  },

  initCollection: function(collection){
    const container = collection.querySelector(collection.dataset.prototypeContainer)

    const add = () => {
      this.add(collection)
    }

    let addListener = e => {
      e.detail.entryNode.querySelector('input[type="file"]').click();
      this.initEntry(collection, e.detail.entryNode)
    }

    collection.addEventListener('addentry', addListener)
    this.eventListeners.push({ el: collection, type: 'addentry', listener: addListener });

    collection.querySelectorAll(`[data-add-collection-entry="${collection.dataset.prototypeContainer}"]`).forEach(button => {
      button.addEventListener('click', add);
      this.eventListeners.push({
        el: button,
        type: 'click',
        listener: add
      });
    })

    container.childNodes.forEach(entry => {
      this.initEntry(collection, entry)
    })
  },

  initEntry: function(collection, entry){
    const remove = () => {
      this.remove(collection, entry)
    }
    entry.addEventListener('removefile', remove);
    this.eventListeners.push({
      el: entry,
      type: 'click',
      listener: remove
    });
  },

  add: function(collection){
    const container      = collection.querySelector(collection.dataset.prototypeContainer)
    const prototype      = collection.dataset.prototype
    const prototype_name = collection.dataset.prototypeName || '__name__'

    const template = document.createElement('template');
    template.insertAdjacentHTML('afterbegin', prototype.replace(new RegExp(prototype_name, "g"), ''));
    const node = template.firstElementChild;
    container.appendChild(node);

    collection.dispatchEvent(new CustomEvent('addentry', {
      bubbles:false,
      detail:{
        index: container.children.length,
        entryNode: node
      }
    }))
  },

  remove: function(collection, node){
    const container = collection.querySelector(collection.dataset.prototypeContainer)
    const index     = Array.prototype.indexOf.call(container, node)
    container.removeChild(node)

    collection.dispatchEvent(new CustomEvent('removeentry', {
      bubbles:false,
      detail:{
        index,
        entryNode: node
      }
    }))
  },

  clearEventListeners: function () {
    this.eventListeners.map(entry => {
      entry.el.removeEventListener(entry.type, entry.listener)
    })
    this.eventListeners = []
  }
}
