<?php defined('BASEPATH') || exit('No direct script access allowed');

/**

 * AdminCMS

 * @package   AdminCMS

 * @author    VRushali Ugale {contributed}

 * @since     Version 1.0

 */

/**

 * Date helper functions.

 *

 * Includes additional date-related functions helpful in AdminCMS development.

 *

 */





if ( ! function_exists('pagination_init'))

{

	function pagination_init($url,$total_rows, $per_page, $num_links)

	{

		$CI = & get_instance();

		

		$config["base_url"] = $url;

		$config["total_rows"] = $total_rows;

		$config["per_page"] = $per_page;

		$config['use_page_numbers'] = TRUE;

		$config['num_links'] = $num_links;

		

		$config['full_tag_open'] = '<ul class="pagination">';

		$config['full_tag_close'] = '</ul><!--pagination-->';

	

		$config['first_link'] = 'First';

		$config['first_tag_open'] = '<li class="prev page paginate_button" >';

		$config['first_tag_close'] = '</li>';

	

		$config['last_link'] = 'Last';

		$config['last_tag_open'] = '<li class="next page paginate_button" >';

		$config['last_tag_close'] = '</li>';

	

		$config['next_link'] = 'Next';

		$config['next_tag_open'] = '<li class="next page paginate_button" >';

		$config['next_tag_close'] = '</li>';

	

		$config['prev_link'] = ' Previous';

		$config['prev_tag_open'] = '<li class="prev page paginate_button" >';

		$config['prev_tag_close'] = '</li>';

	

		$config['cur_tag_open'] = '<li class="page active paginate_button" style="font-weight:bold;"><a href="javascript:void(0);" id="currLink">';

		$config['cur_tag_close'] = '</a></li>';

	

		$config['num_tag_open'] = '<li class="page paginate_button" >';

		$config['num_tag_close'] = '</li>';

		

		return $config;	

	}

 

}



if ( ! function_exists('check_session'))

