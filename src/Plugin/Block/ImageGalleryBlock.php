<?php

namespace Drupal\uwcoe_stories\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\uwcoe_stories\Traits\LoadsMedia;
use Drupal\Core\Block\BlockBase;
use Drupal\uwcoe_stories\Traits\BuildsRenderArrays;

/**
 * Provides a 'Image Gallery' Block.
 *
 * @Block(
 *   id = "uwcoe_stories__image_gallery_block",
 *   admin_label = @Translation("Image Gallery Block"),
 *   category = @Translation("UWCoE Immersive Stories"),
 * )
 */
class ImageGalleryBlock extends BlockBase {

  use LoadsMedia;
  use BuildsRenderArrays;

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'uwcoe_stories_image_gallery_block',
      '#slides' => $this->buildSlides(),
      '#id' => $this->configuration['gallery_id'] ?? NULL,
      '#attached' => [
        'library' => $this->buildLibraries(['uwcoe_stories/image-gallery']),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['gallery_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('HTML ID for this gallery (must be unique to the page)'),
      '#default_value' => $this->configuration['gallery_id'] ?? NULL,
      '#required' => 1,
    ];

    // See  buildForm() of this example:
    // https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Render%21Element%21Textfield.php/class/Textfield/8.9.x.
    $form['table-row'] = [
      '#type' => 'table',
      '#header' => [
        'Order',
        'Image',
        'Caption',
        'Weight',
      ],
      '#empty' => $this
        ->t('Sorry, There are no items!'),
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'table-sort-weight',
        ],
      ],
    ];

    $max = 10;
    $this->sortSlidesByWeight();
    for ($i = 0; $i < $max; $i++) {
      $form['table-row'][$i]['#attributes']['class'][] = 'draggable';
      $form['table-row'][$i]['#weight'] = $this->configuration['slides'][$i]['weight'] ?? $i;

      // This is purely to make the table not look like garbage.
      $form['table-row'][$i]['order'] = [
        '#markup' => '',
      ];
      $form['table-row'][$i]['mid'] = [
        '#title' => 'Image',
        '#type' => 'media_library',
        '#allowed_bundles' => ['image'],
        '#default_value' => $this->configuration['slides'][$i]['mid'] ?? NULL,
        '#description' => 'Upload or select an image.',
        '#cardinality' => 1,
      ];
      $form['table-row'][$i]['caption'] = [
        '#title' => 'Caption',
        '#type' => 'textfield',
        '#default_value' => $this->configuration['slides'][$i]['caption'] ?? NULL,
        '#required' => 0,
      ];
      $form['table-row'][$i]['weight'] = [
        '#type' => 'weight',
        '#title' => 'Weight',
        '#title_display' => 'invisible',
        '#default_value' => $this->configuration['slides'][$i]['weight'] ?? $i,
        '#attributes' => [
          'class' => [
            'table-sort-weight',
          ],
        ],
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    // HTML5 id attributes, at the very least, cannot contain whitespace.
    if (preg_match('/\s/', $form_state->getValue('gallery_id'))) {
      $form_state->setErrorByName('gallery_id', $this->t('ID cannot contain whitespace'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $this->configuration['gallery_id'] = $form_state->getValue('gallery_id');
    $submission = $form_state->getValue('table-row');
    foreach ($submission as $idx => $slide) {
      $this->configuration['slides'][$idx]['mid'] = $slide['mid'];
      $this->configuration['slides'][$idx]['caption'] = $slide['caption'];
      $this->configuration['slides'][$idx]['weight'] = $slide['weight'];
    }
  }

  /**
   * Get an image's URL from its media ID.
   */
  protected function buildSlides() {
    $this->sortSlidesByWeight();

    $slides = [];
    foreach ($this->configuration['slides'] as $slide) {
      if ($slide['mid'] !== NULL) {
        $slides[] = [
          'image' => $this->fileUrlFromMediaId($slide['mid']),
          'caption' => $slide['caption'],
        ];
      }
    }
    return $slides;
  }

  protected function sortSlidesByWeight() {
    if (isset($this->configuration['slides'])) {
      $slides = $this->configuration['slides'];
      $weight = array_column($slides, 'weight');
      array_multisort($weight, SORT_ASC, $slides);
      $this->configuration['slides'] = $slides;
    }
  }

}
