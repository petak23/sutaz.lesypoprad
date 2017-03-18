<?php
namespace App\AdminModule\Presenters;

use Nette\Forms\Container;
use Nette\Application\UI\Multiplier;
use DbTable;
use Nette;
use Latte;

/**
 * Prezenter pre spravu oznamov.
 * 
 * Posledna zmena(last change): 13.03.2017
 *
 * Modul: ADMIN
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.1.7
 */

Container::extensionMethod('addDatePicker', function (Container $container, $name, $label = NULL) {
    return $container[$name] = new \JanTvrdik\Components\DatePicker($label);
});

class OznamPresenter extends \App\AdminModule\Presenters\BasePresenter {
	/** 
   * @inject
   * @var DbTable\Oznam */
	public $oznam;
  
  /** 
   * @inject
   * @var DbTable\Oznam_volba */
	public $oznam_volba;
  /** @var DbTable\User_profiles @inject */
	public $user_profiles;
  
  // -- Komponenty
  /** @var \App\AdminModule\Components\Oznam\PotvrdUcast\IPotvrdUcastControl @inject */
  public $potvrdUcastControlFactory;
  /** @var \App\AdminModule\Components\Oznam\IKomentarControl @inject */
  public $komentarControlControlFactory;
  /** @var \App\AdminModule\Components\Oznam\TitleOznam\ITitleOznamControl @inject */
  public $titleOznamControlFactory;

  // -- Formulare
  /** @var Forms\Oznam\EditOznamFormFactory @inject*/
	public $editOznamForm;
  
	/** @var boolean|FALSE */
	private $oznam_usporiadanie = FALSE;

  /** Vychodzie nastavenia */
	protected function startup() {
    parent::startup();
    //Z DB zisti ako budu oznamy usporiadane
    if (($pomocna = $this->udaje->getKluc("oznam_usporiadanie")) !== FALSE) {
      $this->oznam_usporiadanie = (boolean)$pomocna->text;
    }
    if (($pomocna = $this->udaje->getKluc("oznam_komentare")) !== FALSE) {
      $oznam_komentare = (boolean)$pomocna->text;
    } else {$oznam_komentare = FALSE; }
    $this->template->oznam_komentare = $oznam_komentare;
    $this->template->oznam_title_image_en = (boolean)$this->udaje->getUdajInt("oznam_title_image_en");
	}

  /** Render pre vypis oznamov */
  public function renderDefault() {
		$this->template->oznamy = $this->oznam->vsetky($this->oznam_usporiadanie, $this->id_reg);
  }

  /** Akcia pre pridanie oznamu */
	public function actionAdd() {
    $this["oznamEditForm"]->setDefaults([ //Nastav vychodzie hodnoty
      'id'							=> 0,
      'id_user_profiles'=> $this->getUser()->getId(),
      'id_registracia'	=> 0,
      'id_ikonka'				=> 0,
      'datum_platnosti'	=> StrFTime("%Y-%m-%d", Time()),
      'datum_zadania'   => StrFTime("%Y-%m-%d %H:%M:%S", Time()),
      'potvrdenie'			=> 0,  
      'posli_news'			=> 0,
    ]);
    $this->setView('edit');
    $this->template->h2 = 'Pridanie oznamu';
	}

  /** Akcia pre editaciu oznamu
   * @param int $id Id oznamu
   */
	public function actionEdit($id) {
    if (($oznam = $this->oznam->hladaj_id($id, $this->id_reg)) === FALSE) {
      $this->setView('notFound');
    } else { //Ak je vsetko OK priprav premenne
      $this["oznamEditForm"]->setDefaults($oznam);
      $this["oznamEditForm"]->setDefaults([ //Nastav vychodzie hodnoty
        'datum_zadania'   => StrFTime("%Y-%m-%d %H:%M:%S", Time()),
        'posli_news'			=> 0,
      ]);
      $this->template->h2 = sprintf('Editácia oznamu: %s', $oznam->nazov);
    }
    
	}
  
  /** Render pre pridanie a editaciu oznamu */
  public function renderEdit() {
    $this->template->bezp_kod	= '4RanoS5689q6-498'; //Bezp. kod pre CKEditor$
    $this->template->CKtoolbar= "OznamToolbar".(($this->user->isInRole('admin')) ? "" : "_Spravca");
  }
  
	/** Formular pre editaciu a pridanie oznamu
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentOznamEditForm() {
    $form = $this->editOznamForm->create($this->udaje_webu['oznam_ucast'], $this->template->oznam_title_image_en, $this->nazov_stranky); 
    $form['uloz']->onClick[] = function ($button) { 
      $form_val = $button->getForm();
      $this->flashOut(!count($form_val->errors), 'Oznam:', 'Oznam bol uložený!', 'Došlo k chybe a oznam sa neuložil. Skúste neskôr znovu...');
      
		};
    $form['cancel']->onClick[] = function () {
			$this->redirect('Oznam:');
		};
		return $this->_vzhladForm($form);
	}
  
  /** Odoslanie info e-mailu 
   * @param int $id
   */
	protected function _sendOznamyEmail($id) {
    $values = $this->oznam->find($id);
    $params = [ "site_name" => $this->nazov_stranky,
                "nazov" 		=> $values->nazov,
                "text"      => $values->text,
                "odkaz" 		=> $this->link("//:Front:Oznam:default"),
                "datum_platnosti" => $values->datum_platnosti,
                "oznam_ucast" => $this->user->isAllowed('Admin:Oznam', 'ucast') && $this->udaje->getKluc("oznam_ucast") && $values->potvrdenie,
                "oznam_id"    => $values->id,
                "oznam_kluc"  => $values->oznam_kluc,
              ];
    try {
      $send = $this->sendJednotlivci(__DIR__.'/templates/Oznam/email_oznamy_html.latte', 1, $values->id_registracia, $params, 'Nový oznam na stránke '.$this->nazov_stranky);
      $this->flashMessage('E-mail bol odoslany v poriadku na emaily: '.$send, 'success');
    } catch (Exception $e) {
      $this->flashMessage($e->getMessage(), 'danger');
    }
	}
  
