<?php


/**
 * Validates, generates ands lists form elements based on an array structure.
 * 
 * formFields structure:
 * <code>
	'Name' => array(
 		'type' => 'textfield/textarea/checkbox/select/radio',
 		'description' => 'text that will be shown below the form field',
 		'required' => boolean,
 		'filters' => array (
				array (
						'filter' => php filter,
						'flags' => flags for filter,
						'function' => closure returning true if valid,
						'message' => 'Custom error message' 
				)
		),
		'options' => array('Selection options for', 'checkbox', 'select', 'radio')
 	);
 	</code>
 * 
 * @author Nicolas Zanotti
 */
class FormBuilder {
	private $elements;
	private $input;
	private $title;
	private $hasErrors;
	private $hasEntries;
	
	function __construct($title, $formElements, $userInput = array()) {
		$this->title = $title;
		$this->input = $userInput;
		
		// Add the id for use in HTML
		foreach ($formElements as $key => $value) {
			$formElements[$key]['id'] = $this->sluggify($key);
		}
		
		$this->elements = $formElements;
	}

	public function generate($action = '') {
		$output = (
			  '<form id="' . $this->sluggify($this->title) . '" action="'. $action . '" method="post">'
			. '<table>'
			. '<tbody>'
		);
		
		foreach ($this->elements as $key => $value) {
			$output .= (
					'<tr>'
					. '<td class="label">' . $this->label($key) . '</td>'
					. '<td class="element">' . $this->element($key) . '</td>'
					. '<td class="required">' . $this->required($key) . '</td>'
					. '<td class="error">' . $this->error($key) . '</td>'
					. '</tr>'
			);
				
			if (isset($this->elements[$key]['description'])) {
				$output .= '<tr><td>&nbsp;</td><td class="description" colspan="3"><small>' . $this->elements[$key]['description'] .'</small></td></tr>';
			}
		}
			
		$output .= (
			  '<tr>'
			. '<td>&nbsp;</td>'
			. '<td colspan="3"><input type="submit" /></td>'
			. '</tr>'
			. '</tbody>'
			. '</table>'
			. '</form>'
			. "\n"
		);
		
		return $output;
	}

	public function validate() {
		$this->hasEntries = false;
		$this->hasErrors = false;
		
		foreach ( $this->elements as $key => $value ) {
			
			// Not set
			if (!isset($this->input[$this->id($key)])) break;
			
			// Not required and empty
			if (!isset($value['required']) && (is_string($this->input[$this->id($key)]) && strlen($this->input[$this->id($key)]) == 0)) break;
			
			$this->hasEntries = true;
			
			// No filters defined
			if (!isset($value ['filters'])) break; 
			
			foreach ( $value ['filters'] as $validator ) {
				$isValidAfterFunction = true;
				$isValidAfterFilter = true;
				
				// Apply filters
				if (isset($validator['filter'])) {
					if (isset($validator['flags'])) {
						$isValidAfterFilter = $this->input[$this->id($key)] = filter_var($this->input[$this->id($key)], $validator['filter'], $validator['flags']);
					} else {
						$isValidAfterFilter = $this->input[$this->id($key)] = filter_var($this->input[$this->id($key)], $validator['filter']);
					}
				}
				
				// Apply functions
				if (isset($validator['function'])) {
					$isValidAfterFunction = $validator['function']($this->input[$this->id($key)]);
				}
				
				// Set error message
				if (!$isValidAfterFunction || !$isValidAfterFilter) {
					$this->hasErrors = true;
				
					if(!isset($value['error']) && isset($validator['message'])) {
						$this->elements[$key]['error'] = $validator['message'];
					}
				}
			}
		}
		
		return ($this->hasEntries && ! $this->hasErrors);
	}
	
	public function message() {
		$output = '<h1>' . $this->title . '</h1><dl>';

		foreach ($this->elements as $key => $value) {
			$output .= '<dt>' . $key . '</dt><dd>';
			
			if (is_array($this->userInput($key))) {
				$output .= '<ul>';
				
				foreach ($this->userInput($key) as $inputKey => $inputValue) {
					$output .= '<li>' . $inputValue . '</li>';
				}
			
				$output .= '</ul>';
			
			} else {
				$output .= $this->userInput($key);
			}
			
			$output .= '</dd>';			
		}

		$output .= '</dl>';

		return $output;
	}
	
	public function label($key) {
		$value = $this->elements[$key];
		
		// Radios and checkboxes have their own labels.
		if ($value['type'] != 'radio' && $value['type'] != 'checkbox') {
			return '<label for="' . $this->id($key) . '">' . $key . '</label>';
		} else {
			return '<span class="label">' . $key . '</span>';
		}
	}
	
