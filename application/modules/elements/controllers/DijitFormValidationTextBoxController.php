<?php

class Elements_DijitFormValidationTextBoxController extends Elements_BaseController
{

    public function indexAction()
    {
        $r = $this->getRequest();
        
        $this->view->type = $r->getParam('password') === 'true' ? 'password' : 'text' ;
        $this->view->readonly = $r->getParam('readonly') === 'true' || $r->getParam($r->getParam('mode')) == 'readonly' ? " readonly=\"readonly\"" : '';
        $this->view->disabled = $r->getParam('disabled') === 'true' || $r->getParam($r->getParam('mode')) == 'disabled' ? " disabled=\"disabled\"" : '';
        $this->view->required = $r->getParam('required') === 'true' ? " required=\"true\"" : '';
        $this->view->onblur = $r->getParam('onblur') ? " onblur=\"{$r->getParam('onblur')}\"" : '';
        $this->view->style = $r->getParam('style') ? " style=\"{$r->getParam('style')}\"" : '';
        $this->view->regExp = $r->getParam('regExp') ? " pattern=\"{$r->getParam('regExp')}\"" : '';
        $this->view->onchange = $r->getParam('onchange') ? " onchange=\"{$r->getParam('onchange')}\"" : '';
        $this->view->onclick = $r->getParam('onclick') ? " onclick=\"{$r->getParam('onclick')}\"" : '';
        $this->view->onkeypress = $r->getParam('onkeypress') ? " onkeypress=\"{$r->getParam('onkeypress')}\"" : '';
        $this->view->onkeyup = $r->getParam('onkeyup') ? " onkeyup=\"{$r->getParam('onkeyup')}\"" : '';
        $this->view->placeHolder = $r->getParam('placeHolder') ? " placeHolder=\"{$r->getParam('placeHolder')}\"" : '';
        $this->view->invalidMessage = $r->getParam('invalidMessage') ? " invalidMessage=\"{$r->getParam('invalidMessage')}\"" : '';
        $this->view->missingMessage = $r->getParam('missingMessage') ? "missingMessage=\"{$r->getParam('missingMessage')}\"" : '';
        $this->view->promptMessage= $r->getParam('promptMessage') ? " promptMessage=\"{$r->getParam('promptMessage')}\"" : '';
        $this->view->autocomplete = $r->getParam('autocomplete') ? " autocomplete=\"{$r->getParam('autocomplete')}\"" : '';
        $this->view->autocorrect = $r->getParam('autocorrect') ? " autocorrect=\"{$r->getParam('autocorrect')}\"" : '';
        $this->view->autocapitalize = $r->getParam('autocapitalize') ? " autocapitalize=\"{$r->getParam('autocapitalize')}\"" : '';
        $this->view->spellcheck = $r->getParam('spellcheck') ? " spellcheck=\"{$r->getParam('spellcheck')}\"" : '';
        $this->view->maxlength = $r->getParam('maxlength')? " maxLength=\"{$r->getParam('maxlength')}\"" : '';
        $this->view->trim = $r->getParam('trim', '') === 'true' ? " trim=\"true\"" : '';
    }
}

