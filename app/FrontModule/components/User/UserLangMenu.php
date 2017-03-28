<?php
namespace App\FrontModule\Components\User;

use Nette\Application\UI\Control;
use Nette\Utils\Html;
use Nette\Security\User;
use DbTable;
use Language_support;

/**
 * Plugin pre zobrazenie ponuky o užívateľovi a jazykoch
 * Posledna zmena(last change): 28.03.2017
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2013 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.0.8
 */
class UserLangMenuControl extends Control {
  /** @var Language_support\User Prednastavene texty pre prihlasovaci form */
	public $texty;
	
  /** @var array Lokalne nastavenia */
	private $nastavenie;
  
  /** @var DbTable\Lang */
  public $lang;

  /** @var User */
  protected $user;
 
  /**
   * @param DbTable\Lang $lang
   * @param User $user
   * @param Language_support\User $lang_supp */
  public function __construct(DbTable\Lang $lang, User $user, Language_support\User $lang_supp) {
    parent::__construct();
    $this->lang = $lang;
    $this->user = $user;
    $this->texty = $lang_supp;
  }
  
  /** 
   * Panel neprihlaseneho uzivatela
   * @param array $udaje_webu
   * @param int $vlnh
   * @return \App\FrontModule\Components\User\MenuItem  */
  private function _panelNeprihlaseny($udaje_webu, $vlnh) {
    $menu_user = [];
    $menu_user[] = new MenuItem([
        'odkaz'=>[0=>'User:', 1=>['meno'=>'backlink', 'obsah'=>$this->presenter->storeRequest()]], 
        'class'=>'btn-success',//'log-in noajax'.(($vlnh) ? "" : " prazdny fa fa-lock"),
        'title'=>$udaje_webu['log_in'].$vlnh,
        'nazov'=>($vlnh & 1) ? $udaje_webu['log_in'] : ($vlnh ? NULL : ""),
        'ikonka'=>($vlnh & 2) ? "sign-in" : NULL,
//        'data'=>['name'=>'ajax', 'value'=>'false'],
                        ]);
//    if ($vlnh > 0) {//Ak je >0 zobraz link
//      $menu_user[] = new MenuItem([
//          'odkaz'=>'User:forgottenPassword',
//          'title'=>$udaje_webu['forgot_password'],
//          'ikonka'=>($this->nastavenie['view_log_in_link_in_header'] & 2) ? "frown-o" : NULL,
//          'nazov'=>($this->nastavenie['view_log_in_link_in_header'] & 1) ? $udaje_webu['forgot_password'] : NULL,
//                          ]);
//    } else {//Ak je 0 nezobraz link
//      $this->template->fl = new MenuItem([
//        'odkaz'=>'User:forgottenPassword',
//        'title'=>$udaje_webu['forgot_password'],
//        'class'=>'fl',
//        'ikonka'=>"frown-o",
//        'nazov'=>$udaje_webu['forgot_password'],
//                        ]);
//    }
    if (isset($udaje_webu['registracia_enabled']) && $udaje_webu['registracia_enabled']) {
      $menu_user[] = new MenuItem([
          'odkaz'=>'User:registracia', 
          'nazov'=>$udaje_webu['register'],
          'class'=>'btn-primary',
                          ]);
    }
    return $menu_user;
  }