	public function element($key) {
		$output = '';
		
		switch ($this->elements[$key]['type']) {
			case 'textarea':
				$output .= '<textarea ' . $this->attribute('name', $key) . $this->attribute('id', $key) . '>' . $this->userInput($key) . '</textarea>';
			break;
			
			case 'textfield':
				$output .= '<input type="text" ' . $this->attribute('name', $key) . $this->attribute('id', $key) . $this->attribute('value', $key) . $this->attribute('required', $key) . '/>';
			break;
			
			case 'checkbox':
				if (isset($this->elements[$key]['options'])) {
					foreach ($this->elements[$key]['options'] as $optionsKey => $optionsValue) {
						$slugified = $this->sluggify($optionsValue);
						$nameAttrib = 'name="' . $this->id($key) . '[]" ';
						$valueAttrib = 'value="' . $optionsValue . '" ';
						$idAttrib = 'id="' . $slugified . '" ';
						$checked = '';
						
						if (is_array($this->userInput($key) )) {
							if (in_array($optionsValue, $this->userInput($key))) {
								$checked = 'checked';
							}
						}
				
						$output .= '<span class="checkbox"><input type="checkbox" ' . $nameAttrib . $valueAttrib . $idAttrib . $checked . '/>';
						$output .= '<label for="' . $slugified .'">' . $optionsValue . '</label></span>';
					}
				}
			break;

			case 'radio':
				if (isset($this->elements[$key]['options'])) {
					foreach ($this->elements[$key]['options'] as $optionsKey => $optionsValue) {
						$slugified = $this->sluggify($optionsValue);
						$nameAttrib = 'name="' . $this->id($key) . '" ';
						$valueAttrib = 'value="' . $slugified . '" ';
						$idAttrib = 'id="' . $slugified . '" ';
						$checked = $this->userInput($key) == $slugified ? 'checked' : '';
						
						$output .= '<span class="radio"><input type="radio" ' . $nameAttrib . $valueAttrib . $idAttrib . $checked . '/>';
						$output .= '<label for="' . $slugified .'">' . $optionsValue . '</label></span>';
					}
				}
			break;
			
			case 'select':
				$output .= '<select ' . $this->attribute('name', $key) . $this->attribute('id', $key) . '>';
				if (isset($this->elements[$key]['options'])) {
					foreach ($this->elements[$key]['options'] as $optionsKey => $optionsValue) {
						$selected = $this->userInput($key) == $optionsValue ? ' selected' : '';
						$output .= '<option' . $selected . '>' . $optionsValue . '</option>';
					}
				}
				$output .= '</select>';
			break;
		}

		return $output;
	}
	
	public function required($key) {
		if (isset($this->elements[$key]['required'])) {
			if ($this->elements[$key]['required']) {
				return '<strong>*</strong>';
			}
		}
		return '';
	}
	
	public function error($key) {
		if (isset($this->elements[$key]['error'] )) {
			return $this->elements[$key]['error'];
		}
		return "";
	}
	
	public function userInput($key) {
		if (isset($this->input[$this->id($key)])) {
			return $this->input[$this->id($key)];
		}
		return "";
	}
	
	public function id($key) {
		return $this->elements[$key]['id'];
	}

	private function attribute($type, $key) {
		$output = '';

		switch ($type) {
			case 'required':
				if (isset($this->elements[$key]['required'])) {
					if ($this->elements[$key]['required']) {
						$output = 'required ';
					}
				}
			break;

			case 'value':
				if (strlen($this->userInput($key)) > 0) {
					$output = 'value="' . $this->userInput($key) . '" ';
				}
			break;

			case 'id':
				$output = 'id="' . $this->id($key) . '" ';
			break;
			
			case 'name':
				$output = 'name="' . $this->id($key) . '" ';
			break;

			case 'checked':
				if ($this->userInput($key) == 'on') {
					$output = 'checked="true" ';
				}
			break;
		}


		return $output;
	}

	private function sluggify($string)
	{
		// Replace special charachters
		$string = strtr(utf8_decode($string), utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'), 'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');

		// Prep string with some basic normalization
		$string = strtolower($string);
		$string = strip_tags($string);
		$string = stripslashes($string);
		$string = html_entity_decode($string);

		// Remove quotes (can't, etc.)
		$string = str_replace('\'', '', $string);

		// Replace non-alpha numeric with hyphens
		$string = preg_replace('/[^a-z0-9]+/', '-', $string);

		$string = trim($string, '-');

		return $string;
	}
}
