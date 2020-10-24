<?php

namespace Bot;

use function Bot\Services\mail\get_mail_data;
use Bot\Db_actions;
use Bot\Db_results;

class Mail {
  private static $table = 'mail';

  public function register_mail_track($track, $user_id) {
    $data = get_mail_data($track);
    if (isset($data['status'])) {
      $user = new User();
      $id = $user->get_id($user_id);
      if (isset($id)) {
        $result_select = Db_actions::select_values(
          self::$table,
          ['mail_number' => $track, 'user_id' => (int)$id]
        );

        $is_rowEmpty = Db_results::is_rowEmpty($result_select);
        ['status' => $status] = $data;

        $date = new \DateTime('Europe/Moscow');
        $formated_date = $date->format('Y-m-d H:i:s');

        if ($is_rowEmpty) {
          Db_actions::insert_values(
            self::$table,
            [
              'mail_number' => $track,
              'status' => $status,
              'created_at' => $formated_date,
              'updated_at' => $formated_date,
              'user_id' => $id,
            ],
          );
        } else {
          $mail_status = Db_results::get_row_params($result_select, 'status');
          $has_rewrite_row = $mail_status !== $status;
          if ($has_rewrite_row) {
            Db_actions::update_values(
              self::$table,
              [
                'status' => $status,
                'updated_at' => $formated_date,
              ],
              [
                'user_id' => $id,
                'mail_number' => $track
              ]
            );
          }
        }
      }
    }
    return $data;
  }

  public function handle_mail_units() {
    $result = Db_actions::select_values(self::$table);
    $is_rowEmpty = Db_results::is_rowEmpty($result);

    if (!$is_rowEmpty) {
      $mail_units = Db_results::get_rows($result);
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
