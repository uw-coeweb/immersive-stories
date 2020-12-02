<?php

namespace Drupal\uwcoe_stories\Traits;

/**
 * Help build render arrays for blocks
 */
trait BuildsRenderArrays {

  /**
   * Build up a list of libraries to attach to this block's RA.
   *
   * @param array $additionalLibraries
   *   additional CSS/JS libraries to attach to this block.
   */
  protected function buildLibraries(array $additionalLibraries) {
    return array_merge(
      [
        'uwcoe_stories/base-styles',
      ],
      $additionalLibraries,
    );
  }
}