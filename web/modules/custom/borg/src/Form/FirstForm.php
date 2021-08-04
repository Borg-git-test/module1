<?php

namespace Drupal\borg\Form;

use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;


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
      '#placeholder' => 'name',
      '#description' => $this->t("minimum symbols: 2 maximum symbols: 32"),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
      '#placeholder' => 'email_mail@mail.com',
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::EmailValidate',
        'event' => 'keyup',
      ],
    ];
    $form['error_message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="error_message"></div>',
    ];

    $form['image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Your image:'),
      '#upload_location' => 'public://images/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [2097152],
      ],
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
      '#ajax' => [
        'callback' => '::addMessageAjax',
        'event' => 'click',
      ],
    ];
    return $form;
  }

  public function addMessageAjax(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->deleteAll();
    $response = new AjaxResponse();
    $min = 2;
    $max = 32;
    $current = strlen($form_state->getValue('input'));
    $mail = $form_state->getValue('email');
    $email_exp = '/^[A-Za-z._-]+@[A-Za-z.-]+\.[A-Za-z]{2,4}$/';

  if (!preg_match($email_exp, $mail)) {
    $form_state->setErrorByName('email');
    $response->addCommand(
      new HtmlCommand(
        '.message',
        '<div class="invalid_message">' . $this->t('Your email invalid') . '</div>'));
  }
  else {
    if ($max < $current) {
      $form_state->setErrorByName('input');
      $response->addCommand(
        new HtmlCommand(
          '.message',
          '<div class="invalid_message">' . $this->t('maximum symbols: 32') . '</div>'));
    }
    elseif ($current < $min) {
      $form_state->setErrorByName('input');
      $response->addCommand(
        new HtmlCommand(
          '.message',
          '<div class="invalid_message">' . $this->t('minimum symbols: 2') . '</div>'));
    }
    else {
      $response->addCommand(
        new HtmlCommand(
          '.message',
          '<div class="valid_message">' . $this->t(
            'Your cat name is @name',
            ['@name' => $form_state->getValue('input')]) . '</div>'));
    }
  }
    return $response;
  }

  public function EmailValidate(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $mail = $form_state->getValue('email');
    $email_exp = '/^[A-Za-z._@-]{0,100}$/';

    $selector = '.form-email';
    $cssinvalid = [
      'box-shadow' => '0 0 10px 1px red',
    ];
    $cssvalid = [
      'box-shadow' => 'inherit',
    ];

    if (!preg_match($email_exp, $mail)) {
      $response->addCommand(new CssCommand($selector, $cssinvalid));
      $response->addCommand(new HtmlCommand(
        '.error_message',
        '<div class="invalid_form_message">' . $this->t('invalid symbol') . '</div>'));
    }
    else {
      $response->addCommand(new CssCommand($selector, $cssvalid));
      $response->addCommand(new HtmlCommand(
        '.error_message',
        '<div class="valid_form_message">' . '</div>'));
    }
    return $response;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
  }


}
