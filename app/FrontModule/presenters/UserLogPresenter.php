<?php
namespace App\FrontModule\Presenters;

use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Latte;
use DbTable, Language_support;

/**
 * Prezenter pre spravu uzivatela po prihlásení.
 * (c) Ing. Peter VOJTECH ml.
 * Posledna zmena(last change): 10.04.2017
 *
 *	Modul: FRONT
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.1.3
 *
 */
class UserLogPresenter extends \App\FrontModule\Presenters\BasePresenter {
  /** Nazov prvku na zmazanie
    * @var string */
  public $zdroj_na_zmazanie = 'člena';
  
  /** 
   * @inject
   * @var DbTable\Users */
	public $users;
  /**
   * @inject
   * @var Language_support\UserLog
   */
  public $texty_presentera;
  
  /** @var \Nette\Database\Table\ActiveRow|FALSE */
  private $clen;
  /** @var mix */
  private $hasser;
  /** @var array Nastavenie zobrazovania volitelnych poloziek */
  private $user_view_fields;
  
  /** @var Forms\UserLog\UserEditFormFactory @inject*/
	public $userEditFormFactory;

	protected function startup() {
    parent::startup();
    if ($this->action != 'activateNewEmail' || $this->action != 'newsUnsubscribe') {
      if (!$this->user->isLoggedIn()) { //Neprihlaseneho presmeruj
        $this->flashRedirect(['User:', ['backlink'=>$this->storeRequest()]], $this->trLang('base_nie_je_opravnenie1').'<br/>'.$this->trLang('base_prihlaste_sa'), 'danger,n');
      }
    }
    // Kontrola ACL
    if (!$this->user->isAllowed($this->name, $this->action)) {
      $this->flashRedirect('Homepage:', sprintf($this->trLang('base_nie_je_opravnenie'), $this->action), 'danger');
    }
    //Najdem aktualne prihlaseneho clena
    $this->clen = $this->user_profiles->findOneBy(['id_users'=>$this->user->getIdentity()->getId()]);
    $this->hasser = $this->user->getAuthenticator(); //Ziskanie objektu pre vytvaranie hash hesla a iných
    $this->hasser->PasswordHash(8,FALSE);            //Nastavenie
    $this->user_view_fields = $this->nastavenie['user_view_fields'];
	}
  
  public function actionDefault() {
    $clen = $this->clen;
    $this["userEditForm"]->setDefaults($clen);
    $this["userEditForm"]->setDefaults([ //Nastav vychodzie hodnoty
      'username'    => $clen->users->username,
      'email'       => $clen->users->email,
      'registracia' => $clen->id_registracia." - ".$clen->registracia->nazov." (".$clen->registracia->role.")",
      'prihlasenie' => ($clen->prihlas_teraz !== NULL ? $clen->prihlas_teraz->format('d.m.Y H:i:s')." - " : '').($clen->prihlas_predtym !== NULL ? $clen->prihlas_predtym->format('d.m.Y H:i:s') : ''),
      'created'     => $clen->created->format('d.m.Y H:i:s'),
      'modified'    => $clen->modified->format('d.m.Y H:i:s'), 
    ]);
    if ($clen->id_user_team != NULL) {
      $this["userEditForm"]->setDefaults([
        'team_nazov'=>$clen->user_team->nazov, 'team_pocet'=>$clen->user_team->pocet, 'team_clenovia'=>$clen->user_team->clenovia,
      ]);
    }
  }
  
  public function renderDefault() {
    $this->template->clen = $this->clen;
    $this->template->h2 = $this->trLang('h2');
    $this->template->pass_change = $this->trLang('default_pass_change');
    $this->template->zdroj_na_zmazanie = $this->trLang('zdroj_na_zmazanie');
    $this->template->default_delete = $this->trLang('default_delete');
    $this->template->default_email_change = $this->trLang('default_email_change');
    $this->template->user_view_fields = $this->user_view_fields;
  }

