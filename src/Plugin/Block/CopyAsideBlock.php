<?php

namespace Drupal\uwcoe_stories\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\uwcoe_stories\Traits\BuildsRenderArrays;

/**
 * Provides a Copy Block.
 *
 * @Block(
 *   id = "uwcoe_stories__copy__aside_block",
 *   admin_label = @Translation("Copy Block with Aside"),
 *   category = @Translation("UWCoE Immersive Stories"),
 * )
 */
class CopyAsideBlock extends BlockBase {

  use BuildsRenderArrays;

  /**
   * {@inheritdoc}
   */
  public function build() {
    $ra = [
      '#theme' => 'uwcoe_stories_copy_aside_block',
      '#content' => [
        'body' => [
          '#type' => 'processed_text',
          '#text' => $this->configuration['body']['value'] ?? NULL,
          '#format' => $this->configuration['body']['format'] ?? NULL,
        ],
      ],
      '#attached' => [
        'library' => $this->buildLibraries(['uwcoe_stories/copy', 'uwcoe_stories/copy_aside']),
      ],
    ];

    if (isset($this->configuration['aside_body'])) {
      $ra['#aside'] = [
        'body' => [
          '#type' => 'processed_text',
          '#text' => $this->configuration['aside_body']['value'] ?? NULL,
          '#format' => $this->configuration['aside_body']['format'] ?? NULL,
        ],
      ];
    }
    return $ra;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['body'] = [
      '#title' => 'Body',
      '#type' => 'text_format',
      '#default_value' => $this->configuration['body']['value'] ?? NULL,
      '#format' => 'full_html',
      '#required' => 1,
    ];

    $form['aside_body'] = [
      '#title' => 'Aside Text (HTML Allowed)',
      '#type' => 'text_format',
      '#default_value' => $this->configuration['aside_body']['value'] ?? NULL,
      '#format' => 'full_html',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();

    $this->configuration['body'] = $values['body'];

    if (isset($values['aside_body'])) {
      $this->configuration['aside_body'] = $values['aside_body'];
    }
  }

}
