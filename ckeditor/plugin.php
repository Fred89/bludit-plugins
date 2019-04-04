<?php
/**
 *  Ckeditor + filemanager
 *
 *  @package Bludit
 *  @subpackage Plugins
 *  @author Frédéric K.
 *  @copyright 2015-2018 Frédéric K.
 *	@version 4.8.0
 *  @release 2015-07-14
 *  @update 2018-03-20
 *
 */	
class pluginCKeditor extends Plugin {
	
	private $loadOnController = array(
		'new-content',
		'edit-content'
	);

	public function init()
	{	
		$this->dbFields = array(
			'akey' => pluginCKeditor::randomString()
			);
	}

    /**
     * AFFICHE LA FEUILLE DE STYLE ET LE JAVASCRIPT UNIQUEMENT EN ADMINISTRATION (POSTS/PAGES).
     *
     */	
	public function adminHead()
	{
		// Load the plugin only in the controllers setted in $this->loadOnController
		if (!in_array($GLOBALS['ADMIN_CONTROLLER'], $this->loadOnController)) {
			return false;
		}
		$pluginPath = $this->htmlPath(). 'libs' .DS. 'ckeditor'. DS;			
		return '<script src="' .$pluginPath. 'ckeditor.js"></script>';
	}
	
	public function adminBodyEnd()
	{
		global $L;

		// Load the plugin only in the controllers setted in $this->loadOnController
		if (!in_array($GLOBALS['ADMIN_CONTROLLER'], $this->loadOnController)) {
			return false;
		}
		$langPath = $this->phpPath(). 'libs' . DS . 'ckeditor' . DS . 'lang' . DS;
		if (file_exists($langPath . $L->currentLanguage().'.js')) {
			$lang = $L->currentLanguage();
		} elseif (file_exists($langPath . $L->currentLanguageShortVersion().'.js')) {
			$lang = $L->currentLanguageShortVersion();
		}

		$pluginPath = $this->htmlPath(). 'libs' .DS. 'filemanager' .DS;
		$html = '';
		$html .= '		
				<script>	
		CKEDITOR.replace( "jseditor", {
			language: "' .$lang. '",
			fullPage: false,				
			allowedContent: false,
			filebrowserBrowseUrl : "'.$pluginPath.'dialog.php?type=2&editor=ckeditor&akey='.$this->getValue('akey').'&fldr=",
			filebrowserImageBrowseUrl : "'.$pluginPath.'dialog.php?type=1&editor=ckeditor&akey='.$this->getValue('akey').'&fldr=",
			filebrowserUploadUrl : "'.$pluginPath.'dialog.php?type=2&editor=ckeditor&akey='.$this->getValue('akey').'&fldr="
		});
				
		config.extraPlugins= "bluditbreak";
		config.bluditbreak = [
			{
				name:"Bludit pagebreak",
				icon:"'.$this->htmlPath(). 'pagebreak.gif",
				html:"\n'.PAGE_BREAK.'\n",
				title:"Insert Pagebreak"
			}
		];		
		</script>'.PHP_EOL;
		
		return $html;

	}

	public function form()
	{
		global $L;			
		
		$html  = '<div>';
		$html .= '<label>'.$L->get('Filemanager Access Key').'</label>';
		$html .= '<input name="akey" id="jsakey" type="text" value="'.$this->getValue('akey').'">';
		$html .= '<span class="tip">'.$L->get('Generate key (refresh for new):'). ' <b>'.pluginCKeditor::randomString().'</b></span>';
		$html .= '</div>';

		#$html .= '<div>';
		#$html .= '<label>'.$L->get('select-toolbar').'</label>';
		#$html .= '<select name="toolbar">';
		#$html .= '<option value="basic" '.($this->getValue('toolbar')==='basic'?'selected':'').'>'.$L->get('Basic').'</option>';
		#$html .= '<option value="standard" '.($this->getValue('toolbar')==='standard'?'selected':'').'>'.$L->get('Standard').'</option>';
		#$html .= '<option value="full" '.($this->getValue('toolbar')==='full'?'selected':'').'>'.$L->get('Full').'</option>';
		#$html .= '</select>';
		#$html .= '</div>';
	
		return $html;
	}

	/*
	 * Create a random string
	 * @author	XEWeb <>
	 * @param $length the length of the string to create
	 * @return $str the string
	 */
	public function randomString($length = 12) {
		$str = "";
		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}		
}
