<? defined('BASEPATH')||exit('No Direct Allowed Here');



if(!function_exists('filter')){
 function filter($input)
	{

		if(empty($input))return ' ';
		if(is_array($input))
		{
			foreach($input as $filterinput)
			{
				$filteroutput[]=htmlentities(xss_clean($filterinput, ENT_QUOTES, 'UTF-8'));
			}
				return $filteroutput;
		}
		else
		{
			$filteroutput='';
			$filteroutput=htmlentities(xss_clean($input, ENT_QUOTES, 'UTF-8'));
			return $filteroutput;
		}
	}
}
/*
if(!function_exists('yecho')){
 function yecho($input)
	{
		if(empty($input))
		{exit('NA');}
		else
		{
			return filter($input);
		}
		
	}
}
*/
if (! function_exists('vdebug')) {
    function vdebug($data, $die = true, $add_var_dump = false, $add_last_query = true)
    {
        $CI = &get_instance();
        $CI->load->library('unit_test');
        $bt = debug_backtrace();
        $src = file($bt[0]["file"]);
        $line = $src[$bt[0]['line'] - 1];
        # Match the function call and the last closing bracket
        preg_match('#' . __FUNCTION__ . '\((.+)\)#', $line, $match);
        $max = strlen($match[1]);
        $varname = null;
        $c = 0;
        for ($i = 0; $i < $max; $i++) {
            if($match[1]{$i} == "(") {
                $c++;
            } elseif ($match[1]{$i} == ")") {
                $c--;
            }
            if ($c < 0) {
                break;
            }
            $varname .= $match[1]{$i};
        }

        if (is_object($data)) {
            $message = '<span class="vayes-debug-badge vayes-debug-badge-object">OBJECT</span>';
        } elseif (is_array($data)) {
            $message = '<span class="vayes-debug-badge vayes-debug-badge-array">ARRAY</span>';
        } elseif (is_string($data)) {
            $message = '<span class="vayes-debug-badge vayes-debug-badge-string">STRING</span>';
        } elseif (is_int($data)) {
            $message = '<span class="vayes-debug-badge vayes-debug-badge-integer">INTEGER</span>';
        } elseif (is_true($data)) {
            $message = '<span class="vayes-debug-badge vayes-debug-badge-true">TRUE [BOOLEAN]</span>';
        } elseif (is_false($data)) {
            $message = '<span class="vayes-debug-badge vayes-debug-badge-false">FALSE [BOOLEAN]</span>';
        } elseif (is_null($data)) {
            $message = '<span class="vayes-debug-badge vayes-debug-badge-null">NULL</span>';
        } elseif (is_float($data)) {
            $message = '<span class="vayes-debug-badge vayes-debug-badge-float">FLOAT</span>';
        } else {
            $message = 'N/A';
        }

        $output  = '<div style="clear:both;"></div>';
        $output .= '<meta charset="UTF-8" />';
        $output .= '<style>body{margin:0}::selection{background-color:#E13300!important;color:#fff}::moz-selection{background-color:#E13300!important;color:#fff}::webkit-selection{background-color:#E13300!important;color:#fff}div.debugbody{background-color:#fff;margin:0px;font:9px/12px normal;font-family:Arial,Helvetica,sans-serif;color:#4F5155;min-width:500px}a.debughref{color:#039;background-color:transparent;font-weight:400}h1.debugheader{color:#444;background-color:transparent;border-bottom:1px solid #D0D0D0;font-size:12px;line-height:14px;font-weight:700;margin:0 0 14px;padding:14px 15px 10px;font-family:\'Ubuntu Mono\',Consolas}code.debugcode{font-family:\'Ubuntu Mono\',Consolas,Monaco,Courier New,Courier,monospace;font-size:12px;background-color:#f9f9f9;border:1px solid #D0D0D0;color:#002166;display:block;margin:10px 0;padding:5px 10px 15px}code.debugcode.debug-last-query{display:none}pre.debugpre{display:block;padding:0;margin:0;color:#002166;font:12px/14px normal;font-family:\'Ubuntu Mono\',Consolas,Monaco,Courier New,Courier,monospace;background:0;border:0}div.debugcontent{margin:0 15px}p.debugp{margin:0;padding:0}.debugitalic{font-style:italic}.debutextR{text-align:right;margin-bottom:0;margin-top:0}.debugbold{font-weight:700}p.debugfooter{text-align:right;font-size:11px;border-top:1px solid #D0D0D0;line-height:32px;padding:0 10px;margin:20px 0 0}div.debugcontainer{margin:0px;border:1px solid #D0D0D0;-webkit-box-shadow:0 0 8px #D0D0D0}code.debug p{padding:0;margin:0;width:100%;text-align:right;font-weight:700;text-transform:uppercase;border-bottom:1px dotted #CCC;clear:right}code.debug span{float:left;font-style:italic;color:#CCC}.vayes-debug-badge{background:#285AA5;border:1px solid rgba(0,0,0,0);border-radius:4px;color:#FFF;padding:2px 4px}.vayes-debug-badge-object{background:#A53C89}.vayes-debug-badge-array{background:#037B5A}.vayes-debug-badge-string{background:#037B5A}.vayes-debug-badge-integer{background:#552EF3}.vayes-debug-badge-true{background:#126F0B}.vayes-debug-badge-false{background:#DE0303}.vayes-debug-badge-null{background:#383838}.vayes-debug-badge-float{background:#9E4E09}p.debugp.debugbold.debutextR.lq-trigger:hover + code{display:block}</style>';

        $output .= '<div class="debugbody"><div class="debugcontainer">';
        $output .= '<h1 class="debugheader">'.$varname.'</h1>';
        $output .= '<div class="debugcontent">';
        $output .= '<code class="debugcode"><p class="debugp debugbold debutextR">:: print_r</p><pre class="debugpre">'.$message;
        ob_start();
        print_r($data);
        $output .= "\n\n".trim(ob_get_clean());
        $output .= '</pre></code>';

        if ($add_var_dump) {
            $output .= '<code class="debugcode"><p class="debugp debugbold debutextR">:: var_dump</p><pre class="debugpre">';
            ob_start();
            var_dump($data);
            $vardump = trim(ob_get_clean());
            $vardump = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $vardump);
            $output .=  $vardump;
            $output .= '</pre></code>';
        }

        if ($add_last_query) {
            if ($CI->db->last_query()) {
                $output .= '<p class="debugp debugbold debutextR lq-trigger">Show Last Query</p>';
                $output .= '<code class="debugcode debug-last-query1"><p class="debugp debugbold debutextR">:: $CI->db->last_query()</p>';
                $output .= $CI->db->last_query();
                $output .= '</code>';
            }
        }


        $output .= '</div><p class="debugfooter">Vayes Debug Helper © Yahya A. Erturan</p></div></div>';
        $output .= '<div style="clear:both;"></div>';

        if (PHP_SAPI == 'cli') {
            echo $varname . ' = ' . PHP_EOL . $output . PHP_EOL . PHP_EOL;
            return;
        }

        echo $output;
        if ($die) {
            exit;
        }
    }
}

