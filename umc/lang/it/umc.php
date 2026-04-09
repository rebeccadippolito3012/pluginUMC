<?php
// Stringhe in italiano per il modulo UMC.

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'UMC';
$string['modulename'] = 'UMC';
$string['modulenameplural'] = 'UMC';
$string['modulename_help'] =
    'L\'attività UMC consente ai docenti di fornire agli studenti tre link alla console Snap! (Use, Modify, Create) '
  . 'con descrizioni personalizzate. Ogni console Snap! si apre incorporata in Moodle.';

// Capabilities
$string['umc:addinstance'] = 'Aggiungi una nuova attività UMC';
$string['umc:view'] = 'Visualizza attività UMC';
$string['umc:editcontent'] = 'Modifica contenuto UMC';

// Campi generali
$string['name'] = 'Nome dell\'attività';
$string['name_help'] = 'Inserisci il nome di questa attività UMC.';

$string['intro'] = 'Descrizione';
$string['intro_help'] = 'Introduzione generale o istruzioni per l\'attività.';

// -----------------------------
// URL console Snap! (docente)
// -----------------------------
$string['urluse'] = 'URL Snap! Use';
$string['urluse_help'] =
    'Incolla l\'URL Snap! per la console "Use". Si aprirà incorporata in Moodle.';
$string['urluse_desc'] = 'Istruzioni console Use';
$string['urluse_desc_help'] =
    'Fornisci indicazioni agli studenti su come utilizzare la console "Use".';

$string['urlmodify'] = 'URL Snap! Modify';
$string['urlmodify_help'] =
    'Incolla l\'URL Snap! per la console "Modify". Si aprirà incorporata in Moodle.';
$string['urlmodify_desc'] = 'Istruzioni console Modify';
$string['urlmodify_desc_help'] =
    'Fornisci indicazioni agli studenti su come utilizzare la console "Modify".';

$string['urlcreate'] = 'URL Snap! Create';
$string['urlcreate_help'] =
    'Incolla l\'URL Snap! per la console "Create". Si aprirà incorporata in Moodle.';
$string['urlcreate_desc'] = 'Istruzioni console Create';
$string['urlcreate_desc_help'] =
    'Fornisci indicazioni agli studenti su come utilizzare la console "Create".';

// Elenchi / errori
$string['nouwmcactivities'] = 'Non ci sono attività UMC in questo corso.';
$string['activitynotfound'] = 'Attività UMC non trovata';
$string['invalidcmid'] = 'ID modulo corso non valido';

// Etichette console
$string['snapuse'] = 'Snap! Use';
$string['snapmodify'] = 'Snap! Modify';
$string['snapcreate'] = 'Snap! Create';

// -----------------------------
// Flusso console (vista studente)
// -----------------------------
$string['console_use'] = 'Use';
$string['console_modify'] = 'Modify';
$string['console_create'] = 'Create';

// Pulsanti di navigazione
$string['goto_modify'] = 'Vai a Modify →';
$string['goto_create'] = 'Vai a Create →';
$string['back_to_use'] = '← Torna a Use';
$string['back_to_modify'] = '← Torna a Modify';

// -----------------------------
// Progetto CREATE per utente
// -----------------------------
$string['create_section'] = 'Create';
$string['snap_username'] = 'Nome utente Snap';
$string['snap_project'] = 'Nome progetto Snap (cloud)';
$string['save_create_project'] = 'Salva il progetto Create';

$string['create_help'] =
    'Dopo aver terminato il lavoro in Snap!, salva il progetto nel Cloud di Snap!. '
  . 'Poi inserisci qui il tuo nome utente Snap e il nome ESATTO del progetto. '
  . 'Quando riaprirai questa pagina, la console Create caricherà automaticamente il tuo progetto.';

$string['create_saved'] = 'Progetto Create salvato con successo.';

// -----------------------------
// Progetto MODIFY per utente
// -----------------------------
$string['modify_section'] = 'Modify';
$string['save_modify_project'] = 'Salva il progetto Modify';

$string['modify_help'] =
    'Inizialmente vedi il progetto del docente. Dopo averlo modificato in Snap! e salvato nel Cloud di Snap!, '
  . 'inserisci qui il tuo nome utente Snap e il nome ESATTO del progetto. '
  . 'La prossima volta che apri questa pagina, la console Modify caricherà il TUO progetto modificato.';

$string['modify_saved'] = 'Progetto Modify salvato con successo.';

// -----------------------------
// Istruzioni salvataggio Snap! Cloud (box aiuto studente)
// -----------------------------
$string['snap_help_title'] = 'Come salvare il progetto sul Cloud di Snap!';
$string['moodle_help_title'] = 'Come registrare il progetto in Moodle';

$string['snap_help_step1'] =
    'Clicca sull\'icona della nuvoletta in Snap! e accedi con le tue credenziali Snap!.';

$string['snap_help_step2'] =
    'Quando hai finito, clicca su <strong>File</strong> (in alto a sinistra) e seleziona <strong>Salva con nome…</strong>.';

$string['snap_help_step3'] =
    'Salva il progetto sul Cloud di Snap! assegnandogli un nome che ricorderai.';

$string['snap_help_step4'] =
    'Inserisci lo stesso nome utente Snap e il nome del progetto nei campi qui sotto.';

$string['snap_help_step5'] =
    'Clicca su <strong>Salva progetto</strong> in Moodle prima di lasciare la pagina.';

// -----------------------------
// Avviso checkbox rendi pubblico
// -----------------------------
$string['publicwarning'] =
    '⚠️ <strong>Prima di salvare su Moodle devi rendere pubblico il progetto su Snap!</strong><br>'
  . 'Vai su <strong>File → I miei progetti</strong>, trova il progetto, clicca sui tre puntini (...) e scegli <strong>Pubblica</strong>.';

$string['publicconfirm'] =
    '✅ Ho aperto <strong>I miei progetti</strong> su Snap! e ho impostato il progetto come <strong>Pubblico</strong>';
