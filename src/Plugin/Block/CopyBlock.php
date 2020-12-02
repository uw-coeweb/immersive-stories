<?php

namespace Drupal\uwcoe_stories\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;
use Drupal\uwcoe_stories\Traits\BuildsRenderArrays;

/**
 * Provides a Copy Block.
 *
 * @Block(
 *   id = "uwcoe_stories__copy_block",
 *   admin_label = @Translation("Copy Block"),
 *   category = @Translation("UWCoE Immersive Stories"),
 * )
 */
class CopyBlock extends BlockBase {

  use BuildsRenderArrays;

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'uwcoe_stories_copy_block',
      '#content' => [
        'body' => [
          '#type' => 'processed_text',
          '#text' => $this->configuration['body']['value'] ?? NULL, 
          '#format' => $this->configuration['body']['format'] ?? NULL,
        ],
      ],
      '#attached' => [
        'library' => $this->buildLibraries(['uwcoe_stories/copy']),
      ],
    ];
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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();

    $this->configuration['body'] = $values['body'];
  }
}
