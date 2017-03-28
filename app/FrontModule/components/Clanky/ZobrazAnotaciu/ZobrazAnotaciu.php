<?phpnamespace App\FrontModule\Components\Clanky\ZobrazAnotaciu;use Nette;/** * Komponenta pre zobrazenie konkretneho clanku pre FRONT modul * Posledna zmena(last change): 28.03.2017 * * @author Ing. Peter VOJTECH ml <petak23@gmail.com> * @copyright Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml. * @license * @link http://petak23.echo-msz.eu * @version 1.0.0 * */class ZobrazAnotaciuControl extends Nette\Application\UI\Control {  /** @var array - texty pre sablonu v inom jazyku */  private $texty = [    "not_found"   =>"Chyba zobrazenia! Nesprávny názov šablóny[%s]...",    "platnost_do" =>"Platnosť do: ",    "zadal"       =>"Zadal: ",    "zobrazeny"   =>"Zobrazený: ",      "anotacia"    =>"Anotácia:",    "viac"        =>"viac",    "text_title_image" => "Titulný obrázok",   ];  /** @var mix - data zobrazovaneho clanku */  private $zobraz_clanok;  /** @var int $clanok_hlavicka - ake udaje sa zobrazia v hlavicke clanku */  private $clanok_hlavicka = 0;  /** @var string $parentTemplate Nazov suboru template clanku na zobrazenie */  private $parentTemplate = "ZobrazAnotaciu_default.latte";  /** @var string|FALSE */  private $err = FALSE;  public function __construct($zobraz_clanok) {    parent::__construct();    $this->zobraz_clanok = $zobraz_clanok;  }    /** Rucne nastavenie defaultnej template pre zobrazenie clanku.   * POZOR! Toto nastavenie prebije nastavenie v DB(ak je)   * @param string $pt Nazov template   */  public function setClanokTemplate($pt) {    if (isset($pt) && strlen($pt)) {      if (is_file(__DIR__ . "/ZobrazAnotaciu_".$pt.".latte")) {        $this->parentTemplate = "ZobrazAnotaciu_".$pt.".latte";      } else {        $this->err = sprintf($this->texty['not_found'], $pt);      }    }  }    /** Nastavenie textov pre sablonu v inom jazyku   *  @param array $text texty    */  public function setTexts($text) {    if (isset($text) && is_array($text)) { $this->texty = array_merge ($this->texty, $text); }  }  /** Nastavenie hlavicky clanku   *  @param int $clanok_hlavicka ake udaje sa zobrazia v hlavicke clanku   */  public function setClanokHlavicka($clanok_hlavicka) {    if (isset($clanok_hlavicka) && $clanok_hlavicka) { $this->clanok_hlavicka = $clanok_hlavicka; }  }    /** Otestovanie názvu a existencie suboru template a jeho nastavenie */  private function _testTemplate() {    $pt = $this->zobraz_clanok !== FALSE ? $this->zobraz_clanok->hlavne_menu->nazov_ul_sub : FALSE;    if ($pt && strlen($pt)) {       if (is_file(__DIR__ ."/ZobrazAnotaciu_".$pt.".latte")) {        $this->parentTemplate = "ZobrazAnotaciu_".$pt.".latte";      } else {        $this->err = sprintf($this->texty['not_found'], $pt);      }    }    $this->template->setFile(__DIR__ ."/".$this->parentTemplate);  }    /** Render funkcia pre vypisanie odkazu na clanok    * @see Nette\Application\Control#render()   */  public function render($parameters = []) {     $pthis = $this->presenter;    $this->_testTemplate();    if ($this->err !== FALSE) { //Je nejaka chyba      $this->template->setFile(__DIR__ . '/ZobrazAnotaciu_error.latte');      $this->template->text = $this->err;    } else { //Vsetko je OK      $this->template->clanok_hlavicka = isset($parameters["clanok_hlavicka"]) ? $parameters["clanok_hlavicka"] : $this->clanok_hlavicka;      $this->template->clanok = $this->zobraz_clanok;      $this->template->texty = $this->texty;      $this->template->avatar_path = $pthis->avatar_path;			$this->template->article_avatar_view_in = $pthis->nastavenie["article_avatar_view_in"];      $this->template->nastav = $parameters;    }    $this->template->render();  }   protected function createTemplate($class = NULL) {    $servise = $this;    $template = parent::createTemplate($class);    $template->addFilter('obr_v_txt', function ($text) use($servise){      $serv = $servise->presenter;      $vysledok = '';      $cesta = 'http://'.$serv->nazov_stranky."/";      foreach (explode("#", $text) as $k=>$cast) {        if (substr($cast, 0, 2) == "I-") {          $obr = $serv->dokumenty->find((int)substr($cast, 2));					if ($obr !== FALSE) {            $cast = \Nette\Utils\Html::el('a class="thumbnail fotky" rel="fotky"')->href($cesta.$obr->subor)->title($obr->nazov)                                  ->setHtml(\Nette\Utils\Html::el('img class="img-rounded"')->src($cesta.$obr->thumb)->alt($obr->nazov));					}        }        $vysledok .= $cast;      }      return $vysledok;    });    $template->addFilter('koncova_znacka', function ($text) use($servise){      $rozloz = explode("{end}", $text);      $vysledok = $text;			if (count($rozloz)>1) {		 //Ak som nasiel znacku				$vysledok = $rozloz[0].\Nette\Utils\Html::el('a class="cely_clanok"')->href($servise->link("this"))->title("Zobrazenie celého článku")                ->setHtml('&gt;&gt;&gt; '.$servise->texty["viac"]).'<div class="ostatok">'.$rozloz[1].'</div>';			}      return $vysledok;    });    return $template;	}}