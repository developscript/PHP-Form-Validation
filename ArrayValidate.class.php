<?php

class ArrayValidate
{
    /**
     * @var array
     */
    private $config = array(
        /**
         * lb_erro => Labels Erro, change the "?" the desired value.
         */
        "lb_erro" => array(
            "notnull" => "Field ?, can not be null.",
            "maxlength" => "Field ?, can not be greater than ? characters.",
            "minlength" => "Field ?, can not be less than ? characters.",
            "dateValid" => "Field ?, date invalid.",
        ),
        /**
         * validates => Validations, Create your own validation.
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
            "dateValid" =>
                'if($valid === true && count(explode("/", $valObj)) !== 3){
                    $this->construct_erro("dateValid", array($label));
                }else if($valid === true && count(explode("/", $valObj)) === 3){
                    list($m, $d, $y) = explode("/", $valObj);
                    if(!checkdate($m, $d, $y)){
                        $this->construct_erro("dateValid", array($label));
                    }
                }'
        ),
        /**
         * Format, format the return value.
         * $valObj => field value;
         * $key => field index;
         * $label => field label;
         */
        "format" => array(
            "date" => 'list($m, $d, $y) = explode("/", $valObj); $this->construct_rs($key, $y . "-" . $m . "-" . $d);',
            "convertNumber" => '$this->construct_rs($key, preg_replace("/\D/", "", $valObj));'
        )
    );

    /**
     * Get Config
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set APPEND Config
     * @param $config
     */
    public function setConfig($config, $key = null)
    {
        foreach ($config as $k => $v) {
            if(is_array($v)){
                $this->setConfig($v, $k);
            }else if(!empty($key)){
                $this->config[$key][$k] = $v;
            }
        }
    }

    /**
     * @var array
     */
    private $return = array(
        "data" => array(),
        "erros" => array()
    );

    /**
     * @param $lb_erro
     * @param $data
     */
    private function construct_erro($lb_erro, $data)
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
    private function construct_rs($key, $valObj)
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

                } else {

                    $this->construct_rs($key, $object[$key]);

                }

            }
        }

        return $this->return;
    }

}