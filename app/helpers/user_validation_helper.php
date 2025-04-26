<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#Email Validation
if ( ! function_exists('email_validation'))
{
	function email_validation($email)
	{
		$ci=& get_instance();
		$num_rows = 0;
		$user_array = array('users');
		$size = sizeof($user_array);

		for($i = 0; $i < $size; $i++)
		{
			$ci->db->where('email', $email);
			$num_rows = $ci->db->get($user_array[$i])->num_rows();
			if($num_rows > 0){
				return 0;
			}
		}
		return 1;
	}
}

#Email Validation for Edit
if ( ! function_exists('email_validation_for_edit'))
{
	function email_validation_for_edit($email, $id, $type)
	{
		$num_rows = 0;
		$ci=& get_instance();
		$user_array = array('users');
		$size = sizeof($user_array);
		for($i = 0; $i < $size; $i++)
		{
			if($type == $user_array[$i])
			{
				$ci->db->where_not_in($user_array[$i].'_id', $id);
				$ci->db->where('email', $email);
				$num_rows = $ci->db->get($user_array[$i])->num_rows();
				echo $num_rows;exit;
				if($num_rows > 0){
					return 0;
				}
			}
			else
			{
				$ci->db->where('email', $email);
				$num_rows = $ci->db->get($user_array[$i])->num_rows();
				if($num_rows > 0){
					return 0;
				}
			}
		}
		return 1;
	}
}


// ------------------------------------------------------------------------
/* End of file User_validation.php */
/* Location: ./system/helpers/User_validation.php */
