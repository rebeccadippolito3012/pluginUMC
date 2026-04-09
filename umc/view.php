<?php
// Mostra l'interfaccia studente del modulo UMC (Snap! dentro Moodle).

require_once('../../config.php');
require_once('lib.php');

$id = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('umc', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$umc    = $DB->get_record('umc', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/umc/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($umc->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$PAGE->requires->css(new moodle_url('/mod/umc/styles.css'));
$PAGE->requires->js_call_amd('mod_umc/console_flow', 'init');

// ---------------------------------------------------------------------------
// Helper: converte URL embed/snap in URL editor con editMode
// ---------------------------------------------------------------------------
function umc_to_modify_url(string $url): string {
    if (empty($url)) return '';
    $parsed = parse_url($url);
    $host   = $parsed['host'] ?? '';
    $path   = $parsed['path'] ?? '';

    if (strpos($host, 'snap.berkeley.edu') !== false && strpos($path, '/embed') !== false) {
        $q = [];
        parse_str($parsed['query'] ?? '', $q);
        $project = rawurlencode(urldecode($q['projectname'] ?? $q['project'] ?? ''));
        $user    = rawurlencode(urldecode($q['username']    ?? $q['user']    ?? ''));
        if ($project && $user) {
            return "https://snap.berkeley.edu/snap/snap.html#present:Username={$user}&ProjectName={$project}&editMode";
        }
    }
    if (strpos($host, 'snap.berkeley.edu') !== false && strpos($path, '/snap/snap.html') !== false) {
        if (strpos($url, 'editMode') === false && strpos($url, '#present:') !== false) {
            return $url . '&editMode';
        }
        return $url;
    }
    return $url;
}

// ---------------------------------------------------------------------------
// Helper: costruisce URL editor per il progetto di un utente
// ---------------------------------------------------------------------------
function umc_build_editor_url_for_user(?string $snapusername, ?string $snapproject): string {
    $snapusername = trim((string)$snapusername);
    $snapproject  = trim((string)$snapproject);
    if ($snapusername === '' || $snapproject === '') {
        return 'https://snap.berkeley.edu/snap/snap.html';
    }
    return 'https://snap.berkeley.edu/snap/snap.html#present:Username='
        . rawurlencode($snapusername) . '&ProjectName=' . rawurlencode($snapproject) . '&editMode';
}

// ---------------------------------------------------------------------------
// DB helpers – Modify
// ---------------------------------------------------------------------------
function umc_get_user_modify_project(int $umcid, int $userid) {
    global $DB;
    // Protezione: se la tabella non esiste ancora (upgrade mancante) restituisce false
    // senza generare un errore PHP che romperebbe la pagina.
    try {
        if (!$DB->get_manager()->table_exists('umc_usermodifyproject')) {
            return false;
        }
        return $DB->get_record('umc_usermodifyproject', ['umcid' => $umcid, 'userid' => $userid]);
    } catch (Exception $e) {
        return false;
    }
}
function umc_set_user_modify_project(int $umcid, int $userid, string $u, string $p): void {
    global $DB;
    $u = core_text::substr(trim($u), 0, 255);
    $p = core_text::substr(trim($p), 0, 255);
    $existing = $DB->get_record('umc_usermodifyproject', ['umcid' => $umcid, 'userid' => $userid]);
    $rec = (object)['umcid' => $umcid, 'userid' => $userid,
                    'snapusername' => $u, 'snapproject' => $p, 'timemodified' => time()];
    if ($existing) { $rec->id = $existing->id; $DB->update_record('umc_usermodifyproject', $rec); }
    else           { $DB->insert_record('umc_usermodifyproject', $rec); }
}

// ---------------------------------------------------------------------------
// DB helpers – Create
// ---------------------------------------------------------------------------
function umc_get_user_create_project(int $umcid, int $userid) {
    global $DB;
    try {
        if (!$DB->get_manager()->table_exists('umc_userproject')) {
            return false;
        }
        return $DB->get_record('umc_userproject', ['umcid' => $umcid, 'userid' => $userid]);
    } catch (Exception $e) {
        return false;
    }
}
function umc_set_user_create_project(int $umcid, int $userid, string $u, string $p): void {
    global $DB;
    $u = core_text::substr(trim($u), 0, 255);
    $p = core_text::substr(trim($p), 0, 255);
    $existing = $DB->get_record('umc_userproject', ['umcid' => $umcid, 'userid' => $userid]);
    $rec = (object)['umcid' => $umcid, 'userid' => $userid,
                    'snapusername' => $u, 'snapproject' => $p, 'timemodified' => time()];
    if ($existing) { $rec->id = $existing->id; $DB->update_record('umc_userproject', $rec); }
    else           { $DB->insert_record('umc_userproject', $rec); }
}

// ---------------------------------------------------------------------------
// POST handler: salva progetto e torna alla sezione corretta
// ---------------------------------------------------------------------------
$action = optional_param('action', '', PARAM_ALPHA);

if ($action === 'savecreate' || $action === 'savemodify') {
    require_sesskey();
    $snapusername = clean_param(optional_param('snapusername', '', PARAM_RAW_TRIMMED), PARAM_TEXT);
    $snapproject  = clean_param(optional_param('snapproject',  '', PARAM_RAW_TRIMMED), PARAM_TEXT);

    if ($action === 'savecreate') {
        umc_set_user_create_project((int)$umc->id, (int)$USER->id, $snapusername, $snapproject);
        $open = 'create';
    } else {
        umc_set_user_modify_project((int)$umc->id, (int)$USER->id, $snapusername, $snapproject);
        $open = 'modify';
    }

    // Torna alla stessa sezione con open= così il JS sa dove riaprire
    redirect(
        new moodle_url('/mod/umc/view.php', ['id' => $cm->id, 'open' => $open]),
        get_string('changessaved', 'core'),
        0
    );
}

// ---------------------------------------------------------------------------
// Verifica che le tabelle custom esistano — avvisa l'amministratore se mancano
// ---------------------------------------------------------------------------
$dbman = $DB->get_manager();
$tables_missing = [];
if (!$dbman->table_exists('umc_usermodifyproject')) $tables_missing[] = 'umc_usermodifyproject';
if (!$dbman->table_exists('umc_userproject'))       $tables_missing[] = 'umc_userproject';

// ---------------------------------------------------------------------------
// Calcola URL per gli iframe
// ---------------------------------------------------------------------------

// USE: progetto docente (sola lettura / eseguibile, senza editMode)
// Non passiamo per umc_to_modify_url perché quella aggiunge &editMode.
// Costruiamo un URL #present: pulito oppure usiamo l'URL così com'è.
$useurl = (function() use ($umc): string {
    $url = $umc->urluse ?? '';
    if (empty($url)) return '';
    $parsed = parse_url($url);
    $host   = $parsed['host'] ?? '';
    $path   = $parsed['path'] ?? '';
    // URL embed snap.berkeley.edu/embed → converti in #present: senza editMode
    if (strpos($host, 'snap.berkeley.edu') !== false && strpos($path, '/embed') !== false) {
        $q = [];
        parse_str($parsed['query'] ?? '', $q);
        $project = rawurlencode(urldecode($q['projectname'] ?? $q['project'] ?? ''));
        $user    = rawurlencode(urldecode($q['username']    ?? $q['user']    ?? ''));
        if ($project && $user) {
            return "https://snap.berkeley.edu/snap/snap.html#present:Username={$user}&ProjectName={$project}";
        }
    }
    // URL già in formato #present: → rimuovi editMode se presente
    if (strpos($host, 'snap.berkeley.edu') !== false) {
        return preg_replace('/[&?]editMode/', '', $url);
    }
    return $url;
})();

// MODIFY: se lo studente ha già salvato → suo progetto, altrimenti quello del docente
$usermod        = umc_get_user_modify_project((int)$umc->id, (int)$USER->id);
$currentmoduser = $usermod->snapusername ?? '';
$currentmodproj = $usermod->snapproject  ?? '';
$ismodifysaved  = ($currentmoduser !== '' && $currentmodproj !== '');
$modifyurl = $ismodifysaved
    ? umc_build_editor_url_for_user($currentmoduser, $currentmodproj)
    : umc_to_modify_url($umc->urlmodify ?: ($umc->urluse ?: ''));

// CREATE: se lo studente ha già salvato → suo progetto, altrimenti editor vuoto
$usercreate        = umc_get_user_create_project((int)$umc->id, (int)$USER->id);
$currentcreateuser = $usercreate->snapusername ?? '';
$currentcreateproj = $usercreate->snapproject  ?? '';
$iscreatesaved     = ($currentcreateuser !== '' && $currentcreateproj !== '');
$createurl = $iscreatesaved
    ? umc_build_editor_url_for_user($currentcreateuser, $currentcreateproj)
    : 'https://snap.berkeley.edu/snap/snap.html';

// ---------------------------------------------------------------------------
// Stringhe help box – Prima parte (come salvare su Snap Cloud + rendere pubblico)
// ---------------------------------------------------------------------------
function umc_render_help_snap(string $color): void {
    echo html_writer::start_div('umc-help-box');
    echo html_writer::tag('h4', '☁️ ' . get_string('snap_help_title', 'mod_umc'));
    echo html_writer::start_tag('ol');
    echo html_writer::tag('li', get_string('snap_help_step1', 'mod_umc'));
    echo html_writer::tag('li', get_string('snap_help_step2', 'mod_umc'));
    echo html_writer::tag('li', get_string('snap_help_step3', 'mod_umc'));
    echo html_writer::end_tag('ol');
    echo html_writer::end_div();
}

// ---------------------------------------------------------------------------
// Stringhe help box – Seconda parte (come registrare il progetto in Moodle)
// ---------------------------------------------------------------------------
function umc_render_help_moodle(): void {
    echo html_writer::start_div('umc-help-box');
    echo html_writer::tag('h4', '💾 ' . get_string('moodle_help_title', 'mod_umc'));
    echo html_writer::start_tag('ol');
    echo html_writer::tag('li', get_string('snap_help_step4', 'mod_umc'));
    echo html_writer::tag('li', get_string('snap_help_step5', 'mod_umc'));
    echo html_writer::end_tag('ol');
    echo html_writer::end_div();
}

// ---------------------------------------------------------------------------
// OUTPUT
// ---------------------------------------------------------------------------
echo $OUTPUT->header();



// ── Stepper ──────────────────────────────────────────────────────────────────
echo html_writer::start_tag('ul', ['class' => 'umc-stepper']);
echo html_writer::tag('li', '🔴 Use',      ['data-umc-step' => 'use',    'class' => 'is-active']);
echo html_writer::tag('li', '🔵 Modify', ['data-umc-step' => 'modify']);
echo html_writer::tag('li', '🟢 Create',     ['data-umc-step' => 'create']);
echo html_writer::end_tag('ul');

// ============================================================================
// USE
// ============================================================================
echo html_writer::start_div('', ['id' => 'console-use']);
echo html_writer::tag('h3', '🔴 Use');

if (!empty($umc->urluse_desc)) {
    echo html_writer::div(format_text($umc->urluse_desc, FORMAT_HTML), 'snap-description');
}

if (!empty($useurl)) {
    echo html_writer::tag('iframe', '', [
        'src'           => $useurl,
        'width'         => '100%',
        'height'        => '600px',
        'style'         => 'border:1px solid #ccc; margin-top:10px;',
        'allowfullscreen' => 'true',
        'sandbox'       => 'allow-scripts allow-same-origin allow-forms allow-popups allow-modals',
    ]);
}

echo html_writer::start_div('umc-navigation');
echo html_writer::tag('button', get_string('goto_modify', 'mod_umc'), ['type' => 'button', 'data-umc-next' => 'modify']);
echo html_writer::end_div();
echo html_writer::end_div(); // #console-use

// ============================================================================
// MODIFY
// ============================================================================
echo html_writer::start_div('', [
    'id'    => 'console-modify',
    
    'data-saved'        => $ismodifysaved ? '1' : '0',
    'data-snapusername' => s($currentmoduser),
    'data-snapproject'  => s($currentmodproj),
]);

echo html_writer::tag('h3', '🔵 Modify');

if (!empty($umc->urlmodify_desc)) {
    echo html_writer::div(format_text($umc->urlmodify_desc, FORMAT_HTML), 'snap-description');
}

// ── Legenda parte 1: come salvare su Snap Cloud ───────────────────────────
umc_render_help_snap('blue');

// ── iframe MODIFY ─────────────────────────────────────────────────────────
echo html_writer::tag('iframe', '', [
    'id'            => 'umc-modify-iframe',
    'src'           => $modifyurl,
    'width'         => '100%',
    'height'        => '600px',
    'style'         => 'border:1px solid #ccc; margin-top:10px;',
    'allowfullscreen' => 'true',
    'sandbox'       => 'allow-scripts allow-same-origin allow-forms allow-popups allow-modals',
]);

// ── Legenda parte 2: come salvare su Moodle ───────────────────────────────
umc_render_help_moodle();

// ── Form salvataggio MODIFY ───────────────────────────────────────────────
$formurl = new moodle_url('/mod/umc/view.php', ['id' => $cm->id]);

echo html_writer::start_tag('form', [
    'method' => 'post',
    'action' => $formurl,
    'id'     => 'umc-modify-form',
]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'action',  'value' => 'savemodify']);

echo html_writer::start_div('', ['style' => 'display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end;']);

echo html_writer::start_div('', ['style' => 'min-width:260px;']);
echo html_writer::tag('label', 'Username Snap!', ['for' => 'mod_snapusername', 'style' => 'display:block; font-weight:600;']);
echo html_writer::empty_tag('input', [
    'type'     => 'text',
    'id'       => 'mod_snapusername',
    'name'     => 'snapusername',
    'value'    => s($currentmoduser),
    'style'    => 'width:100%; padding:8px;',
    'required' => 'required',
]);
echo html_writer::end_div();

echo html_writer::start_div('', ['style' => 'min-width:260px;']);
echo html_writer::tag('label', 'Nome progetto Snap!', ['for' => 'mod_snapproject', 'style' => 'display:block; font-weight:600;']);
echo html_writer::empty_tag('input', [
    'type'     => 'text',
    'id'       => 'mod_snapproject',
    'name'     => 'snapproject',
    'value'    => s($currentmodproj),
    'style'    => 'width:100%; padding:8px;',
    'required' => 'required',
]);
echo html_writer::end_div();

echo html_writer::end_div();

// ── Avviso obbligatorio: rendi pubblico su Snap! ──────────────────────────
echo html_writer::start_div('', ['style' => 'margin-top:20px; padding:16px 18px; background:#fff3cd; border:2px solid #e6a817; border-radius:8px;']);
echo html_writer::tag('p', get_string('publicwarning', 'mod_umc'), ['style' => 'margin:0 0 12px 0; font-size:0.97em;']);
echo html_writer::start_div('', ['style' => 'display:flex; align-items:center; gap:10px;']);
echo html_writer::empty_tag('input', [
    'type'  => 'checkbox',
    'id'    => 'umc-modify-public-confirm',
    'style' => 'width:18px; height:18px; cursor:pointer;',
]);
echo html_writer::tag('label',
    get_string('publicconfirm', 'mod_umc'),
    ['for' => 'umc-modify-public-confirm', 'style' => 'cursor:pointer; font-weight:600;']
);
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('', ['style' => 'padding-top:16px;']);
echo html_writer::empty_tag('input', array_merge([
    'type'  => 'submit',
    'id'    => 'umc-modify-submit',
    'value' => get_string('save_modify_project', 'mod_umc'),
    'style' => 'padding:10px 14px;',
], $ismodifysaved ? [] : [
    'disabled' => 'disabled',
    'style'    => 'padding:10px 14px; opacity:0.45; cursor:not-allowed;',
]));
echo html_writer::end_div();

echo html_writer::end_tag('form');

// ── Navigazione MODIFY ───────────────────────────────────────────────────
echo html_writer::start_div('umc-navigation');
echo html_writer::tag('button', get_string('back_to_use', 'mod_umc'), [
    'type'         => 'button',
    'class'        => 'secondary',
    'data-umc-prev' => 'use',
]);
echo html_writer::tag('button', get_string('goto_create', 'mod_umc'), [
    'type'               => 'button',
    'id'                 => 'umc-goto-create',
    'data-umc-next'      => 'create',
    'data-requires-save' => 'modify',
]);
echo html_writer::end_div();
echo html_writer::end_div(); // #console-modify

// ============================================================================
// CREATE
// ============================================================================
echo html_writer::start_div('', [
    'id'    => 'console-create',
    
    'data-saved'        => $iscreatesaved ? '1' : '0',
    'data-snapusername' => s($currentcreateuser),
    'data-snapproject'  => s($currentcreateproj),
    'data-locked'       => $ismodifysaved ? '0' : '1',
]);

echo html_writer::tag('h3', '🟢 Create');

if (!empty($umc->urlcreate_desc)) {
    echo html_writer::div(format_text($umc->urlcreate_desc, FORMAT_HTML), 'snap-description');
}

// ── Legenda parte 1: come salvare su Snap Cloud ───────────────────────────
umc_render_help_snap('green');

// ── iframe CREATE ─────────────────────────────────────────────────────────
echo html_writer::tag('iframe', '', [
    'id'            => 'umc-create-iframe',
    'src'           => $createurl,
    'width'         => '100%',
    'height'        => '600px',
    'style'         => 'border:1px solid #ccc; margin-top:10px;',
    'allowfullscreen' => 'true',
    'sandbox'       => 'allow-scripts allow-same-origin allow-forms allow-popups allow-modals',
]);

// ── Legenda parte 2: come salvare su Moodle ───────────────────────────────
umc_render_help_moodle();

// ── Form salvataggio CREATE ───────────────────────────────────────────────
echo html_writer::start_tag('form', [
    'method' => 'post',
    'action' => $formurl,
    'id'     => 'umc-create-form',
]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'action',  'value' => 'savecreate']);

echo html_writer::start_div('', ['style' => 'display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end;']);

echo html_writer::start_div('', ['style' => 'min-width:260px;']);
echo html_writer::tag('label', 'Username Snap!', ['for' => 'snapusername', 'style' => 'display:block; font-weight:600;']);
echo html_writer::empty_tag('input', [
    'type'     => 'text',
    'id'       => 'snapusername',
    'name'     => 'snapusername',
    'value'    => s($currentcreateuser),
    'style'    => 'width:100%; padding:8px;',
    'required' => 'required',
]);
echo html_writer::end_div();

echo html_writer::start_div('', ['style' => 'min-width:260px;']);
echo html_writer::tag('label', 'Nome progetto Snap!', ['for' => 'snapproject', 'style' => 'display:block; font-weight:600;']);
echo html_writer::empty_tag('input', [
    'type'     => 'text',
    'id'       => 'snapproject',
    'name'     => 'snapproject',
    'value'    => s($currentcreateproj),
    'style'    => 'width:100%; padding:8px;',
    'required' => 'required',
]);
echo html_writer::end_div();

echo html_writer::end_div();

// ── Avviso obbligatorio: rendi pubblico su Snap! (CREATE) ─────────────────
echo html_writer::start_div('', ['style' => 'margin-top:20px; padding:16px 18px; background:#fff3cd; border:2px solid #e6a817; border-radius:8px;']);
echo html_writer::tag('p', get_string('publicwarning', 'mod_umc'), ['style' => 'margin:0 0 12px 0; font-size:0.97em;']);
echo html_writer::start_div('', ['style' => 'display:flex; align-items:center; gap:10px;']);
echo html_writer::empty_tag('input', [
    'type'  => 'checkbox',
    'id'    => 'umc-create-public-confirm',
    'style' => 'width:18px; height:18px; cursor:pointer;',
]);
echo html_writer::tag('label',
    get_string('publicconfirm', 'mod_umc'),
    ['for' => 'umc-create-public-confirm', 'style' => 'cursor:pointer; font-weight:600;']
);
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('', ['style' => 'padding-top:16px;']);
echo html_writer::empty_tag('input', array_merge([
    'type'  => 'submit',
    'id'    => 'umc-create-submit',
    'value' => get_string('save_create_project', 'mod_umc'),
    'style' => 'padding:10px 14px;',
], $iscreatesaved ? [] : [
    'disabled' => 'disabled',
    'style'    => 'padding:10px 14px; opacity:0.45; cursor:not-allowed;',
]));
echo html_writer::end_div();

echo html_writer::end_tag('form');

// ── Navigazione CREATE ───────────────────────────────────────────────────
echo html_writer::start_div('umc-navigation');
echo html_writer::tag('button', get_string('back_to_modify', 'mod_umc'), [
    'type'          => 'button',
    'class'         => 'secondary',
    'data-umc-prev' => 'modify',
]);
echo html_writer::end_div();
echo html_writer::end_div(); // #console-create

echo $OUTPUT->footer();
