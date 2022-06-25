<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru extends CI_Controller {

	public function index()
	{
		$data = [
			'title'   => 'Guru',
			'url'     => 'Guru',
			'header' => 'Kelola Data Guru',
			'content' => 'view_guru'
		];		

		$this->load->view('layout/index', $data, FALSE);
	}

	public function loadTable()
	{
		$select = '*';
		$table  = 'guru';

		$limit = [
			'start'  => $this->input->get('start'),
			'finish' => $this->input->get('length')
		];

		$like['data'][] = [
			'column' => 'id_guru, nama_guru, deleted_guru',
			'param'  => $this->input->get('search[value]')
		];

		$indexOrder = $this->input->get('order[0][column]');
		$order['data'][] = [
			'column' => $this->input->get('columns['.$indexOrder.'][name]'),
			'type'   => $this->input->get('order[0][dir]')
		];

		$where['data'][] = [
			'column' => 'deleted_guru',
			'param'  => FALSE
		];

		$totalData  = $this->query->dataComplete($select, $table, NULL, NULL, NULL, NULL, $where);
		$filterData = $this->query->dataComplete($select, $table, NULL, $like, $order, NULL, $where);
		$queryData  = $this->query->dataComplete($select, $table, $limit, $like, $order, NULL, $where);

		$result['data'] = [];
		if($queryData <> FALSE) {
			$no = $limit['start'] + 1;

			foreach($queryData->result() as $query) {
				if($query->id_guru > 0) {
					$result['data'][] = [
						$no,
						$query->nama_guru,
						'
						<button onclick="showData('.$query->id_guru.')" class="btn btn-warning btn-circle btn-sm"><i class="fas fa-edit"></i></button>
						<button onclick="deleted('.$query->id_guru.')" class="btn btn-danger btn-circle btn-sm" title="Delete Data"><i class="fa fa-trash"></i></button>
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
		$where = ['id_guru' => $id];
		$data  = $this->query->getData('*', 'guru', $where)->row();
		echo json_encode($data);
	}

	public function insertData()
	{
		$data = [
					'nama_guru' => $this->input->post('nama_guru')
		];

		$insert = $this->query->insert('guru', $data);
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
			'nama_guru' => $this->input->post('nama_guru')
		];

		$table  = ['column' => 'id_guru', 'param' => $id, 'table' => 'guru'];
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
		$data       = ['deleted_guru' => TRUE];
		$table      = ['column' => 'id_guru', 'param' => $id, 'table' => 'guru'];
		$deleteData = $this->query->update($table, $data);
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