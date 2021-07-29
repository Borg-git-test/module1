<?php

namespace Drupal\borg\Controller;

use Drupal\Core\Controller\ControllerBase;

class MyController extends ControllerBase {

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
      '#value' => $this->t('Hello! You can add here a photo of your cat.'),
      '#attributes' => [
        'class' => ['text'],
      ],
    ];
    return $text;
  }

  public function form() {
    $forma = \Drupal::formBuilder()->getForm('Drupal\borg\Form\FirstForm');
    return $forma;
  }

  public function myNewPage() {

    return [
      $this->text(),
      $this->form(),
    ];
  }

}
