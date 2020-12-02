<?php

namespace Drupal\uwcoe_stories\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\uwcoe_stories\Traits\LoadsMedia;
use Drupal\Core\Block\BlockBase;
use Drupal\uwcoe_stories\Traits\BuildsRenderArrays;

/**
 * Provides a 'Hero' Block.
**/
class BackgroundBlockBase extends BlockBase {

  use LoadsMedia;
  use BuildsRenderArrays;

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#background' => [
        'type' => $this->configuration['background_type'] ?? NULL,
        'image' => $this->backgroundImage() ?? NULL,
        'color' => $this->configuration['background_color'] ?? NULL,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['background_type'] = [
      '#title' => 'Background type',
      '#type' => 'select',
      '#options' => [
        'image' => 'Background Image',
        'color' => 'Background Color',
      ],
      '#required' => 1,
      '#default_value' => $this->configuration['background_type'] ?? NULL,
    ];
    // Only show if 'Background Image' is selected for Header Type.
    $form['background_image_id'] = [
      '#title' => 'Background image',
      '#type' => 'media_library',
      '#allowed_bundles' => ['image'],
      '#default_value' => $this->configuration['background_image_id'] ?? NULL,
      '#description' => 'Upload or select a background image.',
      '#states' => [
        'visible' => [
          ':input[name="settings[background_type]"]' => ['value' => 'image'],
        ],
      ],
    ];
    // Only show if 'Background Color' is selected for Header Type.
    $form['background_color'] = [
      '#title' => 'Background Color',
      '#type' => 'color',
      '#states' => [
        'visible' => [
          ':input[name="settings[background_type]"]' => ['value' => 'color'],
        ],
      ],
      '#default_value' => $this->configuration['background_color'] ?? '#4b2e84',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();

    $headerType = $values['background_type'];
    $this->configuration['background_type'] = $headerType;

    if ($headerType === 'image') {
      $this->configuration['background_image_id'] = $values['background_image_id'];
      unset($this->configuration['background_color']);
    }
    elseif ($headerType === 'color') {
      $this->configuration['background_color'] = $values['background_color'];
      unset($this->configuration['background_image_id']);
    }
  }

  /**
   * Get an image's URL from its media ID.
   */
  protected function backgroundImage() {
    if (isset($this->configuration['background_image_id'])) {
      return $this->fileUrlFromMediaId($this->configuration['background_image_id']);
    }
    else {
      return NULL;
    }
  }

}
