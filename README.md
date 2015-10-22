# PHP-Form-Validate

PHP-Form-Validate is a forms validation method in PHP, where you predefine rules of each field, eg not null, max length, min length and formatting example only numeric, thus returning the validation errors and the fields in the format desired.


## POST validation example

**Rules**
```php
<?php
include 'ArrayValidate.class.php';
if (!empty($_POST)) {

    $ArrayValidate = new ArrayValidate();

    $valid = array(
        "name" => array(
            "label" => "Name",
            "validates" => array(
                "notnull" => true,
                "maxlength" => 10,
                "minlength" => 4
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
                "notnull" => true
            ),
            "format" => "date"
        )
    );
    //Returns index date with validated values and index error with error labels
    var_dump($ArrayValidate->margeValidate($valid, $_POST));
}
```
