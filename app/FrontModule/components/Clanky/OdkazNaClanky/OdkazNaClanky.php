<?php
namespace App\FrontModule\Components\Clanky\OdkazNaClanky;
use Nette;
use DbTable;

/**
 * Komponenta pre zobrazenie odkazu na iny clanok
 * Posledna zmena(last change): 17.05.2016
 * 
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com> 
 * @copyright Copyright (c) 2012 - 2016 Ing. Peter VOJTECH ml.
 * @license
 * @link http://petak23.echo-msz.eu
 * @version 1.0.4
 */
class OdkazNaClankyControl extends Nette\Application\UI\Control {
  
  /** @var int Id aktualneho jazyka  */
  private $language_id = "Front";
  /** @var array Texty pre sablonu */
  private $texty = [
    "to_foto_galery"  => "Odkaz na fotogalériu:",
    "to_article"      => "Odkaz na článok:",
    "neplatny"        => "Článok už nie je platný!",
    "platil_do"       => "Platil do: ",
    "platny_do"       => "Článok platí do: ",
    "not_found"       => "Article not found! msg: %s",
  ];
  /** @var DbTable\Hlavne_menu_lang */
	public $hlavne_menu_lang;
  
  /** @param DbTable\Hlavne_menu_lang $hlavne_menu_lang */   
  public function __construct(DbTable\Hlavne_menu_lang $hlavne_menu_lang) {
    parent::__construct();
    $this->hlavne_menu_lang = $hlavne_menu_lang;
  }

  /** 
   * Nastavenie textov pre sablonu pre ine jazyky
   * @param array $texts
   * @return \App\FrontModule\Components\Clanky\OdkazNaClanky\OdkazNaClankyControl */
  public function setTexts($texts) {
    if (is_array($texts)) { $this->texty = array_merge($this->texty, $texts);}
    return $this;
  }

  /** 
   * Nastavenie id aktualneho jazyka
   * @param int $id 
   * @return \App\FrontModule\Components\Clanky\OdkazNaClanky\OdkazNaClankyControl */
  public function setLanguage($id = 1) {
    $this->language_id = $id;
    return $this;
  }

  /** 
   * Render funkcia pre vypisanie odkazu na clanok 
   * @param array $p Parametre: id_hlavne_menu - id odkazovaneho clanku, template - pouzita sablona
   * @see Nette\Application\Control#render() */
  public function render($p = []) {
    if (isset($p["id_hlavne_menu"]) && (int)$p["id_hlavne_menu"]) { //Mam id_clanok
      $pom_hlm = $this->hlavne_menu_lang->findOneBy(["id_lang"=>$this->language_id, "id_hlavne_menu"=>$p["id_hlavne_menu"]]);
      if ($pom_hlm === FALSE) { $chyba = "hlavne_menu_lang = ".$p["id_hlavne_menu"]; }
    } else { //Nemam id_clanok
      $chyba = "id_hlavne_menu";
    }
    if (isset($chyba)) { //Je nejaka chyba
      $this->template->setFile(__DIR__ . '/OdkazNaClanky_error.latte');
      $this->template->text = sprintf($this->texty['not_found'], $chyba);
    } else { //Vsetko je OK
			$p_hlm = $pom_hlm->hlavne_menu; //Pre skratenie zapisu
      $this->template->setFile(__DIR__ . "/OdkazNaClanky".(isset($p["template"]) && strlen($p["template"]) ? "_".$p["template"] : "default").".latte");
      $this->template->nazov = $pom_hlm->nazov;
      $this->template->datum_platnosti = $p_hlm->datum_platnosti;
      $this->template->avatar = $p_hlm->avatar;
      $this->template->anotacia = isset($pom_hlm->id_clanok_lang) ? $pom_hlm->clanok_lang->anotacia : NULL;
      $this->template->texty = $this->texty;
			$this->template->link_presenter = $p_hlm->druh->presenter == "Menu" ? "Clanky:" : $p_hlm->druh->presenter.":";
      $this->template->id_hlavne_menu = $p["id_hlavne_menu"];
      $this->template->absolutna = $p_hlm->absolutna;
    }
    $this->template->render();
  }
}

interface IOdkazNaClankyControl {
  /** @return OdkazNaClankyControl */
  function create();
}