<?php

namespace Bot;

use function Bot\Services\mail\get_mail_data;
use Bot\Db_Mail;

class Mail {
  private static $connect;

  public function register_mail_track($track, $user_id) {
    $data = get_mail_data($track);
    if (isset($data['status'])) {
      $user = new User();
      $id = $user->get_id($user_id);
      if (isset($id)) {
        $result_select = DB_Mail::select_values(['mail_number' => $track, 'user_id' => $id]);

        $is_rowEmpty = $result_select->num_rows === 0;
        ['status' => $status] = $data;

        $date = new \DateTime('Europe/Moscow');
        $formated_date = $date->format('Y-m-d H:i:s');

        if ($is_rowEmpty) {
          $query_insert = "INSERT INTO mail (mail_number, status, created_at, updated_at, user_id) VALUES ({$track}, '{$status}', '{$formated_date}', '{$formated_date}', {$id})";
          self::$connect->query($query_insert);
        } else {
          $mail_status = $result_select->fetch_assoc()['status'];
          $has_rewrite_row = $mail_status !== $status;
          if ($has_rewrite_row) {
            $query_update = "UPDATE mail SET status = '{$status}', updated_at = '{$formated_date}' WHERE user_id = {$id}";
            self::$connect->query($query_update);
          }
        }
      }
    }
    return $data;
  }

  public function handle_mail_units() {
    $result = $this->select_values();
    $is_rowEmpty = $result->num_rows === 0;

    if (!$is_rowEmpty) {
      $mail_units = [$result->fetch_assoc()];
      $units = [];

      foreach($mail_units as $el) {
        $user = new User();
        $user_id = $user->get_user_id($el['user_id']);
        $data = $this->register_mail_track($el['mail_number'], $user_id);

        if ($el['status'] !== $data['status']) {
          array_push($units, [
            'user_id' => $user_id,
            'message' => $data['message']
          ]);
        }
      }

      return count($units) > 0 ? $units : null;
    }
  }
}
