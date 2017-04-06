<?phpnamespace App\FrontModule\Presenters\Forms\My;use Nette\Application\UI\Form;use Nette\Utils\Strings;use Nette\Utils\Image;use Nette\Database;use DbTable;/** * Formular a jeho spracovanie pre pridanie a editaciu dokumentu polozky. * Posledna zmena 06.04.2017 *  * @author     Ing. Peter VOJTECH ml. <petak23@gmail.com> * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml. * @license * @link       http://petak23.echo-msz.eu * @version    1.0.0 */class EditDokumentFormFactory {    /** @var DbTable\Dokumenty */	private $dokumenty;  /** @var string */  private $prilohy_adresar;  /** @var array */  private $prilohy_images;  /** @var int */  private $id_user_profiles;  /** @var string */  private $wwwDir;  /**   * @param DbTable\Dokumenty $dokumenty  */  public function __construct(DbTable\Dokumenty $dokumenty) {    $this->dokumenty = $dokumenty;	}    /**   * Formular pre pridanie prilohy a editaciu polozky.   * @param int $upload_size   * @param string $prilohy_adresar   * @param array $prilohy_images   * @return Form  */  public function create($upload_size, $prilohy_adresar, $prilohy_images, $wwwDir = "")  {    $this->prilohy_adresar = $prilohy_adresar;    $this->prilohy_images = $prilohy_images;    $this->wwwDir = $wwwDir;    $form = new Form();		$form->addProtection();    $form->addHidden("id");$form->addHidden("id_hlavne_menu");$form->addHidden("id_registracia");$form->addHidden("id_user_profiles");    $form->addHidden("id_dokumenty_kategoria");    $form->addText('nazov', 'Názov druhu', 55, 255)         ->setRequired("Prosím, zadajte názov fotky!")         ->addRule(Form::MIN_LENGTH, "Názov fotky musí mať minimálne %d znaky", 2);    $form->addSelect('druh', 'Vyber druh', [1=>'fotografia', 2=>'video', 3=>'výtvarné dielo'])         ->setPrompt('Zvoľ druh')         ->setRequired('Musíte si vybrať druh.');    $form->addUpload('priloha', 'Súbor')         ->setOption('description', sprintf('Max veľkosť súboru môže byť:  %s kB', $upload_size/1024))         ->addCondition(Form::FILLED)          ->addRule(Form::MAX_FILE_SIZE, 'Bola prekročená max veľkosť súboru %d B', $upload_size);    $form->onValidate[] = [$this, 'validateFotoPrilohaForm'];		$form->addSubmit('uloz', 'Ulož')         ->setAttribute('class', 'btn-success')         ->onClick[] = [$this, 'editFotoPrilohaFormSubmitted'];    $form->addSubmit('cancel', 'Cancel')->setValidationScope(FALSE)         ->setAttribute('class', 'btn-warning');    $renderer = $form->getRenderer();    $renderer->wrappers['error']['container'] = 'div class="row"';    $renderer->wrappers['error']['item'] = 'div class="col-md-6 col-md-offset-3 alert alert-danger"';    $renderer->wrappers['controls']['container'] = NULL;    $renderer->wrappers['pair']['container'] = 'div class="form-group row"';    $renderer->wrappers['pair']['.error'] = 'has-error';    $renderer->wrappers['control']['container'] = 'div class="col-md-6"';    $renderer->wrappers['label']['container'] = 'div class="col-lg-3 col-xs-4 col-form-label text-right"';    $renderer->wrappers['control']['container'] = 'div class="req col-lg-4 col-md-6 col-xs-8"';    $renderer->wrappers['control']['description'] = 'span class=help-block';    $renderer->wrappers['control']['errorcontainer'] = 'div class="alert alert-danger"';		return $form;	}    /**    * Vlastná validácia pre EditFotoPrilohyForm   * @param Nette\Application\UI\Form $button */  public function validateFotoPrilohaForm($button) {    $values = $button->getForm()->getValues();    if ($button->isSubmitted()->name == 'uloz') {      // Over, ci dany nazov uz je zadany.      if ($values->id == 0 && $this->dokumenty->testNazov($values->nazov, $values->id_user_profiles, $values->id_dokumenty_kategoria)) {        $button->getForm()->getComponent('nazov')->addError("Zadaný názov už existuje! Prosím použite iný.");      }      if ($values->id == 0 && $values->priloha->name == "") {        $button->getForm()->getComponent('priloha')->addError("Ak pridávate dokument, tak musíte vybrať aj súbor dokumentu!");      }    }   }    /**    * Spracovanie formulara pre pridanie a editaciu foto prilohy polozky.   * @param Nette\Forms\Controls\SubmitButton $button Data formulara    * @throws Database\DriverException   */  public function editFotoPrilohaFormSubmitted($button) {		$values = $button->getForm()->getValues(); 	//Nacitanie hodnot formulara    try {      $uloz = [ 			'id_hlavne_menu'	 	=> $values->id_hlavne_menu,			'id_user_profiles' 	=> $values->id_user_profiles,			'id_registracia'		=> $values->id_registracia,      'id_dokumenty_kategoria'=>$values->id_dokumenty_kategoria,			'zmena'							=> StrFTime("%Y-%m-%d %H:%M:%S", Time()),      'druh'              => $values->druh,      ];      $nazov = isset($values->nazov) ? $values->nazov : "";      if ($values->priloha && $values->priloha->name != "") {        $priloha_info = $this->_uploadPriloha($values);        $uloz = array_merge($uloz, [          'nazov'				=> strlen($nazov)>2 ? $nazov : $priloha_info['finalFileName'],          'spec_nazov'	=> Strings::webalize($priloha_info['finalFileName']),          'pripona'			=> $priloha_info['pripona'],          'subor'				=> $this->prilohy_adresar.$priloha_info['finalFileName'],          'thumb'				=> $priloha_info['thumb'],    		]);      } else {        $uloz = array_merge($uloz, ['nazov' => strlen($nazov)>2 ? $nazov : ""]);      }      $vysledok = $this->dokumenty->uloz($uloz, $values->id);      if (!empty($vysledok) && isset($priloha_info['is_image']) && $priloha_info['is_image']) { $this->dokumenty->oprav($vysledok['id'], ['znacka'=>'#I-'.$vysledok['id'].'#']);}		} catch (Database\DriverException $e) {			$button->addError($e->getMessage());		}  }    /**   * Upload prilohy   * @param \Nette\Http\FileUpload $values   * @return array */  private function _uploadPriloha($values) {     $pr = $this->dokumenty->find($values->id);//Zmazanie starej prílohy    if ($pr !== FALSE) {      if (is_file($pr->subor)) { unlink($this->wwwDir."/".$pr->subor);}      if (in_array(strtolower($pr->pripona), ['png', 'gif', 'jpg']) && is_file($pr->thumb)) { unlink($this->wwwDir."/".$pr->thumb);}    }    $fileName = $values->priloha->getSanitizedName();		$pi = pathinfo($fileName);		$file = $pi['filename'];		$ext = $pi['extension'];		$additionalToken = 0;		//Najdi meno suboru		if (file_exists($this->prilohy_adresar.$fileName)) {			do { $additionalToken++;			} while (file_exists($this->prilohy_adresar.$file.$additionalToken.".".$ext));    }		$finalFileName = ($additionalToken == 0) ? $fileName : $file.$additionalToken.".". $ext;		//Presun subor na finalne miesto a ak je to obrazok tak vytvor nahlad		$values->priloha->move($this->prilohy_adresar.$finalFileName);		if ($values->priloha->isImage()) {			$image_name = $this->prilohy_adresar.$finalFileName;			$thumb_name = $this->prilohy_adresar.'tb_'.$finalFileName;			$image = Image::fromFile($image_name);      $image->resize($this->prilohy_images['x'], $this->prilohy_images['y'], Image::SHRINK_ONLY);      $image->save($image_name, 80);			copy($image_name, $thumb_name);			$thumb = Image::fromFile($thumb_name);			$thumb->resize($this->prilohy_images['tx'], $this->prilohy_images['ty'], Image::SHRINK_ONLY | Image::EXACT);			$thumb->save($thumb_name, 80);		}    //Vytvorenie nazvu pre thumb    $thumb_image = isset($thumb_name) ? $this->prilohy_adresar.'tb_'.$finalFileName :                     ("www/ikonky/Free-file-icons-master/48px/".                     (is_file("www/ikonky/Free-file-icons-master/48px/".$ext.".png") ? $ext : "_page").".png");    		$uloz = [			'finalFileName' => $finalFileName,			'pripona'				=> $ext,			'thumb'					=> $thumb_image,      'is_image'      => $values->priloha->isImage()  		];    return $uloz;  }}