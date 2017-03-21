<?php
namespace App\FrontModule\Components\My\FotoPrilohy;

use Nette;
use DbTable;

/**
 * Komponenta pre spravu priloh clanku.
 * 
 * Posledna zmena(last change): 21.03.2017
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com> 
 * @copyright Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link http://petak23.echo-msz.eu
 * @version 1.0.0
 */

class FotoPrilohyControl extends Nette\Application\UI\Control {

  /** @var DbTable\Dokumenty $dokumenty */
  public $dokumenty;
  /** @var string $nazov_stranky */
  private $nazov_stranky;
  /** @var int $id_hlavne_menu */
  private $id_hlavne_menu;
  /** @var int */
  private $upload_size;
  /** @var string */
  private $prilohy_adresar;
  /** @var array */
  private $prilohy_images;
  /** &var EditFotoPrilohyFormFactory */
  public $editFotoPrilohyForm;
  /** @var int */
  private $id_registracia;


  /**
   * @param DbTable\Dokumenty $dokumenty
   * @param EditFotoPrilohyFormFactory $editFotoPrilohyFormFactory */
  public function __construct(DbTable\Dokumenty $dokumenty, EditFotoPrilohyFormFactory $editFotoPrilohyFormFactory, Nette\Security\User $user) {
    parent::__construct();
    $this->dokumenty = $dokumenty;
    $this->editFotoPrilohyForm = $editFotoPrilohyFormFactory;
    $this->id_registracia = $user->getIdentity()->id_registracia;
  }
  
  /** 
   * Nastavenie komponenty
   * @param int $id_hlavne_menu
   * @param string $nazov_stranky
   * @param int $upload_size
   * @param string $prilohy_adresar
   * @param array $prilohy_images Nastavenie obrazkov pre prilohy
   * @return \App\FrontModule\Components\My\FotoPrilohy\FotoPrilohyControl */
  public function setTitle($id_hlavne_menu, $nazov_stranky, $upload_size, $prilohy_adresar, $prilohy_images) {
    $this->id_hlavne_menu = $id_hlavne_menu;
    $this->nazov_stranky = $nazov_stranky;
    $this->upload_size = $upload_size;
    $this->prilohy_adresar = $prilohy_adresar;
    $this->prilohy_images = $prilohy_images;
    return $this;
  }
  
  /** Render */
	public function render() {
    $this->template->setFile(__DIR__ . '/FotoPrilohy.latte');
    $this->template->dokumenty = $this->dokumenty->findBy(['id_hlavne_menu'=>$this->id_hlavne_menu]);
		$this->template->render();
	}
  
  /** 
   * Komponenta formulara pre pridanie a editaciu prílohy polozky.
   * @return Nette\Application\UI\Form */
  public function createComponentEditFotoPrilohyForm() {
    $form = $this->editFotoPrilohyForm->create($this->upload_size, $this->prilohy_adresar, $this->prilohy_images);
    $form->setDefaults(["id"=>0, "id_hlavne_menu"=>$this->id_hlavne_menu, "id_registracia"=>$this->id_registracia]);
    $form['uloz']->onClick[] = function ($button) { 
      $this->presenter->flashOut(!count($button->getForm()->errors), 'this', 'Foto príloha bola úspešne uložená!', 'Došlo k chybe a zmena sa neuložila. Skúste neskôr znovu...');
		};
    return $this->presenter->_vzhladForm($form);
  }
  
  public function handleEditFotoPriloha($id) {
    $this->presenter->redirect('My:edit', $id);
  }
  
  public function handleShowInText($id) {
    $priloha = $this->dokumenty->find($id);
    $priloha->update(['zobraz_v_texte'=>(1 - $priloha->zobraz_v_texte)]);
		if (!$this->presenter->isAjax()) {
      $this->redirect('this');
    } else {
      $this->redrawControl('');
    } 
  }
}

interface IFotoPrilohyControl {
  /** @return FotoPrilohyControl */
  function create();
}