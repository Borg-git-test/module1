<?php

namespace Drupal\borg\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

class CatsListForm extends ConfirmFormBase {

  public function getFormId() {
    return 'cats_list';
  }

  public function getQuestion() {
    return t('Do you want to delete');
  }
  public function getCancelUrl() {
    return new Url('<current>');
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
    $connect = Database::getConnection();
    $output = $connect->select('borg', 'x')
      ->fields('x', ['image', 'cat_name', 'email', 'time', 'id'])
      ->execute();
    $results = $output->fetchAllAssoc('time', \PDO::FETCH_ASSOC);
    $results = array_reverse($results);
    $header = [
      'image' => t('image'),
      'cat_name' => t('cat name'),
      'email' => t('email'),
      'time' => t('time'),
      'update' => t('update'),
      'delete' => t('delete'),
    ];
    $rows = [];
    $i = 0;
    foreach ($results as $value) {
      $file = File::load($value['image']);
      $value['image'] = [
        '#type' => 'image',
        '#theme' => 'image_style',
        '#style_name' => 'large',
        '#uri' => $file->getFileUri(),
      ];
      $value['image_url'] = file_create_url($file->getFileUri());
      $renderer = \Drupal::service('renderer');
      $value['image'] = $renderer->render($value['image']);
      $value['time'] = date('j/F/Y H:i:s', $value['time']);
      $id = $value['id'];
      $value['delete'] = [
        '#type' => 'link',
        '#url' => Url::fromUserInput("/borg/form/delete/$id"),
        '#title' => $this->t('delete'),
        '#attributes' => [
          'data-dialog-type' => ['modal'],
          'class' => ['button', 'use-ajax', 'btn-danger', 'btn'],
        ],
      ];
      $value['update'] = [
        '#type' => 'link',
        '#url' => Url::fromUserInput("/borg/form/update/$id"),
        '#title' => $this->t('update'),
        '#attributes' => [
          'data-dialog-type' => ['modal'],
          'class' => ['button', 'use-ajax', 'btn-danger', 'btn'],
        ],
      ];
      $value['update'] = $renderer->render($value['update']);
      $value['delete'] = $renderer->render($value['delete']);
      $rows[$i] = $value;
      $i += 1;
    }
    $form['table'] = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $rows,
      '#empty' => t('No info in database.'),
    ];
//    $form['submit'] = [
//      '#type' => 'submit',
//      '#value' => $this->t('Delete all selected'),
//    ];
    $form['submit'] = [
      '#type' => 'link',
      '#url' => Url::fromUserInput("/borg/form/delete_all"),
      '#title' => $this->t('Delete all selected'),
      '#attributes' => [
        'data-dialog-type' => ['modal'],
        'class' => ['button', 'use-ajax', 'btn-danger', 'btn'],
      ],
    ];
//    return $form;
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $value = $form['table']['#value'];
    $connect = Database::getConnection();

    foreach ($value as $key) {

      $output = $connect->select('borg', 'x')
        ->fields('x', ['image'])
        ->condition('id', $form['table']['#options'][$key]['id'])
        ->execute();
      $fid = $output->fetchAssoc();
      $file = File::load($fid['image']);
      $file->setTemporary();
      $file->delete();

      $connect->delete('borg')
        ->condition('id', $form['table']['#options'][$key]['id'])
        ->execute();
    }
    $this->messenger()->addMessage($this->t("succesfully deleted"));
    $form_state->setRedirectUrl(Url::fromUri($_SERVER["HTTP_REFERER"]));
  }

}