//priyanka d - 06-april-23 >> added this for weekly off csc dates task
if (!function_exists('getcentrenonavailability'))
{
  function getcentrenonavailability($venue_code)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://iibf.cscexams.in/backend/web/user/getcentrenonavailability');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $response = (array)json_decode($response);

    $i = 0;
    $disabledDates = array();
    $weeklyOff = '';

    foreach ($response as $currData)
    {
      $currData = (array)$currData;
      if ($currData['venue_code'] == $venue_code)
      {
        if ($currData['weekly_off'] != null && $currData['weekly_off'] != '')
        {
          $weeklyOff = $currData['weekly_off'];
          //echo $currData['weekly_off'];
        }
      }

      if ($currData['venue_code'] == $venue_code && $currData['off_duty_from'] != '' && $currData['off_duty_to'] != '')
      {
        //echo'<pre>';print_r($currData);
        //$disabledDates[$i]['off_duty_from']=$currData['off_duty_from'];
        //$disabledDates[$i]['off_duty_to']=$currData['off_duty_to'];
        $period = new DatePeriod(new DateTime($currData['off_duty_from']), new DateInterval('P1D'), new DateTime($currData['off_duty_to']));
        foreach ($period as $key => $value)
        {
          $disabledDates[] = $value->format('Y-m-d');
          //echo $value->format('Y-m-d').'<br>';
        }
        $i++;
        $disabledDates[] = date('Y-m-d', strtotime($currData['off_duty_to']));
      }
    }
    return array('disabledDates' => $disabledDates, 'weeklyOff' => $weeklyOff);
  }
}
