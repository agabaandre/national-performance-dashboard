<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_mdl extends CI_Model
{

	public function create($data = array())
	{
		return $this->db->insert('user', $data);
	}

	public function read()
	{
		return $this->db->select("
				user.*, 
				CONCAT_WS(' ', firstname, lastname) AS fullname 
			")
			->from('user')
			->order_by('lastname', 'ASC')
			->get()
			->result();
	}

	public function single($id = null)
	{
		return $this->db->select('*')
			->from('user')
			->where('id', $id)
			->get()
			->row();
	}

	public function update($data = array())
	{
		return $this->db->where('id', $data["id"])
			->update("user", $data);
	}

	public function delete($id = null)
	{
		return $this->db->where('id', $id)
			->where_not_in('is_admin', 1)
			->delete("user");
	}

	public function dropdown()
	{
		$data = $this->db->select("id, CONCAT_WS(' ', firstname, lastname) AS fullname")
			->from("user")
			->where('status', 1)
			->where_not_in('is_admin', 1)
			->get()
			->result();
		$list[''] = display('select_option');
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->id] = $value->fullname;
			return $list;
		} else {
			return false;
		}
	}

	/**
	 * Server-side DataTables method
	 * Returns filtered, sorted, and paginated user data
	 */
	public function get_datatables($start, $length, $search, $order_column, $order_dir)
	{
		// Build query for filtered count
		$this->db->select("user.id");
		$this->db->from('user');

		// Search functionality
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('firstname', $search);
			$this->db->or_like('lastname', $search);
			$this->db->or_like('email', $search);
			$this->db->or_like('user_type', $search);
			$this->db->or_like('ip_address', $search);
			$this->db->group_end();
		}

		// Get filtered count
		$filtered_records = $this->db->count_all_results('', false);

		// Reset query builder to avoid duplicate table/alias error
		$this->db->reset_query();

		// Build main query
		$this->db->select("
			user.*, 
			CONCAT_WS(' ', firstname, lastname) AS fullname 
		");
		$this->db->from('user');

		// Apply search again
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('firstname', $search);
			$this->db->or_like('lastname', $search);
			$this->db->or_like('email', $search);
			$this->db->or_like('user_type', $search);
			$this->db->or_like('ip_address', $search);
			$this->db->group_end();
		}

		// Ordering
		$columns = array(
			0 => 'id',
			1 => 'image',
			2 => 'fullname',
			3 => 'email',
			4 => 'last_login',
			5 => 'user_type',
			6 => 'ip_address',
			7 => 'status',
			8 => 'id'
		);

		if (isset($columns[$order_column])) {
			if ($order_column == 2) {
				// Order by fullname (concatenated)
				$this->db->order_by('lastname', $order_dir);
				$this->db->order_by('firstname', $order_dir);
			} else {
				$this->db->order_by($columns[$order_column], $order_dir);
			}
		} else {
			$this->db->order_by('lastname', 'ASC');
		}

		// Pagination
		if ($length > 0) {
			$this->db->limit($length, $start);
		}

		// Execute query
		$query = $this->db->get();
		return array(
			'data' => $query->result(),
			'total_records' => $filtered_records
		);
	}

	/**
	 * Get total count of all users
	 */
	public function count_all()
	{
		return $this->db->count_all('user');
	}




}