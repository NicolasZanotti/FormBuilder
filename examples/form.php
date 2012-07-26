<?php

require_once '../src/Form.php';
require_once '../src/Validators.php';

$validators = new Validators();

$formFields = array (
		'Name' => array (
				'type' => 'textfield',
				'required' => true,
				'filters' => array (
						array (
								'filter' => FILTER_SANITIZE_STRING,
								'flags' => FILTER_FLAG_STRIP_HIGH,
								'message' => 'Please enter a name.'
						) 
				)
		),
		'Email' => array (
				'type' => 'textfield',
				'required' => true,
				'filters' => array (
						array (
								'filter' => FILTER_SANITIZE_STRING,
								'flags' => FILTER_FLAG_STRIP_HIGH,
								'function' => $validators->containsContent,
								'message' => 'Please enter an email address.' 
						),
						array (
								'filter' => FILTER_VALIDATE_EMAIL,
								'message' => 'Please enter a valid email address.' 
						) 
				) 
		),
		'Fruit' => array (
				'type' => 'checkbox',
				'options' => array (
						'Banana',
						'Orange',
						'Apple' 
				)
		),
		'Computer' => array (
				'type' => 'select',
				'options' => array (
						'Mac',
						'PC' 
				) 
		),
		'Comments' => array (
				'type' => 'textarea',
				'filters' => array (
						array (
								'filter' => FILTER_SANITIZE_STRING,
								'flags' => FILTER_FLAG_STRIP_HIGH,
						)
				)
		),
		'Meeting' => array (
				'type' => 'radio',
				'options' => array (
						'Attending',
						'Occupied' 
				) 
		) 
);

$userInput = $_POST;


$form = new Form('Test', $formFields, $userInput);

?>
<!DOCTYPE html>
<html>
<head lang="en">
<meta charset="utf-8" />
<title>Form Example</title>
<style type="text/css">
.error {
	color: red;
}

.required {
	color: orange;
}
</style>
</head>
<body>
	<?php print $form->validate() ? "Form validated." : $form->generate($_SERVER['PHP_SELF']); ?>
</body>
</html>
