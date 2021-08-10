<?php

namespace Drupal\borg\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfirmFormBase;

class UpdateButton extends FormBase {

  public function getFormId() {
    return 'update_form';
  }

//  public $cid;
//
//  public function getQuestion() {
//    return t('Do you want to update %id' , ['%id' => $this->cid]);
//  }
//  public function getCancelUrl() {
//    return new Url('borg.cats');
//  }
//  public function getDescription() {
//    return '';
//  }
//
//  public function getConfirmText() {
//    return t('Update');
//  }
//
//  public function getCancelText() {
//    return t('Cancel');
//  }


  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->cid = $id;
    
    $form['messaged'] = [
      '#type' => 'markup',
      '#markup' => '<div class="dialog_message"></div>',
    ];
    $form['inputed'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#placeholder' => 'name',
      '#description' => $this->t("minimum symbols: 2 maximum symbols: 32"),
//      '#required' => TRUE,
      '#default_value' => $form_state->getValue('inputed'),
    ];

    $form['emailed'] = [
      '#type' => 'email',
      '#title' => $this->t('Your email:'),
      '#placeholder' => 'email_mail@mail.com',
//      '#required' => TRUE,
      '#attributes' => [
        'class' => ['email_dialog'],
      ],
      '#ajax' => [
        'callback' => '::EmailValidated',
        'event' => 'keyup',
      ],
    ];
    $form['error_messaged'] = [
      '#type' => 'markup',
      '#markup' => '<div class="error_dialog_message"></div>',
    ];

    $form['imaged'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Your image:'),
      '#upload_location' => 'public://images/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [2097152],
      ],
//      '#required' => TRUE,
    ];

    $form['submited'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
//      '#ajax' => [
//        'callback' => '::addMessageAjax',
//        'event' => 'click',
//      ],
    ];
    return $form;
  }

//  public function validateForm(array &$form, FormStateInterface $form_state) {
//    parent::validateForm($form, $form_state);
//  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
//    $connect = Database::getConnection();
//    $connect->delete('borg')
//      ->condition('id', $this->cid)
//      ->execute();
    $this->messenger()->addMessage($this->t("succesfully updated"));
    $form_state->setRedirect('borg.cats');
  }

//  public function addMessageAjax(array &$form, FormStateInterface $form_state) {
//    \Drupal::messenger()->deleteAll();
//    $response = new AjaxResponse();
//    $min = 2;
//    $max = 32;
//    $current = strlen($form_state->getValue('input'));
//    $mail = $form_state->getValue('email');
//    $email_exp = '/^[A-Za-z._-]+@[A-Za-z.-]+\.[A-Za-z]{2,4}$/';
//    $image = $form_state->getValue('image');
//
//
//    if (empty($image)) {
//      $response->addCommand(
//        new HtmlCommand(
//          '.message',
//          '<div class="invalid_message">' . $this->t('Image field is empty')
//          . '</div>'
//        )
//      );
//    }
//    else {
//      if (!preg_match($email_exp, $mail)) {
//        $response->addCommand(
//          new HtmlCommand(
//            '.message',
//            '<div class="invalid_message">' . $this->t('Your email invalid')
//            . '</div>'
//          )
//        );
//      }
//      else {
//        if ($max < $current) {
//          $response->addCommand(
//            new HtmlCommand(
//              '.message',
//              '<div class="invalid_message">' . $this->t('maximum symbols: 32')
//              . '</div>'
//            )
//          );
//        }
//        elseif ($current < $min) {
//          $response->addCommand(
//            new HtmlCommand(
//              '.message',
//              '<div class="invalid_message">' . $this->t('minimum symbols: 2')
//              . '</div>'
//            )
//          );
//        }
//        else {
//          $response->addCommand(
//            new HtmlCommand(
//              '.message',
//              '<div class="valid_message">' . $this->t(
//                'Your cat name is @name',
//                ['@name' => $form_state->getValue('input')]
//              ) . '</div>'
//            )
//          );
//          $this->DatabaseInput($form_state);
//        }
//      }
//    }
//    return $response;
//  }

  public function EmailValidated(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $mail = $form_state->getValue('emailed');
    $email_exp = '/^[A-Za-z._@-]{0,100}$/';

    $selector = '.email_dialog';
    $cssinvalid = [
      'box-shadow' => '0 0 10px 1px red',
    ];
    $cssvalid = [
      'box-shadow' => 'inherit',
    ];

    if (!preg_match($email_exp, $mail)) {
      $response->addCommand(new CssCommand($selector, $cssinvalid));
      $response->addCommand(new HtmlCommand(
        '.error_dialog_message',
        '<div class="invalid_dialog_form_message">' . $this->t('invalid symbol') . '</div>'));
    }
    else {
      $response->addCommand(new CssCommand($selector, $cssvalid));
      $response->addCommand(new HtmlCommand(
        '.error_dialog_message',
        '<div class="valid_dialog_form_message">' . '</div>'));
    }
    return $response;
  }



}
