<?php
namespace PeterVojtech\Email;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Application\UI;
use Latte;
use DbTable;

/**
 * Komponenta pre zjedndusenie odoslania emailu
 * Posledna zmena(last change): 19.12.2016
 * 
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com> 
 * @copyright  Copyright (c) 2012 - 2016 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.0.3
 */

class EmailControl extends UI\Control {
  
  /** @var Nette\Mail\Message */
  private $mail;
  /** @var string */
  private $email_list;
  /** @var string */
  private $template;
  /** @var string */
  private $from;
  /** @var DbTable\User_profiles */
  public $user_profiles;

  /**
   * @param DbTable\User_profiles $user_profiles */
  public function __construct(DbTable\User_profiles $user_profiles) {
    parent::__construct();
    $this->mail = new Message;
    $this->user_profiles = $user_profiles;
  }
  
  /** 
   * @param string $template Kompletná cesta k súboru template
   * @param type $from
   * @param type $id_registracia 
   * @return \PeterVojtech\Email\EmailControl */
  public function nastav($template, $from, $id_registracia) {
    $this->template = $template;
    $this->from = $this->user_profiles->find($from)->users->email;
    $this->email_list = $this->user_profiles->emailUsersListStr($id_registracia);
    foreach (explode(",", $this->email_list) as $c) {
      $this->mail->addTo(trim($c));
    }
    return $this;
  }
  
  /** Funkcia pre odoslanie emailu
   * @param array $params Parametre správy
   * @param string $subjekt Subjekt emailu
   * @return string Zoznam komu bol odoslany email
   * @throws SendException
   */
  public function send($params, $subjekt) {
    $templ = new Latte\Engine;
//    echo($templ->renderToString($this->template, $params));die();
    $this->mail->setFrom($params["site_name"].' <'.  $this->from.'>');
    $this->mail->setSubject($subjekt)
         ->setHtmlBody($templ->renderToString($this->template, $params));
    try {
      $sendmail = new SendmailMailer;
      $sendmail->send($this->mail);
      return $this->email_list;
    } catch (Exception $e) {
      throw new SendException('Došlo k chybe pri odosielaní e-mailu. Skúste neskôr znovu...'.$e->getMessage());
    }
  }
}
interface IEmailControl {
  /** @return EmailControl */
  function create();
}