<?php
/**
 *  Ckeditor + filemanager
 *
 *  @package Bludit
 *  @subpackage Plugins
 *  @author Frédéric K.
 *  @copyright 2015-2016 Frédéric K.
 *	@version 1.0.8
 *  @release 2015-07-14
 *  @update 2016-03-07
 *
 */	
class pluginCKeditor extends Plugin {
	
	private $loadWhenController = array(
		'new-post',
		'new-page',
		'edit-post',
		'edit-page'
	);
	public function init()
	{	
		$this->dbFields = array(
			'toolbar' => 'basic',
			'skin' => 'bludit',
			'akey' => pluginCKeditor::randomString()
			);
	}

    /**
     * AFFICHE LA FEUILLE DE STYLE ET LE JAVASCRIPT UNIQUEMENT EN ADMINISTRATION (POSTS/PAGES).
     *
     */	
	public function adminHead()
	{
		global $Site;
		global $layout;
		$pluginPath = $this->htmlPath(). 'libs' .DS. 'ckeditor'. DS;
		
		$html = '';

		if(in_array($layout['controller'], $this->loadWhenController))
		{
			$language = $Site->shortLanguage();
			$_SESSION["editor_lang"] = $Site->language();
			$html .= '<script src="'.$pluginPath. 'ckeditor.js"></script>'.PHP_EOL;
			$html .= '<script src="'.$pluginPath. 'lang' .DS. $language.'.js"></script>'.PHP_EOL;		 
		}

		return $html;
	}
	
	public function adminBodyEnd()
	{
		global $Security, $Site, $layout;
		
		$pluginPath = $this->htmlPath(). 'libs' .DS. 'filemanager' .DS;
		$html = '';

		if(in_array($layout['controller'], $this->loadWhenController))
		{
			$language = $Site->shortLanguage();
			$html .= '		
				<script>
	$(\'textarea[name="content"]\').each(function(){  
		
		CKEDITOR.replace( this , {
			language: \''.$language.'\',
			fullPage: false,
			allowedContent: false,
			filebrowserBrowseUrl : \''.$pluginPath.'dialog.php?type=2&editor=ckeditor&akey='.$this->getDbField('akey').'&fldr=\',
			filebrowserImageBrowseUrl : \''.$pluginPath.'dialog.php?type=1&editor=ckeditor&akey='.$this->getDbField('akey').'&fldr=\',
			filebrowserUploadUrl : \''.$pluginPath.'dialog.php?type=2&editor=ckeditor&akey='.$this->getDbField('akey').'&fldr=\'
		});
			CKEDITOR.config.entities = false; // pour faciliter la lecture du code source, les accents ne sont pas transformés en entités HTML (inutiles avec le codage utf-8 des pages)
			    
			// Correction orthographique en français par défaut :
			CKEDITOR.config.language = \''.$language.'\';  
			CKEDITOR.config.wsc_lang = \''.$Site->locale().'\';  
			CKEDITOR.config.scayt_sLang = \''.$Site->locale().'\';
			// config.scayt_autoStartup = false;    // Ligne à activer s’il faut supprimer la correction orthographique automatique, qui génère beaucoup d’accès Internet et peut ralentir l’édition.
			
			'.($this->getDbField('toolbar') == 'standard' ? 'CKEDITOR.config.toolbar = 
			[[\'Bold\', \'Italic\', \'Underline\', \'-\', \'NumberedList\', \'BulletedList\', \'-\', \'JustifyLeft\',\'JustifyCenter\',\'JustifyRight\',\'JustifyBlock\', \'-\', \'Link\', \'Unlink\', \'Image\', \'RemoveFormat\', \'-\', \'Table\', \'TextColor\', \'BGColor\', \'ShowBlocks\'], [\'Source\'], [\'Maximize\'],
			\'/\',
			[\'Styles\',\'Format\',\'Font\',\'FontSize\']];' : '').'
			
			'.($this->getDbField('toolbar') == 'basic' ? 'CKEDITOR.config.toolbar = 
			[[\'Bold\', \'Italic\', \'Underline\', \'-\', \'NumberedList\', \'BulletedList\', \'-\', \'JustifyLeft\',\'JustifyCenter\',\'JustifyRight\',\'JustifyBlock\', \'-\', \'Link\', \'Unlink\', \'Image\', \'RemoveFormat\'], [\'Source\'], [\'Maximize\'] ];' : '').'	
			
			CKEDITOR.config.skin = \''.$this->getDbField('skin').'\';
	
	});
		</script>'.PHP_EOL;
		}
		return $html;
	}

	public function form()
	{
		global $Language;			
			
		$html = '<div class="uk-form-select" data-uk-form-select>
    <span></span>';	
		$html .= '<label for="toolbar">'.$Language->get('Select toolbar').'</label>';
        $html .= '<select name="toolbar">';
        $toolbarOptions = array('basic' => $Language->get('Basic'),'standard' => $Language->get('Standard'),'advanced' => $Language->get('Advanced'));
        foreach($toolbarOptions as $text=>$value)
            $html .= '<option value="'.$text.'"'.( ($this->getDbField('toolbar')===$text)?' selected="selected"':'').'>'.$value.'</option>';
        $html .= '</select>';
        $html .= '<div class="uk-form-help-block">'.$Language->get('Advanced is the full package of CKEditor').'</div>';
		$html .= '</div>';		

		$html .= '<div>';
		$html .= '<label for="jsakey">'.$Language->get('Filemanager Access Key').'</label>';
	    $html .= '<div class="uk-form-icon">';
		$html .= '<i class="uk-icon-key"></i>';
		$html .= '<input class="uk-form-width-large" name="akey" id="jsakey" type="text" value="'.$this->getDbField('akey').'">';
		$html .= '</div>';
		$html .= '<div class="uk-form-help-block">'.$Language->get('Generate key (refresh for new):'). ' <b>'.pluginCKeditor::randomString().'</b></div>';
		$html .= '</div>';
		
		$html .= '<div class="uk-form-select" data-uk-form-select>
    <span></span>';	
		$html .= '<label for="skin">'.$Language->get('Select skin').'</label>';
        $html .= '<select name="skin">';
        $skinOptions = array('kama'=>'Kama','flat'=>'Flat','moono'=>'Moono','minimalist'=>'Minimalist','icy_orange'=>'Icy Orange','moono-dark'=>'Moono Dark','bludit'=>'Bludit');
        foreach($skinOptions as $text=>$value)
            $html .= '<option value="'.$text.'"'.( ($this->getDbField('skin')===$text)?' selected="selected"':'').'>'.$value.'</option>';
        $html .= '</select>';
		$html .= '</div>';	
				
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
