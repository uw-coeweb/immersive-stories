<?php

namespace Drupal\uwcoe_stories\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Hero' Block.
 *
 * @Block(
 *   id = "uwcoe_stories__hero_block",
 *   admin_label = @Translation("Hero Block"),
 *   category = @Translation("UWCoE Immersive Stories"),
 * )
 */
class HeroBlock extends BackgroundBlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $ra = parent::build();
    return array_merge($ra, [
      '#theme' => 'uwcoe_stories_hero_block',
      '#content' => [
        'title_text' => $this->configuration['title_text'] ?? NULL,
        'intro_text' => $this->configuration['intro_text'] ?? NULL,
        'text_align' => $this->configuration['text_align'] ?? NULL,
      ],
      '#attached' => [
        'library' => $this->buildLibraries(['uwcoe_stories/hero']),
      ],
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    return array_merge($form, [
      'title_text' => [
        '#title' => 'Header Text',
        '#type' => 'textfield',
        '#required' => 1,
        '#default_value' => $this->configuration['title_text'] ?? 'Header text',
      ],
      'intro_text' => [
        '#title' => 'Intro Text',
        '#type' => 'textarea',
        '#rows' => 5,
        '#cols' => 3,
        '#required' => 0,
        '#maxlength' => 512,
        '#default_value' => $this->configuration['intro_text'] ?? 'Intro text',
      ],
      'text_align' => [
        '#title' => 'Text Alignment',
        '#type' => 'select',
        '#options' => [
          'left' => 'Left',
          'center' => 'Center',
          'right' => 'Right',
        ],
        '#required' => 1,
        '#default_value' => $this->configuration['text_align'] ?? NULL,
      ],
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();

    $this->configuration['title_text'] = $values['title_text'];
    $this->configuration['intro_text'] = $values['intro_text'];
    $this->configuration['text_align'] = $values['text_align'];
  }

}
