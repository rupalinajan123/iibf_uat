<?php

Class Crypt_lib {

	const CRYPT_KEY = 'c+cNh7y44v4wLjb30U2xwcbimGz0ci4VoSxmQvhC94o=';
	// encryption using aes 256 cbc
	public function encrypts_dat($plain_text, $key = '') {
		$method = 'aes-256-cbc';
		if (empty($key)) {
			$key = CRYPT_KEY;
		}
		if (is_array($plain_text)) {
			$str = $this->array_to_str($plain_text);
		} else {
			$str = $plain_text;
		}
		$enc = $this->aesEncryptStat($str, $method, $key);
		return $enc;
	}

	//  decryption using aes 256 cbc
	public function decrypts_dat($cipher_text, $array = FALSE, $key = '') {
		$method = 'aes-256-cbc';
		if (empty($key)) {
			$key = CRYPT_KEY;
		}
		$dec = $this->aesDecryptStat($cipher_text, $method, $key);
		if ($array) {
			return $this->str_to_array($dec, "|");
		}
		return $dec;
	}

	// encrypt
	public function aesEncryptStat($plain_text, $method, $key) {
		//$rnd      = $this->_get_random($method); // get random key
		$aes_key = base64_decode($key);
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
		$enc_int = $this->_aes_encrypt($plain_text, $method, $aes_key, $iv); // encrypt random
		$enc_data = $iv . $enc_int; // pad data and iv bytes
		return base64_encode($enc_data);
	}

	// decrypt
	public function aesDecryptStat($enc_payload, $method, $key) {
		$enc = $this->_stat_split($enc_payload); // decode cipher
		$aes_key = base64_decode($key);
		$dec_data = $this->_aes_decrypt($enc['dat'], $method, $aes_key, $enc['iv']); // decrypt dynamic
		return $dec_data;
	}

	public function _stat_split($enc) {
		$enc_dat = base64_decode($enc);
		$d['iv'] = substr($enc_dat, 0, 16);
		$d['dat'] = substr($enc_dat, 16);
		return $d;
	}

	// aes encrypt core
	public function _aes_encrypt($plain_text, $method, $aes_key, $iv) {
		$enc = openssl_encrypt($plain_text, $method, $aes_key, OPENSSL_RAW_DATA, $iv);
		return $enc;
	}

	// aes decrypt core
	public function _aes_decrypt($cipher_text, $method, $aes_key, $iv) {
		$dec = openssl_decrypt($cipher_text, $method, $aes_key, OPENSSL_RAW_DATA, $iv);
		return $dec;
	}

	private function str_to_array($str, $delim = '|', $seperator = '=') {
		if (!$str) {
			return false;
		}

		$vals = explode($delim, $str);

		if (!$vals && count($vals) <= 0) {
			return false;
		}

		$ret = array();
		foreach ($vals as $req) {
			$eq_pos = strpos($req, $seperator);
			if ($eq_pos >= 0) {
					$k = trim(substr($req, 0, $eq_pos));
					$v = trim(substr($req, $eq_pos + strlen($seperator)));
					if ($k) {
						$ret[$k] = $v;
					}
			} //End if($eq_pos >= 0 )
		} //End foreach ..
		return $ret;
	} // End private function _string_to_array

	private function array_to_str($m, $delim = '|', $seperator = '=') {
		$arr = (array) $m;
		$ret = "";
		foreach ($arr as $k => $v) {
			$ret .= trim($k) . $seperator . trim($v) . $delim;
		}
		return $ret;
	} // function end _array_to_str

}