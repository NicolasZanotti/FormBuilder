<?php

require_once '../src/Form.php';
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Form test case.
 */
class FormTest extends PHPUnit_Framework_TestCase {
	private $Form;
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Form = null;
		parent::tearDown();
	}
	
	/**
	 * Tests Form->generate() with textarea.
	 */
	public function testGenerateTextArea() {
		$formFields = array (
				'Name' => array (
						'type' => 'textarea'
				)
		);
	
		$this->Form = new Form('Test', $formFields);
	
		$expectedElement = new DOMDocument();
		$expectedElement->loadHTML('<form id="test" action="" method="post"><table><tbody><tr><td><label for="name">Name</label></td><td><textarea name="name" id="name" ></textarea></td><td class="required"></td><td class="error"></td></tr><tr><td>&nbsp;</td><td colspan="3"><input type="submit" /></td></tr></tbody></table></form>');
		$expected = $expectedElement->saveHTML();
	
		$actualElement = new DOMDocument();
		$actualElement->loadHTML($this->Form->generate());
		$actual = $actualElement->saveHTML();
	
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Tests Form->generate() with textfield.
	 */
	public function testGenerateTextField() {
		$formFields = array (
				'Name' => array (
						'type' => 'textfield' 
				) 
		);
		
		$this->Form = new Form('Test', $formFields);
		
		$expectedElement = new DOMDocument();
		$expectedElement->loadHTML('<form id="test" action="" method="post"><table><tbody><tr><td><label for="name">Name</label></td><td><input type="text" name="name" id="name" /></td><td class="required"></td><td class="error"></td></tr><tr><td>&nbsp;</td><td colspan="3"><input type="submit" /></td></tr></tbody></table></form>');
		$expected = $expectedElement->saveHTML();
		
		$actualElement = new DOMDocument();
		$actualElement->loadHTML($this->Form->generate());
		$actual = $actualElement->saveHTML();
		
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Tests Form->generate() with checkbox.
	 */
	public function testGenerateCheckbox() {
		$formFields = array (
				'Software' => array (
						'type' => 'checkbox',
						'options' => array (
								'Word',
								'Powerpoint' 
						) 
				) 
		);
		
		$this->Form = new Form('Test', $formFields);
		
		$expectedElement = new DOMDocument();
		$expectedElement->loadHTML('<form id="test" action="" method="post"><table><tbody><tr><td><span class="label">Software</span></td><td><span class="checkbox"><input type="checkbox" name="software[]" value="Word" id="word" /><label for="word">Word</label></span><span class="checkbox"><input type="checkbox" name="software[]" value="Powerpoint" id="powerpoint" /><label for="powerpoint">Powerpoint</label></span></td><td class="required"></td><td class="error"></td></tr><tr><td>&nbsp;</td><td colspan="3"><input type="submit" /></td></tr></tbody></table></form>');
		$expected = $expectedElement->saveHTML();
		
		$actualElement = new DOMDocument();
		$actualElement->loadHTML($this->Form->generate());
		$actual = $actualElement->saveHTML();
		
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Tests Form->generate() with radio.
	 */
	public function testGenerateRadio() {
		$formFields = array (
				'Meeting' => array (
						'type' => 'radio',
						'options' => array (
								'Attending',
								'Occupied' 
						) 
				) 
		);
		
		$this->Form = new Form('Test', $formFields);
		
		$expectedElement = new DOMDocument();
		$expectedElement->loadHTML('<form id="test" action="" method="post"><table><tbody><tr><td><span class="label">Meeting</span></td><td><span class="radio"><input type="radio" name="meeting" value="attending" id="attending" /><label for="attending">Attending</label></span><span class="radio"><input type="radio" name="meeting" value="occupied" id="occupied" /><label for="occupied">Occupied</label></span></td><td class="required"></td><td class="error"></td></tr><tr><td>&nbsp;</td><td colspan="3"><input type="submit" /></td></tr></tbody></table></form>');
		$expected = $expectedElement->saveHTML();
		
		$actualElement = new DOMDocument();
		$actualElement->loadHTML($this->Form->generate());
		$actual = $actualElement->saveHTML();
		
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Tests Form->generate() with select.
	 */
	public function testGenerateSelect() {
		$formFields = array (
				'Computer' => array (
						'type' => 'select',
						'options' => array (
								'Mac',
								'PC'
						)
				)
		);
	
		$this->Form = new Form('Test', $formFields);
	
		$expectedElement = new DOMDocument();
		$expectedElement->loadHTML('<form id="test" action="" method="post"><table><tbody><tr><td><label for="computer">Computer</label></td><td><select name="computer" id="computer" ><option>Mac</option><option>PC</option></select></td><td class="required"></td><td class="error"></td></tr><tr><td>&nbsp;</td><td colspan="3"><input type="submit" /></td></tr></tbody></table></form>');
		$expected = $expectedElement->saveHTML();
	
		$actualElement = new DOMDocument();
		$actualElement->loadHTML($this->Form->generate());
		$actual = $actualElement->saveHTML();
	
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Tests Form->generate() with the requited attribute.
	 */
	public function testGenerateRequired() {
		$formFields = array (
				'Name' => array (
						'type' => 'textfield',
						'required' => true 
				) 
		);
		
		$this->Form = new Form('Test', $formFields);
		
		$expectedElement = new DOMDocument();
		$expectedElement->loadHTML('<form id="test" action="" method="post"><table><tbody><tr><td><label for="name">Name</label></td><td><input type="text" name="name" id="name" required /></td><td class="required"><strong>*</strong></td><td class="error"></td></tr><tr><td>&nbsp;</td><td colspan="3"><input type="submit" /></td></tr></tbody></table></form>');
		$expected = $expectedElement->saveHTML();
		
		$actualElement = new DOMDocument();
		$actualElement->loadHTML($this->Form->generate());
		$actual = $actualElement->saveHTML();
		
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * Tests Form->element() with the textarea attribute and user input.
	 */
	public function testTextareaFilled() {
		$formFields = array (
				'Software' => array (
						'type' => 'textarea'
				)
		);
	
		$userInput = array (
				'software' => 'Foo'
		);
	
		$this->Form = new Form('Test', $formFields, $userInput);
	
		$expected = '<textarea name="software" id="software" >Foo</textarea>';
		$actual = $this->Form->element('Software');
	
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Tests Form->element() with the textinput attribute and user input.
	 */
	public function testTextfieldFilled() {
		$formFields = array (
				'Software' => array (
						'type' => 'textfield'
				)
		);
	
		$userInput = array (
				'software' => 'Foo'
		);
	
		$this->Form = new Form('Test', $formFields, $userInput);
	
		$expected = '<input type="text" name="software" id="software" value="Foo" />';
		$actual = $this->Form->element('Software');
	
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Tests Form->element() with the checkbox attribute and user input.
	 */
	public function testCheckboxChecked() {
		$formFields = array (
				'Software' => array (
						'type' => 'checkbox',
						'options' => array (
								'Word',
								'Powerpoint' 
						) 
				) 
		);
		
		$userInput = array('software'=>array('0'=>'Word', '1'=>'Powerpoint'));
		
		$this->Form = new Form('Test', $formFields, $userInput);
		
		$expected = '<span class="checkbox"><input type="checkbox" name="software[]" value="Word" id="word" checked/><label for="word">Word</label></span><span class="checkbox"><input type="checkbox" name="software[]" value="Powerpoint" id="powerpoint" checked/><label for="powerpoint">Powerpoint</label></span>';
		$actual = $this->Form->element('Software');
		
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Tests Form->element() with the radio attribute and user input.
	 */
	public function testRadioChecked() {
		$formFields = array (
				'Software' => array (
						'type' => 'radio',
						'options' => array (
								'Word',
								'Powerpoint'
						)
				)
		);
	
		$userInput = array('software'=>'word');
	
		$this->Form = new Form('Test', $formFields, $userInput);
	
		$expected = '<span class="radio"><input type="radio" name="software" value="word" id="word" checked/><label for="word">Word</label></span><span class="radio"><input type="radio" name="software" value="powerpoint" id="powerpoint" /><label for="powerpoint">Powerpoint</label></span>';
		$actual = $this->Form->element('Software');
	
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Tests Form->element() with the select attribute and user input.
	 */
	public function testOptionSelected() {
		$formFields = array (
				'Software' => array (
						'type' => 'select',
						'options' => array (
								'Word',
								'Powerpoint'
						)
				)
		);
	
		$userInput = array('software'=>'Powerpoint');
	
		$this->Form = new Form('Test', $formFields, $userInput);
	
		$expected = '<select name="software" id="software" ><option>Word</option><option selected>Powerpoint</option></select>';
		$actual = $this->Form->element('Software');
	
		$this->assertEquals($expected, $actual);
	}
}
