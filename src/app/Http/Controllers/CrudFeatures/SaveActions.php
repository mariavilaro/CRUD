<?php

namespace Backpack\CRUD\app\Http\Controllers\CrudFeatures;

//save_and_back save_and_edit save_and_new
trait SaveActions
{
    /**
     * Get the save configured save action or the one stored in a session variable.
     * @return [type] [description]
     */
    public function getSaveAction()
    {
        $saveAction = session('save_action', config('backpack.crud.default_save_action', 'save_and_back'));
        $saveOptions = [];
        $saveCurrent = [
            'value' => $saveAction,
            'label' => $this->getSaveActionButtonName($saveAction),
        ];

        switch ($saveAction) {
            case 'save_and_edit':
                $saveOptions['save_and_back'] = $this->getSaveActionButtonName('save_and_back');
                $saveOptions['save_and_new'] = $this->getSaveActionButtonName('save_and_new');
                break;
            case 'save_and_new':
                $saveOptions['save_and_back'] = $this->getSaveActionButtonName('save_and_back');
                $saveOptions['save_and_edit'] = $this->getSaveActionButtonName('save_and_edit');
                break;
            case 'save_and_back':
            default:
                $saveOptions['save_and_edit'] = $this->getSaveActionButtonName('save_and_edit');
                $saveOptions['save_and_new'] = $this->getSaveActionButtonName('save_and_new');
                break;
        }

        return [
            'active' => $saveCurrent,
            'options' => $saveOptions,
        ];
    }

    /**
     * Change the session variable that remembers what to do after the "Save" action.
     * @param [type] $forceSaveAction [description]
     */
    public function setSaveAction($forceSaveAction = null)
    {
        if ($forceSaveAction) {
            $saveAction = $forceSaveAction;
        } else {
            $saveAction = \Request::input('save_action', config('backpack.crud.default_save_action', 'save_and_back'));
        }

        if (session('save_action', 'save_and_back') !== $saveAction) {
            \Alert::info(trans('backpack::crud.save_action_changed_notification'))->flash();
        }

        session(['save_action' => $saveAction]);
    }

    /**
     * Redirect to the correct URL, depending on which save action has been selected.
     * @param  [type] $itemId [description]
     * @return [type]         [description]
     */
    public function performSaveAction($itemId = null)
    {
        $saveAction = \Request::input('save_action', config('backpack.crud.default_save_action', 'save_and_back'));
        $returnUrl = \Request::input('return_url', $this->crud->route);
        $itemId = $itemId ? $itemId : \Request::input('id');

        switch ($saveAction) {
            case 'save_and_new':
                $redirectUrl = $this->crud->route.'/create';
                $redirectUrl .= '?';
                if ($this->crud->create_param && \Request::has($this->crud->create_param)) {
                    $redirectUrl .= $this->crud->create_param . '=' . \Request::input($this->crud->create_param) . '&';
                }
                if ($returnUrl != $this->crud->route) {
                    $redirectUrl .= 'return_url='.$returnUrl . '&';
                }
                $redirectUrl = rtrim($redirectUrl, '?&');
                break;
            case 'save_and_edit':
                $redirectUrl = $this->crud->route.'/'.$itemId.'/edit';
                $redirectUrl .= '?';
                if (\Request::has('locale')) {
                    $redirectUrl .= 'locale='.\Request::input('locale') . '&';
                }
                if ($returnUrl != $this->crud->route) {
                    $redirectUrl .= 'return_url='.$returnUrl . '&';
                }
                $redirectUrl = rtrim($redirectUrl, '?&');
                break;
            case 'save_and_back':
            default:
                $redirectUrl = $returnUrl;
                break;
        }

        return \Redirect::to($redirectUrl);
    }

    /**
     * Get the translated text for the Save button.
     * @param  string $actionValue [description]
     * @return [type]              [description]
     */
    private function getSaveActionButtonName($actionValue = 'save_and_back')
    {
        switch ($actionValue) {
            case 'save_and_edit':
                return trans('backpack::crud.save_action_save_and_edit');
                break;
            case 'save_and_new':
                return trans('backpack::crud.save_action_save_and_new');
                break;
            case 'save_and_back':
            default:
                return trans('backpack::crud.save_action_save_and_back');
                break;
        }
    }

    /**
     * Get the referring URL if it is on the same site as backpack.
     * Otherwise get the list all crud route.
     * @return string the return url.
     */
    protected function getReturnUrl()
    {
        if (\Request::has('return_url')) {
            return \Request::input('return_url');
        }

        if (!isset($_SERVER['HTTP_REFERER']) || !isset($_SERVER['HTTP_HOST'])) {
            return $this->crud->route;
        }

        $referer = parse_url($_SERVER['HTTP_REFERER']);

        if ($referer['host'] === $_SERVER['HTTP_HOST'] && (stripos($referer['path'], $this->crud->route)) && isset($referer['query'])
                || \Request::has('custom_return_url')) {
            return $_SERVER['HTTP_REFERER'];
        }

        return $this->crud->route;
    }
}
