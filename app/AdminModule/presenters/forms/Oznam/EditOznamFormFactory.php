<?phpnamespace App\AdminModule\Presenters\Forms\Oznam;use Nette\Application\UI\Form;use DbTable;use Nette\Security\User;use Nette\Utils\Html;/** * Komponenta pre vypísanie a spracovanie formulara oznamov.  *  * Posledna zmena(last change): 03.03.2017 * * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>  * @copyright Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml. * @license * @link http://petak23.echo-msz.eu * @version 1.0.0 */class EditOznamFormFactory {  /** @var DbTable\Oznam */	private $oznam;  /** @var array */  public $urovneReg;  /** @var DbTable\Ikonka */  public $ikonka;  /** @var mix */  private $hasser;  /**   * @param DbTable\Oznam $oznam   * @param DbTable\Registracia $registracia   * @param DbTable\Ikonka $ikonka   * @param User $user */  public function __construct(DbTable\Oznam $oznam, DbTable\Registracia $registracia, DbTable\Ikonka $ikonka, User $user) {		$this->oznam = $oznam;    $this->ikonka = $ikonka;    $this->urovneReg = $registracia->hladaj_urovne(0, ($user->isLoggedIn()) ? $user->getIdentity()->id_registracia : 0)->fetchPairs('id', 'nazov');    $this->hasser = $user->getAuthenticator(); //Ziskanie objektu pre vytvaranie hash hesla a iných    $this->hasser->PasswordHash(8,FALSE);      //Nastavenie	}    /**   * Formular pre editaciu a pridanie oznamu   * @param int $oznam_ucast Povolenie potvrdenia ucasti   * @param boolean $oznam_title_image_en Povolenie titulneho obrazka   * @param string $nazov_stranky Nazov stranky   * @return Form */  public function create($oznam_ucast, $oznam_title_image_en, $nazov_stranky) {    $form = new Form();		$form->addProtection();    $form->addHidden("id");$form->addHidden("id_user_profiles");$form->addHidden("datum_zadania");		$form->addDatePicker('datum_platnosti', 'Dátum platnosti')				 ->addRule(Form::FILLED, 'Dátum platnosti musí byť zadaný!');		$form->addText('nazov', 'Nadpis:', 50, 80)				 ->addRule(Form::MIN_LENGTH, 'Nadpis musí mať spoň %d znakov!', 3)				 ->setRequired('Názov musí byť zadaný!');		$form->addSelect('id_registracia', 'Povolené prezeranie pre min. úroveň:', $this->urovneReg);		if ($oznam_ucast) {      $form->addCheckbox('potvrdenie', ' Potvrdenie účasti');    } else {      $form->addHidden('potvrdenie');    }    if (!$oznam_title_image_en) {       $ikonky = $this->ikonka->findAll()->fetchPairs('id', 'nazov');      $outDir = 'http://'.$nazov_stranky.'/www/ikonky/64/';      foreach ($ikonky as $key => $nazov) {        $iko[$key] = Html::el('img', ['class'=>'ikonkySmall'])->src($outDir.$nazov.'_64.png');      }      $form->addRadiolist('id_ikonka', 'Ikonka pred článkom:', $iko)           ->setAttribute('class', 'ikons-set')           ->getSeparatorPrototype()->setName(NULL);    }		$form->addTextArea('text', 'Text:')				 ->setAttribute('cols', 60)->setAttribute('class', 'jquery_ckeditor');    $form->addSubmit('uloz', 'Ulož')         ->setAttribute('class', 'btn btn-success')         ->onClick[] = [$this, 'editOznamFormSubmitted'];    $form->addSubmit('cancel', 'Cancel')->setAttribute('class', 'btn btn-default')         ->setValidationScope(FALSE);		return $form;	}    /**    * Spracovanie vstupov z formulara   * @param Nette\Forms\Controls\SubmitButton $button Data formulara */	public function editOznamFormSubmitted($button)	{    $values = $button->getForm()->getValues();    try {      if (($oznam = $this->oznam->ulozOznam($values)) !== FALSE && $oznam->oznam_kluc == null) { //Priradenie kluca k oznamu        $oznam->update(['oznam_kluc'=>$this->hasser->HashPassword('Potvrd ucast'.$oznam->id)]);      }      $values->offsetSet('id', $oznam->id); //Aby som ho mohol neskor pouzit		} catch (Database\DriverException $e) {			$button->addError($e->getMessage());		}	}}