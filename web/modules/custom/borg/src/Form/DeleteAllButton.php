<?php

namespace Drupal\borg\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Form\ConfirmFormBase;

class DeleteAllButton extends ConfirmFormBase {

  public function getFormId() {
    return 'delete_all_form';
  }

  public $cid = [];

  public function getQuestion() {
    return t('Do you want to delete %id' , ['%id' => $this->cid]);
  }
  public function getCancelUrl() {
    return Url::fromUri($_SERVER["HTTP_REFERER"]);
  }
  public function getDescription() {
    return '';
  }

  public function getConfirmText() {
    return t('Delete');
  }

  public function getCancelText() {
    return t('Cancel');
  }


  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $submit = new CatsListForm;
    $submit->submitForm();

    $this->messenger()->addMessage($this->t("succesfully deleted"));
    $form_state->setRedirectUrl(Url::fromUri($_SERVER["HTTP_REFERER"]));
  }

}