  /**
	 * Edit user form component factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentUserEditForm() {
    $form = $this->userEditFormFactory->create($this->user_view_fields, $this->template->basePath, $this->context->parameters['wwwDir'], $this->clen, $this->nazov_stranky);  
    $form['uloz']->onClick[] = function ($form) {
      $this->flashOut(!count($form->errors), 'UserLog:', $this->trLang('user_edit_save_ok'), $this->trLang('user_edit_save_err'));
		};
    $form['cancel']->onClick[] = function () {
			$this->redirect('UserLog:');
		};
		return $form; //$this->_vzhladForm($form);
	}
  
  public function actionMailChange() {
    $this->template->h2 = sprintf($this->trLang('mail_change_h2'),$this->clen->meno,$this->clen->priezvisko);
    $this->template->email = sprintf($this->trLang('mail_change_txt'),$this->clen->users->email);
	}

	/**
	 * Mail change component factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentMailChangeForm() {
		$form = new Form();
		$form->addProtection();
    $form->addHidden('id', $this->clen->id);
		$form->addPassword('heslo', $this->trLang('MailChangeForm_heslo'), 50, 80)
				 ->setRequired($this->trLang('MailChangeForm_heslo_sr'));
    $form->addText('email', $this->trLang('MailChangeForm_new_email'), 50, 80)
				 ->addRule(Form::EMAIL, $this->trLang('MailChangeForm_new_email_ar'))
				 ->setRequired($this->trLang('MailChangeForm_new_email_sr'));
    $form->addSubmit('uloz', $this->trLang('base_save'))
         ->setAttribute('class', 'btn btn-success')
         ->onClick[] = [$this, 'userMailChangeFormSubmitted'];
    $form->addSubmit('cancel', 'Cancel')
         ->setAttribute('class', 'btn btn-default')
         ->setValidationScope([])
         ->onClick[] = function () { $this->redirect('UserLog:');};
		return $this->_vzhladForm($form);
	}

	public function userMailChangeFormSubmitted($button) {
		$values = $button->getForm()->getValues(); 	//Nacitanie hodnot formulara
    $this->clen = $this->user_profiles->find($values->id); //Najdenie clena
		if (!$this->hasser->CheckPassword($values->heslo, $this->clen->users->password)) {
			$this->flashRedirect('this', $this->trLang('pass_incorect'), 'danger');
		}
    // Over, ci dany email uz existuje. Ak ano konaj.
    if ($this->users->testEmail($values->email)) {
      $this->flashMessage(sprintf($this->trLang('mail_change_email_duble'), $values->email), 'danger');
      return;
    }
    //Vygeneruj kluc pre zmenu e-mailu
    $email_key = $this->hasser->HashPassword($values->email.StrFTime("%Y-%m-%d %H:%M:%S", Time()));
    $clen = $this->user_profiles->find(1); //Najdenie odosielatela emailu
    $templ = new Latte\Engine;
    $params = [
      "site_name" => $this->nazov_stranky,
      "nadpis"    => sprintf($this->trLang('email_change_mail_nadpis'),$this->nazov_stranky),
      "email_change_mail_txt" => sprintf($this->trLang('email_change_mail_txt'),$this->nazov_stranky),
      "email_change_mail_txt1" => $this->trLang('email_change_mail_txt1'),
      "email_nefunkcny_odkaz" => $this->trLang('email_nefunkcny_odkaz'),
      "email_pozdrav" => $this->trLang('email_pozdrav'),
      "nazov"     => $this->trLang('mail_change'),
      "odkaz" 		=> 'http://'.$this->nazov_stranky.$this->link("UserLog:activateNewEmail", $this->clen->id, $email_key),
    ];
    $mail = new Message;
    $mail->setFrom($this->nazov_stranky.' <'.$clen->users->email.'>')
         ->addTo($values->email)
         ->setSubject($this->trLang('mail_change'))
         ->setHtmlBody($templ->renderToString(__DIR__ . '/templates/UserLog/email_change-html.latte', $params));
    try {
      $sendmail = new SendmailMailer;
      $sendmail->send($mail);
      $this->users->find($this->clen->id_users)->update(['new_email'=>$values->email,'new_email_key'=>$email_key]);
      $this->flashRedirect('UserLog:', $this->trLang('mail_change_send_ok'), 'success');
    } catch (Exception $e) {
      $this->flashMessage($this->trLang('mail_change_send_err').$e->getMessage(), 'danger');
    } 
	}

  public function actionActivateNewEmail($id, $new_email_key) {
    $users_data = $this->user_profiles->find($id);
    if ($new_email_key == $users_data->users->new_email_key){ //Aktivacia prebeha v poriadku
      try {
        $this->users->uloz(['email'=>$users_data->users->new_email,
                                 'new_email'=>NULL,
                                 'new_email_key'=>NULL], $users_data->id_users);
        $this->user_profiles->uloz(['modified' => StrFTime("%Y-%m-%d %H:%M:%S", Time())],$id);
        $pomocny = $this->user->isLoggedIn() ? '' : $this->trLang('activate_mail_login');
        $this->flashMessage($this->trLang('activate_mail_ok').$pomocny, 'success');
      } catch (Exception $e) {
        $this->flashMessage($this->trLang('activate_mail_err').$e->getMessage(), 'danger,n');
      }
    } else { $this->flashMessage($this->trLang('activate_mail_err1'), 'danger'); } 	//Neuspesna aktivacia
    $this->redirect('Homepage:');
  }

  public function actionPasswordChange() {
    $this->template->h2 = sprintf($this->trLang('pass_change_h2'),$this->clen->meno,$this->clen->priezvisko);
	}
  
  public function actionNewsUnsubscribe($id_user_profiles, $news_key) {
    $user_for_unsubscribe = $this->user_profiles->find($id_user_profiles);
    if ($user_for_unsubscribe !== FALSE && $user_for_unsubscribe->news_key == $news_key) {
      $user_for_unsubscribe->update(['news'=>"N", 'news_key'=>NULL]);
      $this->flashMessage(sprintf($this->trLang('unsubscribe_news_ok'), $user_for_unsubscribe->users->email), 'success');
    } else {
      $this->flashMessage($this->trLang('unsubscribe_news_err'), 'danger');
    }
    $this->redirect('Homepage:');
  }

	/**
	 * Password change form component factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentPasswordChangeForm() {
		$form = new Form();
		$form->addProtection();
    $form->addHidden('id', $this->clen->id);
		$form->addPassword('heslo', $this->trLang('PasswordChangeForm_heslo'), 50, 80)
				 ->setRequired($this->trLang('PasswordChangeForm_heslo_sr'));
    $form->addPassword('new_heslo', $this->trLang('PasswordChangeForm_new_heslo'), 50, 80)
         ->addRule(Form::MIN_LENGTH, $this->trLang('PasswordChangeForm_new_heslo_ar'), 3)
				 ->setRequired($this->trLang('PasswordChangeForm_new_heslo_sr'));
		$form->addPassword('new_heslo2', $this->trLang('PasswordChangeForm_new_heslo2'), 50, 80)
         ->addRule(Form::EQUAL, $this->trLang('PasswordChangeForm_new_heslo2_ar'), $form['new_heslo'])
				 ->setRequired($this->trLang('PasswordChangeForm_new_heslo2_sr'));
    $form->addSubmit('uloz', $this->trLang('base_save'))
         ->setAttribute('class', 'btn btn-success')
         ->onClick[] = [$this, 'userPasswordChangeFormSubmitted'];
    $form->addSubmit('cancel', 'Cancel')
         ->setAttribute('class', 'btn btn-default')
         ->setValidationScope([])
         ->onClick[] = function () { $this->redirect('UserLog:');};
		return $this->_vzhladForm($form);
	}

	public function userPasswordChangeFormSubmitted($button) {
		$values = $button->getForm()->getValues(); 	//Nacitanie hodnot formulara
		if ($values->new_heslo != $values->new_heslo2) {
			$this->flashRedirect('this', $this->trLang('PasswordChangeForm_new_heslo2_ar'), 'danger');
		}
    $this->clen = $this->user_profiles->find($values->id); //Najdenie clena
		if (!$this->hasser->CheckPassword($values->heslo, $this->clen->users->password)) {
			$this->flashRedirect('this', $this->trLang('pass_incorect'), 'danger');
		}
		//Vygeneruj kluc pre zmenu hesla
		$new_password = $this->hasser->HashPassword($values->new_heslo);
    $values->new_heslo = 'xxxxx'; //Len pre istotu
    $values->new_heslo2= 'xxxxx'; //Len pre istotu
    try {
      $this->users->find($this->clen->id_users)->update(['password'=>$new_password]);
      $this->user_profiles->uloz(['modified' => StrFTime("%Y-%m-%d %H:%M:%S", Time())],$values->id);
			$this->flashMessage($this->trLang('pass_change_ok'), 'success');
		} catch (Exception $e) {
			$this->flashMessage($this->trLang('pass_change_err').$e->getMessage(), 'danger,n');
		}
    $this->redirect('UserLog:');
	}
  
  /*********** signal processing ***********/
	function confirmedDelete($id, $nazov) {
    if (!$this->user_view_fields['delete']) {
      $this->flashRedirect("User:", $this->trLang('base_nie_je_opravnenie1'), "danger");
      return;
    }
		$path = $this->context->parameters['wwwDir'] . "/files/".$id;
    if (is_dir($path)) { //Vymazanie adresaru s avatarom
      foreach (glob("$path*.{jpg,jpeg,gif,png}", GLOB_BRACE) as $file) {
        @unlink($file);
      }
      rmdir($path);
    }
    $clen_id_up = $this->user_profiles->findOneBy(['id_users'=>$id])->id;
    try {
      $this->getUser()->logout();
      $this->user_profiles->delUser($clen_id_up);
      $this->user_profiles->oprav($clen_id_up, ['id_users'=>1]);
      $this->users->zmaz($id);
      $this->user_profiles->zmaz($clen_id_up);
      $this->flashMessage(sprintf($this->trLang('delete_user_ok'),$nazov), 'success');
      
		} catch (Exception $e) {
			$this->flashMessage($this->trLang('delete_user_err').$e->getMessage(), 'danger');
		}
    if (!$this->isAjax()) { $this->redirect('User:'); }
	}
  
  /** Spolocna obluha formularov pri stornovani formulara */
	public function formCancelled() {
		$this->redirect('UserLog:');
	}
}
