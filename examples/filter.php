<?php

require_once '../src/Form.php';
require_once '../src/Validators.php';
require_once '../lib/FirePHPCore/fb.php';

$validators = new Validators();

$formFields = array (
		'First' => array (
				'type' => 'textfield',
				'filters' => array (
						array (
								'filter' => FILTER_SANITIZE_STRING,
								'flags' => FILTER_FLAG_STRIP_HIGH,
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
		'URL' => array (
				'type' => 'textfield',
				'required' => true,
				'filters' => array (
						array (
								'filter' => FILTER_SANITIZE_STRING,
								'flags' => FILTER_FLAG_STRIP_HIGH,
								'function' => $validators->containsContent,
								'message' => 'Please enter a URL.' 
						),
						array (
								'filter' => FILTER_VALIDATE_URL,
								'flags' => FILTER_FLAG_PATH_REQUIRED,
								'message' => 'Please enter a valid URL.' 
						) 
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
