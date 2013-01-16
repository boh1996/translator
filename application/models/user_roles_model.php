<?php
class User_Roles_Model extends CI_Model {

	/**
	 * This function retrives all the users roles for a project
	 * @since 1.0
	 * @access public
	 * @param integer $user_id    The id of the user to retrive roles for
	 * @param integer $project_id The project to retrieve the users roles for
	 * @return array An array containing "Role"'s objects
	 */
	public function get_user_project_roles ($user_id,$project_id) {
		$this->load->library("role");

		$query = $this->db->from("user_project_roles")->where(array(
			"user_id"		=> $user_id,
			"project_id"	=> $project_id
		))->select("role_id")->get();

		if ($query->num_rows()) {
			$roles = array();
			foreach ($query->result() as $row) {
				$Role = new Role();
				$Role->Load($row->role_id);
				$roles[] = $Role;
			}
			return $roles;
		} else {
			return array();
		}
	}

	/**
	 * This function retrive the modes/permissons for a user, for a specific project
	 * @since 1.0
	 * @access public
	 * @param integer $user_id    The users id
	 * @param integer $project_id The project to retrieve modes for
	 * @return array<string> An array containing the different permissons
	 */
	public function get_user_project_modes ($user_id,$project_id) {
		$roles = self::Get_User_Project_Roles($user_id,$project_id);

		$modes = array();

		if (count($roles) > 0) {
			foreach ($roles as $role) {
				$modes = array_merge($modes, $role->modes);
			}
			return array_unique($modes);
		} else {
			return array();
		}
	}

	/**
	 * This function add role to a project for a user
	 * @since 1.0
	 * @access public
	 * @param integer $user_id    The user to add the role to
	 * @param integer $project_id The project the user gets a role for
	 * @param integer $role_id    The role to add
	 */
	public function add_user_role ($user_id, $project_id, $role_id) {
		$data = array(
			"role_id"		=> $role_id,
			"project_id"	=> $project_id,
			"user_id"		=> $user_id
		);

		if (! self::exists($data)) {
			$this->db->insert("user_project_rolest",$data);
		}
	}

	/**
	 * This function deletes a role from the users list of project roles
	 * @since 1.0
	 * @access public
	 * @param  integer $user_id    The users id
	 * @param  integer $project_id The project to remove the users role from
	 * @param  integer $role_id    The role to remove
	 */
	public function delete_user_role ( $user_id, $project_id, $role_id ) {
		$this->db->where(array(
			"role_id"		=> $role_id,
			"user_id"		=> $user_id,
			"project_id" 	=> $project_id
		))->delete("user_project_roles");
	}

	/**
	 * This function can be used to check if data exists
	 * @since 1.0
	 * @access public
	 * @return boolean
	 * @param  array $where The where clause
	 * @param  string $table The table to search in
	 */
	public function exists($where,$table){
	    $this->db->where($where);
	    $query = $this->db->get($table);
	    if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }
	}
}