<?php 

class Language
{
	protected $language;
	
	public function __construct()
	{
		$this->language = 'francais';
	}
	
	public function setLang($lang)
	{
		$this->language = $lang;
	}

	public function getLang()
	{
		return $this->language;
	}
	
}

?>