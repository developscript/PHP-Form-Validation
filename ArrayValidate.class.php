<?php

class ArrayValidate
{
    public $config = array(
        "lb_erro" => array(
            "notnull" => "Field ? can not be null.",
            "maxlength" => "Field ? can not be greater than ? characters.",
            "minlength" => "Field ? can not be less than ? characters.",
            "type_integer" => "Field ?, invalid number."
        ),
        "validates" => array(
            "notnull" => 'if($valid === true && empty($val)){ $this->construct_erro("notnull", array($label)); }',
            "maxlength" => 'if (strlen($val) > intval($valid)) { $this->construct_erro("maxlength", array($label,$valid)); }',
            "minlength" => 'if (strlen($val) < intval($valid)) { $this->construct_erro("minlength", array($label,$valid)); }',
            "numeric" => 'if($valid === true && !is_numeric($val)){ $this->construct_erro("type_integer", array($label)); }'
        ),
        "format" => array(
            "date" => 'list($d, $m, $y) = explode("/", $val); $this->construct_rs($key, $y . "-" . $m . "-" . $d);',
            "convertNumber" => '$this->construct_rs($key, preg_replace("/\D/", "", $val));'
        )
    );

    public $return = array(
        "data" => array(),
        "erros" => array()
    );

    public function construct_erro($lb_erro, $data)
    {
        if (array_key_exists($lb_erro, $this->config['lb_erro'])) {
            if (is_array($data)) {
                $text = $this->config['lb_erro'][$lb_erro];
                for ($i = 0; strpos($text, '?'); $i++) {
                    $text = preg_replace('/\?/', $data[$i], $text, $i + 1);
                }
                $this->return["erros"][] = $text;
            } else {
                $this->return["erros"][] = $this->config['lb_erro'][$lb_erro];
            }
        }
    }

    public function construct_rs($key, $val)
    {
        $this->return["data"][$key] = $val;
    }


    public function margeValidate($validate, $object)
    {

        foreach ($validate as $key => $value) {
            $val = $object[$key];
            $label = $value['label'];

            foreach ($value['validates'] as $kv => $vv) {
                if (array_key_exists($kv, $this->config['validates'])) {
                    $valid = $vv;
                    eval($this->config['validates'][$kv]);
                }
            }
            if (!empty($val)) {

                if (isset($value['format']) && array_key_exists($value['format'], $this->config['format'])) {

                    eval($this->config['format'][$value['format']]);

                }else{

                    $this->construct_rs($key, $object[$key]);

                }

            }
        }

        return $this->return;
    }

}