{

	function check_session()

	{

		$CI = & get_instance();

		$CI->load->library('session');

		

		$session_arr = array();

		$session_arr['field'] = '';

		$session_arr['value'] = '';

		$session_arr['per_page'] = '';

		$session_arr['start'] = '';

		$session_arr['sortkey'] = '';

		$session_arr['sortval'] = '';

		$field = '';

		$value = '';

		$sortkey = '';

		$sortval = '';

		$per_page = '';

		$start = 0;

		$limit = 10;

		

		if($CI->session->userdata('per_page')=='')

		{

			if($CI->input->post('per_page')!='')

				$per_page = $CI->input->post('per_page');

			else

				$per_page = $limit;

			$CI->session->set_userdata('per_page',$per_page);

		}

		else

		{

			if(isset($_POST['per_page']) && $CI->input->post('per_page') != $CI->session->userdata('per_page'))

			{

				$per_page = $CI->input->post('per_page');	

				$CI->session->set_userdata('per_page',$per_page);

			}

		}

		

		$per_page = $CI->session->userdata('per_page');

		

		$last = $CI->uri->total_segments();

		$page = $CI->uri->segment($last);

		

		if($page == '' || !is_numeric($page))

			$page = 1;

		

		$start = $per_page * ($page-1);

		

		

		if($CI->session->userdata('field')=='')

		{

			$field = $CI->input->post('field');

			if(strpos($field, '-BETWEEN') !== false)

			{

				$new_field = explode('-BETWEEN',$field);

				$field = $new_field[0];

			}

			$CI->session->set_userdata('field',$field);

		}

		else

		{

			if(isset($_POST['field']) && $CI->input->post('field') != $CI->session->userdata('field'))

			{

				$field = $CI->input->post('field');

				if(strpos($field, '-BETWEEN') !== false)

				{

					$new_field = explode('-BETWEEN',$field);

					$field = $new_field[0];

				}

				$CI->session->set_userdata('field',$field);

			}

		}

		

		if($CI->session->userdata('value')=='')

		{

			$value = $CI->input->post('value');

			$CI->session->set_userdata('value',$value);	

		}

		else

		{

			if(isset($_POST['value']) && $CI->input->post('value') != $CI->session->userdata('value'))

			{

				$value = $CI->input->post('value');

				/*if(strpos($value, '~') !== false)

				{
	
					$new_value = explode('~',$value);

					$value = $new_value[0];

				}*/	

				$CI->session->set_userdata('value',$value);

			}

		}

		

		if($CI->session->userdata('field')!='' && $CI->session->userdata('value')!='')

		{

			$field = $CI->session->userdata('field');

			$value = $CI->session->userdata('value');

		}

		

		if($CI->session->userdata('sortkey')=='')

		{

			$sortkey = $CI->input->post('sortkey');

			$CI->session->set_userdata('sortkey',$sortkey);	

		}

		else

		{

			if(isset($_POST['sortkey']) && $CI->input->post('sortkey') != $CI->session->userdata('sortkey'))

			{

				$sortkey = $CI->input->post('sortkey');	

				$CI->session->set_userdata('sortkey',$sortkey);

			}

		}

		

		if($CI->session->userdata('sortval')=='')

		{

			if($CI->input->post('sortval')=='ascending')

				$sortval = 'ASC';

			else

				$sortval = 'DESC';

			$CI->session->set_userdata('sortval',$sortval);	

		}

		else

		{

			if(isset($_POST['sortval']) && $CI->input->post('sortval') != $CI->session->userdata('sortval'))

			{

				if($CI->input->post('sortval')=='ascending')

					$sortval = 'ASC';

				else

					$sortval = 'DESC';	

				$CI->session->set_userdata('sortval',$sortval);

			}

		}

		

		$sortkey = $CI->session->userdata('sortkey');

		$sortval = $CI->session->userdata('sortval');



		$session_arr['field'] = $field;

		$session_arr['value'] = $value;

		$session_arr['sortkey'] = $sortkey;

		$session_arr['sortval'] = $sortval;

		$session_arr['per_page'] = $per_page;

		$session_arr['start'] = $start;

		return $session_arr;

	}

	function check_dra_session()

	{

		$CI = & get_instance();

		$CI->load->library('session');

		

		$session_arr = array();

		$session_arr['field'] = '';

		$session_arr['value'] = '';

		$session_arr['per_page'] = '';

		$session_arr['start'] = '';

		$session_arr['sortkey'] = '';

		$session_arr['sortval'] = '';

		$field = '';

		$value = '';

		$sortkey = '';

		$sortval = '';

		$per_page = '';

		$start = 0;

		$limit = 10;

		if($CI->session->userdata('per_page')=='')
		{
			if($CI->input->post('per_page')!='')

				$per_page = $CI->input->post('per_page');

			else

				$per_page = $limit;

			$CI->session->set_userdata('per_page',$per_page);

		}
		else
		{
			if(isset($_POST['per_page']) && $CI->input->post('per_page') != '' && $CI->input->post('per_page') != $CI->session->userdata('per_page'))
			{

				$per_page = $CI->input->post('per_page');	

				$CI->session->set_userdata('per_page',$per_page);

			}

		}

		

		$per_page = $CI->session->userdata('per_page');

		

		$last = $CI->uri->total_segments();

		$page = $CI->uri->segment($last);

		

		if($page == '' || !is_numeric($page))

			$page = 1;

		

		$start = $per_page * ($page-1);

		

		

		if($CI->session->userdata('field')=='')

		{

			$field = $CI->input->post('field');

			if(strpos($field, '-BETWEEN') !== false)

			{

				$new_field = explode('-BETWEEN',$field);

				$field = $new_field[0];

			}

			$CI->session->set_userdata('field',$field);

		}

		else

		{

			if(isset($_POST['field']) && $CI->input->post('field') != $CI->session->userdata('field'))

			{

				$field = $CI->input->post('field');

				if(strpos($field, '-BETWEEN') !== false)

				{

					$new_field = explode('-BETWEEN',$field);

					$field = $new_field[0];

				}

				$CI->session->set_userdata('field',$field);

			}

		}


		if($CI->session->userdata('value') == '')
		{
			$value = $CI->input->post('value');

			$CI->session->set_userdata('value',$value);	
		}
		else
		{ 
			if(isset($_POST['value']) && $CI->input->post('value') != '' && $CI->input->post('value') != $CI->session->userdata('value'))
			{
				$value = $CI->input->post('value');

				/*if(strpos($value, '~') !== false)
				{
					$new_value = explode('~',$value);
					echo count($new_value); exit;

					$value = $new_value[0];
				}*/
					
				$CI->session->set_userdata('value',$value);
			}

		}

		

		if($CI->session->userdata('field')!='')
		{
			$field = $CI->session->userdata('field');
		}

		if($CI->session->userdata('value')!='')
		{
			$value = $CI->session->userdata('value');
		}		

		if($CI->session->userdata('sortkey')=='')
		{
			$sortkey = $CI->input->post('sortkey');

			$CI->session->set_userdata('sortkey',$sortkey);	
		}

		else

		{
			if(isset($_POST['sortkey']) && $CI->input->post('sortkey') != '' && $CI->input->post('sortkey') != $CI->session->userdata('sortkey'))
			{
				$sortkey = $CI->input->post('sortkey');	
				$CI->session->set_userdata('sortkey',$sortkey);
			}
		}

		if($CI->session->userdata('sortval')=='')
		{
			if($CI->input->post('sortval')=='ascending')

				$sortval = 'ASC';

			else

				$sortval = 'DESC';

			$CI->session->set_userdata('sortval',$sortval);	

		}
		else
		{
			if(isset($_POST['sortval']) && $CI->input->post('sortval') != '' && $CI->input->post('sortval') != $CI->session->userdata('sortval'))
			{
				if($CI->input->post('sortval')=='ascending')

					$sortval = 'ASC';

				else

					$sortval = 'DESC';	

				$CI->session->set_userdata('sortval',$sortval);

			}

		}

		

		$sortkey = $CI->session->userdata('sortkey');

		$sortval = $CI->session->userdata('sortval');



		$session_arr['field'] = $field;

		$session_arr['value'] = $value;

		$session_arr['sortkey'] = $sortkey;

		$session_arr['sortval'] = $sortval;

		$session_arr['per_page'] = $per_page;

		$session_arr['start'] = $start;

		return $session_arr;

	}


}