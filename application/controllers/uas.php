<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class uas extends CI_Controller {

	public function index()
	{
		$data = [
			'title'   => 'Ekspedisi',
			'url'     => 'Ekspedisi',
			'header' => 'Kelola Data Ekspedisi',
			'content' => 'view_uas'
		];		

		$this->load->view('layout/index', $data, FALSE);
	}

	public function loadTable()
	{
		$select = '*';
		$table  = 'ekspedisi';

		$limit = [
			'start'  => $this->input->get('start'),
			'finish' => $this->input->get('length')
		];

		$like['data'][] = [
			'column' => 'pengirim, penerima, alamat, telfon, berat', 'id_ekspedisi',
			'param'  => $this->input->get('search[value]')
		];

		$indexOrder = $this->input->get('order[0][column]');
		$order['data'][] = [
			'column' => $this->input->get('columns['.$indexOrder.'][name]'),
			'type'   => $this->input->get('order[0][dir]')
		];

		

		$totalData  = $this->query->dataComplete($select, $table, NULL, NULL, NULL, NULL, NULL);
		$filterData = $this->query->dataComplete($select, $table, NULL, $like, $order, NULL, NULL);
		$queryData  = $this->query->dataComplete($select, $table, $limit, $like, $order, NULL, NULL);

		$result['data'] = [];
		if($queryData <> FALSE) {
			$no = $limit['start'] + 1;

			foreach($queryData->result() as $query) {
				if($query->id_ekspedisi> 0) {
					$result['data'][] = [
						$no,
						$query->pengirim,
						$query->penerima,
						$query->alamat,
						$query->telfon,
						$query->berat,
						'
							<button onclick="showData('.$query->id_ekspedisi.')" class="btn btn-warning btn-circle btn-sm"><i class="fas fa-edit"></i></button>
							<button onclick="deleted('.$query->id_ekspedisi.')" class="btn btn-danger btn-circle btn-sm" title="Delete Data"><i class="fa fa-trash"></i></button>
						'
					];
					$no++;
				}
			}
		}

		$result['recordsTotal'] = 0;
		if($totalData <> FALSE) {
			$result['recordsTotal'] = $totalData->num_rows();
		}

		$result['recordsFiltered'] = 0;
		if($filterData <> FALSE) {
			$result['recordsFiltered'] = $filterData->num_rows();
		}

		echo json_encode($result);
	}

	public function showData($id)
	{
		$where = ['id_ekspedisi' => $id];
		$data  = $this->query->getData('*', 'ekspedisi', $where)->row();
		echo json_encode($data);
	}

	public function insertData()
	{
		$data = [
				'pengirim' => $this->input->post('pengirim'),
				'penerima' => $this->input->post('penerima'),
				'alamat' => $this->input->post('alamat'),
				'telfon' => $this->input->post('telfon'),
				'berat' => $this->input->post('berat')
		];

		$insert = $this->query->insert('ekspedisi', $data);
		if($insert) {
			$response['ping'] = 200;
		} else {
			$response['ping'] = 500;
		}

		echo json_encode($response);
	}

	public function updateData($id)
	{
		
		$data = [
			'pengirim' => $this->input->post('pengirim'),
			'penerima' => $this->input->post('penerima'),
			'alamat' => $this->input->post('alamat'),
			'telfon' => $this->input->post('telfon'),
			'berat' => $this->input->post('berat')
		];

		$table  = ['column' => 'id_ekspedisi', 'param' => $id, 'table' => 'ekspedisi'];
		$update = $this->query->update($table, $data);
		if($update) {
			$response['ping'] = 200;
		} else {
			$response['ping'] = 500;
		}

		echo json_encode($response);
	}

	public function deleted($id)
	{
		
		$where = [
			'id_ekspedisi' => $id
		];

		$deleteData = $this->query->delete('ekspedisi', $where);
		if($deleteData) {
			$response['ping'] = 200;
		} else {
			$response['ping'] = 500;
		}

		echo json_encode($response);
	}

}

/* End of file Guru.php */
/* Location: ./application/controllers/Guru.php */