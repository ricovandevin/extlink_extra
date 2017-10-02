<?php

namespace Drupal\extlink_extra\Controller;

use Drupal\Core\Controller\ControllerBase;

class ExtlinkExtraController extends ControllerBase {

  /**
   * Render the exit modal.
   *
   * @return array
   */
  public function leave() {
    return [
      '#theme' => 'extlink_extra_leaving',
    ];
  }
}
