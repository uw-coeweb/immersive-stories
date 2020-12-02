"use strict";

const contentBlockId = 'block-uwcoe-system-main';

class FloatingMedia {
  constructor(media) {
    this.media = media;
    // Set the media container to the same dimensions as the image
    const image = media.querySelector('img');
    this.height = parseInt(image.getAttribute('height') || image.offsetHeight);
    this.width = parseInt(image.getAttribute('width') || image.offsetWidth);
    this.media.style.height = this.height;
    this.media.style.width = this.width;

    this.initMargin();
  }

  initMargin() {
    // Set the negative margin-left/right to the correct value
    // This will center the media images width-wise on either the left or right
    // edge of the container.
    if (parseInt(window.innerWidth) > 992) {
      if (this.media.classList.contains('align-right')) {
        this.media.style.setProperty('margin-right', (this.width / 2 * -1) + 'px');
        this.alignment = 'right';
      }
      else if (this.media.classList.contains('align-left')) {
        this.media.style.setProperty('margin-left', (this.width / 2 * -1) + 'px');
        this.alignment = 'left'
      }
      else {
        this.alignment = 'center';
      }
    } else {
      this.media.style.setProperty('margin-right', 'auto');
      this.media.style.setProperty('margin-left', 'auto');
    }
  }

  /**
   * Prevent the floating media objects from extending past the screen
   */
  handleWindowResize = () => {
    const windowSize = parseInt(window.innerWidth);
    const edgePadding = 40;
    const edgeRight = windowSize - edgePadding;
    const edgeLeft = edgePadding;
    const xMedia = parseInt(this.media.getBoundingClientRect().left);

    if ((xMedia + this.width) > edgeRight) {
      let overhang = (xMedia + this.width) - edgeRight;
      let currentMarginRight = parseInt(window.getComputedStyle(this.media).getPropertyValue('margin-right'));
      this.media.style.setProperty('margin-right', (currentMarginRight + overhang) + 'px');
    } else if (xMedia < edgeLeft) {
      let overhang = edgeLeft - xMedia;
      let currentMarginLeft = parseInt(window.getComputedStyle(this.media).getPropertyValue('margin-left'));
      this.media.style.setProperty('margin-left', (currentMarginLeft + overhang) + 'px');
    }
    else {
      this.initMargin();
    }
  }
}

/** 
 * Find any media objects in appropriate copy blocks and initialize them
*/
const handleDomChange = (mutationsList) => {
  for (let mutation of mutationsList) {
    if (mutation.type === 'childList' && mutation.target.id === contentBlockId) {
      const existingCopyBlocks = mutation.target.querySelectorAll('.uwcoe-stories-copy-body');
      for (let copyNode of existingCopyBlocks) {

        // When in the Edit Layout screen, newly added/changed blocks do not
        // go through the HOOK_theme_suggestions_alter hook, so we can't use 
        // the 'uwcoe-stories-media' class
        Array.from(copyNode.children).forEach((child) => {
          // Instead, just search for anything that has alignment classes from the WYSIWYG
          if (child.classList.contains('align-left') || child.classList.contains('align-right')) {
            new FloatingMedia(child);
          }
        })
      }
    }
  }
};

(window.onload = () => {
  // On the normal display page, we can just target this class on page load
  let mediaObjects = [];
  document.querySelectorAll('.uwcoe-stories-copy-body .uwcoe-stories-media').forEach((el) => {
    let media = new FloatingMedia(el);
    mediaObjects.push(media);
    media.handleWindowResize();

  });

  window.onresize = () => {
    mediaObjects.forEach((media) => {
      media.handleWindowResize();
    });
  };

  // On the edit page blocks are destroyed and reloaded without reloading
  // the page, so we need to watch the DOM for new/edited blocks directly
  const observer = new MutationObserver(handleDomChange);
  observer.observe(document.getElementById(contentBlockId), { childList: true });
})();
