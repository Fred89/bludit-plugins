<?php
/**
 *  Contact
 *
 *  @package Bludit
 *  @subpackage Plugins
 *  @author Frédéric K.
 *  @copyright 2015-2018 Frédéric K.
 *	@version 2.5
 *  @release 2015-08-10
 *  @update 2018-05-02
 *
 */
class pluginContact extends Plugin {
		
	# DONNÉES DU PLUG-IN.
	public function init()
	{
		$this->dbFields = array(
			'email'	=> '',		// <= Your contact email
			'page'	=> '',		// <= Slug url of contact page
			'type'	=> 'text',	// <= True = HTML or False for text mail format
			'smtphost' => '',
			'smtpport' => '',
			'username' => '',
			'password' => '',
			'fromaddress' => '',
			'fromname' => '',
			'subject' => ''
			);
	}
	# ADMINISTRATION DU PLUG-IN.
	public function form() 
	{
		global $Language,$L,$dbPages;
		#$pageOptions = $dbPages->getStaticDB();
		#$postOptions = $dbPages->getPublishedDB();		
		#$options = array_merge( $postOptions, $pageOptions );
		
		// Liste des pages ou afficher le formulaire
		// On récupère les pages statiques	
		$pages = $dbPages->getStaticDB();
		// Récupération de la valeur clé des pages				
		foreach($pages as $pageKey) {
			// Création de l'objet page
			$page = buildPage($pageKey);
			// Récupération du titre de la page
			$pageOptions[$pageKey] = $page->title();
			// On tri le tableau
			ksort($pageOptions);
		}	
			
		// Liste des posts ou afficher le formulaire
		// On récupère les posts publiés	
		$posts = $dbPages->getPublishedDB();
		// Récupération de la valeur clé des pages				
		foreach($posts as $postKey) {
			// Création de l'objet page
			$post = buildPage($postKey);
			// Récupération du titre de l'article
			$postOptions[$postKey] = $post->title();
			// On tri le tableau
			ksort($postOptions);
		}
		
		// On merge le tableau
		$options = array_merge( $postOptions, $pageOptions );	
		
		// Email
		HTML::formInputText(array(
			'name'			=> 'email',
			'label'			=> $Language->get('Email'),
			'type'			=> 'email',
			'value'			=> $this->getDbField('email'),
			'class'			=> 'uk-width-1-2 uk-form-large',
			'placeholder'	=> '',
			'tip'			=> '',
			'disabled'		=> false
		));	

		// Content to display form
		HTML::formSelect(array(
			'name'			=> 'page',
			'label'			=> $Language->get('Select a content'),
			'class'			=> 'uk-width-1-3 uk-form-large',
			'options'		=> $options,
			'selected'		=> $this->getValue('page'),
			'tip'			=> '',
			'addEmptySpace'	=> false,
			'disabled' 		=> false
		));	

		// Mail Content type
		HTML::formSelect(array(
			'name'			=> 'type',
			'label'			=> $Language->get('Content type'),
			'class'			=> 'uk-width-1-3 uk-form-large',
			'options'		=> array( 'html'=>$Language->get('HTML'),'text'=>$Language->get('TEXT') ),
			'selected'		=> $this->getValue('type'),
			'tip'			=> '',
			'addEmptySpace'	=> false,
			'disabled' 		=> false
		));

		/**
		 * SMTP Settings
		 * Contribution by Dominik Sust
		 * Git: https://github.com/HarleyDavidson86/bludit-plugins/commit/eb395c73ea4800a00f4ec5e9c9baabc5b9db19e8 
		**/
		HTML::title(array('title'=>$Language->get('smtp-options'), 'icon'=>'fa fa-server '));	

		// Host
		HTML::formInputText(array(
			'name'			=> 'smtphost',
			'label'			=> $Language->get('smtp-host'),
			'type'			=> 'text',
			'value'			=> $this->getDbField('smtphost'),
			'class'			=> 'uk-width-1-2 uk-form-large',
			'placeholder'	=> '',
			'tip'			=> '',
			'disabled'		=> false
		));	
		
		// Port
		HTML::formInputText(array(
			'name'			=> 'smtpport',
			'label'			=> $Language->get('smtp-port'),
			'type'			=> 'text',
			'value'			=> $this->getDbField('smtpport'),
			'class'			=> 'uk-width-1-2 uk-form-large',
			'placeholder'	=> '',
			'tip'			=> '',
			'disabled'		=> false
		));	
		
		// Username
		HTML::formInputText(array(
			'name'			=> 'username',
			'label'			=> $Language->get('smtp-username'),
			'type'			=> 'text',
			'value'			=> $this->getDbField('username'),
			'class'			=> 'uk-width-1-2 uk-form-large',
			'placeholder'	=> '',
			'tip'			=> '',
			'disabled'		=> false
		));	
		
		// Password
		HTML::formInputText(array(
			'name'			=> 'password',
			'label'			=> $Language->get('smtp-password'),
			'type'			=> 'password',
			'value'			=> $this->getDbField('password'),
			'class'			=> 'uk-width-1-2 uk-form-large',
			'placeholder'	=> '',
			'tip'			=> '',
			'disabled'		=> false
		));	
				
		// Email
		HTML::formInputText(array(
			'name'			=> 'fromaddress',
			'label'			=> $Language->get('smtp-from-address'),
			'type'			=> 'email',
			'value'			=> $this->getDbField('fromaddress'),
			'class'			=> 'uk-width-1-2 uk-form-large',
			'placeholder'	=> '',
			'tip'			=> '',
			'disabled'		=> false
		));	

		// Name
		HTML::formInputText(array(
			'name'			=> 'fromname',
			'label'			=> $Language->get('smtp-from-name'),
			'type'			=> 'text',
			'value'			=> $this->getDbField('fromname'),
			'class'			=> 'uk-width-1-2 uk-form-large',
			'placeholder'	=> '',
			'tip'			=> '',
			'disabled'		=> false
		));	
		
		// Subject
		HTML::formInputText(array(
			'name'			=> 'subject',
			'label'			=> $Language->get('smtp-subject'),
			'type'			=> 'text',
			'value'			=> $this->getDbField('subject'),
			'class'			=> 'uk-width-1-2 uk-form-large',
			'placeholder'	=> '',
			'tip'			=> '',
			'disabled'		=> false
		));	
	}
    /**
     * AFFICHE LA FEUILLE DE STYLE ET LE JAVASCRIPT UNIQUEMENT SUR LA PAGE DEMANDÉE.
     *
     */			
	public function siteHead()
	{
		global $Page, $Url;
		$html = '';
		
		if ( !$Url->notFound() &&
		     ( $Url->whereAmI()=='page' && $Page->slug()===$this->getDbField('page') ) 
		   )
		{
			$pluginPath = $this->htmlPath();
			/** 
			 * ON INCLUT LA CSS PAR DÉFAUT DU PLUG-IN OU LA CSS PERSONNALISÉE STOCKER DANS NOTRE THÈME SI ELLE EXISTE.
			 */
		    $css = THEME_DIR_CSS . 'contact.css';
		    if(file_exists($css))
			    $html .= Theme::css('css' . DS . 'contact.css');
		    else
			    $html .= '<link rel="stylesheet" href="' .$pluginPath. 'layout' . DS . 'contact.css">' .PHP_EOL;	    				
		}
		return $html;
	}  
    /**
     * AJOUTE LE FORMULAIRE DE CONTACT APRÈS LE CONTENU DE LA PAGE.
     *
     */		
	public function pageEnd()
	{
		global $Page, $Url, $Site, $Language, $Security;
		$pluginPath = $this->htmlPath();
		# On charge le script uniquement sur la page en paramètre
		if ( !$Url->notFound() &&
		     ( $Url->whereAmI()=='page' && $Page->slug()===$this->getDbField('page') ) 
		   )
		{
		   $error = false;
		   $success = false;
		   
		   # $_POST
		   $name       		= isset($_POST['name']) ? $_POST['name'] : '';
		   $email      		= isset($_POST['email']) ? $_POST['email'] : '';
		   $message    		= isset($_POST['message']) ? $_POST['message'] : '';
		   $interested 		= isset($_POST['interested']) ? $_POST['interested'] : '';			            		           
		   $contentType 	= $this->getDbField('type'); // Type de mail (text/html)
		   $smtphost 		= $this->getDbField('smtphost'); 
		   $smtpport 		= $this->getDbField('smtpport'); 
		   $smtpusername 	= $this->getDbField('username'); 
		   $smtppassword 	= $this->getDbField('password'); 
		   $smtpfromaddress = $this->getDbField('fromaddress'); 
		   $smtpfromname 	= $this->getDbField('fromname'); 
		   $smtpsubject 	= $this->getDbField('subject');
		             
		    if(isset($_POST['submit'])){	

					// Renew the token. This token will be the same inside the session for multiple forms.
					$Security->generateTokenCSRF();
								   	            
		            # Paramètres
		            $site_title   = $Site->title();
		            $site_charset = CHARSET;
		            $site_email   = $this->getDbField('email');
		            
		            # Object du mail
		            $subject        = $Language->get('New contact from'). ' ' .$site_title;
		            # Contenu du mail.
		            $email_content  = $Language->get('Name'). ' ' .$name."\r\n";
		            $email_content .= $Language->get('Email'). ' ' .$email."\r\n";
		            $email_content .= $Language->get('Message')."\r\n".$message."\r\n";
		            
		            # Entêtes du mail
		            $email_headers  = "From: ".$name." <".$email.">\r\n";
		            $email_headers .= "Reply-To: ".$email."\r\n";
		            $email_headers .= 'MIME-Version: 1.0' ."\r\n";
		            # Content-Type
		            if($contentType==='html')
		               $email_headers .= 'Content-type: text/html; charset="' .$site_charset. '"' ."\r\n";
				    else
					   $email_headers .= 'Content-type: text/plain; charset="' .$site_charset. '"' ."\r\n";
		
				    $email_headers .= 'Content-transfer-encoding: 8bit' ."\r\n";
				    $email_headers .= 'Date: ' .date("D, j M Y G:i:s O")."\r\n"; // Sat, 7 Jun 2001 12:35:58 -0700
				
		            # On vérifie les champs qu'ils soient remplis
			        if(trim($name)==='')
				       $error = $Language->get('Please enter your name');			       	       	       
			        elseif(trim($email)==='')
				       $error = $Language->get('Please enter a valid email address');
			        elseif(trim($message)==='')
				       $error = $Language->get('Please enter the content of your message');
				    elseif($interested)
				       $error = $Language->get('Oh my god a Bot!');
				    if(!$error) {
						if (empty($smtphost)) {
					    # Si tout ok, on envoi notre mail
		                if(mail($site_email, $subject, $email_content, $email_headers)) { 
		                  # Retourne le message de confirmation d’envoi           
		                  $success = $Language->get('Thank you for having contacted me. I will reply you as soon as possible. ');				                
		                  # Redirection sur le formulaire
		                  # Redirect::page( '', $Page->slug() );	                  
		                } else {
		                  $error = $Language->get('Oops! An error occurred while sending your message, thank you to try again later. ');
		                }
						} else {
							#Sending via SMTP
							require __DIR__ . DS . 'phpmailer' . DS . 'PHPMailerAutoload.php';
							try {
							$mail = new PHPMailer;

							$mail->isSMTP();
							$mail->Host = $smtphost;
							$mail->Port = $smtpport;
							$mail->SMTPAuth = true;
							$mail->Username = $smtpusername;
							#Function is needed if Password contains special characters like &
							$mail->Password = html_entity_decode($smtppassword);
							$mail->isHTML(true);
							$mail->setFrom($smtpfromaddress, $smtpfromname);
							$mail->addAddress($site_email);
							$mail->Subject  = $smtpsubject;
							
							$mailtext  = '<b>'.$Language->get('Name').': </b>'.$name.'<br>';
							$mailtext .= '<b>'.$Language->get('Email').': </b>'.$email.'<br>';
							$mailtext .= '<b>'.$Language->get('Message').': </b>'.$message.'<br>';

							$mail->Body     = $mailtext;
							if(!$mail->send()) {
								$error = $Language->get('Oops! An error occurred while sending your message, thank you to try again later. ');
							} else {
								$success = $Language->get('Thank you for having contacted me. I will reply you as soon as possible. ');				                
							}
							} catch (phpmailerException $e) {
							  echo $e->errorMessage(); //Pretty error messages from PHPMailer
							} catch (Exception $e) {
							  echo $e->getMessage(); //Boring error messages from anything else!
							}
							
						}
		            }
		        # On retourne les erreurs    
		        if($error) echo '<div class="alert alert-danger">' .$error. '</div>' ."\r\n";
		        elseif($success) echo '<div class="alert alert-success">' .$success. '</div>' ."\r\n";
		    }	
						    							    
			/** 
			 * 
			 * ON INCLUT LE TEMPLATE PAR DÉFAUT DU PLUG-IN OU LE TEMPLATE PERSONNALISÉ STOCKER DANS NOTRE THÈME S'IL EXISTE.
			 */ 
		    $template = THEME_DIR_PHP . 'contact.php';
		    if(file_exists($template))
			    include($template);
		    else 
			    include(__DIR__ . DS . 'layout' . DS . 'contact.php');	    			
		    
		}
	}   

}