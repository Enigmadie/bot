<?php

namespace Bot;

use function Bot\Services\mail\get_mail_data;

use Bot\User;

class Mail {
  private static $connect;

  public static function up($connect) {
    self::$connect = $connect;
    $query = "SELECT * FROM mail";
    $result = self::$connect->query($query);

    if (empty($result)) {
      $query = "CREATE TABLE mail (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        mail_number BIGINT(15),
        status TEXT,
        created_at DATETIME,
        updated_at DATETIME,
        user_id INT(11),
        FOREIGN KEY (user_id) REFERENCES user (id)
        )";
      $result = self::$connect->query($query);
      if (self::$connect->error) {
        die("Model Mail is failed: " . self::$connect->error);
      }
    }
  }

  public function register_mail_track($track, $user_id) {
    $user = new User();
    $id = $user->get_user_id($user_id);
    if (isset($id)) {
      $query_select = "SELECT * FROM mail WHERE mail_number = {$track} AND user_id = {$id}";
      $result_select = self::$connect->query($query_select);

      $is_rowEmpty = $result_select->num_rows === 0;
      $data = get_mail_data($track);
      ['status' => $status, 'message' => $message] = $data;

      $date = new \DateTime('Europe/Moscow');
      $formated_date = $date->format('Y-m-d H:i:s');

      if ($is_rowEmpty) {
        $query_insert = "INSERT INTO mail (mail_number, status, created_at, updated_at, user_id) VALUES ({$track}, '{$status}', '{$formated_date}', '{$formated_date}', {$id})";
        self::$connect->query($query_insert);
        error_log(print_r(self::$connect->error), true);
      } else {
        error_log(print_r($result_select->fetch_assoc()));
        $mail_status = $result_select->fetch_assoc()['status'];
        $has_rewrite_row = $mail_status !== $status;
        if ($has_rewrite_row) {
          $query_update = "UPDATE mail SET status = '{$status}', updated_at = '{$formated_date}' WHERE user_id = {$id}";
          self::$connect->query($query_update);
        }
      }
      return $message;
    }
  }
}
