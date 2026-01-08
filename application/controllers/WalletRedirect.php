<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WalletRedirect extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		}

	 ##---------redirect url (prafull)-----------##
	public function wallet()
	{	
    if($this->session->userdata('memtype'))
		{
			if($this->session->userdata('memtype')=='O' || $this->session->userdata('memtype')=='A'||$this->session->userdata('memtype')=='F')
			{
				$csc_id=$this->uri->segment('3'); 
				redirect(base_url().'CSCApplyexam/wallet_make_payment/'.$csc_id);
			}
			else if($this->session->userdata('csctype')=='reg')
			{
				$csc_id=$this->uri->segment('3'); 
				redirect(base_url().'CSCNonreg/wallet_make_payment/'.$csc_id);
			}
			elseif($this->session->userdata('csctype')=='exm'){
				$csc_id=$this->uri->segment('3'); 
				redirect(base_url().'CSCNonMember/wallet_make_payment/'.$csc_id);
			}
      elseif($this->session->userdata('csctype')=='iibfbcbf_apply_exam')
      {
        /* $server_ip = $_SERVER['SERVER_ADDR'];
        echo "<br>Server IP Address: $server_ip";
        echo "<br><br>";

        echo '<pre>';
        print_r($_SESSION);
        print_r($_POST);
        print_r($_REQUEST);
        echo '</pre>'; */ 
        $csc_id = $this->uri->segment($this->uri->total_segments());
        //echo site_url('iibfbcbf/agency/apply_exam_csc_agency/wallet_make_payment/'.$csc_id); exit;
        redirect(site_url('iibfbcbf/agency/apply_exam_csc_agency/wallet_make_payment/'.$csc_id));

				/* $csc_id=$this->uri->segment('3'); 
				redirect(base_url().'CSCNonMember/wallet_make_payment/'.$csc_id); */
			}
			elseif($this->session->userdata('csctype')=='iibfbcbf_csc_exam_recovery')
      { 
        $csc_id = $this->uri->segment($this->uri->total_segments());
        //echo site_url('iibfbcbf/agency/apply_exam_csc_agency/wallet_make_payment/'.$csc_id); exit;
        redirect(site_url('Csc_exam_recovery/wallet_make_payment/'.$csc_id));

				/* $csc_id=$this->uri->segment('3'); 
				redirect(base_url().'CSCNonMember/wallet_make_payment/'.$csc_id); */
			}
		}

	}
	
}
