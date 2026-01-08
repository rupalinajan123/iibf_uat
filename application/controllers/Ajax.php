<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Ajax extends CI_Controller {

	

	public function __construct()

	{

		parent::__construct();	

		$this->load->helper('date_helper');

	}



	

	

	public function calculateExperience()

	{
	

		$exp =0;

		$fromDate = $this->input->post('fromDate');

		$toDate = $this->input->post('toDate');

		//if(strtotime($fromDate) && strtotime($toDate))

		//{

			if(strtotime($fromDate) > strtotime($toDate))

			{

				echo '0.0';

			}

			else

			{

				$res = calculateAgeDiff($fromDate, $toDate, 1);

				$exp = $res;

			}

			if($exp > 0)

			{

				echo $exp; 

			}

			/*else

			{

				echo '0.0';

			}*/

		///}

		//else

			//echo '2';

	 }

	public function test()
	{
		echo 'innnnndfgdfgdfgdfgdfgdfgdgfg';exit;
	}	

}

