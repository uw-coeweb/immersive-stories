<?php

namespace Drupal\uwcoe_stories\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Hero' Block.
 *
 * @Block(
 *   id = "uwcoe_stories__heading_block",
 *   admin_label = @Translation("Heading Block"),
 *   category = @Translation("UWCoE Immersive Stories"),
 * )
 */
class HeadingBlock extends BackgroundBlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $ra = parent::build();
    return array_merge($ra, [
      '#theme' => 'uwcoe_stories_heading_block',
      '#content' => [
        'heading_text' => $this->configuration['heading_text'] ?? NULL,
        'subheading_text' => $this->configuration['subheading_text'] ?? NULL,
        'text_align' => $this->configuration['text_align'] ?? NULL,
        'text_color' => $this->configuration['text_color'] ?? NULL,
      ],
      '#attached' => [
        'library' => $this->buildLibraries(['uwcoe_stories/heading']),
      ],
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['heading_text'] = [
      '#title' => 'Heading',
      '#type' => 'textfield',
      '#required' => 1,
      '#default_value' => $this->configuration['heading_text'] ?? 'Header text',
    ];
    $form['subheading_text'] = [
      '#title' => 'Subheading',
      '#type' => 'textarea',
      '#rows' => 5,
      '#cols' => 3,
      '#required' => 0,
      '#maxlength' => 512,
      '#default_value' => $this->configuration['subheading_text'] ?? 'Intro text',
    ];
    $form['text_align'] = [
      '#title' => 'Text Alignment',
      '#type' => 'select',
      '#options' => [
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
      ],
      '#required' => 1,
      '#default_value' => $this->configuration['text_align'] ?? NULL,
    ];
    $form['text_color'] = [
      '#title' => 'Text Color',
      '#type' => 'select',
      '#options' => [
        'light' => 'White text with shadow (Light)',
        'dark' => 'Dark gray text',
        'purple' => 'Purple (primary) text',
      ],
      '#required' => 1,
      '#default_value' => $this->configuration['text_color'] ?? NULL,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();

    $this->configuration['heading_text'] = $values['heading_text'];
    $this->configuration['text_align'] = $values['text_align'];
    $this->configuration['text_color'] = $values['text_color'];

    if (isset($values['subheading_text'])) {
      $this->configuration['subheading_text'] = $values['subheading_text'];
    }
  }

}
