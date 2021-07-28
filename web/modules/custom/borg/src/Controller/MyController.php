<?php

namespace Drupal\borg\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class MyController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function myNewPage() {
    return [
      '#type' => 'markup',
      '#markup' => "<p>Hello! You can add here a photo of your cat.</p>",
    ];
  }

}
