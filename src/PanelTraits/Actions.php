<?php

namespace Backpack\CRUD\PanelTraits;

trait Actions
{
    // ------------
    // Actions
    // ------------

    /**
     * Remove a save action from the actions array.
     *
     * @param  [string] action.
     *
     * @return array
     */
    public function removeAction($action)
    {
        $action = (array)$action;
        $action = array_flip($action);
        return $this->actions = array_diff_key($this->actions, $action);
    }

}
