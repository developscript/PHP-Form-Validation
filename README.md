# PHP-Form-Validate

PHP-Form-Validate is a forms validation method in PHP, where you predefine rules of each field, eg not null, max length, min length and formatting example only numeric, thus returning the validation errors and the fields in the format desired.

##Instantiating.
Instantiate the class, if you can not instantiate the class stop there because you are doing it wrong... rsrsrsr
```php
<?php
include 'ArrayValidate.class.php';
$ArrayValidate = new ArrayValidate();
```

##Add new label, validation or format.
Add new validation rules, formatting and errors, very easy.

###Function "setConfig", used to set new rules.
Example:
```php
$ArrayValidate->setConfig(
    array(
        //data
    )
);
```

###"lb_erro" => Labels Erro, change the "?" the desired value.
Example:
```php
$ArrayValidate->setConfig(
    array(
        "lb_erro" => array(
            "maxlength" => "Field ?, can not be greater than ? characters."
        )
    )
);
```

###"validates" => Validations, Create your own validation.
Create a simple validation with "if".
Parameters:
* $valid => value method. eg "notnull" => true, $valid => true;
* $valObj => field value;
* $key => field index;
* $label => field label;

Example:
```php
$ArrayValidate->setConfig(
    array(
        "validates" => array(
            "maxlength" =>
            'if (strlen($valObj) > intval($valid)) {
                $this->construct_erro("maxlength", array($label,$valid));
            }'
        )
    )
);
```

###"format" => Format, format the return value.
Return the fields already formatted.
Parameters:
* $valObj => field value;
* $key => field index;
* $label => field label;

Example:
```php
$ArrayValidate->setConfig(
    array(
        "format" => array(
            "convertNumber" => '$this->construct_rs($key, preg_replace("/\D/", "", $valObj));'
        )
    )
);
```

###Function "construct_rs", construct return result.
$this->construct_rs => construct return result, send parameters key more value.

###Function "construct_erro", call label error.
$this->construct_erro => call label error "maxlength", send parameters array($label,$valid) to change "?".

###Example "setConfig"
```php
$ArrayValidate->setConfig(
    array(
        "lb_erro" => array(
            "type_integer" => "Field ?, invalid number."
        ),
        "validates" => array(
            "numeric" =>
                'if($valid === true && !is_numeric($valObj)){
                $this->construct_erro("type_integer", array($label));
            }'
        )
    )
);
```


## POST validation example
The name of "input" must be equal to "index" the "array" validation.
Example: $_POST['name'] == array("name" => array());

**Rules**
```php
<?php
if (!empty($_POST)) {

    include 'ArrayValidate.class.php';
    $ArrayValidate = new ArrayValidate();

    $ArrayValidate->setConfig(
        array(
            "lb_erro" => array(
                "type_integer" => "Field ?, invalid number."
            ),
            "validates" => array(
                "numeric" =>
                    'if($valid === true && !is_numeric($valObj)){
                    $this->construct_erro("type_integer", array($label));
                }'
            )
        )
    );
    
    /**
     * Rules
     */
    $valid = array(
        "name" => array(
            "label" => "Name",
            "validates" => array(
                "notnull" => true,
                "maxlength" => 10,
                "minlength" => 4
            )
        ),
        "lastname" => array(
            "label" => "Last Name",
            "validates" => array(
                "notnull" => true,
                "maxlength" => 10,
                "minlength" => 4,
            )
        ),
        "age" => array(
            "label" => "Age",
            "validates" => array(
                "notnull" => true,
                "maxlength" => 3,
                "minlength" => 2,
                "numeric" => true
            )
        ),
        "convertNumber" => array(
            "label" => "Convert Number",
            "validates" => array(
                "maxlength" => 10
            ),
            "format" => "convertNumber"
        ),
        "convertDate" => array(
            "label" => "Convert Date",
            "validates" => array(
                "notnull" => true,
                "dateValid" => true
            ),
            "format" => "date"
        )
    );

    //Returns index date with validated values and index error with error labels
    var_dump($ArrayValidate->margeValidate($valid, $_POST));
}
```
