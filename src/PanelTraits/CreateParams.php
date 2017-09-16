<?php

namespace Backpack\CRUD\PanelTraits;

trait CreateParams
{
    // ------------
    // Actions
    // ------------

    /**
     * Define the url parameter that will be passed for the creation of child elements.
     *
     * @param  [string] param.
     *
     */
    public function setCreateParam($param)
    {
        $this->create_param = $param;
    }

}
