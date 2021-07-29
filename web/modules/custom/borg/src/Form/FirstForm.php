<?php

namespace Drupal\borg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

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
    $form['input'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#description' => $this->t("minimum symbols: 2 maximum symbols: 32"),
//      '#maxlength' => 32,
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $min = 2;
    $max = 32;
    $current = strlen($form_state->getValue('input'));
    if ($max <= $current) {
      $this->messenger()->addError($this->t(
        'maximum symbols: 32'
      ));
    }
    elseif ($current <= $min) {
      $this->messenger()->addError($this->t(
        'minimum symbols: 2'
      ));
    }
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus(
      $this->t(
        'Your cat name is @name',
        ['@name' => $form_state->getValue('input')]
      )
    );
  }

}
