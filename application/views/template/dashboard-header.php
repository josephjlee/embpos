<?php $css_list = activate_page_css("{$this->uri->segment(1)}/{$this->uri->segment(2)}"); ?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <?php if ($this->uri->segment('1') == 'keuangan' && $this->uri->segment('2') == 'lihat_invoice') : ?>
    <title><?= $title; ?></title>
  <?php else : ?>
    <title>Embryo | <?= $title; ?></title>
  <?php endif; ?>

  <!-- Libraries-->
  <link href="<?= base_url('vendors/'); ?>fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url('vendors/'); ?>sb-admin-2/css/sb-admin-2.css" rel="stylesheet">
  <?php foreach ($css_list as $css) : ?>
    <link href="<?= base_url($css['src']); ?>" rel="stylesheet">
  <?php endforeach; ?>
  <link rel="stylesheet" href="<?= base_url('vendors/flatpickr/dist/flatpickr.min.css') ?>">
  <script src="<?= base_url('vendors/flatpickr/dist/flatpickr.js') ?>"></script>

  <script src="<?= base_url('vendors/html2canvas.min.js') ?>"></script>

  <link rel="stylesheet" href="<?= base_url('vendors/datatables/buttons.dataTables.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('vendors/datatables/buttons.bootstrap4.min.css') ?>">


  <!-- Custom style -->
  <link href="<?= base_url('assets/'); ?>css/custom-style.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">