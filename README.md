# UWCoe Immersive Stories

### ** This repo is not actively maintained, does not receive regular attention from the College of Engineering Dean's Office Web Team, and is provided here for reference purposes only. Feel free to fork and modify this code as you see fit. This software is provided as-is, with no warrenty.  **

Requires: Drupal ^8.0

Requires: PHP ^7.0

Tested up to Drupal 8.9.2 and PHP 7.4

The Immersive Stories module for Drupal 8 provides block and layout plugins for use in Drupal 8's Layout Builder UI. Once the module is enabled you can use these plugins on any content type whose display is managed by Layout Builder.

The module assumes that the images on your site are managed via Drupal 8's core Media module.

## Installation
Install this module as you would any Drupal 8 module.

## Component Reference
- Hero Block
    - A large header section intended for display at the top of the page.
    - Options:
        - Title
        - Subtitle
        - Background (image|color)
        - Text Alignment (left|center|right)
- Header Block
    - Nearly identical in function to a Hero block, but with slightly smaller text and height. Intended for use at the beginning of a content section.
    - Options:
        - Title
        - Subtitle
        - Background (image|color)
        - Text Alignment (left|center|right)
- Copy Block
    - Provides a content section that can display rich text, using the standard Drupal 8 WYSIWYG editor
- Copy Block, With Aside
    - A standard Copy block, but with an additional side section that contains rich text
- Embedded Video Block
    - Displays a full-width embedded video, e.g from YouTube or Vimeo, with an optional caption
    - Options:
        - Video URL
        - Caption (optional)
- Image Collage block
    - Displays up to 3 images in a collage style, with one large image on the left, and 2 smaller images on the right, stacked on eachother.
    - Options:
        - Images (up to 3)
        - Caption (optional)
- Image Gallery block
    - Displays an image carousel, up to a maximum of ten, and a caption for each slide.
    - Options:
        - For each image:
            - Image
            - Caption (optional)
- Call to Action (CTA) Block
    - Displays a call to action card, presumably to be used at the bottom of a page. Users can include a link.
    - Options:
        - Title
        - Link URL
        - Link Text