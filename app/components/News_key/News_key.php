<?php
namespace PeterVojtech\News_key;
use Nette\Application\UI;
use Nette\Security\User;
use DbTable;

/**
 * Komponenta pre pracu s klucom pre novinky v odkaze
 * Posledna zmena(last change): 06.03.2017
 * 
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com> 
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.0.0
 */

class NewsKeyControl extends UI\Control {
  
  /** @var DbTable\User_profiles */
  public $user_profiles;
  /** @var Nette\Security\User */
  public $user;
  /** @var mix */
  private $hasser;

  /**
   * @param DbTable\User_profiles $user_profiles
   * @param User $user */
  public function __construct(DbTable\User_profiles $user_profiles, User $user) {
    parent::__construct();
    $this->user_profiles = $user_profiles;
    $this->user = $user;
    $this->hasser = $this->user->getAuthenticator(); //Ziskanie objektu pre vytvaranie hash hesla a iných
    $this->hasser->PasswordHash(8,FALSE);            //Nastavenie
  }
  
  /**
   * Generuje news_key a uloží k danému uzivatelovi
   * @param int $id_user_profiles
   * @return string|boolean */
  public function Generate($id_user_profiles) {
    $u = $this->user_profiles->find($id_user_profiles);
    $email = $u->users->email;
    if ($email != '---' && $u->news == "A") {
      $new_news_key = $this->hasser->HashPassword($email."news=>A");
      $u->update(['news_key'=>$new_news_key]);
    } else {
      $new_news_key = FALSE;
    }
    return $new_news_key;
  }
  
  /**
   * Vymaze news_key konkretneho uzivatela
   * @param int $id_user_profiles
   * @return int|FALSE */
  public function Delete($id_user_profiles) {
    return $this->user_profiles->find($id_user_profiles)->update(['news_key'=>NULL]);
  }
  
  /**
   * Rozhodne co urobit
   * @param boolean $podmienka
   * @param int $id_user_profiles */
  public function Spracuj($podmienka, $id_user_profiles) {
    if ($podmienka) {
      $this->Generate($id_user_profiles);
    } else {
      $this->Delete($id_user_profiles);
    }
  }
}