<?php

class Elements_ZweiFormValidationTextareaController extends Elements_BaseController
{

    public function indexAction()
    {
        $r = $this->getRequest();
        
        $this->view->autocomplete = $r->getParam('autocomplete') ? " autocomplete=\"{$r->getParam('autocomplete')}\"" : '';
        $this->view->autocorrect = $r->getParam('autocorrect') ? " autocorrect=\"{$r->getParam('autocorrect')}\"" : '';
        $this->view->autocapitalize = $r->getParam('autocapitalize') ? " autocapitalize=\"{$r->getParam('autocapitalize')}\"" : '';
        $this->view->spellcheck = $r->getParam('spellcheck') ? " spellcheck=\"{$r->getParam('spellcheck')}\"" : '';
        
        $this->view->style = $r->getParam('style') ? " style=\"{$r->getParam('style')}\"" : '';
        $this->view->regExp = $r->getParam('regExp') ? " regExp=\"{$r->getParam('regExp')}\"" : '';
        $this->view->invalidMessage = $r->getParam('invalidMessage') ? "invalidMessage=\"{$r->getParam('invalidMessage')}\"" : '';
        $this->view->promptMessage= $r->getParam('promptMessage') ? " promptMessage=\"{$r->getParam('promptMessage')}\"" : '';
        $this->view->maxlength = $r->getParam('maxlength', '');
        $this->view->trim = $r->getParam('trim', '') === 'true' ? " trim=\"true\"" : '';
    }
}

