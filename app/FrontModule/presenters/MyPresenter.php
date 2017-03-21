<?php
namespace App\FrontModule\Presenters;

//use Nette;
use DbTable, Language_support;

/**
 * Prezenter pre vypísanie profilu a správu príloh.
 * (c) Ing. Peter VOJTECH ml.
 * Posledna zmena(last change): 21.03.2017
 *
 *	Modul: FRONT
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.0.1
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
  /** @var \Nette\Database\Table\ActiveRow */
  private $dokument;
  
  /** @var \App\FrontModule\Components\My\FotoPrilohy\IFotoPrilohyControl @inject */
  public $fotoPrilohyControlFactory;
  /** @var string */
  private $h2;

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
  }
  
  public function renderDefault() {
    $this->template->clen = $this->clen;
    $this->template->h2 = $this->trLang('h2');
    $this->template->texty = $this->texty_presentera;
    $this->template->foto = $this->dokumenty->findBy(["id_user_profiles"=>  $this->clen->id, "id_hlavne_menu"=>  $this->udaje_webu["hl_udaje"]["id"]]);
  }
  
  public function actionAdd() {
    $this->h2 = "Pridanie fotky";
    $this["fotoEditForm"]->setDefaults(["id"=>0, "id_hlavne_menu"=>$this->udaje_webu["hl_udaje"]["id"], "id_registracia"=>$this->id_reg]);
    $this->setView("edit");
    
  }
  
  /** Akcia pre editaciu informacii o dokumente
   * @param int $id Id dokumentu na editaciu
   */
  public function actionEdit($id) {
		if (($this->dokument = $this->dokumenty->find($id)) === FALSE) { 
      return $this->error(sprintf("Pre zadané id som nenašiel prílohu! id=' %s'!", $id)); 
    }
    $this->h2 =  'Editácia údajov fotky:'.$this->dokument->nazov;
    $this["fotoEditForm"]->setDefaults($this->dokument);
  }
  
  /** Render pre editaciu prilohy. */
	public function renderEdit() {
		$this->template->h2 = $this->h2;
	}
  
  /** Formular pre editaciu info. o dokumente.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentFotoEditForm() {
    $ft = new \App\FrontModule\Components\My\FotoPrilohy\EditFotoPrilohyFormFactory($this->dokumenty, $this->user, $this->nastavenie['wwwDir']);
    $form = $ft->create($this->upload_size, "www/files/myfoto/", $this->nastavenie['prilohy_images']);
//    $form->setDefaults($this->dokument);
    $form['uloz']->onClick[] = function ($button) {
      $this->flashOut(!count($button->getForm()->errors), 'My:', 'Foto príloha bola úspešne uložená!', 'Došlo k chybe a zmena sa neuložila. Skúste neskôr znovu...');
		};
    $form['cancel']->onClick[] = function () {
			$this->redirect('My:');
		};
		return $this->_vzhladForm($form);
	}
  
  /**
   * Komponenta pre pridanie príloh
   * @return \App\FrontModule\Components\My\FotoPrilohy\IFotoPrilohyControl */
	public function createComponentFotoPrilohy() {
    $prilohyClanok = $this->fotoPrilohyControlFactory->create(); 
    $prilohyClanok->setTitle($this->udaje_webu["hl_udaje"]["id"], $this->nazov_stranky, $this->upload_size, "www/files/myfoto/", $this->nastavenie['prilohy_images']);
    return $prilohyClanok;
  }

  /*********** signal processing ***********/
	function confirmedDelete($id, $nazov) {
    $pr = $this->dokumenty->find($id);//najdenie prislusnej polozky menu, ku ktorej priloha patri
    if ($pr !== FALSE) {
      $vysledok = $this->vymazSubor($pr->subor) ? (in_array(strtolower($pr->pripona), ['png', 'gif', 'jpg']) ? $this->vymazSubor($pr->thumb) : TRUE) : FALSE;
      $this->_ifMessage($vysledok ? $pr->delete() : FALSE, 'Foto príloha bola vymazaná!', 'Došlo k chybe a foto príloha nebola vymazaná!');   
    } else { $this->flashRedirect("My:", 'Došlo k chybe a foto príloha nebola vymazaná lebo sa nenašla v DB!', 'danger');}
	}
  
  /** 
   * Funkcia vymaze subor ak exzistuje
	 * @param string $subor Nazov suboru aj srelativnou cestou
	 * @return int Ak zmaze alebo neexistuje(nie je co mazat) tak 1 inak 0 */
	public function vymazSubor($subor) {
		return (is_file($subor)) ? unlink($this->context->parameters["wwwDir"]."/".$subor) : -1;
	}
  
  /** 
   * Vypis spravy podla podmienky 
   * @param boolean $if
   * @param string $dobre
   * @param string $zle */
  public function _ifMessage($if, $dobre, $zle) {
    if ($if) { $this->flashMessage($dobre, 'success'); }
    else { $this->flashMessage($zle, 'danger'); }
  }
}