"use strict";

class ImageGallery {
  constructor(id) {
    const idSelector = '#' + id;

    this.slides = document.querySelectorAll(idSelector + ' .slide');
    this.indicators = document.querySelectorAll(idSelector + ' .nav-indicator');
    this.activeIndex = 0;
  
    document.querySelectorAll(idSelector + ' .nav-right').forEach((button) => {
      button.onclick = this.onRightNavClick;
    });
    document.querySelectorAll(idSelector + ' .nav-left').forEach((button) => {
      button.onclick = this.onLeftNavClick;
    });
  
    this.indicators.forEach((ind) => {
      ind.onclick = this.onIndicatorClick;
    });
  }

  /**
   * Go to a slide
   */
  onIndicatorClick = (e) => {
    const indicator = e.target;

    // Which indicator did we click on?
    const index = Array.from(this.indicators).indexOf(indicator);
    this.setActiveSlide(index);
  }

  /**
   * Move to the next slide
   */
  onRightNavClick = () => {
    this.setActiveSlide((this.activeIndex + 1) % this.slides.length);
  }

  /**
   * Move to the previous slide
   */
  onLeftNavClick = () => {
    if (this.activeIndex === 0) {
      this.activeIndex = this.slides.length;
    }
    this.setActiveSlide((this.activeIndex - 1) % this.slides.length);
  }

  /**
   * set the active slide
   */
  setActiveSlide(index) {
    this.indicators.forEach((ind) => {
      ind.classList.remove('active');
      
    });
    this.slides.forEach((slide) => {
      slide.classList.remove('active');
    });
    this.indicators[index].classList.add('active');
    this.slides[index].classList.add('active');
    this.activeIndex = index;
  }
};

// Find all the image galleries on the page and bind an ImageGallery to them
document.querySelectorAll('.uwcoe-stories-image-gallery').forEach((gallery) => {
  const id = gallery.id;
  new ImageGallery(id);
});