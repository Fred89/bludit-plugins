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
 *  @update 2018-03-10
 *
 */	
class pluginCKeditor extends Plugin {
	
	public function init()
	{	
		$this->dbFields = array(
/*
			'toolbar' => 'basic',
			'skin' => 'bludit',
*/
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

			$language = substr($Site->language(), 0, 2);
			$_SESSION["editor_lang"] = $Site->language();
			$html .= '<script src="'.$pluginPath. 'ckeditor.js"></script>'.PHP_EOL;
			$html .= '<script src="'.$pluginPath. 'lang' .DS. $language.'.js"></script>'.PHP_EOL;		 

		return $html;
	}
	
	public function adminBodyEnd()
	{
		global $Security, $Site, $layout;
		
		$pluginPath = $this->htmlPath(). 'libs' .DS. 'filemanager' .DS;
		$html = '';

			$language = substr($Site->language(), 0, 2);
			$html .= '		
				<script>	
		CKEDITOR.replace( "jscontent", {
			language: \''.$language.'\',
			fullPage: false,
			allowedContent: false,
			filebrowserBrowseUrl : \''.$pluginPath.'dialog.php?type=2&editor=ckeditor&akey='.$this->getDbField('akey').'&fldr=\',
			filebrowserImageBrowseUrl : \''.$pluginPath.'dialog.php?type=1&editor=ckeditor&akey='.$this->getDbField('akey').'&fldr=\',
			filebrowserUploadUrl : \''.$pluginPath.'dialog.php?type=2&editor=ckeditor&akey='.$this->getDbField('akey').'&fldr=\'
		});

		</script>'.PHP_EOL;
		return $html;
	}

	public function form()
	{
		global $Language;			
		
		$html = '';	
/*
		$html .= '<div class="uk-form-select" data-uk-form-select>
    <span></span>';	
		$html .= '<label for="toolbar">'.$Language->get('Select toolbar').'</label>';
        $html .= '<select name="toolbar">';
        $toolbarOptions = array('basic' => $Language->get('Basic'),'standard' => $Language->get('Standard'),'advanced' => $Language->get('Advanced'));
        foreach($toolbarOptions as $text=>$value)
            $html .= '<option value="'.$text.'"'.( ($this->getDbField('toolbar')===$text)?' selected="selected"':'').'>'.$value.'</option>';
        $html .= '</select>';
        $html .= '<div class="uk-form-help-block">'.$Language->get('Advanced is the full package of CKEditor').'</div>';
		$html .= '</div>';	
*/	

		$html .= '<div>';
		$html .= '<label for="jsakey">'.$Language->get('Filemanager Access Key').'</label>';
	    $html .= '<div class="uk-form-icon">';
		$html .= '<i class="uk-icon-key"></i>';
		$html .= '<input class="uk-form-width-large" name="akey" id="jsakey" type="text" value="'.$this->getDbField('akey').'">';
		$html .= '</div>';
		$html .= '<div class="uk-form-help-block">'.$Language->get('Generate key (refresh for new):'). ' <b>'.pluginCKeditor::randomString().'</b></div>';
		$html .= '</div>';
		
/*
		$html .= '<div class="uk-form-select" data-uk-form-select>
    <span></span>';	
		$html .= '<label for="skin">'.$Language->get('Select skin').'</label>';
        $html .= '<select name="skin">';
        $skinOptions = array('kama'=>'Kama','flat'=>'Flat','moono'=>'Moono','minimalist'=>'Minimalist','icy_orange'=>'Icy Orange','moono-dark'=>'Moono Dark','bludit'=>'Bludit');
        foreach($skinOptions as $text=>$value)
            $html .= '<option value="'.$text.'"'.( ($this->getDbField('skin')===$text)?' selected="selected"':'').'>'.$value.'</option>';
        $html .= '</select>';
		$html .= '</div>';
*/	
				
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
