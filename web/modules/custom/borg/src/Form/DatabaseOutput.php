<?php

namespace Drupal\borg\Form;

use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DatabaseOutput extends Database {

  public function DatabaseOutput() {
    $connect = Database::getConnection();
    $output = $connect->select('borg', 'x')
      ->fields('x', ['id', 'image', 'cat_name', 'email', 'time'])
      ->execute();
    $results = $output->fetchAllAssoc('time', \PDO::FETCH_ASSOC);
    $results = array_reverse($results);
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
      $value['delete'] = [
        '#type' => 'submit',
        '#value' => 'delete',
        '#attributes' => [
          'class' => ['btn-danger'],
        ],
        '#ajax' => [
          'callback' => '::deleteElement',
        ],
      ];
      $value['update'] = [
        '#type' => 'submit',
        '#value' => 'update',
        '#attributes' => [
          'class' => ['btn-warning'],
        ],
        '#ajax' => [
          'callback' => '::updateElement',
        ],
      ];
//      $value['delete'] = $this->delete();
      $rows[$i] = $value;
      $i += 1;
    }
    return $rows;
  }

//  public function deleteElement($entry) {
//    $connect = Database::getConnection();
//    $connect->delete('borg')
//      ->condition('id', $entry)
//      ->execute();
//  }
//  public function delete() {
//    $delete = \Drupal::formBuilder()->getForm('Drupal\borg\Form\DeleteButton');
////    $delete = \Drupal::formBuilder()->getForm('\Drupal\borg\Form\FirstForm');
//    return $delete;
//  }
}
