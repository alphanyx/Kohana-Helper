<?php

use \Modules\Smarty;

class Controller_Base extends Controller {
	public $template = false; 
	public $autoRender = true;
	public $view = false;
	
	public function before() {
		$this->template = implode('/', array($this->request->directory(), $this->request->controller(), $this->request->action())); 
		$this->view = new Smarty();
	}
	
	public function after() {
		if($this->autoRender) $this->render();
	}
	
	public function render() { 
		$this->view->controller = $this;
		$this->response->body($this->view->render($this->template));
	}
}