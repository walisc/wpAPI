<?php


/**
 * Class Checkbox
 *
 *
 * @package wpAPI\Core\Elements\Checkbox
 */
class Checkbox extends BaseElement
{

    function __construct($id, $label, $permissions=[], $elementPath='')
    {
        parent::__construct($id, $label, $permissions, $elementPath);
    }

    function ReadView($post)
    {
        $activeValue = $this->GetDatabaseValue($post) == "on" ? "checked" : "";
        echo $this->twigTemplate->render('/read_view.mustache', ["active_value" => $activeValue]);
    }

    function EditView( $post)
    {
       parent::EditView($post);

       $activeValue = $this->GetDatabaseValue($post) == "on" ? "checked" : "";
       echo $this->twigTemplate->render('/edit_view.mustache', ["active_value" => $activeValue,
                                                                                "id" => $this->id]);
    }

    function ProcessPostData($post_id)
    {
        parent::ProcessPostData($post_id);
        $data = sanitize_text_field($_POST[$this->id]);

        $this->SaveElementData($post_id, $data);
        
    }
}