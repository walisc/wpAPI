<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/13
 * Time: 7:46 PM
 */



class MultiSelect extends BaseElement
{
    //Value -> label
    public $options = [];


    function __construct($id, $label, $options, $permissions=[], $elementPath='')
    {
        parent::__construct($id, $label, $permissions, $elementPath);
        $this->options = $options;
    }

    function ReadView($post)
    {
        $value = $this->GetDatabaseValue($post);
        $display_values = [];

        foreach ($value as $selected_value => $selected_label)
        {
            if (array_key_exists($selected_value, $this->options))
            {
                array_push($display_values, $selected_label);
            }
        }

        echo $this->twigTemplate->render(get_class($this).'/read_view.mustache', ["value" => implode(', ', $display_values)]);
    }

    function EditView( $post)
    {
       parent::EditView($post);

        $select_values = [];
        $db_value = $this->GetDatabaseValue($post);

        if (is_array($db_value))
        {
        foreach ($db_value as $selected_value => $selected_label)
        {
            if (array_key_exists($selected_value, $this->options))
            {
                array_push($select_values, $selected_value);
            }
        }
        }

       echo $this->twigTemplate->render(get_class($this).'/edit_view.mustache', ["options" => $this->options,
                                                                                "id" => $this->id,
                                                                                "selected_option" => $select_values ]);
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = $_POST[$this->id];
        $value_key_array = [];

        if (is_array($data)) {
            foreach ($data as $key) {
                if (array_key_exists($key, $this->options) and $key != "") { //TODO: Might need to think of better way to allow none selection
                    $value_key_array[$key] = $this->options[$key];

                }
            }
        }

        $this->SaveElementData($post_id, $value_key_array);
        
    }
}