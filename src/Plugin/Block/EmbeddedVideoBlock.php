<?php

namespace Drupal\uwcoe_stories\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\uwcoe_stories\Traits\BuildsRenderArrays;

/**
 * Provides a Copy Block.
 *
 * @Block(
 *   id = "uwcoe_stories__embedded_video_block",
 *   admin_label = @Translation("Embedded Video Block"),
 *   category = @Translation("UWCoE Immersive Stories"),
 * )
 */
class EmbeddedVideoBlock extends BlockBase {

  use BuildsRenderArrays;

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'uwcoe_stories_embedded_video_block',
      '#content' => [
        'caption' => [
          '#type' => 'processed_text',
          '#text' => $this->configuration['caption']['value'] ?? NULL,
          '#format' => $this->configuration['caption']['format'] ?? NULL,
        ],
        'video_src' => $this->configuration['video_src'] ?? NULL,
      ],
      '#attached' => [
        'library' => $this->buildLibraries(['uwcoe_stories/embedded-video']),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['caption'] = [
      '#title' => 'Caption',
      '#type' => 'text_format',
      '#default_value' => $this->configuration['caption']['value'] ?? NULL,
      '#format' => 'full_html',
      '#required' => 0,
    ];

    $form['video_src'] = [
      '#title' => 'Video Embed Link',
      '#type' => 'url',
      '#default_value' => $this->configuration['video_src'] ?? NULL,
      '#required' => 1,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();

    $this->configuration['caption'] = $values['caption'];
    $this->configuration['video_src'] = $values['video_src'];
  }
}