  /** 
   * Panel prihlaseneho uzivatela
   * @param string $baseUrl
   * @param string $log_out
   * @return \App\FrontModule\Components\User\MenuItem */
  private function _panelPrihlaseny($baseUrl, $log_out) {
    $menu_user = [];
    $udata = $this->user->getIdentity();
    if ($this->nastavenie['view_avatar']) {
      $obb = Html::el('img class="avatar"');
      if ($udata->avatar_25 && is_file('www/'.$udata->avatar_25)) {
        $obb = $obb->src($baseUrl.'/www/'.$udata->avatar_25)->alt('avatar');
      } else {
        $obb = $obb->src($baseUrl.'/www/ikonky/64/figurky_64.png')->alt('bez avatara');
      }
    } else {$obb = "";}
    $menu_user[] = new MenuItem([
          'odkaz'=>'UserLog:', 
          'nazov'=>$obb." ".$udata->meno.' '.$udata->priezvisko,
          'title'=>$udata->meno.' '.$udata->priezvisko,
          'class'=>'btn-success',]);
    if ($this->user->isAllowed('admin', 'enter')) {
      $menu_user[] = new MenuItem([
        'odkaz'=>':Admin:Homepage:',
        'title'=>'Administrácia',
        'ikonka'  => ($this->nastavenie['admin_link'] & 1) ? 'pencil' : '',
        'nazov'=>($this->nastavenie['admin_link'] & 2) ? $this->texty->trText('base_AdminLink_name') : '',
        'class'=>'btn-primary noajax',
        'data'=>['name'=>'ajax', 'value'=>'false'],
      ]);
    }
    if ($this->user->isInRole('admin')) {
      $hl_m_db_info = $this->lang->getDBInfo();
      $menu_user[] = new MenuItem([
        'abs_link'=>$baseUrl."/www/adminer/?server=".$hl_m_db_info['host']."&db=".$hl_m_db_info['dbname'], 
        'title'=>'Adminer',
        'target'=>'_blank',
        'nazov'=>Html::el('img')->src($baseUrl.'/www/ikonky/16/graf_16.png')->alt('Adminer'),
        'class'=>'noajax',
        'data'=>['name'=>'ajax', 'value'=>'false'],
                          ]);
    }
    $menu_user[] = new MenuItem([
        'odkaz'=>'signOut!',
        'ikonka'=>"sign-out",
        'nazov'=>$log_out,
        'class'=>'btn-warning noajax',
        'data'=>['name'=>'ajax', 'value'=>'false'],
                        ]);
    return $menu_user;
  }

  /** Vykreslenie komponenty */
  public function render($params) {
		//Inicializacia
		$pthis = $this->presenter;
		$baseUrl = $this->template->baseUrl;
    $this->nastavenie = $pthis->nastavenie['user_panel'];
    $this->nastavenie['view_avatar'] = $pthis->nastavenie['user_panel']['view_avatar'] && $pthis->nastavenie['user_view_fields']['avatar'];
		if ($this->user->isLoggedIn()) { 
      //Panel prihlaseneho uzivatela
      $menu_user = $this->_panelPrihlaseny($baseUrl, $pthis->udaje_webu['log_out']);
		} elseif (($vlnh = $this->nastavenie['view_log_in_link_in_header']) >= 0) { 
      //Panel neprihlaseneho uzivatela
      $menu_user = $this->_panelNeprihlaseny($pthis->udaje_webu, $vlnh);
		}
		$lang_temp = $this->lang->findBy(['prijaty'=>1]);
		if ($lang_temp !== FALSE && count($lang_temp)>1) {
			foreach($lang_temp as $lm) {
				$menu_user[] = new MenuItem([
						'odkaz'=>['setLang!', $lm->skratka],
						'title'=>$lm->nazov.", ".$lm->nazov_en,
						'class'=>($lm->skratka == $pthis->language) ? "lang actual" : "lang",
            'nazov'=>Html::el('img')->src($baseUrl.'/www/ikonky/flags/'.$lm->skratka.'.png')->alt('Adminer')
				]);
			}
		}
		$this->template->menu_user = isset($menu_user) ? $menu_user : [];
		$this->template->language = $pthis->language;
		$this->template->setFile(__DIR__ .'/'. (isset($params['templateFile']) ? $params['templateFile'] : 'UserLangMenu').'.latte');
		$this->template->render();
	}
		
}

class MenuItem extends \Nette\Object {
  public $odkaz;
  public $abs_link;
  public $nazov = "";
  public $class = "";
  public $title = "";
  public $target = "";
  public $ikonka;
  public $data = ['name'=>'', 'value'=>''];
  
  function __construct(array $params) {
    foreach ($params as $k => $v) { $this->$k = $v;}
    $this->title = $this->title == "" ? $this->nazov : $this->title;
  }
}

interface IUserLangMenuControl {
  /** @return UserLangMenuControl */
  function create();
}