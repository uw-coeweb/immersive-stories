<?php

namespace Drupal\uwcoe_stories\Traits;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

/**
 * Attach to a Drupal class that has dependency injection.
 */
trait LoadsMedia {

  protected function fileUrlFromMediaId($mid) {
    $media = $this->loadMedia($mid);
    $fid = $media->field_media_image->target_id;
    $file = File::load($fid);

    $url = $file->url();
    return $url;
  }

  protected function loadMedia($mid) {
    return Media::load($mid);
  }
}