  /** 
   * Funkcia pre odoslanie emailov pre skupinu príjemcov po jednom
   * @param string $template Kompletná cesta k súboru template
   * @param type $from
   * @param type $id_registracia
   * @param array $params Parametre správy
   * @param string $subjekt Subjekt emailu
   * @return string Zoznam komu bol odoslany email
   * @throws SendException   */
  public function sendJednotlivci($template, $from, $id_registracia, $params, $subjekt) {
    $templ = new Latte\Engine;
    $email_to_array = $this->user_profiles->emailUsersListArray($id_registracia);
    $email_list = "";
    $from = $this->nazov_stranky.' <'.  $this->user_profiles->find($from)->users->email.'>';
    $sum = count($email_to_array); $iter = 0;
    foreach ($email_to_array as $id_user_profiles=>$users_email) {
      $mail = new Nette\Mail\Message;
      $mail->setFrom($from);
      $mail->setSubject($subjekt);
      $mail->addTo(trim($users_email));
      $params['id_user_profiles'] = $id_user_profiles;
      $params['volby'] = $this->_volby($params);
      $params["odhlasenie"] = ['odkaz'=>$this->link('//:Front:UserLog:NewsUnsubscribe', ['id_user_profiles'=>$id_user_profiles, 'news_key'=>$this->user_profiles->find($id_user_profiles)->news_key]),
                               'text'=>"Odhlásenie z odberu noviniek."];
      $mail->setHtmlBody($templ->renderToString($template, $params));
      try {
        $sendmail = new Nette\Mail\SendmailMailer;
        $sendmail->send($mail);
        $email_list .= $users_email.($sum == $iter ? '' : ', '); 
      } catch (Exception $e) {
        throw new SendException('Došlo k chybe pri odosielaní e-mailu. Skúste neskôr znovu...'.$e->getMessage());
      }
      unset($mail);
    }
    return $email_list;
  }
  
  private function _volby($params) {
    $volby = $this->oznam_volba->volby();
    $out = [];
    foreach ($volby as $k => $v) {
      $out[] = ['nazov' => $v,
                'odkaz' => $this->link('//:Front:Oznam:UcastFromEmail', ['id_user_profiles'=>$params['id_user_profiles'], 'id_oznam'=>$params['oznam_id'], 'id_volba_oznam'=>$k, 'oznam_kluc'=>$params['oznam_kluc']])
             ];
    }
    return $out;
  }
  
  /** Signal pre odoslanie informacneho emailu */
  public function handlePosliEmail($id) {
    $this->_sendOznamyEmail($id);
		$this->redirect('this');
	}
  
  /** Signal pre zmenu presmerovania v pripade, ze nemam aktualne oznamy */
  public function handleOznPresmerovanie() {
    //$this->udaje->opravKluc('oznam_usporiadanie', "1");
		$this->redirect('this');
	}
  
  /** Komponenta pre tvorbu titulku oznamov.
   * @return \App\AdminModule\Components\Oznam\TitleOznam */
  public function createComponentTitleOznam() {
    $title = $this->titleOznamControlFactory->create();
//    $title->setTitle($this->zobraz_clanok, $this->name, $this->udaje_webu['komentare'], $this->nastavenie['aktualny_projekt_enabled'], $this->nastavenie['clanky']['zobraz_anotaciu']);
    return $title;
  }
  
  /** Komponenta na obsluhu potvrdenia ucasti */
	public function createComponentPotvrdUcast() {
		return new Multiplier(function ($id_oznam) {
      $potvrd = $this->potvrdUcastControlFactory->create();
      $potvrd->setParametre($id_oznam, $this->user->isAllowed('Admin:Oznam', 'ucast'));
			return $potvrd;
		});
	}
  
  /** Obsluha komentara
   * @return Multiplier */
	public function createComponentKomentar() {
		return new Multiplier(function ($id_oznam) {
      $komentar = $this->komentarControlControlFactory->create();
      $komentar->setParametre($id_oznam, $this->user->isInRole('admin'));
			return $komentar;
		});
	}
  
  /** Funkcia pre spracovanie signálu vymazavania
	  * @param int $id Id oznamu */
	function confirmedDelete($id)	{
    if ($this->oznam->vymazOznam($id) == 1) { $this->flashMessage('Aktualita(oznam) bola úspešne vymazaná!', 'success'); } 
    else { $this->flashMessage('Došlo k chybe a aktualita(oznam) nebola vymazaná!', 'danger'); }
    if (!$this->isAjax()) { $this->redirect('Oznam:'); }
  }
  
  /**
   * Vytvorenie spolocnych helperov pre sablony
   * @param type $class
   * @return type */
  protected function createTemplate($class = NULL) {
    $servise = $this;
    $template = parent::createTemplate($class);
    $template->addFilter('vlastnik', function ($id_user_profiles = 0, $action = 'edit') use($servise) {
      $user = $servise->user;// Vrati true ak: si prihlaseny && si admin || (mas opravnenie a si valstnik)
      $out = $user->isLoggedIn() ? ($user->isInRole('admin') ? TRUE : 
                                          ($user->isAllowed($servise->name , $action) ? ($id_user_profiles ? $user->getIdentity()->id == $id_user_profiles : FALSE) : FALSE)) : FALSE;
      return $out;
    });
    return $template;
	}
  
}
