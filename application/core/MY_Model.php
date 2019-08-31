<?php

class MY_Model extends CI_Model {

  public function image_exist($table, $col_val_arr)
  {

    $table_col = key($col_val_arr);
    $col_val   = $col_val_arr[$table_col];

    // Check existing image in the table
    $this->db->select('image');
    $this->db->where($table_col, $col_val);

    return $this->db->get($table)->row_array()['image'];
  }

}