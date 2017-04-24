<?php
namespace App\FrontModule\Presenters;

use DbTable, Language_support;

/**
 * Prezenter pre vypísanie profilu a správu foto príloh.
 * (c) Ing. Peter VOJTECH ml.
 * Posledna zmena(last change): 24.04.2017
 *
 *	Modul: FRONT
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.0.6
 */
class MyPresenter extends \App\FrontModule\Presenters\BasePresenter {
  
  /** 
   * @inject
   * @var DbTable\Users */
	public $users;
    /** 
   * @inject
   * @var DbTable\Dokumenty_kategoria */
	public $dokumenty_kategoria;
  /**
   * @inject
   * @var Language_support\My */
  public $texty_presentera;
  /** @var ints */
  private $user_id;
  /** @var array Nastavenie zobrazovania volitelnych poloziek */
  private $user_view_fields;
  
  /** @var Forms\My\EditFotoPrilohyFormFactory @inject */
  public $editFotoPrilohyFormFactory;
    /** @var Forms\My\EditDokumentFormFactory @inject */
  public $editDokumentFormFactory;
  /** @var \App\FrontModule\Components\My\FotoPrilohy\IFotoPrilohyControl @inject */
  public $fotoPrilohyControlFactory;
  
  /** @var */
  public $dkategoria;

  /** @var string */
  private $h2;
  /** @var \Oli\GoogleAPI\IMapAPI */
  private $map;
  /** @var \Oli\GoogleAPI\IMarkers */
  private $markers;
  /** @var Nette\Database\Table\Selection */
  public $fotky;
  /** @var int */
  public $kategoria;
  /** @var boolean Nastavenie zobrazenia vlastneho profilu */
  private $my_profile = TRUE;

  public function __construct(\Oli\GoogleAPI\IMapAPI $mapApi, \Oli\GoogleAPI\IMarkers $markers) {
    parent::__construct();
    $this->map = $mapApi;
    $this->markers = $markers;
  }
  
  protected function startup() {
    parent::startup();
    // Kontrola ACL
    if (!$this->user->isAllowed($this->name, $this->action)) {
      $this->flashRedirect('Homepage:', sprintf($this->trLang('base_nie_je_opravnenie'), $this->action), 'danger');
    }
    //Najdem aktualne prihlaseneho clena
    $this->user_id = $this->user->getIdentity()->getId();
    $this->user_view_fields = $this->nastavenie['user_view_fields'];
    $this->template->pocet_prispevkov = $this->dokumenty->findBy(['id_user_profiles' => $this->user_id])->count('*'); //, 'id_dokumenty_kategoria' =>2
    $this->dkategoria = $this->dokumenty_kategoria->findAll();
	}
  
  /**
   * Defaultna akcia 
   * @param int $user_id Id uzivatela, ktorý sa zobrazí */
  public function actionDefault($user_id = 0) {
    $this->user_id = ($this->user->getIdentity()->id_registracia > 3 && $user_id) ? $user_id : $this->user_id;
    $this->my_profile = !($this->user->getIdentity()->id_registracia > 3 && $user_id);
    $this->fotky = $this->dokumenty->findBy(["id_user_profiles" =>$this->user_id, "id_hlavne_menu" =>$this->udaje_webu["hl_udaje"]["id"]]); 
  }
  
  public function renderDefault() {
    $this->template->clen = $this->user_profiles->findOneBy(['id_users'=>$this->user_id]);
    $this->template->h2 = $this->trLang('h2');
    $this->template->texty = $this->texty_presentera;
    $this->template->foto = $this->fotky;
    $this->template->dkategoria = $this->dkategoria;
    $this->template->my_profile = $this->my_profile;
    $this->template->pocet_prispevkov = $this->dokumenty->findBy(['id_user_profiles' => $this->user_id])->count('*'); //, 'id_dokumenty_kategoria' =>2
  }
  
  /**
   * Akcia pre pridanie fotky k profilu */
  public function actionAdd($kategoria = 0) {
    $this->kategoria = $kategoria;
    if ($kategoria == 0) {
      $this->h2 = "Zvoľ kategóriu:";
    } elseif ($kategoria == 1) {
      $this->h2 = "Nový súťažný príspevok:";
      $this["fotoEditForm"]->setDefaults([
        "id"              =>0, 
        "id_hlavne_menu"  =>$this->udaje_webu["hl_udaje"]["id"], 
        "id_user_profiles"=>$this->user_id,
        "id_registracia"  =>1,
        "id_dokumenty_kategoria"=> 1,
        "coords"          =>['lat' => 49.017935, 'lng' => 20.276091],
        ]);
    } elseif ($kategoria == 2) {
      $this->h2 = "Nový súťažný príspevok:";
      $this["dokumentForm"]->setDefaults([
        "id"              =>0, 
        "id_hlavne_menu"  =>$this->udaje_webu["hl_udaje"]["id"], 
        "id_user_profiles"=>$this->user_id,
        "id_registracia"  =>1,
        "id_dokumenty_kategoria"=>2,
        ]);
    }
    $this->setView("edit");
    
  }
  
