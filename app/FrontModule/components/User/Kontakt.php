<?php
namespace App\FrontModule\Components\User;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Security\User;
use Latte;
use DbTable;
use Language_support;

/**
 * Komponenta pre vytvorenie kontaktneho formulara a odoslanie e-mailu
 * 
 * Posledna zmena(last change): 06.04.2017
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.0.4
 */

class KontaktControl extends Control {

  /** @var string */
  private $emails = "";
  /** @var int */
  private $textA_rows = 8;
  /** @var int */
  private $textA_cols = 60;
  /** @var string */
  private $nazov_stranky = "";
    /** @var User */
  protected $user;
  /** @var Language_support\User Prednastavene texty pre prihlasovaci form */
	public $texty;
  
  /**
   * @param User $user
   * @param Language_support\User $lang_supp */
  public function __construct(User $user, Language_support\User $lang_supp) {
    parent::__construct();
    $this->user = $user;
    $this->texty = $lang_supp;
  }
  
  /** 
   * Vratenie textu pre dany kluc a jazyk
   * @param string $key - kluc daneho textu
   * @return string - hodnota pre dany text */
  private function trLang($key) {
    return $this->texty->trText($key);
  }

  /** Funkcia pre nastavenie textov formulara a sablony.
   * @param int $rows - pocet riadkov textarea
   * @param int $cols - pocet stlpcov textarea */
  public function setSablona($rows = NULL, $cols = NULL) {
    if (isset($rows)) { $this->textA_rows = $rows; }
    if (isset($cols)) { $this->textA_cols = $cols; }
  }

  /** Funkcia pre nastavenie emailovych adries, na ktore sa odosle formular
	 * @param  string $emails - pole s emailovymi adresami
	 */
  public function setSpravca($emails) {
    if (isset($emails)) {
      $this->emails = $emails;
    }
  }
  
  /** Funkcia pre nastavenie nazvu stranky
	 * @param  string $nazov_stranky
	 */
  public function setNazovStranky($nazov_stranky) {
    $this->nazov_stranky = $nazov_stranky;
  }

  /**
   * @see Nette\Application\Control#render()
   */
  public function render() {
    $this->template->setFile(__DIR__ . '/Kontakt.latte');
		$this->template->h4 = $this->trLang('komponent_kontakt_h4');
		$this->template->uvod = $this->trLang('komponent_kontakt_uvod');
    $this->template->render();
  }

  /** Potvrdenie ucasti form component factory.
   * @return Nette\Application\UI\Form
   */
  protected function createComponentKontaktForm() {
      $form = new Form;
      $form->addProtection();
      $form->addText('meno', $this->trLang('komponent_kontakt_meno'), 30, 50);
      $form->addText('email', $this->trLang('komponent_kontakt_email'), 30, 50)
         ->setType('email')
				 ->addRule(Form::EMAIL, $this->trLang('komponent_kontakt_email_ar'))
				 ->setRequired($this->trLang('komponent_kontakt_email_sr'));
      $form->addTextArea('text', $this->trLang('komponent_kontakt_text'))
           ->setAttribute('rows', $this->textA_rows)
           ->setAttribute('cols', $this->textA_cols)
           ->setRequired($this->trLang('komponent_kontakt_text_sr'));
      $renderer = $form->getRenderer();
      $renderer->wrappers['error']['container'] = 'div class="row"';
      $renderer->wrappers['error']['item'] = 'div class="col-md-6 col-md-offset-3 alert alert-danger"';
      $renderer->wrappers['controls']['container'] = NULL;
      $renderer->wrappers['pair']['container'] = 'div class="form-group row"';
      $renderer->wrappers['pair']['.error'] = 'has-error';
      $renderer->wrappers['control']['container'] = 'div class="col-md-6"';
      $renderer->wrappers['label']['container'] = 'div class="col-lg-3 col-xs-4 col-form-label text-right"';
      $renderer->wrappers['control']['container'] = 'div class="req col-lg-4 col-md-6 col-xs-8"';
      $renderer->wrappers['control']['description'] = 'span class=help-block';
      $renderer->wrappers['control']['errorcontainer'] = 'div class="alert alert-danger"';
      $form->addSubmit('uloz', $this->trLang('komponent_kontakt_uloz'))
           ->setAttribute('class', 'btn btn-success');
      $form->onSuccess[] = [$this, 'onZapisDotaz'];
//      dump($this->user->getIdentity()->data);die();
      if ($this->user->isLoggedIn()) {
        $form['meno']->setDisabled()->setDefaultValue($this->user->getIdentity()->data['meno']." ".$this->user->getIdentity()->data['priezvisko']);
        $form['email']->setDisabled()->setDefaultValue($this->user->getIdentity()->data['email']);
      }
      
      return $form;
  }

  /** Spracovanie formulara
   * @param \Nette\Application\UI\Form $form
   */
  public function onZapisDotaz(Form $form) {
    $values = $form->getValues(); 				//Nacitanie hodnot formulara
    $templ = new Latte\Engine;
    $params = array(
      "nadpis"      => sprintf('Správa z kontaktného formulará stránky %s', $this->nazov_stranky),
      "dotaz_meno"  => sprintf('Užívateľ s menom %s poslal nasledujúcu správu:', $values->meno),
      "dotaz_text"  => $values->text,
    );
    $mail = new Message;
    $mail->setFrom($values->meno.' <'.$values->email.'>')
         ->addTo($this->emails)
         ->setSubject(sprintf('Správa z kontaktného formulará stránky %s', $this->nazov_stranky))
         ->setHtmlBody($templ->renderToString(__DIR__ . '/Kontakt_email-html.latte', $params));
    try {
      $sendmail = new SendmailMailer;
      $sendmail->send($mail);
      $this->presenter->flashMessage($this->trLang('komponent_kontakt_send_ok'), 'success');
    } catch (Exception $e) {
      $this->presenter->flashMessage($this->trLang('komponent_kontakt_send_er').$e->getMessage(), 'danger,n');
    }
    $this->redirect('this');
  }
}

interface IKontaktControl {
  /** @return KontaktControl */
  function create();
}