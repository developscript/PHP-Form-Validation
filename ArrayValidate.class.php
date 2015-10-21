<?php

class ArrayValidate
{
    /**
     * @var array
     */
    public $config = array(
        /**
         * Labels Erro, change the "?" the desired value.
         */
        "lb_erro" => array(
            "notnull" => "Field ? can not be null.",
            "maxlength" => "Field ? can not be greater than ? characters.",
            "minlength" => "Field ? can not be less than ? characters.",
            "type_integer" => "Field ?, invalid number."
        ),
        /**
         * Validations, Create your own validation.
         * $valid => value method. eg "notnull" => true, $valid => true;
         * $valObj => field value;
         * $key => field index;
         * $label => field label;
         */
        "validates" => array(
            "notnull" =>
                'if($valid === true && empty($valObj)){
                    $this->construct_erro("notnull", array($label));
                }',
            "maxlength" =>
                'if (strlen($valObj) > intval($valid)) {
                    $this->construct_erro("maxlength", array($label,$valid));
                }',
            "minlength" =>
                'if (strlen($valObj) < intval($valid)) {
                    $this->construct_erro("minlength", array($label,$valid));
                }',
            "numeric" =>
                'if($valid === true && !is_numeric($valObj)){
                    $this->construct_erro("type_integer", array($label));
                }'
        ),
        /**
         * Format, format the return value.
         * $valObj => field value;
         * $key => field index;
         * $label => field label;
         */
        "format" => array(
            "date" => 'if(count(explode("/", $valObj)) === 3){ list($d, $m, $y) = explode("/", $valObj); $this->construct_rs($key, $y . "-" . $m . "-" . $d); }',
            "convertNumber" => '$this->construct_rs($key, preg_replace("/\D/", "", $valObj));'
        )
    );
    /**
     * @var array
     */
    public $return = array(
        "data" => array(),
        "erros" => array()
    );

    /**
     * @param $lb_erro
     * @param $data
     */
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

    /**
     * @param $key
     * @param $valObj
     */
    public function construct_rs($key, $valObj)
    {
        $this->return["data"][$key] = $valObj;
    }

    /**
     * @param $validate
     * @param $object
     * @return array
     */
    public function margeValidate($validate, $object)
    {

        foreach ($validate as $k => $v) {
            $key = $k;
            $valObj = $object[$key];
            $label = $v['label'];

            foreach ($v['validates'] as $kv => $vv) {
                if (array_key_exists($kv, $this->config['validates'])) {
                    $valid = $vv;
                    eval($this->config['validates'][$kv]);
                }
            }
            if (!empty($valObj)) {

                if (isset($v['format']) && array_key_exists($v['format'], $this->config['format'])) {

                    eval($this->config['format'][$v['format']]);

                }else{

                    $this->construct_rs($key, $object[$key]);

                }

            }
        }

        return $this->return;
    }

}