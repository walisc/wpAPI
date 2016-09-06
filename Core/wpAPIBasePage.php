<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/08/12
 * Time: 12:28 PM
 */
abstract class wpAPIBasePage
{
    
    protected $slug;
    protected $label;
    protected $parent_slug;
    protected $viewState = wpAPIPermissions::EditPage;
    
    function __construct($slug, $label)
    {
        $this->slug = $slug;
        $this->label = $label;
    }

    public function Render($parent_slug)
    {

        if ($parent_slug != null)
        {
            $this->parent_slug = $parent_slug;
        }
                
        add_action($this->RenderHook(), [$this, "Generate"], 8); #have to make priority lower than 10 to allow for none replacement when using custom post type. See note for show_in_menu @ https://codex.wordpress.org/Function_Reference/register_post_type

    }

    abstract function Generate();
    abstract function RenderHook(); //return what render hook to use

}
