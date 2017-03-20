<?php
namespace App\FrontModule\Presenters;

//use Nette\Application\UI\Form;
//use Nette\Mail\Message;
//use Nette\Mail\SendmailMailer;
//use Latte;
use DbTable, Language_support;

/**
 * Prezenter pre vypísanie profilu a správu príloh.
 * (c) Ing. Peter VOJTECH ml.
 * Posledna zmena(last change): 20.03.2017
 *
 *	Modul: FRONT
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.0.0
 *
 */
class MyPresenter extends \App\FrontModule\Presenters\BasePresenter {

  /** 
   * @inject
   * @var DbTable\Users */
	public $users;
  /**
   * @inject
   * @var Language_support\My */
  public $texty_presentera;
  
  /** @var \Nette\Database\Table\ActiveRow|FALSE */
  private $clen;

  /** @var array Nastavenie zobrazovania volitelnych poloziek */
  private $user_view_fields;
  
  /** @var Forms\My\EditFotoFormFactory @inject*/
	public $editFotoFormFactory;

	protected function startup() {
    parent::startup();
    // Kontrola ACL
    if (!$this->user->isAllowed($this->name, $this->action)) {
      $this->flashRedirect('Homepage:', sprintf($this->trLang('base_nie_je_opravnenie'), $this->action), 'danger');
    }
    //Najdem aktualne prihlaseneho clena
    $this->clen = $this->user_profiles->findOneBy(['id_users'=>$this->user->getIdentity()->getId()]);
    $this->user_view_fields = $this->nastavenie['user_view_fields'];
	}
  
  public function actionDefault() {
//    $clen = $this->clen;
//    $this["userEditForm"]->setDefaults($clen);
//    $this["userEditForm"]->setDefaults([ //Nastav vychodzie hodnoty
//      'username'    => $clen->users->username,
//      'email'       => $clen->users->email,
//      'registracia' => $clen->id_registracia." - ".$clen->registracia->nazov." (".$clen->registracia->role.")",
//      'prihlasenie' => ($clen->prihlas_teraz !== NULL ? $clen->prihlas_teraz->format('d.m.Y H:i:s')." - " : '').($clen->prihlas_predtym !== NULL ? $clen->prihlas_predtym->format('d.m.Y H:i:s') : ''),
//      'created'     => $clen->created->format('d.m.Y H:i:s'),
//      'modified'    => $clen->modified->format('d.m.Y H:i:s'), 
//    ]);
  }
  
  public function renderDefault() {
    $this->template->clen = $this->clen;
    $this->template->h2 = $this->trLang('h2');
    $this->template->texty = $this->texty_presentera;
    $this->template->foto = $this->dokumenty->findBy(["id_user_profiles"=>  $this->clen->id, "id_hlavne_menu"=>  $this->udaje_webu["hl_udaje"]["id"]]);
//    dump( $this->udaje_webu["hl_udaje"]);die();
//    $this->template->pass_change = $this->trLang('default_pass_change');
//    $this->template->zdroj_na_zmazanie = $this->trLang('zdroj_na_zmazanie');
//    $this->template->default_delete = $this->trLang('default_delete');
//    $this->template->default_email_change = $this->trLang('default_email_change');
//    $this->template->user_view_fields = $this->user_view_fields;
  }
  
  public function actionAdd($id = 0) {
    $foto = $this->dokumenty->find($id);
    $this["editFotoForm"]->setDefaults();
    $this->setView("edit");
  }
  
  

  /**
	 * Edit user form component factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentEditFotoForm() {
    $form = $this->editFotoFormFactory->create($this->context->parameters['wwwDir']);  
    $form['uloz']->onClick[] = function ($form) {
      $this->flashOut(!count($form->errors), 'My:', $this->trLang('default_save_ok'), $this->trLang('default_save_err'));
		};
    $form['cancel']->onClick[] = function () {
			$this->redirect('My:');
		};
		return $this->_vzhladForm($form);
	} 
}