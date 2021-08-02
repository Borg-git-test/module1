<?php

namespace Drupal\borg\Form;

use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

/**
 * Class MyForm
 *
 * @package Drupal\borg\Form
 */
class FirstForm extends FormBase {

  public function getFormId() {
    return "borg_first_form";
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="message"></div>',
    ];

    $form['input'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#description' => $this->t("minimum symbols: 2 maximum symbols: 32"),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
      '#ajax' => [
        'callback' => '::addMessageAjax',
      ],
    ];
    return $form;
  }

  public function addMessageAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $min = 2;
    $max = 32;
    $current = strlen($form_state->getValue('input'));
    if ($max < $current) {
      $response->addCommand(
        new HtmlCommand(
          '.message',
          '<div class="invalid_message">' . $this->t('maximum symbols: 32') . '</div>'
        )
      );
    }
    elseif ($current < $min) {
      $response->addCommand(
        new HtmlCommand(
          '.message',
          '<div class="invalid_message">' . $this->t('minimum symbols: 2') . '</div>'
        )
      );
    }
    else {
      $response->addCommand(
        new HtmlCommand(
          '.message',
          '<div class="valid_message">' . $this->t(
                  'Your cat name is @name',
                  ['@name' => $form_state->getValue('input')]
          ) . '</div>')
      );
    }
    return $response;
  }


    public function validateForm(array &$form, FormStateInterface $form_state) {
      $form_state->clearErrors();
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
