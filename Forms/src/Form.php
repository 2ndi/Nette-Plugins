<?php
/**
 * Copyright (c) 2013 Milan Felix Sulc <rkfelix@gmail.com>
 */

namespace Forms;

use Nette\Templating\ITemplate;

/**
 * Base form for special forms
 *
 * @author Milan Felix Sulc <rkfelix@gmail.com>
 * @licence MIT
 * @version 1.0
 */
abstract class Form extends \Nette\Application\UI\Form
{

	/** @var string */
	protected $templateFile = NULL;

	/**
	 * Custom form render for separate form
	 *
	 * @seeks APP_DIR/Forms/MyFormName.latte
	 */
	public function render()
	{
		$file = $this->getTemplateFilePath($this->templateFile);
		if (file_exists($file)) {
			$template = $this->createTemplate($file);
			$template->_form = $template->form = $this;
			$template->render();
		} else {
			$args = func_get_args();
			array_unshift($args, $this);
			echo call_user_func_array(array($this->getRenderer(), 'render'), $args);
		}
	}

	/**
	 * Derives template path from class name
	 *
	 * @param string|null $name
	 * @return string
	 */
	protected function getTemplateFilePath($name = NULL)
	{
		$class = $this->getReflection();
		$name = !$name ? $class->getShortName() : $name;
		return dirname($class->getFileName()) . \DIRECTORY_SEPARATOR . lcfirst($name) . ".latte";
	}

	/**
	 * Create template
	 *
	 * @param string
	 * @return ITemplate
	 */
	public function createTemplate($file = NULL)
	{
		if ($file) {
			return $this->getPresenter()->createTemplate()->setFile($file);
		}

		return $this->getPresenter()->createTemplate();
	}
}