  /** Akcia pre editaciu informacii o dokumente
   * @param int $id Id dokumentu na editaciu
   */
  public function actionEdit($id) {
		if (($priloha = $this->dokumenty->find($id)) === FALSE) { 
      return $this->error(sprintf("Pre zadané id som nenašiel prílohu! id=' %s'!", $id)); 
    }
    $this->kategoria = $priloha->id_dokumenty_kategoria;
    if ($this->kategoria == 1) {
      $this->h2 =  'Editácia fotky: '.$priloha->nazov;
      $this["fotoEditForm"]->setDefaults($priloha);
      $this["fotoEditForm"]->setDefaults(["coords"=>['lat' => $priloha->lat, 'lng' => $priloha->lng]]);
    } elseif ($this->kategoria == 2) {
      $this->h2 =  'Editácia dokumentu: '.$priloha->nazov;
      $this["dokumentForm"]->setDefaults($priloha);
    }
    
    
  }
  
  /** Render pre editaciu prilohy. */
	public function renderEdit() {
    $this->template->dkategoria = $this->dkategoria;
    $this->template->h2 = $this->h2;
    $this->template->kategoria = $this->kategoria;
    $this->template->pocet_prispevkov_visual = $this->dokumenty->findBy(['id_user_profiles' => $this->user_id, 'id_dokumenty_kategoria' =>2])->count('*');
	}
  
  /** 
   * Formular pre editaciu fotiek
	 * @return Nette\Application\UI\Form */
	protected function createComponentFotoEditForm() {
    $form = $this->editFotoPrilohyFormFactory->create($this->upload_size, "www/files/myfoto/", $this->nastavenie['prilohy_images'], $this->nastavenie['wwwDir']);
    $form['uloz']->onClick[] = function ($button) {
      $this->flashOut(!count($button->getForm()->errors), 'My:', 'Foto príloha bola úspešne uložená!', 'Došlo k chybe a zmena sa neuložila. Skúste neskôr znovu...');
//      dump($button->getForm()->errors);
		};
    $form['cancel']->onClick[] = function () {
			$this->redirect('My:');
		};
    return $form;//$this->_vzhladForm($form);
	}
  
    /** 
   * Formular pre editaciu dokumentov
	 * @return Nette\Application\UI\Form */
	protected function createComponentDokumentForm() {
    $form = $this->editDokumentFormFactory->create($this->upload_size, "www/files/myfoto/", $this->nastavenie['prilohy_images'], $this->nastavenie['wwwDir']);
    $form['uloz']->onClick[] = function ($button) {
      $this->flashOut(!count($button->getForm()->errors), 'My:', 'Dokument bol úspešne uložený!', 'Došlo k chybe a zmena sa neuložila. Skúste neskôr znovu...');
//      dump($button->getForm()->errors);
		};
    $form['cancel']->onClick[] = function () {
			$this->redirect('My:');
		};
    return $form;//$this->_vzhladForm($form);
	}
  
  /**
   * Komponenta pre pridanie fotografie
   * @return \App\FrontModule\Components\My\FotoPrilohy\IFotoPrilohyControl */
	public function createComponentFotoPrilohy() {
    $prilohyClanok = $this->fotoPrilohyControlFactory->create(); 
    $prilohyClanok->setTitle($this->udaje_webu)->setUserId($this->user_id);
    return $prilohyClanok;
  }
  
  /**
   * Komponenta pre vykreslenie mapy s fotkami
   * @return \Oli\GoogleAPI\MapAPI */
  public function createComponentMap() {
    $map = $this->map->create();
    $markers = $this->markers->create();
    foreach ($this->fotky->where('id_dokumenty_kategoria', 1) as $marker) {
      $markers->addMarker([$marker->lat, $marker->lng], FALSE, $marker->nazov)
              ->setMessage('<h2>'.$marker->nazov.'</h2><br />'
                      . '<img src="'.$this->template->basePath.'/'.$marker->thumb.'" alt="'.$marker->nazov.'" class="img-rounded">'
                      . '<br />'.$marker->nazov_latin)
              ->setIcon('www/images/icons/find_48.png');
    }
    $map->addMarkers($markers);
    return $map;
  }

  /**
   * Spracovanie signálu pre mazanie fotky
   * @param int $id Id mazanej fotky */
	function confirmedDelete($id) {
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
   * @param boolean $if Podmienka
   * @param string $dobre Sprava v pripade ak je podmienka TRUE
   * @param string $zle Sprava v pripade ak je podmienka FALSE */
  public function _ifMessage($if, $dobre, $zle) {
    if ($if) { $this->flashMessage($dobre, 'success'); }
    else { $this->flashMessage($zle, 'danger'); }
  }
}