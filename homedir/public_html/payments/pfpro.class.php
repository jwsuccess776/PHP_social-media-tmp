<?php

/*
System requirements:
-Payflow Pro SDK from VeriSign (get it at http://manager.verisign.com), still in its original
directory structure
-A Linux server -- anybody want to write a Windows port?
-A copy of PHP that is NOT running in safe mode (you need to be able to run the system() and putenv() functions)
*/

/* Class PFPro - a Payflow Pro payment processing class that doesn't require built-in Payflow Pro support!
It does, however, rely on the presence of a properly-packaged pfpro executable, on a *nix system, with safe
mode disabled. Sorry, IIS folks. */

class PFPro {
    var $cvv_level;
    var $avs_level;
    var $user;
    var $password;
    var $partner;
    var $vendor;
    var $price;
    var $exp_date;
    var $cvv;
    var $card;
    var $address;
    var $zip;
    var $trx_result = array();

    function PFPro($path, $user, $password, $partner, $vendor)
    {
        if(!is_dir($path)) die("$path does not exist or is not a directory");
        else chdir($path);

        putenv("LD_LIBRARY_PATH=.:".__INCLUDE_CLASS_PATH."/:\${LD_LIBRARY_PATH:-};");
//        putenv("LD_LIBRARY_PATH=.:../lib/:\${LD_LIBRARY_PATH:-};");
        putenv("PFPRO_CERT_PATH=../certs/");

        $this->user = $user;
        $this->password = $password;
        $this->vendor = $vendor;
        $this->partner = $partner;
    }

    function getResult()
    {
        if($this->trx_result) return $this->trx_result;
        else return false;
    }

    function getLastMessage()
    {
        if($this->trx_result['RESPMSG']) return $this->trx_result['RESPMSG'];
        else return false;
    }

    function getPNREF()
    {
        if($this->trx_result['PNREF']) return $this->trx_result['PNREF'];
        else return false;
    }

    function setSecurityLevel($avs = "medium", $cvv = "medium")
    {
        $this->cvv_level = $cvv;
        $this->avs_level = $avs;
    }

    function run_trx($param_array, $timeout)
    {
        /* THIS FUNCTION HAS YET TO BE DEBUGGED!! */

        if(!is_array($param_array)) die("Parameter list sent to run_trx is not an array");

        $keys = array_keys($param_array);
        $values = array_values($param_array);
        if(sizeof($keys) != sizeof($values)) die("Size error comparing keys and values during run_trx");

        $parms = array();
        for($i=0; $i<sizeof($keys); $i++) {
            $key = $keys[$i];
            $value = $values[$i];
            array_push($parms, "$key=$value");
        }

        $param_list = implode("&", $parms);
        if($this->card == "4111111111111111") $host = "test-payflow.verisign.com";
        else $host = "payflow.verisign.com";

        $return_str = exec("./pfpro $host 443 \"$param_list\" $timeout");
        parse_str($return_str, $this->trx_result);
        //print_r($return_str);
    }

    function setCustomerInfo($address, $zip)
    {
        $this->address = $address;
        $this->zip = $zip;
    }

    function setPaymentInfo($card, $exp, $cvv, $price)
    {
        $this->card = $card;
        $this->exp_date = $exp;
        $this->cvv = $cvv;
        $this->price = $price;
    }

    function process($timeout = 60)
    {
        $params = array(
            'TRXTYPE' => 'S',
            'TENDER' => 'C',
            'USER' => $this->user,
            'PWD' => $this->password,
            'PARTNER' => $this->partner,
            'VENDOR' => $this->vendor,
            'STREET' => $this->address,
            'ZIP' => $this->zip,
            'AMT' => $this->price,
            'CVV2' => $this->cvv,
            'ACCT' => $this->card,
            'EXPDATE' => $this->exp_date
        );
        //print_r($params);
        $this->run_trx($params, $timeout);

        if($this->trx_result['RESULT'] != 0) return false;
        else return true;
    }

    function isInternational()
    {
        if($this->trx_result['IAVS'] == "Y") return true;
        else return false;
    }

    function void($pnref, $timeout = 60)
    {
        if(!$pnref) $pnref = $this->trx_result['PNREF'];

        $params = array(
            'TRXTYPE' => 'V',
            'TENDER' => 'C',
            'USER' => $this->user,
            'PWD' => $this->password,
            'PARTNER' => $this->partner,
            'VENDOR' => $this->vendor,
            'ORIGID' => $pnref
        );
        $this->run_trx($params, $timeout);

        if($this->trx_result['RESULT'] != 0) return false;
        else return true;
    }

    function credit($pnref, $timeout = 60)
    {
        if(!$pnref) $pnref = $this->trx_result['PNREF'];

        $params = array(
            'TRXTYPE' => 'C',
            'TENDER' => 'C',
            'USER' => $this->user,
            'PWD' => $this->password,
            'PARTNER' => $this->partner,
            'VENDOR' => $this->vendor,
            'ORIGID' => $pnref
        );
        $this->run_trx($params, $timeout);

        if($this->trx_result['RESULT'] != 0) return false;
        else return true;
    }

    function check_cvv()
    {
        if(!$this->trx_result['CVV2MATCH']) return false;
        else {
            if($this->cvv_level == "none")
                return true;
            else if($this->cvv_level == "medium" && $this->trx_result['CVV2MATCH'] == "N")
                return false;
            else if($this->cvv_level == "full" && $this->trx_result['CVV2MATCH'] != "Y")
                return false;
            else
                return true;
        }
    }

    function check_avs()
    {
        if(!$this->trx_result['AVSADDR'] || !$this->trx_result['AVSZIP']) return false;
        else {
            if($this->avs_level == "none")
                return true;
            else if($this->avs_level == "light" && $this->trx_result['AVSADDR'] == "N" && $this->trx_result['AVSZIP'] == "N")
                return false;
            else if($this->avs_level == "medium" && ($this->trx_result['AVSADDR'] == "N" || $this->trx_result['AVSZIP'] == "N"))
                return false;
            else if($this->avs_level == "full" && ($this->trx_result['AVSADDR'] != "Y" || $this->trx_result['AVSZIP'] != "Y"))
                return false;
            else
                return true;
        }
    }

    function fraudCheck()
    {
        if(!$this->trx_result['AVSADDR'] || !$this->trx_result['AVSZIP']) return false;
        else {
            if($this->check_avs() && $this->check_cvv())
                return true;
            else
                return false;
        }
    }
}

?>