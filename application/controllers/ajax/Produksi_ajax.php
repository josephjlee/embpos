<?php
defined('BASEPATH') or exit('No direct script access allowed.');

class Produksi_ajax extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('pesanan_model');
		$this->load->model('produksi_model');
	}

	public function list_all_designs()
	{
		$designs = [
			'data' => []
		];

		foreach ($this->produksi_model->get_design_list() as $design) {

			$design['thumbnail'] = isset($design['artwork']) ? base_url('assets/img/artwork/') . $design['artwork'] : '-';

			$design['required'] = [
				'display' => date('d/m/Y', strtotime($design['required'])),
				'raw'     => strtotime($design['required']),
				'input'   => date('Y-m-d', strtotime($design['required']))
			];

			$design['status'] = [
				'display' => $design['status'],
				'raw'     => $design['production_status_id'],
			];

			array_push($designs['data'], $design);
		};

		header('Content-Type: application/json');
		echo json_encode($designs);
	}

	public function list_all_embro()
	{
		$embros = [
			'data' => []
		];

		foreach ($this->produksi_model->get_embro_list() as $embro) {

			$embro['thumbnail'] = isset($embro['artwork']) ? base_url('assets/img/artwork/') . $embro['artwork'] : '-';

			$embro['required'] = [
				'display' => date('d/m/Y', strtotime($embro['required'])),
				'raw'     => strtotime($embro['required']),
				'input'   => date('Y-m-d', strtotime($embro['required']))
			];

			$embro['status'] = [
				'display' => $embro['status'],
				'raw'     => $embro['production_status_id'],
			];

			array_push($embros['data'], $embro);
		};

		header('Content-Type: application/json');
		echo json_encode($embros);
	}

	public function list_all_finishing()
	{
		$finishing_list = [
			'data' => []
		];

		foreach ($this->produksi_model->get_finishing_list() as $finishing) {

			$finishing['thumbnail'] = base_url('assets/img/artwork/') . $finishing['image'];

			$finishing['deadline'] = [
				'display' => date('d/m/Y', strtotime($finishing['deadline'])),
				'raw'     => strtotime($finishing['deadline']),
				'input'   => date('Y-m-d', strtotime($finishing['deadline']))
			];

			$finishing['status'] = [
				'display' => $finishing['status'],
				'raw'     => $finishing['production_status_id'],
			];

			$finishing['quantity'] = [
				'display' => moneyStrDot($finishing['quantity']),
				'raw'     => $finishing['quantity'],
			];

			array_push($finishing_list['data'], $finishing);
		};

		header('Content-Type: application/json');
		echo json_encode($finishing_list);
	}

	public function list_embro_output_log()
	{
		$embro = [
			'data' => []
		];

		foreach ($this->produksi_model->get_embro_output_log() as $embro_log) {
			array_push($embro['data'], $embro_log);
		};

		header('Content-Type: application/json');
		echo json_encode($embro);
	}
}
