<?php

namespace Drupal\uwcoe_stories\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\uwcoe_stories\Plugin\Block\BackgroundBlockBase;

/**
 * Provides a 'Hero' Block.
 *
 * @Block(
 *   id = "uwcoe_stories__cta_block",
 *   admin_label = @Translation("Call to Action Block"),
 *   category = @Translation("UWCoE Immersive Stories"),
 * )
 */
class CallToActionBlock extends BackgroundBlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $ra = parent::build();
    return array_merge($ra, [
      '#theme' => 'uwcoe_stories_cta_block',
      '#content' => [
        'title_text' => $this->configuration['title_text'] ?? NULL,
        'body' => [
          '#type' => 'processed_text',
          '#text' => $this->configuration['body']['value'] ?? NULL,
          '#format' => $this->configuration['body']['format'] ?? NULL,
        ],
        'link_text' => $this->configuration['link_text'] ?? NULL,
        'link_url' => $this->configuration['link_url'] ?? NULL,
      ],
      '#attached' => [
        'library' => $this->buildLibraries(['uwcoe_stories/cta']),
      ],
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['title_text'] = [
      '#title' => 'Title',
      '#type' => 'textfield',
      '#required' => 1,
      '#default_value' => $this->configuration['title_text'] ?? 'Header text',
    ];
    $form['body'] = [
      '#title' => 'Body',
      '#type' => 'text_format',
      '#default_value' => $this->configuration['body']['value'] ?? NULL,
      '#format' => 'full_html',
      '#required' => 1,
    ];
    $form['link_text'] = [
      '#title' => 'Button text',
      '#type' => 'textfield',
      '#required' => 1,
      '#default_value' => $this->configuration['link_text'] ?? 'Click here',
    ];
    $form['link_url'] = [
      '#title' => 'Button link (must include https:// for external links)',
      '#type' => 'url',
      '#required' => 1,
      '#default_value' => $this->configuration['link_url'] ?? NULL,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();

    $this->configuration['title_text'] = $values['title_text'];
    $this->configuration['body'] = $values['body'];
    $this->configuration['link_text'] = $values['link_text'];
    $this->configuration['link_url'] = $values['link_url'];
  }

}
