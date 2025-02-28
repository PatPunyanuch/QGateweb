<?php
class Backoffice_model extends CI_Model
{
	// *********************************LOGINPAGE *********************************************************
	public function modelCheckLogin($empcode, $password_encoded)
	{
		$sql = "select * from sys_staff_web where ss_emp_code ='{$empcode}' and ss_emp_password ='{$password_encoded}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		if (empty($row)) {
			return "false";
		} else {
			return "true";
		}
	}
	public function modelCheckLoginSession($empcode, $password_encoded)
	{
		$sql = "SELECT * FROM sys_staff_web
		INNER JOIN mst_plant_admin_web  ON  sys_staff_web.mpa_id = mst_plant_admin_web.mpa_id 
		WHERE ss_emp_code ='{$empcode}' AND ss_emp_password ='{$password_encoded}'";
		$res = $this->db->query($sql);
		if ($res->num_rows() != 0) {
			$result = $res->result_array();
			return $result[0];
		} else {
			return false;
		}
		// if (empty($row)) {
		// 	return "false";
		// } else {
		// 	return "true";
		// }
	}

	// ******************** FORGOTPASSWORD *********************************************************
	public function modelCheckEmail($email)
	{
		$sql = "select * from sys_staff_web where ss_email ='{$email}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		if (empty($row)) {
			return "false";
		} else {
			return "true";
		}
	}
	public function modelSetPassword($email, $password_encoded)
	{
		// if($email =! ""){
		$sql = "select * from sys_staff_web where ss_email ='{$email}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		// var_dump();
		// exit();
		if ($row == null) {
			return 'false';
		} else {
			$sql = "update sys_staff_web set ss_emp_password = '{$password_encoded}' where ss_email = '{$email}'";
			$res = $this->db->query($sql);
			return 'true';
		}

		// ******************** GETNAMETOHOMEPAGE *********************************************************


	}
	public function modelGetName($empcode)
	{
		$sql = "select ss_emp_fname from sys_staff_web where ss_emp_code ='{$empcode}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}
	// *********************** SHOWMENU AND GET STAFF DATA***********************************************************************
	public function modelShowMenu($empcode)
	{
		$sql = "SELECT ss_id,ss_emp_code,ss_emp_fname,spg_name,sm_name_menu,ssm_name_submenu,ssm_method,sm_name_icon,mpa_phase_plant FROM sys_staff_web 
		INNER JOIN sys_permission_group_web ON sys_staff_web.spg_id=sys_permission_group_web.spg_id
		INNER JOIN sys_permission_detail_web ON sys_permission_group_web.spg_id = sys_permission_detail_web.spg_id
		INNER JOIN sys_submenu_web ON sys_permission_detail_web.ssm_id =sys_submenu_web.ssm_id
		INNER JOIN sys_menu_web ON sys_submenu_web.sm_id = sys_menu_web.sm_id
		INNER JOIN mst_plant_admin_web  ON  sys_staff_web.mpa_id = mst_plant_admin_web.mpa_id 
		
		WHERE  ss_emp_code = '{$empcode}' and  ssm_status = '1' and spd_status = '1' ORDER BY ss_id";

		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
		// echo "<pre>";
		//  print_r($row);
		// echo "</pre>";
	}
	// *********************** getphasetoEditProfile ***********************************************************************

	public function modelGetPhase()
	{
		$sql = "select  mpa_id,mpa_phase_plant,mpa_name from mst_plant_admin_web ";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}
	// *********************** UPDATEUSER IN EDITPROFILEPAGE ***********************************************************************

	public function convert($attr, $table, $condition)
	{
		$sql = "SELECT $attr FROM $table WHERE $condition";
		$query = $this->db->query($sql);
		$get = $query->result_array();
		if (empty($get)) {
			return "0";
		} else {
			return $get["0"][$attr];
		}
	}
	public function modelUpdateDetailUser($empcode, $firstname, $lastname, $email, $plant)
	{
		$sql = "UPDATE sys_staff_web 
		SET ss_emp_fname = '{$firstname}',ss_emp_lname= '{$lastname}',ss_email='{$email}',mpa_id= '{$plant}'
		,ss_update_by = '{$empcode}',ss_update_date = CURRENT_TIMESTAMP
		WHERE ss_emp_code = '{$empcode}'";
		$res = $this->db->query($sql);
		if (empty($res)) {
			return "false";
		} else {
			return "true";
		}
	}
	// ****************************************************** Change PAssword *******************************************
	public function modelCheckCurrentPass($empcode, $password_encoded)
	{
		$sql = "SELECT * FROM sys_staff_web WHERE ss_emp_code = '{$empcode}' AND ss_emp_password = '{$password_encoded}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		if (empty($row)) {
			return "false";
		} else {
			return "true";
		}
	}

	public function modelChangePass($empcode, $confirmpass_encoded)
	{
		$sql = "UPDATE sys_staff_web SET ss_emp_password = '{$confirmpass_encoded}' WHERE ss_emp_code = '{$empcode}'";
		$res = $this->db->query($sql);
		if (empty($res)) {
			return "false";
		} else {
			return "true";
		}
	}
	// ************************************************ MANAGE USER WEB *********************************************************
	public function gettableUserWeb()
	{
		$sql = "SELECT ss_id,ss_emp_code,ss_emp_fname,ss_emp_lname,ss_email,spg_name,mpa_name,ss_status from sys_staff_web
		INNER JOIN mst_plant_admin_web ON sys_staff_web.mpa_id = mst_plant_admin_web.mpa_id
		INNER JOIN sys_permission_group_web ON sys_staff_web.spg_id = sys_permission_group_web.spg_id";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}
	public function editStatus($staffid)
	{
		$sql = "select ss_id,ss_emp_code,ss_status from sys_staff_web 
				INNER JOIN sys_permission_group_web ON sys_staff_web.spg_id = sys_permission_group_web.spg_id
				WHERE ss_id = '{$staffid}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$result = $row[0]["ss_status"];
		if ($result == 1) {
			$sql = "UPDATE sys_staff_web SET ss_status = 0 WHERE  ss_id = '{$staffid}'";
			$res = $this->db->query($sql);
			if ($res) {
				return true;
			} else {
				return false;
			}
		} else if ($result == 0) {
			$sql = "UPDATE sys_staff_web SET ss_status = 1 WHERE  ss_id = '{$staffid}'";
			$res = $this->db->query($sql);
			if ($res) {
				return true;
			} else {
				return false;
			}
		} else {
			return  true;
		}
	}
	public function checkUserAdd($empcode)
	{
		$sql = "select sys_staff_web.ss_emp_code from sys_staff_web WHERE sys_staff_web.ss_emp_code ='{$empcode}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		if ($row) {
			return "false";
		} else {
			return "true";
		}
	}
	public function getTableGroupPermission()
	{
		$sql = "SELECT * FROM sys_permission_group_web";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}
	public function insertUserWeb($empcode, $firstname, $lastname, $email, $groupper, $password_encoded, $plant, $empcodeadmin)
	{
		// echo " empcode==> ".$empcode;
		// echo "  firstname==> ".$firstname;
		// echo "  lastname==> ".$lastname;
		// echo "  groupper==> ".$groupper;
		// echo "  email==> ".$email;
		// echo "  password==> ".$password_encoded;
		// echo "  plant==> ".$plant;
		// echo "  empcodeadmin==> ".$empcodeadmin;
		$sql = "INSERT INTO sys_staff_web (ss_emp_code,ss_emp_fname,ss_emp_lname,spg_id,ss_email,ss_emp_password,mpa_id,ss_create_by,ss_create_date)
		VALUES('{$empcode}','{$firstname}','{$lastname}','{$groupper}','{$email}','{$password_encoded}','{$plant}','{$empcodeadmin}',CURRENT_TIMESTAMP)";
		$res = $this->db->query($sql);
		if (empty($res)) {
			return "false";
		} else {
			return "true";
		}
	}
	public function GetDataEditUser($ss_id)
	{
		$sql = "select ss_id,ss_emp_code,ss_emp_fname,ss_emp_lname,spg_name,ss_email,mpa_name,ss_status from sys_staff_web 
		INNER JOIN sys_permission_group_web ON sys_staff_web.spg_id = sys_permission_group_web.spg_id
		INNER JOIN mst_plant_admin_web ON sys_staff_web.mpa_id = mst_plant_admin_web.mpa_id
		WHERE ss_id = '{$ss_id}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}
	public function UpdateUserWeb($empcode, $firstname, $lastname, $email, $groupper, $plant, $empcodeadmin)
	{
		$sql = "UPDATE sys_staff_web SET ss_emp_code = '{$empcode}',ss_emp_fname = '{$firstname}',ss_emp_lname = '{$lastname}',ss_email = '{$email}' 
		,spg_id = '{$groupper}',mpa_id = '{$plant}' ,ss_create_by='{$empcodeadmin}',ss_create_date = CURRENT_TIMESTAMP
		WHERE ss_emp_code = '{$empcode}'";
		$res = $this->db->query($sql);
		if ($res) {
			return "true";
		} else {
			return "false";
		}
	}

	// ***************************** MANAGE PERMISSION WEB *****************************************
	public function getTableManagePermissionWeb()
	{
		$sql = "SELECT spg_id, spg_name, spg_status FROM  sys_permission_group_web";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}
	public function checkAddNamePermissionWeb($permissionwebname){//ถ้าquery แล้วมีอยู่ใน DB Return false ไม่ให้Add
		$sql = "SELECT * FROM sys_permission_group_web WHERE spg_name = '{$permissionwebname}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		if(empty($row)){
			return "true";
		} else {
			return "false";
		}
	}
	public function insertPermissionWeb($permissionwebname,$empcodeadmin)
	{
		$sql = "INSERT INTO sys_permission_group_web(spg_name, spg_create_by, spg_create_date)
		VALUES ('{$permissionwebname}', '{$empcodeadmin}',CURRENT_TIMESTAMP)";
		$res = $this->db->query($sql);
		if (empty($res)) {
			return "false";
		} else {
			return "true";
		}
	}
	public function insertPermissionDetailWeb($permissionwebnameconvert, $checkboxaddsubmenu, $empcodeadmin){
		$sql = "INSERT INTO sys_permission_detail_web (spg_id,ssm_id,create_by,create_date)
		VALUES ('{$permissionwebnameconvert}','{$checkboxaddsubmenu}','{$empcodeadmin}',CURRENT_TIMESTAMP)";
		$res = $this->db->query($sql);
		if($res){
			return "true";
		}else {
			return "false";
		}

	}
	public function modelGetMenu()
	{
		$sql = "select  * from sys_menu_web";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}
	public function modelGetSubmenu()
	{
		$sql = "select  * from sys_submenu_web";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}
	public function editStatusPermissionWeb($Perid)
	{
		$sql = "select * from sys_permission_group_web WHERE spg_id = '{$Perid}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		$result = $row[0]["spg_status"];
		if ($result == 1) {
			$sql = "UPDATE sys_permission_group_web SET spg_status = 0 WHERE  spg_id = '{$Perid}'";
			$res = $this->db->query($sql);
			if ($res) {
				return true;
			} else {
				return false;
			}
		} else if ($result == 0) {
			$sql = "UPDATE sys_permission_group_web SET spg_status = 1 WHERE  spg_id = '{$Perid}'";
			$res = $this->db->query($sql);
			if ($res) {
				return true;
			} else {
				return false;
			}
		} else {
			return  true;
		}
	}
	public function GetDataEditPermissionweb($spg_id)
	{
		$sql = "SELECT spg_name FROM sys_permission_group_web WHERE spg_id = '{$spg_id}'";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}
	public function GetDataEditPermissionMenuweb($spg_id)
	{
		$sql = "";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}


// ***************************** MANAGE PERMISSION WEB *****************************************
	public function getTableManageMenuweb()
	{
		$sql = "SELECT * FROM sys_submenu_web
		INNER JOIN sys_menu_web ON sys_submenu_web.sm_id = sys_menu_web.sm_id";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}
}
