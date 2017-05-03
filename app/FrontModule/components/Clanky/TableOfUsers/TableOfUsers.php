<?php
namespace App\FrontModule\Components\Clanky\TableOfUsers;

use Nette\Application\UI;
use Nette\Security\User;
use DbTable;

/**
 * Komponenta pre zobrazenie aktívnych uzivatelov pre FRONT modul
 * Posledna zmena(last change): 03.05.2017
 *
 * @author Ing. Peter VOJTECH ml <petak23@gmail.com>
 * @copyright Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link http://petak23.echo-msz.eu
 * @version 1.0.3
 *
 */
class TableOfUsersControl extends UI\Control {

  /** @var DbTable\Dokumenty */
  private $dokumenty;
  /** @var DbTable\User_profiles */
  private $user_profiles;
  /** @var DbTable\User_team */
  private $user_team;
  /** @var Nette\Security\User */
  private $user;
  /** @var array - texty pre sablonu v inom jazyku */
  private $texty = [
    "not_found"   =>"Položka sa žiaľ nenašla! msg: %s",
    "h3" 					=>"Tabuľka súťažiacich",
    "nie_je"      =>"Zatiaľ nie je žiaden súťažiaci.", 
  ];

  /**
   * @param DbTable\Dokumenty $dokumenty */
  public function __construct(DbTable\Dokumenty $dokumenty, DbTable\User_profiles $user_profiles, DbTable\User_team $user_team, User $user) {
    parent::__construct();
    $this->dokumenty = $dokumenty;
    $this->user_profiles = $user_profiles;
    $this->user_team = $user_team;
    $this->user = $user;
  }
	
	/** Nastavenie textov pre sablonu v inom jazyku
   * @param array $text texty 
   * @return \App\FrontModule\Components\Clanky\PrilohyClanokControl */
  public function setTexts($text) {
    $this->texty = array_merge($this->texty, $text);
    return $this;
  }
  
  /** 
   * Render funkcia pre vypisanie odkazu na clanok 
   * @see Nette\Application\Control#render() */
  public function render() { 
    $this->template->setFile(__DIR__ . "/TableOfUsers.latte");
    $prispevky = $this->dokumenty->prispevky();
    $tou = [];
    if ($prispevky !== FALSE && count($prispevky)) {
      foreach ($prispevky as $p) {
        $tou[] = ['id'    => ($this->user->isLoggedIn() && $this->user->getIdentity()->id_registracia > 3) ? $p->id_user_profiles : 0,
                  'meno'  => $p->meno . " " . $p->priezvisko,
                  'tim'   => ($p->id_user_team !== NULL) ? $this->user_team->teamName($p->id_user_team) : "-",
                  'pocet' => $p->pocet,
               ];
      }
    }
    $this->template->tou = $tou;
    $this->template->sutaziacich = $this->user_profiles->findBy(["id_registracia"=>1])->count();
    $this->template->prispevkov = $this->dokumenty->findBy(["id_dokumenty_kategoria"=>1])->count();
    $this->template->texty = $this->texty;
    $this->template->render();
  }
}

interface ITableOfUsersControl {
  /** @return TableOfUsersControl */
  function create();
}