<?php

namespace Drupal\borg\Form;

use Drupal\Core\Database\Database;

class DatabaseOutput extends Database {

  public function DatabaseOutput() {
    $connect = Database::getConnection();
    $output = $connect->select('borg', 'x')
      ->fields('x', ['cat_name', 'email', 'image', 'time'])
      ->execute();
    $results = $output->fetchAll(\PDO::FETCH_ASSOC);
    $results = array_reverse($results);
    $rows = [];

    foreach ($results as $value) {
      array_push($rows, $value);
    }

    $content = [
      '#type' => 'table',
      '#rows' => $rows,
      '#empty' => t('No entries available.'),
    ];
    return $content;
  }

}
