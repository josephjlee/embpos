<?php

class Produk_model extends CI_Model
{

  public function simpan($product)
  {

    if (!empty($product['product_id'])) {
      return $this->perbarui($product);
    }

    return $this->tambah($product);
  }

  public function tambah($product)
  {

    $product_data = $this->siapkan_data($product);

    return $this->db->insert('product', $product_data);
  }

  public function perbarui($product)
  {
    $product_data = $this->siapkan_data($product);

    $this->db->where('product_id', $product['product_id']);

    return $this->db->update('product', $product_data);
  }

  public function hapus($product)
  {
    $this->db->where('product_id', $product['product_id']);
    return $this->db->delete('product');
  }

  public function detach_image($product)
  {
    $this->db->where('product_id', $product['product_id']);
    return $this->db->update('product', ['image' => NULL]);
  }

  public function get_all_products()
  {

    $this->db->select('
      product.product_id,
      product.image,
      product.sku,
      product.title,
      product.item_id,
      item.name AS item_name,
      item.icon AS item_icon,
      product.stock, 
      ( SELECT IFNULL( SUM(quantity),0 ) FROM product_sale WHERE product_sale.product_id = product.product_id ) AS sold,
      product.base_price,
      product.sell_price
    ');
    $this->db->join('item', 'product.item_id = item.item_id');

    return $this->db->get('product')->result_array();
  }

  public function get_simple_product_catalog()
  {
    $this->db->select('
      product.product_id, 
      product.title, 
      product.stock, 
      product.sell_price, 
      product.image,
      item.icon AS item_icon
    ');

    $this->db->from('product');
    $this->db->join('item', 'product.item_id = item.item_id');

    return $this->db->get()->result_array();
  }

  public function get_product_by_product_id($product_id)
  {

    $this->db->select('
      product.product_id,
      product.image,
      product.sku,
      product.title,
      product.description,
      product.item_id,
      item.name AS item_name,
      product.stock,
      product.base_price,
      product.sell_price
    ');

    $this->db->join('item', 'product.item_id = item.item_id');
    $this->db->where('product_id', $product_id);

    return $this->db->get('product')->row_array();
  }

  public function get_product_category()
  {
    $this->db->select('item_id, name');
    $this->db->from('item');
    $this->db->where('for_product', 1);
    return $this->db->get()->result_array();
  }

  public function siapkan_data($product)
  {

    $product_db_data = [];

    foreach ($product as $col => $val) {
      $product_db_data[$col] = $val;

      if ($col == 'stock' || $col == 'base_price' || $col == 'sell_price') {
        $product_db_data[$col] = str_replace(',', '', $val);
      }
    }

    return $product_db_data;
  }

  public function get_stock_by_product_id($product_id)
  {
    $this->db->select('stock');
    $this->db->from('product');
    $this->db->where('product_id', $product_id);

    return $this->db->get()->row_array()['stock'];
  }

  public function get_product_sale_qty_by_id($product_sale_id)
  {
    $this->db->select('quantity');
    $this->db->from('product_sale');
    $this->db->where('product_sale_id', $product_sale_id);

    return $this->db->get()->row_array()['quantity'];
  }

  public function update_stock_on_purchase($product_solds)
  {
    $data = [];

    $i = 0;

    foreach ($product_solds as $products) {
      $data[$i]['product_id'] = $products['product_id'];
      $data[$i]['stock'] = $this->get_stock_by_product_id($products['product_id']) - $products['quantity'];
      $i++;
    }

    $this->db->update_batch('product', $data, 'product_id');
  }

  public function update_stock_on_update($product_solds)
  {
    $data = [];

    $i = 0;

    foreach ($product_solds as $products) {
      $data[$i]['product_id'] = $products['product_id'];
      $data[$i]['stock'] = $this->get_stock_by_product_id($products['product_id']) + $this->get_product_sale_qty_by_id($products['product_sale_id']) - $products['quantity'];
      $i++;
    }

    $this->db->update_batch('product', $data, 'product_id');
  }

  public function get_stock_data()
  {
    $this->db->select('
      SUM(stock*sell_price) AS value,
      SUM(stock) AS quantity
    ');

    $this->db->from('product');

    return $this->db->get()->row_array();
  }
}
