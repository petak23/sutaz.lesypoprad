<?php
namespace App\FrontModule\Components\My\FotoPrilohy;

use Nette;
use DbTable;

/**
 * Komponenta pre spravu priloh clanku.
 * 
 * Posledna zmena(last change): 22.03.2017
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com> 
 * @copyright Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link http://petak23.echo-msz.eu
 * @version 1.0.1
 */

class FotoPrilohyControl extends Nette\Application\UI\Control {

  /** @var DbTable\Dokumenty $dokumenty */
  public $dokumenty;
  /** @var string $nazov_stranky */
  private $nazov_stranky;
  /** @var array $udaje_webu */
  private $udaje_webu;
  /** @var int */
  private $upload_size;
  /** @var string */
  private $prilohy_adresar;
  /** @var array */
  private $prilohy_images;
  /** @var Nette\Security\User */
  private $user;


  /**
   * @param DbTable\Dokumenty $dokumenty
   * @param Nette\Security\User $user */
  public function __construct(DbTable\Dokumenty $dokumenty, Nette\Security\User $user) {
    parent::__construct();
    $this->dokumenty = $dokumenty;
    $this->user = $user;
  }
  
  /** 
   * Nastavenie komponenty
   * @param array $udaje_webu
   * @param string $nazov_stranky
   * @param int $upload_size
   * @param string $prilohy_adresar
   * @param array $prilohy_images Nastavenie obrazkov pre prilohy
   * @return \App\FrontModule\Components\My\FotoPrilohy\FotoPrilohyControl */
  public function setTitle($udaje_webu, $nazov_stranky, $upload_size, $prilohy_adresar, $prilohy_images) {
    $this->udaje_webu = $udaje_webu;
    $this->nazov_stranky = $nazov_stranky;
    $this->upload_size = $upload_size;
    $this->prilohy_adresar = $prilohy_adresar;
    $this->prilohy_images = $prilohy_images;
    return $this;
  }
  
  /** Render */
	public function render() {
    $this->template->setFile(__DIR__ . '/FotoPrilohy.latte');
    $this->template->dokumenty = $this->dokumenty->findBy(['id_hlavne_menu'=>$this->udaje_webu["hl_udaje"]["id"], "id_user_profiles" =>$this->user->getIdentity()->getId()]);
    $this->template->max_pocet_foto = $this->udaje_webu["max_pocet_foto"];
		$this->template->render();
	}
  
  /**
   * Signál pre editáciu foto-prilohy
   * @param int $id */
  public function handleEditFotoPriloha($id) {
    $this->presenter->redirect('My:edit', $id);
  }
}

interface IFotoPrilohyControl {
  /** @return FotoPrilohyControl */
  function create();
}