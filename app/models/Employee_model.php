<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employee_model  extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	
	function getManageEmployee($offset="",$record="", $countType="")
	{
		if($_GET)
		{
			if($countType == 1) #GetTotalCount
			{
				$limit = "";
			}
			else if($countType == 2) #Get Page Wise Count
			{
				$limit = "limit ".$record." , ".$offset." "; 
			}

			if(empty($_GET['person_id'])){
				$person_id = 'NULL';
			}else{
				$person_id = $_GET['person_id'];
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			if(empty($_GET['mobile_number'])){
				$mobile_number = 'NULL';
			}else{
				$mobile_number = "concat('%','".$_GET['mobile_number']."','%')";
			}

			$query = "select 
						employee.person_id,
						employee.employee_number,
						
						employee.gender,
						employee.date_of_birth,
						employee.date_of_joining,
						employee.first_name,
						employee.last_name,
						employee.middle_name,
						employee.mobile_number,
						employee.email_address,
						employee.mob_ctry_code,
						employee.alt_mob_ctry_code,
						employee.created_date,
						country.country_code,
						employee.active_flag
			from per_people_all as employee

			left join geo_countries as country on
				country.country_id = employee.country_id

			where 1=1 and employee.deleted_flag ='N'
			and employee.person_id = coalesce($person_id,employee.person_id)
			and employee.active_flag = if('".$active_flag."' = 'All',employee.active_flag,'".$active_flag."')
			and employee.mobile_number like coalesce($mobile_number,employee.mobile_number)
			order by employee.person_id desc $limit";
			
			$result = $this->db->query($query)->result_array();
			//print_r($result);exit;
			return $result;
		}
		else
		{
			return array();
		}
	}

	/*function getManageBloodGroupCount()
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								blood_group.blood_group_name like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select blood_group.blood_group_id 
		from emp_blood_group as blood_group
		where $condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}*/
	function getManageBloodGroup($offset="",$record="", $countType="")
	{
		if($_GET)
		{
			if($countType == 1) #GetTotalCount
			{
				$limit = "";
			}
			else if($countType == 2) #Get Page Wise Count
			{
				$limit = "limit ".$record." , ".$offset." "; 
			}

			

			$active_flag = $_GET['active_flag'];
			$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";

			$query = "select * from emp_blood_group as blood_group
			where 1=1
				and (
					blood_group.blood_group_name like coalesce($keywords,blood_group.blood_group_name)
				)
				and blood_group.active_flag = if('".$active_flag."' = 'All',blood_group.active_flag,'".$active_flag."')
				order by blood_group.blood_group_id desc";
			$result = $this->db->query($query)->result_array();
			return $result;

		} 
		else
		{
			return array();
		}
	}
	
	/*function getManageBloodGroup($offset="",$record="")
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								blood_group.blood_group_name like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select * from emp_blood_group as blood_group
		where $condition
			order by blood_group.blood_group_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}*/
	
	function getManageEmployeeBanksCount()
	{
		$condition = "1 = 1 and primary_bank = 1";

		if (!empty($_GET['keywords']) ) 
		{
			$condition .= ' and (
				users.first_name like "%'.($_GET['keywords']).'%" or 
				emp_bank_details.bank_name like "%'.($_GET['keywords']).'%" or 
				emp_bank_details.branch_name like "%'.($_GET['keywords']).'%" or 
				emp_bank_details.ifsc_code like "%'.($_GET['keywords']).'%" or 
				emp_bank_details.micr_code like "%'.($_GET['keywords']).'%" or 
				emp_bank_details.account_number like "%'.($_GET['keywords']).'%" 
			)
			';

		}
		$query = "select emp_bank_details.bank_id
					from emp_bank_details
					left join users on users.user_id  = emp_bank_details.user_id
					where $condition ";

		$result = $this->db->query($query)->result_array();
		return count($result);			
	}

	function getManageEmployeeBanks($offset="",$record="")
	{
		$condition = "1 = 1 and primary_bank = 1";

		if (!empty($_GET['keywords']) ) 
		{
			$condition .= ' and (
				users.first_name like "%'.($_GET['keywords']).'%" or 
				emp_bank_details.bank_name like "%'.($_GET['keywords']).'%" or 
				emp_bank_details.branch_name like "%'.($_GET['keywords']).'%" or 
				emp_bank_details.ifsc_code like "%'.($_GET['keywords']).'%" or 
				emp_bank_details.micr_code like "%'.($_GET['keywords']).'%" or 
				emp_bank_details.account_number like "%'.($_GET['keywords']).'%" 
			)
			';
			
		}

		$query = "select emp_bank_details.* , users.first_name ,users.last_name ,users.random_user_id
					from emp_bank_details
					left join users on users.user_id  = emp_bank_details.user_id
					where $condition 
						order by emp_bank_details.bank_id asc
							limit ".$record." , ".$offset."";

		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function empRelationCount()
	{
		$condition = " 1=1 ";

		if( isset($_GET['keywords']) && $_GET['keywords'] !="" )
		{
			$condition .= 'and relation.relationship_name like "%'.($_GET['keywords']).'%" ';
		}

		$query = "select 
				relation.relationship_id
			from emp_relationships as relation
		
		where $condition";

		$result = $this->db->query($query)->result_array();
		return count($result);
	}

	function empRelation($offset="",$record="")
	{
		$condition = " 1=1 ";

		if( isset($_GET['keywords']) && $_GET['keywords'] !="" )
		{
			$condition .= 'and relation.relationship_name like "%'.($_GET['keywords']).'%" ';
		}

		$query = "select relation.relationship_id,relation.relationship_name,relation.relationship_status
			from emp_relationships as relation
			where $condition
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}
	
	function getManageFixedExpCount()
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									expences.expense_name like "%'.($_GET['keywords']).'%" or 
									expences.expense_description like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select 
					expences.expenses_id
		from emp_fixed_expenses_types as expences
		where $condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageFixedExp($offset="",$record="")
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									expences.expense_name like "%'.($_GET['keywords']).'%" or 
									expences.expense_description like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select *
			from emp_fixed_expenses_types as expences
		
		where $condition
			order by expences.expenses_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}
	
	#Manage Salary Slip Starts
	
	function getManageEmployeeSalaryslipCount()
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									users.first_name like "%'.($_GET['keywords']).'%" 
								)
							';
		}
		
		$query = "select 
					emp_salaryslip.emp_salaryslip_id,
					users.first_name
		from emp_salaryslip
		
		left join users on users.user_id = emp_salaryslip.employee_id
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageEmployeeSalaryslip($offset="",$record="")
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									users.first_name like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select *,
				users.first_name
			
			from emp_salaryslip
		
		left join users on users.user_id = emp_salaryslip.employee_id
		
		where $condition
			order by emp_salaryslip.emp_salaryslip_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}
	#Manage Salary Slip Ends
	
	function getManageDesinationsCount()
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									designations.designation_name like "%'.($_GET['keywords']).'%" or 
									designations.designation_description like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select 
					designations.designation_id
		from emp_designations as designations
		where $condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageDesinations($offset="",$record="")
	{
		$condition = " 1=1 ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									designation.designation_name like "%'.($_GET['keywords']).'%" or 
									designation.designation_description like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select *
			from emp_designations as designation
		
		where $condition
			order by designation.designation_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function getManageEmployeeTrackerCount()
	{
		$condition = " 1=1 and users.register_type =1"; #
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								users.uan_number like "%'.($_GET['keywords']).'%" or 
								users.first_name like "%'.($_GET['keywords']).'%" or 
								users.middle_name like "%'.($_GET['keywords']).'%" or 
								users.last_name like "%'.($_GET['keywords']).'%" or 
								users.member_id like "%'.($_GET['keywords']).'%" or
								users.random_user_id like "%'.($_GET['keywords']).'%"  or 
								org_projects.project_description like "%'.($_GET['keywords']).'%" or
								org_tasks.task_description like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		if(!empty($_GET['branch_id']))
		{
			$condition .= ' and users.branch_id="'.$_GET['branch_id'].'"';
		}
		
		if(!empty($_GET['employee_id']))
		{
			$condition .= ' and users.user_id="'.$_GET['employee_id'].'"';
		}
		
		if(!empty($_GET['role_id']))
		{
			$condition .= ' and role.role_id="'.$_GET['role_id'].'"';
		}
		
		#Filter Date Start here
		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			if($_GET['from_date'] == $_GET['to_date'])
			{
				$condition .= ' and attendance.attendance_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
			}
			else
			{ 
				#$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
				$condition .= ' and attendance.attendance_date >= '.strtotime($_GET['from_date']).' and attendance.attendance_date <= '.strtotime($_GET['to_date']).' ';
			}
		}
		
		if( !empty($_GET['from_date']) )
		{
			$condition .= ' and attendance.attendance_date >= '.strtotime($_GET['from_date']).' ';
			
		}
		if( !empty($_GET['to_date']) )
		{
			$condition .= ' and attendance.attendance_date <= '.strtotime($_GET['to_date']).' ';
			
		}
		#Filter Date End here
		
		$query = "select 
					users.user_id
		from users 
		
		left join branch on branch.branch_id = users.branch_id 
		left join emp_departments as department on department.department_id = users.department_id 
		left join emp_designations as designation on designation.designation_id = users.designation_id 

		left join emp_attendance as attendance on attendance.employee_id = users.user_id 
		left join emp_roles as role on role.role_id = users.branch_id  
		join org_tasks on org_tasks.task_id = attendance.task_id 
		left join org_projects on org_projects.project_id = attendance.project_id 
		
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageEmployeeTracker($offset="",$record="")
	{
		$condition = " 1=1 and users.register_type =1"; #
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								users.first_name like "%'.($_GET['keywords']).'%" or 
								branch.branch_name like "%'.($_GET['keywords']).'%" or 
								department.department_name like "%'.($_GET['keywords']).'%" or 
								designation.designation_name like "%'.($_GET['keywords']).'%" or 
								role.role_name like "%'.($_GET['keywords']).'%" or  
								users.random_user_id like "%'.($_GET['keywords']).'%"  or 
								org_projects.project_description like "%'.($_GET['keywords']).'%" or
								org_tasks.task_description like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		if(!empty($_GET['branch_id']))
		{
			$condition .= ' and users.branch_id="'.$_GET['branch_id'].'"';
		}
		
		if(!empty($_GET['employee_id']))
		{
			$condition .= ' and users.user_id="'.$_GET['employee_id'].'"';
		}
		
		if(!empty($_GET['role_id']))
		{
			$condition .= ' and role.role_id="'.$_GET['role_id'].'"';
		}
		
		#Filter Date Start here
		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			if($_GET['from_date'] == $_GET['to_date'])
			{
				$condition .= ' and attendance.attendance_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
			}
			else
			{ 
				#$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
				$condition .= ' and attendance.attendance_date >= '.strtotime($_GET['from_date']).' and attendance.attendance_date <= '.strtotime($_GET['to_date']).' ';
			}
		}
		
		if( !empty($_GET['from_date']) )
		{
			$condition .= ' and attendance.attendance_date >= '.strtotime($_GET['from_date']).' ';
			
		}
		if( !empty($_GET['to_date']) )
		{
			$condition .= ' and attendance.attendance_date <= '.strtotime($_GET['to_date']).' ';
			
		}
		#Filter Date End here
		
		$query = "select 
					users.user_id,
					users.random_user_id,
					users.gender,
					users.date_of_birth,
					users.date_of_joining,
					
					users.first_name,
					
					branch.branch_id,
					branch.branch_name,
					department.department_id,
					department.department_name,
					designation.designation_id,
					designation.designation_name,
					role.role_id,
					role.role_name,
					
					org_projects.project_description,
					org_tasks.task_description,
					attendance.attendance_date,
					attendance.wages
		from users
		
		left join branch on branch.branch_id = users.branch_id 
		left join emp_departments as department on department.department_id = users.department_id 
		left join emp_designations as designation on designation.designation_id = users.designation_id 

		left join emp_attendance as attendance on attendance.employee_id = users.user_id 
		left join emp_roles as role on role.role_id = users.branch_id  
		join org_tasks on org_tasks.task_id = attendance.task_id 
		left join org_projects on org_projects.project_id = attendance.project_id
		
		where $condition
			order by attendance.attendance_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}
	
	function getManageEmployeeBasedTrackerCount($id="")
	{
		$condition = " 1=1 and users.register_type =1 and users.user_id='".$id."'"; #
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								users.uan_number like "%'.($_GET['keywords']).'%" or 
								users.first_name like "%'.($_GET['keywords']).'%" or 
								users.middle_name like "%'.($_GET['keywords']).'%" or 
								users.last_name like "%'.($_GET['keywords']).'%" or 
								users.member_id like "%'.($_GET['keywords']).'%" or
								users.random_user_id like "%'.($_GET['keywords']).'%"  or 
								org_projects.project_description like "%'.($_GET['keywords']).'%" or
								org_tasks.task_description like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		if(!empty($_GET['branch_id']))
		{
			$condition .= ' and users.branch_id="'.$_GET['branch_id'].'"';
		}
		
		if(!empty($_GET['employee_id']))
		{
			$condition .= ' and users.user_id="'.$_GET['employee_id'].'"';
		}
		
		if(!empty($_GET['role_id']))
		{
			$condition .= ' and role.role_id="'.$_GET['role_id'].'"';
		}
		
		#Filter Date Start here
		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			if($_GET['from_date'] == $_GET['to_date'])
			{
				$condition .= ' and attendance.attendance_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
			}
			else
			{ 
				#$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
				$condition .= ' and attendance.attendance_date >= '.strtotime($_GET['from_date']).' and attendance.attendance_date <= '.strtotime($_GET['to_date']).' ';
			}
		}
		
		if( !empty($_GET['from_date']) )
		{
			$condition .= ' and attendance.attendance_date >= '.strtotime($_GET['from_date']).' ';
			
		}
		if( !empty($_GET['to_date']) )
		{
			$condition .= ' and attendance.attendance_date <= '.strtotime($_GET['to_date']).' ';
			
		}
		#Filter Date End here
		
		$query = "select 
					users.user_id
		from users 
		
		join branch on branch.branch_id = users.branch_id
		join emp_departments as department on department.department_id = users.department_id
		join emp_designations as designation on designation.designation_id = users.designation_id
		join emp_roles as role on role.role_id = users.role_id
		
		join emp_attendance as attendance on attendance.employee_id = users.user_id
		
		
		left join org_tasks on org_tasks.task_id = attendance.task_id
		left join org_projects on org_projects.project_id = attendance.project_id
		
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageEmployeeBasedTracker($offset="",$record="",$id="")
	{
		$condition = " 1=1 and users.register_type =1 and users.user_id='".$id."' "; #
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								users.first_name like "%'.($_GET['keywords']).'%" or 
								branch.branch_name like "%'.($_GET['keywords']).'%" or 
								department.department_name like "%'.($_GET['keywords']).'%" or 
								designation.designation_name like "%'.($_GET['keywords']).'%" or 
								role.role_name like "%'.($_GET['keywords']).'%" or 
								org_projects.project_description like "%'.($_GET['keywords']).'%" or
								org_tasks.task_description like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		
		if(!empty($_GET['branch_id']))
		{
			$condition .= ' and users.branch_id="'.$_GET['branch_id'].'"';
		}
		
		if(!empty($_GET['employee_id']))
		{
			$condition .= ' and users.user_id="'.$_GET['employee_id'].'"';
		}
		
		if(!empty($_GET['role_id']))
		{
			$condition .= ' and role.role_id="'.$_GET['role_id'].'"';
		}
		
		#Filter Date Start here
		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			if($_GET['from_date'] == $_GET['to_date'])
			{
				$condition .= ' and attendance.attendance_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
			}
			else
			{ 
				#$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
				$condition .= ' and attendance.attendance_date >= '.strtotime($_GET['from_date']).' and attendance.attendance_date <= '.strtotime($_GET['to_date']).' ';
			}
		}
		
		if( !empty($_GET['from_date']) )
		{
			$condition .= ' and attendance.attendance_date >= '.strtotime($_GET['from_date']).' ';
			
		}
		if( !empty($_GET['to_date']) )
		{
			$condition .= ' and attendance.attendance_date <= '.strtotime($_GET['to_date']).' ';
			
		}
		#Filter Date End here
		
		$query = "select 
					users.user_id,
					users.random_user_id,
					users.gender,
					users.date_of_birth,
					users.date_of_joining,
					
					users.first_name,
					
					branch.branch_id,
					branch.branch_name,
					department.department_id,
					department.department_name,
					designation.designation_id,
					designation.designation_name,
					role.role_id,
					role.role_name,
					
					org_projects.project_id,
					org_projects.project_description,
					org_tasks.task_description,
					attendance.attendance_date,
					attendance.wages
		from users
		
		join branch on branch.branch_id = users.branch_id
		join emp_departments as department on department.department_id = users.department_id
		join emp_designations as designation on designation.designation_id = users.designation_id
		join emp_roles as role on role.role_id = users.role_id
		
		join emp_attendance as attendance on attendance.employee_id = users.user_id
		left join org_tasks on org_tasks.task_id = attendance.task_id
		left join org_projects on org_projects.project_id = attendance.project_id
		
		where $condition
			order by attendance.attendance_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}
	
	function getManageEmpAssignedProjectsCount($id="")
	{
		$condition = " 1=1 and hr_emp_assign_projects.user_id='".$id."' "; #
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									org_projects.project_code like "%'.($_GET['keywords']).'%" or 
									org_projects.project_description like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select 
					hr_emp_assign_projects.assign_id
		from hr_emp_assign_projects
		
		left join org_projects on org_projects.project_id = hr_emp_assign_projects.project_id
		
		left join hr_project_workstatus_header on 
			hr_project_workstatus_header.project_id = hr_emp_assign_projects.project_id
		
		where $condition
		";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageEmpAssignedProjects($offset="",$record="",$id="")
	{
		$condition = " 1=1 and hr_emp_assign_projects.user_id='".$id."' "; #
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									org_projects.project_code like "%'.($_GET['keywords']).'%" or 
									org_projects.project_description like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select 
					hr_emp_assign_projects.*,
					org_projects.project_id,
					org_projects.project_code,
					org_projects.project_description,
					hr_project_workstatus_header.assign_project_status as project_work_status
		from hr_emp_assign_projects
		
		left join org_projects on 
			org_projects.project_id = hr_emp_assign_projects.project_id
		
		left join hr_project_workstatus_header on 
			hr_project_workstatus_header.project_id = hr_emp_assign_projects.project_id
		
		where $condition
			order by hr_emp_assign_projects.assign_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}
	#Position Starts
	function getManagePositionsCount()
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								position.position_name like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select position.position_id
		from hr_positions as position
		where $condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManagePositions($offset="",$record="")
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								position.position_name like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select * from hr_positions as position
		where $condition
			order by position.position_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}
	#Position Ends
	
	#Qualification Starts
	function getManageQualificationCount()
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									qualification.qualification_name like "%'.($_GET['keywords']).'%" 
								)
							';
		}
		
		$query = "select 
					qualification.qualification_id
		from qualification 
		where $condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageQualification($offset="",$record="")
	{
		$condition = " 1=1 ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									qualification.qualification_name like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select *
			from qualification
		
		where $condition
			order by qualification.qualification_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	#Qualification Ends
	
	#Department Starts
	function getManageDepartmentCount()
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									department.department_name like "%'.($_GET['keywords']).'%" or 
									department.department_description like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select 
					department.department_id
		from emp_departments as department
		where $condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageDepartment($offset="",$record="")
	{
		$condition = " 1=1 ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									department.department_name like "%'.($_GET['keywords']).'%" or 
									department.department_description like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select *
			from emp_departments as department
		
		where $condition
			order by department.department_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	#Department
	
	/* function getManageEmpDocuments($id="")
	{
		$result = $this->db->query("
			select 
				user_document_attachments.user_id,
				user_document_attachments.image_2,
				user_document_attachments.caption,
				user_document_attachments.description,
				user_document_attachments.document_type,
				user_document_categories.category_name

				from user_document_attachments 
			
			left join user_document_categories on
					user_document_categories.category_id =  user_document_attachments.category_id
			where
				user_document_attachments.user_id ='".$id."'
			")->result_array();
		return $result;
	} */
	
	function getEmployeeData($id="")
	{
		$query = "
			select 
			users.first_name,
			users.mobile_number,
			users.random_user_id,
			users.email,
			users.user_id
			from users
			
			where users.user_id='".$id."' 
		";
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}
	
	#assigned payslip
	/* function getAssignedPayslip($user_id="")
	{
		$condition = "1=1 and hr_emp_assign_payslip_categories.user_id = $user_id";

		$query = "select 
					hr_emp_assign_payslip_categories.emp_assign_id,
					hr_emp_assign_payslip_categories.category_id,
					hr_emp_assign_payslip_categories.category_type,
					hr_payslip_categories.category_name	
					from hr_emp_assign_payslip_categories
				left join hr_payslip_categories on hr_payslip_categories.category_id = hr_emp_assign_payslip_categories.category_id
					where $condition";

		$result = $this->db->query($query)->result_array();
		
		return $result;
	} */
	
	#getAssignedPayslipCount
	function getAssignedPayslipCount($user_id="")
	{
		$condition = "1=1 and hr_emp_assign_payslip_categories.user_id = '".$user_id."'";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									hr_payslip_categories.category_name like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select 
					hr_emp_assign_payslip_categories.emp_assign_id
					from hr_emp_assign_payslip_categories
				left join hr_payslip_categories on hr_payslip_categories.category_id = hr_emp_assign_payslip_categories.category_id
					where $condition
			";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	#getAssignedPayslip
	function getAssignedPayslip($offset="",$record="",$user_id="")
	{
		$condition = "1=1 and hr_emp_assign_payslip_categories.user_id = '".$user_id."'";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									hr_payslip_categories.category_name like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select 
					hr_emp_assign_payslip_categories.emp_assign_id,
					hr_emp_assign_payslip_categories.category_id,
					hr_emp_assign_payslip_categories.category_type,
					hr_emp_assign_payslip_categories.assigned_status,
					hr_emp_assign_payslip_categories.deduction_status,
					hr_payslip_categories.category_name	
					from hr_emp_assign_payslip_categories
				left join hr_payslip_categories on hr_payslip_categories.category_id = hr_emp_assign_payslip_categories.category_id
					where $condition
					
					order by hr_emp_assign_payslip_categories.emp_assign_id desc
				limit ".$record." , ".$offset."
			";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	#getEmployeeBankDetailsCount
	function getEmployeeBankDetailsCount($user_id="")
	{
		$condition = "1=1 and emp_bank_details.user_id = '".$user_id."'";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									emp_bank_details.bank_name like "%'.($_GET['keywords']).'%" or
									emp_bank_details.account_number like "%'.($_GET['keywords']).'%" or
									emp_bank_details.ifsc_code like "%'.($_GET['keywords']).'%" or
									emp_bank_details.micr_code like "%'.($_GET['keywords']).'%" or
									emp_bank_details.branch_name like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select emp_bank_details.bank_id from emp_bank_details
				where $condition
			";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	#getEmployeeBankDetails
	function getEmployeeBankDetails($offset="",$record="",$user_id="")
	{
		$condition = "1=1 and emp_bank_details.user_id = '".$user_id."'";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									emp_bank_details.bank_name like "%'.($_GET['keywords']).'%" or
									emp_bank_details.account_number like "%'.($_GET['keywords']).'%" or
									emp_bank_details.ifsc_code like "%'.($_GET['keywords']).'%" or
									emp_bank_details.micr_code like "%'.($_GET['keywords']).'%" or
									emp_bank_details.branch_name like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select emp_bank_details.*	from emp_bank_details
				where $condition
					order by emp_bank_details.bank_id desc
				limit ".$record." , ".$offset."
			";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	#getManageEmpDocumentsCount
	function getManageEmpDocumentsCount($user_id="")
	{
		$condition = "1=1 and user_document_attachments.user_id = '".$user_id."'";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									user_document_categories.category_name like "%'.($_GET['keywords']).'%" or
									user_document_attachments.caption like "%'.($_GET['keywords']).'%" or
									user_document_attachments.description like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		if(!empty($_GET['document_type']))
		{
			$condition .= ' and user_document_attachments.document_type= "'.$_GET['document_type'].'"';
		}
		
		$query ="
			select 
				user_document_attachments.attachement_id

				from user_document_attachments 
			
			left join user_document_categories on
					user_document_categories.category_id =  user_document_attachments.category_id
			where $condition
			";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	#getManageEmpDocuments
	function getManageEmpDocuments($offset="",$record="",$user_id="")
	{
		$condition = "1=1 and user_document_attachments.user_id ='".$user_id."'";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									user_document_categories.category_name like "%'.($_GET['keywords']).'%" or
									user_document_attachments.caption like "%'.($_GET['keywords']).'%" or
									user_document_attachments.description like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		if(!empty($_GET['document_type']))
		{
			$condition .= ' and user_document_attachments.document_type= "'.$_GET['document_type'].'"';
		}
		
		$query ="
			select 
				user_document_attachments.user_id,
				user_document_attachments.image_2,
				user_document_attachments.caption,
				user_document_attachments.description,
				user_document_attachments.document_type,
				user_document_categories.category_name

				from user_document_attachments 
			
			left join user_document_categories on
					user_document_categories.category_id =  user_document_attachments.category_id
			where $condition
				
				limit ".$record." , ".$offset."
			"; #order by emp_bank_details.bank_id desc
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	#Ratings Starts
	function getManageRatingCount()
	{
		if ($_GET) 
		{

			$condition = " 1=1 ";
			if(!empty($_GET['keywords']))
			{
				$condition .= ' and (
									users.first_name like "%'.($_GET['keywords']).'%" or 
									users.random_user_id like "%'.($_GET['keywords']).'%"
									)
								';
			}

			if(!empty($_GET['financial_year_id']))
			{
				$condition .= ' and ratings.financial_year_id='.$_GET['financial_year_id'];
			}

			if(!empty($_GET['period_id']))
			{
				$condition .= ' and ratings.period_id='.$_GET['period_id'];
			}

			if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
			{
				if($_GET['from_date'] == $_GET['to_date'])
				{
					$condition .= ' and ratings.string_from_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
				}
				else
				{ 
					#$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
					$condition .= ' and ratings.string_from_date >= '.strtotime($_GET['from_date']).' and ratings.string_from_date <= '.strtotime($_GET['to_date']).' ';
				}
			}
			
			if( !empty($_GET['from_date']) )
			{
				$condition .= ' and ratings.string_from_date >= '.strtotime($_GET['from_date']).' ';
				
			}
			if( !empty($_GET['to_date']) )
			{
				$condition .= ' and ratings.string_from_date <= '.strtotime($_GET['to_date']).' ';
				
			}
			$query = "select 
						ratings.header_id

			from emp_rating_line as ratings

			left join emp_periods on 
				emp_periods.period_id = ratings.period_id

			left join users on 
				users.user_id = ratings.user_id	

			left join org_financial_years on 
				org_financial_years.financial_year_id = ratings.financial_year_id

			where $condition";
			$result = $this->db->query($query)->result_array();
		}
		else
		{	
			$result =array();
		}

		return count($result);
	}
	
	function getManageRating($offset="",$record="")
	{
		if ($_GET) 
		{
			$condition = " 1=1 ";
			
			if(!empty($_GET['keywords']))
			{
				$condition .= ' and (
									users.first_name like "%'.($_GET['keywords']).'%" or
									users.random_user_id like "%'.($_GET['keywords']).'%"
									)
								';
			}

			if(!empty($_GET['financial_year_id']))
			{
				$condition .= ' and ratings.financial_year_id='.$_GET['financial_year_id'];
			}

			if(!empty($_GET['period_id']))
			{
				$condition .= ' and ratings.period_id='.$_GET['period_id'];
			}

			if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
			{
				if($_GET['from_date'] == $_GET['to_date'])
				{
					$condition .= ' and ratings.string_from_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
				}
				else
				{ 
					#$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
					$condition .= ' and ratings.string_from_date >= '.strtotime($_GET['from_date']).' and ratings.string_from_date <= '.strtotime($_GET['to_date']).' ';
				}
			}
			
			if( !empty($_GET['from_date']) )
			{
				$condition .= ' and ratings.string_from_date >= '.strtotime($_GET['from_date']).' ';
				
			}
			if( !empty($_GET['to_date']) )
			{
				$condition .= ' and ratings.string_from_date <= '.strtotime($_GET['to_date']).' ';
				
			}
			$query = "select ratings.*,
				users.first_name,
				users.last_name,
				users.random_user_id,
				emp_periods.month,
				emp_periods.year,
				
				org_financial_years.financial_from_year,
				org_financial_years.financial_to_year,

				org_financial_years.financial_from_month,
				org_financial_years.financial_to_month

				from emp_rating_line as ratings
				
				left join emp_periods on 
					emp_periods.period_id = ratings.period_id

				left join users on 
					users.user_id = ratings.user_id	

				left join org_financial_years on 
					org_financial_years.financial_year_id = ratings.financial_year_id

			where $condition
				order by ratings.line_id desc
					limit ".$record." , ".$offset."
			";
			$result = $this->db->query($query)->result_array();
		}
		else
		{	
			$result =array();
		}
		return $result;
	}
	#Ratings

	#Ratings Starts
	function getManageLeaveCount()
	{
		if ($_GET) 
		{

			$condition = " 1=1 ";
			
			if(!empty($_GET['keywords']))
			{
				$condition .= ' and (
										users.first_name like "%'.($_GET['keywords']).'% " or
										users.last_name like "%'.($_GET['keywords']).'% " or
										users.random_user_id like "%'.($_GET['keywords']).'%"
									)
								';
			}

			if(!empty($_GET['financial_year_id']))
			{
				$condition .= ' and leaves.financial_year_id='.$_GET['financial_year_id'];
			}
			
			if(!empty($_GET['period_id']))
			{
				$condition .= ' and leaves.period_id='.$_GET['period_id'];
			}

			if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
			{
				if($_GET['from_date'] == $_GET['to_date'])
				{
					$condition .= ' and leaves.string_from_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
				}
				else
				{ 
					#$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
					$condition .= ' and leaves.string_from_date >= '.strtotime($_GET['from_date']).' and leaves.string_from_date <= '.strtotime($_GET['to_date']).' ';
				}
			}
			
			if( !empty($_GET['from_date']) )
			{
				$condition .= ' and leaves.string_from_date >= '.strtotime($_GET['from_date']).' ';
				
			}
			if( !empty($_GET['to_date']) )
			{
				$condition .= ' and leaves.string_from_date <= '.strtotime($_GET['to_date']).' ';
				
			}
			$query = "select 
						leaves.header_id

			from emp_leaves_line as leaves

			left join emp_periods on 
				emp_periods.period_id = leaves.period_id

			left join org_financial_years on 
				org_financial_years.financial_year_id = leaves.financial_year_id

			left join users on 
				users.user_id = leaves.user_id

			where $condition";
			$result = $this->db->query($query)->result_array();
		}
		else
		{	
			$result =array();
		}
		return count($result);
	}
	
	function getManageLeave($offset="",$record="")
	{
		if ($_GET) 
		{
			$condition = " 1=1 ";
			
			if(!empty($_GET['keywords']))
			{
				$condition .= ' and (
										users.first_name like "%'.($_GET['keywords']).'% " or
										users.last_name like "%'.($_GET['keywords']).'% " or
										users.random_user_id like "%'.($_GET['keywords']).'%"
									)
								';
			}

			if(!empty($_GET['financial_year_id']))
			{
				$condition .= ' and leaves.financial_year_id='.$_GET['financial_year_id'];
			}

			if(!empty($_GET['period_id']))
			{
				$condition .= ' and leaves.period_id='.$_GET['period_id'];
			}

			if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
			{
				if($_GET['from_date'] == $_GET['to_date'])
				{
					$condition .= ' and leaves.string_from_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
				}
				else
				{ 
					#$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
					$condition .= ' and leaves.string_from_date >= '.strtotime($_GET['from_date']).' and leaves.string_from_date <= '.strtotime($_GET['to_date']).' ';
				}
			}
			
			if( !empty($_GET['from_date']) )
			{
				$condition .= ' and leaves.string_from_date >= '.strtotime($_GET['from_date']).' ';
				
			}
			if( !empty($_GET['to_date']) )
			{
				$condition .= ' and leaves.string_from_date <= '.strtotime($_GET['to_date']).' ';
				
			}

			$query = "select 
				leaves.*,
				users.first_name,
				users.last_name,
				users.random_user_id,
				emp_periods.month,
				emp_periods.year,
				org_financial_years.financial_from_year,
				org_financial_years.financial_to_year,

				org_financial_years.financial_from_month,
				org_financial_years.financial_to_month

				from emp_leaves_line as leaves

				left join emp_periods on 
					emp_periods.period_id = leaves.period_id
				
				left join org_financial_years on 
					org_financial_years.financial_year_id = leaves.financial_year_id

				left join users on 
					users.user_id = leaves.user_id	
			where $condition
				order by leaves.line_id desc
					limit ".$record." , ".$offset."
			";
			
			$result = $this->db->query($query)->result_array();
		}
		else
		{	
			$result =array();
		}
		return $result;
	}
	#Ratings

	function getManageIncentiveCount()
	{
        if ($_GET) 
		{
            $condition = " 1=1 ";
            if (!empty($_GET['keywords'])) {
                $condition .= ' and (
										users.first_name like "%'.($_GET['keywords']).'%" or
										users.last_name like "%'.($_GET['keywords']).'%" or
										users.random_user_id like "%'.($_GET['keywords']).'%"
									)
								';
            }

			if(!empty($_GET['financial_year_id']))
			{
				$condition .= ' and incentive.financial_year_id='.$_GET['financial_year_id'];
			}

            if (!empty($_GET['period_id'])) {
                $condition .= ' and incentive.period_id='.$_GET['period_id'];
            }

            if (!empty($_GET['element_id'])) {
                $condition .= ' and incentive.element_id='.$_GET['element_id'];
            }


            if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
                if ($_GET['from_date'] == $_GET['to_date']) {
                    $condition .= ' and incentive.string_from_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
                } else {
                    #$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
                    $condition .= ' and incentive.string_from_date >= '.strtotime($_GET['from_date']).' and incentive.string_from_date <= '.strtotime($_GET['to_date']).' ';
                }
            }
            
            if (!empty($_GET['from_date'])) {
                $condition .= ' and incentive.string_from_date >= '.strtotime($_GET['from_date']).' ';
            }
            if (!empty($_GET['to_date'])) {
                $condition .= ' and incentive.string_from_date <= '.strtotime($_GET['to_date']).' ';
            }
            $query = "select incentive_id from emp_incentive as incentive

			left join users on 
				users.user_id = incentive.user_id

			left join hr_payslip_categories on 
				hr_payslip_categories.category_id = incentive.element_id

			left join emp_periods on 
				emp_periods.period_id = incentive.period_id
			
			left join org_financial_years on 
				org_financial_years.financial_year_id = incentive.financial_year_id

			where $condition";

            $result = $this->db->query($query)->result_array();
            return count($result);
		}
		else
		{
			return 0;
		}
	}
	
	function getManageIncentive($offset="",$record="")
	{
        if ($_GET) 
		{
            $condition = " 1=1 ";

            if (!empty($_GET['keywords'])) {
                $condition .= ' and (
										users.first_name like "%'.($_GET['keywords']).'%" or
										users.last_name like "%'.($_GET['keywords']).'%" or
										users.random_user_id like "%'.($_GET['keywords']).'%"
									)
								';
            }

			if(!empty($_GET['financial_year_id']))
			{
				$condition .= ' and incentive.financial_year_id='.$_GET['financial_year_id'];
			}

            if (!empty($_GET['period_id'])) {
                $condition .= ' and incentive.period_id='.$_GET['period_id'];
            }

            if (!empty($_GET['element_id'])) {
                $condition .= ' and incentive.element_id='.$_GET['element_id'];
            }

            if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
                if ($_GET['from_date'] == $_GET['to_date']) {
                    $condition .= ' and incentive.string_from_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
                } else {
                    #$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
                    $condition .= ' and incentive.string_from_date >= '.strtotime($_GET['from_date']).' and incentive.string_from_date <= '.strtotime($_GET['to_date']).' ';
                }
            }
            
            if (!empty($_GET['from_date'])) {
                $condition .= ' and incentive.string_from_date >= '.strtotime($_GET['from_date']).' ';
            }

            if (!empty($_GET['to_date'])) {
                $condition .= ' and incentive.string_from_date <= '.strtotime($_GET['to_date']).' ';
            }

            $query = "select incentive.*,
						
				users.first_name,
				users.last_name,
				users.random_user_id,
				hr_payslip_categories.category_name,
				emp_periods.month,
				emp_periods.year,
				org_financial_years.financial_from_year,
				org_financial_years.financial_to_year,

				org_financial_years.financial_from_month,
				org_financial_years.financial_to_month

				from emp_incentive as incentive

				left join users on 
					users.user_id = incentive.user_id

				left join hr_payslip_categories on 
					hr_payslip_categories.category_id = incentive.element_id

				left join emp_periods on 
					emp_periods.period_id = incentive.period_id
				
				left join org_financial_years on 
					org_financial_years.financial_year_id = incentive.financial_year_id

			where $condition
				order by incentive.incentive_id desc
					limit ".$record." , ".$offset."
			";
            
            $result = $this->db->query($query)->result_array();
            return $result;
		}
		else
		{
			return array();
		}
	}


	#Recovery
	function getManageRecoveryCount()
	{
        if ($_GET) 
		{
            $condition = " 1=1 ";
            if (!empty($_GET['keywords'])) {
                $condition .= ' and (
									users.first_name like "%'.($_GET['keywords']).'%" or
									users.last_name like "%'.($_GET['keywords']).'%" or
									users.random_user_id like "%'.($_GET['keywords']).'%"
									)
								';
            }

			if(!empty($_GET['financial_year_id']))
			{
				$condition .= ' and recovery.financial_year_id='.$_GET['financial_year_id'];
			}

            if (!empty($_GET['period_id'])) {
                $condition .= ' and recovery.period_id='.$_GET['period_id'];
            }

            if (!empty($_GET['element_id'])) {
                $condition .= ' and recovery.element_id='.$_GET['element_id'];
            }

            if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
                if ($_GET['from_date'] == $_GET['to_date']) {
                    $condition .= ' and recovery.string_from_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
                } else {
                    #$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
                    $condition .= ' and recovery.string_from_date >= '.strtotime($_GET['from_date']).' and recovery.string_from_date <= '.strtotime($_GET['to_date']).' ';
                }
            }
            
            if (!empty($_GET['from_date'])) {
                $condition .= ' and recovery.string_from_date >= '.strtotime($_GET['from_date']).' ';
            }

            if (!empty($_GET['to_date'])) {
                $condition .= ' and recovery.string_from_date <= '.strtotime($_GET['to_date']).' ';
            }

            $query = "select recovery.recovery_id from emp_recovery as recovery

			left join users on 
				users.user_id = recovery.user_id

			left join hr_payslip_categories on 
				hr_payslip_categories.category_id = recovery.element_id

			left join emp_periods on 
				emp_periods.period_id = recovery.period_id

			left join org_financial_years on 
					org_financial_years.financial_year_id = recovery.financial_year_id

			where $condition";
            $result = $this->db->query($query)->result_array();
            return count($result);
        } 
		else
		{
			return 0;
		}
	}
	
	function getManageRecovery($offset="",$record="")
	{
        if ($_GET) 
		{
            $condition = " 1=1 ";
            if (!empty($_GET['keywords'])) {
                $condition .= ' and (
									users.first_name like "%'.($_GET['keywords']).'%" or
									users.last_name like "%'.($_GET['keywords']).'%" or
									users.random_user_id like "%'.($_GET['keywords']).'%"
									)
								';
            }

			if(!empty($_GET['financial_year_id']))
			{
				$condition .= ' and recovery.financial_year_id='.$_GET['financial_year_id'];
			}

            if (!empty($_GET['period_id'])) {
                $condition .= ' and recovery.period_id='.$_GET['period_id'];
            }

            if (!empty($_GET['element_id'])) {
                $condition .= ' and recovery.element_id='.$_GET['element_id'];
            }

            if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
                if ($_GET['from_date'] == $_GET['to_date']) {
                    $condition .= ' and recovery.string_from_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
                } else {
                    #$condition .= ' and invoice_date between '.strtotime($_GET['from_date']).' and '.strtotime($_GET['to_date']).' ';
                    $condition .= ' and recovery.string_from_date >= '.strtotime($_GET['from_date']).' and recovery.string_from_date <= '.strtotime($_GET['to_date']).' ';
                }
            }
            
            if (!empty($_GET['from_date'])) {
                $condition .= ' and recovery.string_from_date >= '.strtotime($_GET['from_date']).' ';
            }

            if (!empty($_GET['to_date'])) {
                $condition .= ' and recovery.string_from_date <= '.strtotime($_GET['to_date']).' ';
            }

            $query = "select 
				recovery.*,
				users.first_name,
				users.last_name,
				users.random_user_id,
				emp_periods.month,
				emp_periods.year,

				org_financial_years.financial_from_year,
				org_financial_years.financial_to_year,

				org_financial_years.financial_from_month,
				org_financial_years.financial_to_month,

				hr_payslip_categories.category_name

				from emp_recovery as recovery

				left join users on 
					users.user_id = recovery.user_id

				left join hr_payslip_categories on 
					hr_payslip_categories.category_id = recovery.element_id

				left join emp_periods on 
					emp_periods.period_id = recovery.period_id

				left join org_financial_years on 
					org_financial_years.financial_year_id = recovery.financial_year_id

			where $condition
				order by recovery.recovery_id desc
					limit ".$record." , ".$offset."
			";
            
            $result = $this->db->query($query)->result_array();
            return $result;
        }
		else
		{
			return array();
		}


	}

	#Salary Structure Starts
	function getManageSalaryCount()
	{
		if($this->user_id == 1)
		{
			$condition = " 1=1";
		}
		else
		{
			$condition = " 1=1 and emp_salary.user_id='".$this->user_id."' ";
		}
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								users.first_name like "%'.($_GET['keywords']).'%" or 
								users.random_user_id like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		#From & To Date search start
		/* if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$condition .= " and (emp_salary.created_date BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		
		if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			
			$condition .= " and emp_salary.created_date <= '".$fromDate."' ";
		}
		
		if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
		{
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and emp_salary.created_date >= '".$toDate."' ";
		} */

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$condition = " emp_salary.from_date >= '".$fromDate."' and 
								emp_salary.to_date <= '".$toDate."' ";
		}

		if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			
			$condition .= " and emp_salary.from_date >= '".$fromDate."' ";
		}
		
		if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
		{
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and emp_salary.to_date <= '".$toDate."' ";
		}
		#From & To Date search end

		$query = "select 
					emp_salary.header_id,
					users.first_name
		from emp_salary_structure_header as emp_salary

		left join users on 
			users.user_id = emp_salary.user_id
		where 
			$condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageSalary($offset="",$record="")
	{
		if($this->user_id == 1)
		{
			$condition = " 1=1";
		}
		else
		{
			$condition = " 1=1 and emp_salary.user_id='".$this->user_id."' ";
		}
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								users.first_name like "%'.($_GET['keywords']).'%" or
								users.random_user_id like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		#From & To Date search start
		/* if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$condition .= " and (emp_salary.created_date BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		
		if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			
			$condition .= " and emp_salary.created_date <= '".$fromDate."' ";
		}
		
		if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
		{
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and emp_salary.created_date >= '".$toDate."' ";
		} */

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$condition = " emp_salary.from_date >= '".$fromDate."' and 
								emp_salary.to_date <= '".$toDate."' ";
		}

		if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			
			$condition .= " and emp_salary.from_date >= '".$fromDate."' ";
		}
		
		if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
		{
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and emp_salary.to_date <= '".$toDate."' ";
		}
		#From & To Date search end

		$query = "select emp_salary.*,
			users.first_name,
			users.last_name,
			users.random_user_id
			from emp_salary_structure_header as emp_salary

			left join users on 
				users.user_id = emp_salary.user_id
		where $condition
			order by emp_salary.header_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	#Salary Structure


	public function deleteSalaryElementItems($header_id="",$element_id="")
	{
		#$sql = "select * from emp_salary_structure_line where header_id = ? AND element_id = ?";
		#$delete_quantity = $this->db->query($sql,array($header_id,$element_id))->row()->quantity;
		
		/* $sql = "select * from warehouses_products where warehouse_id = ? AND product_id = ?";
		$warehouse_quantity = $this->db->query($sql,array($warehouse_id,$product_id))->row()->quantity;
	
		$wquantity = $warehouse_quantity - $delete_quantity;
		$sql = "update warehouses_products set quantity = ? where warehouse_id = ? AND product_id = ?";
		$this->db->query($sql,array($wquantity,$warehouse_id,$product_id));
		
		$sql = "select * from products where product_id = ?";
		$product_quantity = $this->db->query($sql,array($product_id))->row()->quantity;
	
		$pquantity = $product_quantity - $delete_quantity;
		$sql = "update products set quantity = ? where product_id = ?";
		$this->db->query($sql,array($pquantity,$product_id)); */
		
		$sql = "delete from emp_salary_structure_line where header_id = ? AND element_id = ?";
		if($this->db->query($sql,array($header_id,$element_id)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	#Salary Structure Starts
	function getEmployeeAnnexureCount($user_id="")
	{
		if($this->user_id == 1)
		{
			$condition = " 1=1";
		}
		else
		{
			$condition = " 1=1 and emp_salary.user_id='".$this->user_id."' ";
		}

		$condition .= " and emp_salary.user_id='".$user_id."' ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								users.first_name like "%'.($_GET['keywords']).'%" or 
								users.random_user_id like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		#From & To Date search start
		/* if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$condition .= " and (emp_salary.created_date BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		
		if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			
			$condition .= " and emp_salary.created_date <= '".$fromDate."' ";
		}
		
		if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
		{
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and emp_salary.created_date >= '".$toDate."' ";
		} */

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$condition = " emp_salary.from_date >= '".$fromDate."' and 
								emp_salary.to_date <= '".$toDate."' ";
		}

		if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			
			$condition .= " and emp_salary.from_date >= '".$fromDate."' ";
		}
		
		if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
		{
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and emp_salary.to_date <= '".$toDate."' ";
		}
		#From & To Date search end


		$query = "select 
					emp_salary.header_id,
					users.first_name
		from emp_salary_structure_header as emp_salary

		left join users on 
			users.user_id = emp_salary.user_id
		where 
			$condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getEmployeeAnnexure($offset="",$record="",$user_id="")
	{
		if($this->user_id == 1)
		{
			$condition = " 1=1";
		}
		else
		{
			$condition = " 1=1 and emp_salary.user_id='".$this->user_id."' ";
		}

		$condition .= " and emp_salary.user_id='".$user_id."' ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								users.first_name like "%'.($_GET['keywords']).'%" or
								users.random_user_id like "%'.($_GET['keywords']).'%"
								)
							';
		}
		

		#From & To Date search start
		/* if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$condition .= " and (emp_salary.created_date BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		
		if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			
			$condition .= " and emp_salary.created_date <= '".$fromDate."' ";
		}
		
		if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
		{
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and emp_salary.created_date >= '".$toDate."' ";
		} */

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$condition = " emp_salary.from_date >= '".$fromDate."' and 
								emp_salary.to_date <= '".$toDate."' ";
		}

		if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			
			$condition .= " and emp_salary.from_date >= '".$fromDate."' ";
		}
		
		if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
		{
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and emp_salary.to_date <= '".$toDate."' ";
		}

		#From & To Date search end
		
		$query = "select emp_salary.*,
			users.first_name,
			users.last_name,
			users.random_user_id
			from emp_salary_structure_header as emp_salary

			left join users on 
				users.user_id = emp_salary.user_id
		where $condition
			order by emp_salary.header_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	#Salary Structure
	
	function getCaptainAll(){
		$query="select user.user_id,user.user_name from per_user as user
				join ord_order_headers as orh on orh.waiter_id=user.user_id
				group by orh.waiter_id";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
}
