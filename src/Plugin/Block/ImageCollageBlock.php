<?php

namespace Drupal\uwcoe_stories\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\uwcoe_stories\Traits\LoadsMedia;
use Drupal\uwcoe_stories\Traits\BuildsRenderArrays;

/**
 * Provides a 'Image Gallery' Block.
 *
 * @Block(
 *   id = "uwcoe_stories__image_collage_block",
 *   admin_label = @Translation("Image Collage Block"),
 *   category = @Translation("UWCoE Immersive Stories"),
 * )
 */
class ImageCollageBlock extends BlockBase {

  use BuildsRenderArrays;
  use LoadsMedia;

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'uwcoe_stories_image_collage_block',
      '#images' => $this->getImages(),
      '#caption' => $this->configuration['caption'],
      '#attached' => [
        'library' => $this->buildLibraries(['uwcoe_stories/image-collage']),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    foreach (['first', 'second', 'third'] as $number) {
      $key = 'image_' . $number . '_mid';
      $form[$key] = [
        '#title' => $number . ' Image',
        '#type' => 'media_library',
        '#allowed_bundles' => ['image'],
        '#default_value' => $this->configuration[$key] ?? NULL,
        '#description' => 'Upload or select an image.',
        '#required' => 1,
      ];
    }

    $form['caption'] = [
      '#title' => 'Caption',
      '#type' => 'textfield',
      '#default_value' => $this->configuration['caption'] ?? NULL,
      '#maxlength' => 512,
      '#required' => 0,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();

    foreach (['first', 'second', 'third'] as $number) {
      $key = 'image_' . $number . '_mid';
      $this->configuration[$key] = $values[$key];
    }

    if (isset($values['caption'])) {
      $this->configuration['caption'] = $values['caption'];
    }
  }

  /**
   * Get the URLs for our images.
   */
  protected function getImages() {

    $imageUrls = [];
    foreach (['first', 'second', 'third'] as $number) {
      $configKey = 'image_' . $number . '_mid';
      $imageUrls[$number] = $this->fileUrlFromMediaId($this->configuration[$configKey]);
    }
    return $imageUrls;
  }

}
