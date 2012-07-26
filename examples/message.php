<?php

require_once '../src/Form.php';
require_once '../src/Validators.php';

$validators = new Validators();
$formFields = array (
		'First' => array (
				'type' => 'textfield',
				'required' => true,
				'validators' => array (
						array (
								'function' => $validators->containsContent,
								'message' => 'Please enter your first name.' 
						) 
				) 
		),
		'Email' => array (
				'type' => 'textfield',
				'required' => true,
				'validators' => array (
						array (
								'function' => $validators->containsContent,
								'message' => 'Please enter an email address.' 
						),
						array (
								'function' => $validators->isValidEmail,
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
				'type' => 'textarea' 
		),
		'Meeting' => array (
				'type' => 'radio',
				'options' => array (
						'Attending',
						'Occupied' 
				) 
		) 
);

$userInput = array (
		'first' => 'Firstname',
		'email' => 'test@test.com',
		'fruit' => array (
				'Banana',
				'Apple' 
		),
		'computer' => 'PC',
		'comments' => 'This is a comment.',
		'meeting' => 'attending' 
);

$form = new Form('Test', $formFields, $userInput);

?>
<!DOCTYPE html>
<html>
<head lang="en">
<meta charset="utf-8" />
<title>Form Message Example</title>
</head>
<body>
	<?php print $form->message(); ?>
</body>
</html>
