<?phpnamespace App\AdminModule\Presenters\Forms\Adresar;use Nette\Application\UI\Form;use DbTable;use Nette\Database;class EditAdresarFormFactory {  /** @var DbTable\Adresar */  private $adresar;  public function __construct(DbTable\Adresar $adresar) {		$this->adresar = $adresar;	}    /**   * Edit hlavne menu form component factory.   * @return Nette\Application\UI\Form   */    public function create()  {    $form = new Form();		$form->addProtection();    $form->addGroup();    $form->addHidden('id');    $form->addText('nazov', 'Názov kontaktu:', 40, 30)         ->addRule(Form::MIN_LENGTH, 'Názov kontaktu musí mať aspoň %s znakov', 2)         ->setAttribute('autofocus', 'autofocus')				 ->setRequired('Názov kontaktu musí byť zadaný!');    $form->addText('meno', 'Meno a priezvisko:', 90, 70);    $form->addText('adresa', 'Adresa:', 90, 255);    $form->addText('telefon', 'Telefónne číslo:', 40, 30);    $form->addText('mobil', 'Mobilné číslo:', 40, 20);    $form->addText('email', 'E-mailová adresa', 40, 40)         ->setType('email')         ->addCondition(Form::FILLED)          ->addRule(Form::EMAIL, 'Musí byť zadaná korektná e-mailová adresa(napr. janko@hrasko.sk)');    $form->addText('poznamka', 'Poznámka:', 90, 255);		$form->addSubmit('uloz', 'Ulož')         ->setAttribute('class', 'btn btn-success')         ->onClick[] = [$this, 'editAdresaFormSubmitted'];    $form->addSubmit('cancel', 'Cancel')->setAttribute('class', 'btn btn-default')         ->setValidationScope(FALSE);		return $form;	}    /** Spracovanie vstupov z formulara   * @param Nette\Forms\Controls\SubmitButton $button Data formulara   */	public function editAdresaFormSubmitted($button)	{    try {			$this->adresar->ulozAdresu($button->getForm()->getValues());		} catch (Database\DriverException $e) {			$button->addError($e->getMessage());		}	}}