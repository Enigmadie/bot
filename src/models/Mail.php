<?php

namespace Bot;

class Mail {
  private static $connect;

  public static function up($connect) {
    self::$connect = $connect;
    $query = "SELECT * FROM mail";
    $result = self::$connect->query($query);

    if (empty($result)) {
      $query = "CREATE TABLE mail (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        mail_number INT(30),
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

  public function register_mail_track($track, $id) {
    $query_select = "SELECT * FROM mail WHERE mail_number = {$track} AND user_id = {$id}";
    $result_select = self::$connect->query($query_select);
    $is_rowEmpty = $result_select->num_rows === 0;

    /* $query_manipulation = $is_rowEmpty */
    /*   ? "INSERT INTO */ 


  }
}
