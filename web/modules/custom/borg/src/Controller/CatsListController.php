<?php

namespace Drupal\borg\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\borg\Form\DatabaseOutput;

class CatsListController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */

  public function text() {
    $text = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('Hello! Here you can delete or update any cats.'),
      '#attributes' => [
        'class' => ['text'],
      ],
    ];
    return $text;
  }

  public function database() {
    $output = new DatabaseOutput();
    $outputs = $output->DatabaseOutput();
    return $outputs;
  }

  public function myAdminPage() {
    return [
      '#theme' => 'borg_cats_list',
      '#text' => $this->text(),
      '#element' => $this->database(),
    ];
  }

}
