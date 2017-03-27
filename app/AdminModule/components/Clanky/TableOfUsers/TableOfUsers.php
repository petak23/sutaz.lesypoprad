<?php
namespace App\AdminModule\Components\Clanky\TableOfUsers;

use Nette;
use DbTable;

/**
 * Komponenta pre zobrazenie aktívnych uzivatelov pre ADMIN modul
 * Posledna zmena(last change): 27.03.2017
 *
 * @author Ing. Peter VOJTECH ml <petak23@gmail.com>
 * @copyright Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link http://petak23.echo-msz.eu
 * @version 1.0.0
 *
 */
class TableOfUsersControl extends Nette\Application\UI\Control {

  /** @var DbTable\Dokumenty */
  private $dokumenty;
  /** @var DbTable\User_profiles */
  private $user_profiles;
  /** @var Nette\Security\User */
//  private $user;

  /**
   * @param DbTable\Dokumenty $dokumenty */
  public function __construct(DbTable\Dokumenty $dokumenty, DbTable\User_profiles $user_profiles/*, Nette\Security\User $user*/) {
    parent::__construct();
    $this->dokumenty = $dokumenty;
    $this->user_profiles = $user_profiles;
//    $this->user = $user;
  }
  
  /** Render funkcia pre vypisanie odkazu na clanok 
   * @see Nette\Application\Control#render()
   */
  public function render() { 
    $this->template->setFile(__DIR__ . "/TableOfUsers.latte");
    $this->template->user_profiles = $this->user_profiles->findBy(["id_registracia <= 3"]);
    $this->template->dokumenty = $this->dokumenty->findAll();
    $this->template->render();
  }
}

interface ITableOfUsersControl {
  /** @return TableOfUsersControl */
  function create();
}