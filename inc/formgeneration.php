<?php
/**
** Copyright © Larry Wakeman - 2012
**
** All rights reserved. No part of this publication may be reproduced, stored in a retrieval system, 
** or transmitted in any form or by any means without the prior written permission of the copyright
** owner and must contain the avove copyright notice.
**
** Permission is granted to anyone but this copyright noticemust be included.
*/
/*
    This class is used to create html forms with validation.  To use, in the head(html):
    
<script language="JavaScript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script language="JavaScript" src="validation.js"></script>
<script language="JavaScript">
    var valid = new validate();
</script>

    To setup the class:
    
<?php
include('formgeneration.php');     // load the class
$vf = new formgeneration('valid');  // The name of the javascript class is passed in
?>
    Check the example index.php for an example of creating a child class that extends this class
    
    To create the form(html):
    
<?php echo $vf->open_form()."\n"; ?>

    To close the form:
    
<?php echo $vf->close_form()."\n"; ?>

    To create a label:
    
<?php echo $vf->create_label('Name of object', 'Label')."\n"; ?>

    To create a textbox:
    
<?php echo $vf->create_text('name', 'required')."\n"; ?>

    Textarea:
    
<?php echo $vf->create_textarea('name', 'required', rows, columns)."\n"; ?>

    Checkbox:
    
<?php echo $vf->create_Check('name')."\n"; ?>

    Select List:
    
<?php 
                $values = array(
                    '' => 'Please Select',
                    '1' => 'Option 1',
                    '2' => 'Option 2',
                    '3' => 'Option 3',
                );
               echo $vf->create_select('name', 'required', $values)."\n";
?>

        Radio button group:
        
<?php
        $values = array(
            'Milk' => 'Milk',
            'Butter' => 'Butter',
            'Cheese' => 'Cheese',
        );
        echo $vf->create_radio_group('name', 'required', $values)."\n"; 
?>

        Post submit processng.
        
        The set up is the same for the form.  I use the following to redirect back to the input form on a validation error.
        
<?php
    // post submit processing, normally done on thetarget page though one could useredirects
    if (isset($_POST['submit'])) {
        $error = $vf->validate();
        if ($error != '') {
            // do validation error proccessing
            unset ($_POST['submit']); // we don't want this in the post data going back to the original form
?>
<form name="submision_form" id="submision_form" method="POST" action="/">
    <?php echo $vf->savePostGet(); ?>
    <input type="hidden" name="Message" value="<?php echo $error; ?>">
</form>
<script type="text/javascript">
    $(document).ready(function() {
            alert ('<?php echo $error; ?>');
            $("#submision_form").submit();
    });
</script>
<?php
            exit; // redirect back to the original page
        } else {
            // Save the data or whatever
        }
    }
?>

*/
class formgeneration {
/**
 ** Object Variables
 */
    private $js_class;   // javascript validation object.
/**
 ** Class Construtor
 */
    public function __construct($class) {
        $this->js_class = $class;
    }
   
/**
 ** Function to create a form element
 */
    public function open_form($action ='', $method='POST', $name='submision_form') {
        if ($action == '') $action =  $_SERVER['REQUEST_URI'];
        return '<div id="validationError" class="validationError" style="display: none;"></div>
<form action="'.$action.'" method="'.$method.'" name="'.$name.'"  onsubmit="return '.$this->js_class.'.validateForm(this);">';
    }
   
/**
 ** Function to close a form element
 */
    public function close_form(){
        return '</form>';
    }

/**
 ** Function to create a  label element
 */
    public function create_label($name, $label, $hidden=false){
        $hidden_text='';
        if ($hidden) $hidden_text = ' style="display: none;"';
        return '<label for="'.$name.'"'.$hidden_text.'>'.$label.'</label>';
    }
    
// <input type="text" style="display:none;" nameerror"="" value="adsfa&gt;&lt;div id=" onchange="return valid.validateInput(this);"
//  class="required" id="name" name="name">
/**
 ** Function to create a  text input element
 */
    public function create_text($name, $classes, $inputvalue='', $other_attributes=''){
        $return = '<input type="text" name="'.$name.'" id="'.$name.'" class="'.$classes.'"';
        if ($other_attributes) $return .= $other_attributes;
        $return .= ' onchange="return '.$this->js_class.'.validateInput(this);"';
        if (isset($_POST[$name])) {
            $return .= ' value="'.$_POST[$name].'"';
        } else if ($inputvalue) {
            $return .= ' value="'.$inputvalue.'"';
        }
        $return .= '><div id="'.$name.'Error" class="validationError" style="display:none;"></div>';
        return $return;
    }

/**
 ** Function to create a  textarea element
 */
    public function create_textarea($name, $classes, $rows, $columns,$inputvalue='', $other_attributes=''){
       $return = '<textarea name="'.$name.'" id="'.$name.'" class="'.$classes.'" rows="'.$rows.'" cols="'.$columns.'"';
        if ($other_attributes) $return .= $other_attributes;
        $return .= ' onchange="return '.$this->js_class.'.validateInput(this);"';
        $return .= '>';
        if (isset($_POST[$name])) {
            $return .= $_POST[$name];
        } else if ($inputvalue) {
            $return .= $inputvalue;
        }
        $return .= '</textarea><div id="'.$name.'Error" class="validationError" style="display:none;"></div>';
        return $return;
    }
    
/**
 ** Function to conditionally check a check box
 */
    private function showCheck($value) {
        if ($value) {
            return " checked=\"checked\"";
        }
        return "";
    }

/**
 ** Function to create a  check box element
 */
	public function create_Check ($name, $inputvalue=0, $class='', $other_attributes='') {
    	if ($class) $class = ' class="'.$class.'"';
        $return = '<input type="checkbox" name="'.$name.'" id="'.$name.'"'.$class;
        if (isset($_POST[$name])) {
            if ($_POST[$name]) {
                $return .= $this->showCheck($_POST[$name]);
            }
        } else {
            $return .= $this->showCheck($inputvalue);
        }
        $return .= '><div id="'.$name.'Error" class="validationError" style="display:none;"></div>';
        return $return;
    }

/**
 ** Function to create a  select list element
 */
	public function create_select ($name, $class, $values, $selected='', $other_attributes='') {
        $return = '<select name="'.$name.'" id="'.$name.'" class="'.$class."\"";
        $return .= ' onchange="return '.$this->js_class.'.validateInput(this);">'."\n";
        foreach ($values as $key => $value) {
            $selected = '';
            if (isset($_POST[$name])) {
                if ($_POST[$name] == $key)  $selected = ' selected="selected"';
            } else {
                if ($selected == $key)  $selected = ' selected="selected"';
            }
        }
        $return .= '<option value="'.$key.'"'.$selected.'>'.$value."</option>\n";
        $return .=  '</select><div id="'.$name.'Error" class="validationError" style="display:none;"></div>'."\n";
        return $return;
    }

/**
 ** Function to create a  group of radio buttons
 */
    public function create_radio_group($name, $class,  $values,  $selected='', $other_attributes='') {
        if (isset($_POST[$name])) $selected = $_POST[$name];
        $return = '';
        foreach ($values as $key => $value) {
            $checked = '';
            if ($key == $selected) $checked = ' checked';
            $return .= '<input type="radio" name="'.$name.'" id="'.$name.'" value="'.$key.'" class="'.$class.'"'.$checked.'> '.$value.'<br>'."\n";
        }
        $return .= '<div id="'.$name.'Error" class="validationError" style="display:none;"></div>'."\n";
        return $return;
    }

/**
 ** Function to perform validation onthe server side
 **
 **    Note that this function returns a null string.  This fuction isdesigned to perform validation that javascript can't.
 **    Things that this function might be able to do is to validate international addresses and phone numbers.
 **    The intent is that the developer will write a class that inherits this class and write the real routine there.
 */
    public function validate() {
        return '';
    }

 /**
 ** Function to save post or get data for retry
 */
    function savePostGet($array=null, $offset ='') {
        if ($array == null) $array = $_POST;
        if ($offset != '') $array = $array[$offset];
        $return = '';
        foreach ($array as $key => $value) {
            $return .= '<input type="hidden" value="'.$value.'" ';
            if ($offset != '') 
                $return .= 'name="['.$offset.']'.$key.'">';
            else
                $return .= 'name="'.$key.'">';
            $return .="\n";
        }
        return $return;  
    }
    
}
?>