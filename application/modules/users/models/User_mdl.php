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




}