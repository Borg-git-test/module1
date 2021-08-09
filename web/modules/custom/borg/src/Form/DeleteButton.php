<?php

namespace Drupal\borg\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Url;
use Drupal\Core\Database\Database;

class DeleteButton extends FormBase {

  public function getFormId() {
    return 'delete_form';
  }

  public $ctid;

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['delete'] = [
      '#type' => 'submit',
      '#value' => 'delete',
      '#attributes' => [
        'class' => ['btn-danger'],
      ],
      '#ajax' => [
        'callback' => '::deleteElement',
        'event' => 'click',
      ],
    ];
//    $GLOBALS['ctid'] = $cid;
//    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
//    $query = \Drupal::database();
//    $query->delete('borg')
//      ->condition('id', $this->id)
//      ->execute();
//    public function deleteElement($entry) {
          $connect = Database::getConnection();
          $connect->delete('borg')
            ->condition('id', $GLOBALS['ctid'])
            ->execute();
      //  }
//    drupal_set_message("succesfully deleted");
//    $form_state->setRedirect('borg.cats');
  }
  public function deleteElement() {
    $response = new AjaxResponse();
    $currentURL = Url::fromRoute('borg.cats');
    $response->addCommand(new RedirectCommand($currentURL->toString()));
    return $response;
  }
}
