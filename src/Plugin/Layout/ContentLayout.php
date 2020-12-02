<?php

namespace Drupal\uwcoe_stories\Plugin\Layout;

use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\uwcoe_stories\Traits\LoadsMedia;

/**
 * Layout for Immersive Story content sections.
 *
 * @Layout(
 *   id = "uwcoe_stories_section_layout",
 *   label = @Translation("UWCoE Immersive Story Section"),
 *   category = @Translation("UWCoE Immersive Story"),
 *   template = "templates/section-layout",
 *   library = "uwcoe_stories/section-layout",
 *   regions = {
 *     "main" = {
 *       "label" = @Translation("Main content"),
 *     }
 *   }
 * )
 */
class ContentLayout extends LayoutDefault implements PluginFormInterface {

  use LoadsMedia;

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $configuration = $this->getConfiguration();

    $form['section_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Section ID'),
      '#default_value' => $configuration['section_id'] ?? NULL,
      '#required' => 1,
    ];
    $form['background_image_id'] = [
      '#title' => 'Background image',
      '#type' => 'media_library',
      '#allowed_bundles' => ['image'],
      '#default_value' => $this->configuration['background_image_id'] ?? NULL,
      '#description' => 'Upload or select a background image.',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    // HTML5 id attributes, at the very least, cannot contain whitespace.
    if (preg_match('/\s/', $form_state->getValue('section_id'))) {
      $form_state->setErrorByName('section_id', $this->t('ID cannot contain whitespace'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['section_id'] = $form_state->getValue('section_id');

    if ($form_state->getValue('background_image_id')) {
      $this->configuration['background_image_id'] = $form_state->getValue('background_image_id');
      $this->configuration['background_url'] = $this->fileUrlFromMediaId($this->configuration['background_image_id']);
    }
    else {
      unset($this->configuration['background_image_id']);
      unset($this->configuration['background_url']);
    }
  }

}
