<?phpnamespace Language_support;/** * Texty pre podporu jazykov pre UserPresenter * Posledna zmena(last change): 04.06.2015 *  * @author Ing. Peter VOJTECH ml. <petak23@gmail.com> * @copyright  Copyright (c) 2012 - 2015 Ing. Peter VOJTECH ml. * @license * @link       http://petak23.echo-msz.eu * @version 1.0.1 */class User extends lang_supp_main {  /** @var array - texty pre UserPresenter */  protected $texty = array(    'sk'=>array( //Slovencina      'h2_default' => 'Prihlásenie',      'h2_forgottenPassword' => 'Zabudnuté heslo',      'h2_registracia' => 'Registrácia',      'h2_resetPassword' => 'Reset hesla',      'default_user_logedIn' => '<div class="stav st_info">Už ste prihlásený!</div>',      'forgot_pass_txt' => 'Zadajte emailovú adresu, ktorá je pripojená k Vášmu účtu a na ktorú bude odoslaný email pre reset hesla.',      'registracia_not_enabled' => 'Na tomto webe registrácia nie je dovolená!',      'registracia_username_duble' => 'Prosím, zvolte si iné prihlasovacie meno, lebo toto je už použité iným užívateľom.',      'registracia_email_duble' => 'K email-u %s už existuje účet. Ak si nepamätáte prístupové heslo požiadajte o neho cez        položku <a href="%s" title="Zabudnuté heslo">Zabudnuté heslo</a> .',      'RegistraciaForm_meno' => 'Meno:',      'RegistraciaForm_meno_ar' => 'Meno musí mať spoň %d znakov!',      'RegistraciaForm_meno_sr' => 'Meno musí byť zadané!',      'RegistraciaForm_priezvisko' => 'Priezvisko:',      'RegistraciaForm_priezvisko_ar' => 'Priezvisko musí mať spoň %d znakov!',      'RegistraciaForm_priezvisko_sr' => 'Priezvisko musí byť zadané!',      'RegistraciaForm_username' => 'Prihlasovacie meno:',      'RegistraciaForm_username_ar' => 'Prihlasovacie meno musí mať spoň %d znakov!',      'RegistraciaForm_username_sr' => 'Prihlasovacie meno musí byť zadané!',      'Form_email' => 'E-mailová adresa:',      'Form_email_ar' => 'Musíte zadať e-mailovú adresu v správnom tvare!(napr.: niekto@niekde.sk)',      'Form_email_sr' => 'E-mail musí byť zadaný!',      'Form_email_ph' => 'zadajte e-mail',      'RegistraciaForm_heslo' => 'Prihlasovacie heslo:',      'RegistraciaForm_heslo_ar' => 'Prihlasovacie heslo musí mať aspoň %d znakov!',      'RegistraciaForm_heslo_sr' => 'Heslo musí byť zadané!',      'RegistraciaForm_heslo2' => 'Over heslo:',      'RegistraciaForm_heslo2_ar' => 'Zadané heslá (prihlasovacie a overené) sa musia zhodovať.',      'RegistraciaForm_heslo2_sr' => 'Overované heslo musí byť zadané!',      'RegistraciaForm_pohl' => 'Pohlavie:',      'RegistraciaForm_uloz' => 'Zaregistruj',      'RegistraciaForm_m' => 'Muž',      'RegistraciaForm_z' => 'Žena',      'reset_pass_txt1' => 'Chystáte sa nastaviť nové heslo k Vášmu účtu. ',      'reset_pass_email' => 'Emailová adresa vášho účtu je: %s',      'reset_pass_txt2' => 'Zadajte a potvrďte opätovným zadaním vaše nové heslo. ',      'reset_pass_err1' => 'Pokúsili ste sa spustiť akciu s nesprávnym parametrom! Akcia bola ukončená!',      'reset_pass_err2' => 'Pokúsili ste sa zresetovať heslo účtu! Vaša požiadavka nemôže byť akceptovaná, lebo daný úžívateľ           o resetovanie hesla nepožiadal, alebo je táto požiadavka už neaktuálna!',      'reset_pass_err3' => 'Pri pokuse o resetovanie hesla účtu došlo k chybe. Akciu nebolo možné uskutočniť!           Skúste neskôr znovu... Ak sa to bude opakovať kontaktujte prosím administrátora(e-mail na spodku stránky)!',      'ResetPasswordForm_new_heslo' => 'Nové heslo:',      'ResetPasswordForm_new_heslo_sr' => 'Nové heslo musí byť zadané!',      'ResetPasswordForm_new_heslo2' => 'Potvrď nové heslo:',      'ResetPasswordForm_new_heslo2_ar' => 'Zadané nové heslá sa musia zhodovať!',      'ResetPasswordForm_new_heslo2_sr' => 'Potvrdené nové heslo musí byť zadané!',      'register_aktivacia' => 'Aktivácia účtu',      'register_email_ok' => 'Na Vami zadanú emailovú adresu bola odoslaná správa pre aktiváciu Vášho účtu. Aktiváciu je nutné dokončiť najneskôr do 7 dní. Inak bude vaše konto zrušené bez upozornenia a budete sa musieť zaregistrovať znovu!!!           Ďalej prosím postupujte podľa tejto správy!',      'send_email_err' => 'Došlo k chybe pri odosielaní e-mailu. Skúste neskôr znovu...<br />',      'register_save_err' => 'Došlo k chybe a údaje o užívateľovi sa neuložili. Skúste neskôr znovu...',      'activate_ok' => 'Váš účet je teraz aktívny! Môžete sa prihlásiť...',      'activate_err1' => 'Došlo k chybe pri aktivácii Vášho účtu. Skúste neskôr znovu...<br />',      'activate_err2' => 'Pri pokuse o aktiváciu účtu došlo k chybe. Aktiváciu účtu nebolo možné uskutočniť!           Skúste neskôr znovu... Ak sa to bude opakovať kontaktujte prosím administrátora(e-mail na spodku stránky)!',      'ForgottenPasswordForm_uloz' => 'Pošli',      'forgot_pass' => 'Zabudnuté heslo',      'forgot_pass_email_ok' => 'Na Vami zadanú emailovú adresu bola odoslaná správa pre resetovanie Vášho hesla.           Ďalej prosím postupujte podľa tejto správy!',      'forgot_pass_user_err' => 'Došlo k chybe. K danej emailovej adrese %s neexzistuje užívateľ!',      'reset_pass_hesla_err' => 'Nové heslá sa nezhodujú!',      'reset_pass_ok' => 'Vaše heslo bolo v úspešne zmenené. Môžete ho použiť pri prihlásení sa...',      'reset_pass_err' => 'Došlo k chybe a heslo nebolo zmenené. Skúste neskôr znovu...<br />',      //E-mail template      'email_nefunkcny_odkaz' => 'Odkaz nefunguje? Skopírujte nasledujúci odkaz do adresového riadnu Vášho prehliadača:',      'email_pozdrav' => 'Príjemný deň praje',      'email_activate_nadpis' => 'Aktivácia účtu po registrácii na %s !',      'email_activate_txt' => 'Ďakujeme, že ste sa zaregistrovali na našej stránke. Pre ukončenie registrácie je potrebné Váš účet aktivovať. Aktiváciu vykonáte kliknutím na nasledujúci odkaz:',      'email_reset_nadpis' => 'Reset hesla na %s !',      'email_reset_txt' => ' Na našej stránke ste požiadali o resetovanie hesla. Pre uskutčnenie tejto akcie je potrebné kliknúť na nasledujúci odkaz:',    ),    'en'=>array( //Anglictina    ),  );}