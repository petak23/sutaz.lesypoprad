<?php
namespace App\FrontModule\Presenters;

use \Nette\Application\UI\Multiplier;
use DbTable, Language_support;

/**
 * Prezenter pre spravu oznamov.
 * (c) Ing. Peter VOJTECH ml.
 * Posledna zmena(last change): 07.03.2017
 *
 *	Modul: FRONT
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.1.1
 *
 * Akcie: Default - zobrazenie vsetkych aktualnych oznamov a rozhodovanie 
 */
class OznamPresenter extends \App\FrontModule\Presenters\BasePresenter {
  /** 
   * @inject
   * @var DbTable\Oznam */
	public $oznam;
  /** 
   * @inject
   * @var DbTable\Oznam_ucast */
	public $oznam_ucast;
  /**
   * @inject
   * @var Language_support\Oznam */
  public $texty_presentera;

	/** @var \Nette\Database\Table\Selection */
	private $aktualne;
  
  // -- Komponenty
  /** @var \App\FrontModule\Components\Oznam\IPotvrdUcastControl @inject */
  public $potvrdUcastControlFactory;
  /** @var \App\FrontModule\Components\Oznam\IKomentarControl @inject */
  public $komentarControlControlFactory;

  /** Akcia pre nacitanie aktualnych oznamov */
	public function actionDefault() {
    //Z DB zisti ako budu oznamy usporiadane
    if (($pomocna = $this->udaje->getKluc("oznam_usporiadanie")) !== FALSE) {
      $oznamy_usporiadanie = (boolean)$pomocna->text;
    } else { $oznamy_usporiadanie = FALSE; }
    $this->aktualne = $this->oznam->aktualne($oznamy_usporiadanie, $this->id_reg);
    //Ak nie su oznamy najdi 1. clanok cez udaje a ak je tak presmeruj na neho
    if ($this->aktualne->count() == 0) {
      if (($id = $this->udaje->getUdajInt('oznam_presmerovanie')) > 0) {
        $this->flashRedirect(['Clanky:default', $id], $this->trLang('ziaden_aktualny'), 'info');
      } else {
        $this->setView("prazdne");
      }
    }
	}
  
  /** Render pre vypis aktualnych oznamov */
	public function renderDefault() {
    $this->template->plati_do = $this->trLang('plati_do');
    $this->template->zverejnene = $this->trLang('zverejnene');
    $this->template->zobrazeny = $this->trLang('zobrazeny');
    $this->template->h2 = $this->trLang('h2');
    $this->template->oznamy = $this->aktualne;
	}
  
  /** Render pre vypis infa o tom, ze nie su aktualne oznamy */
  public function renderPrazdne() {
    $this->template->h2 = $this->trLang('h2');
    $this->template->ziaden_aktualny = $this->trLang('ziaden_aktualny');
  }

  public function actionUcastFromEmail($id_user_profiles, $id_oznam, $id_volba_oznam, $oznam_kluc) {
    if (($oznam = $this->oznam->find($id_oznam)) !== FALSE
        && $oznam->potvrdenie == 1
        && $oznam->oznam_kluc == $oznam_kluc
        && $this->user_profiles->find($id_user_profiles) !== FALSE
         ) {
      $this->oznam_ucast->zapisUcast($id_user_profiles, $id_oznam, $id_volba_oznam);
      $this->flashMessage('Vaša účasť pre oznam "'.$oznam->nazov.'" je zapísaná.', 'success');
    } else {
      $this->flashMessage('Volba nebola zapísaná! Pravdepodobne je chybný odkaz', 'danger');
    }
    $this->redirect('Oznam:default');
  }
  
  /** Obsluha potvrdenia ucasti 
   * @return Multiplier */
	public function createComponentPotvrdUcast() {
		return new Multiplier(function ($id_oznam) {
			$potvrd = $this->potvrdUcastControlFactory->create();
			$potvrd->setParametre($id_oznam);
			return $potvrd;
		});
	}
  
  /** Obsluha komentara
   * @return Multiplier */
	public function createComponentKomentar() {
		return new Multiplier(function ($id_oznam) {
      $komentar = $this->komentarControlControlFactory->create();
      $komentar->setParametre($id_oznam);
			return $komentar;
		});
	}
}
