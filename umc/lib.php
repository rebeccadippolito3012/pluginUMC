<?php
// File lib.php per il modulo UMC.

defined('MOODLE_INTERNAL') || die(); // Previene l'accesso diretto al file.

/**
 * Indica a Moodle quali funzionalità supporta il modulo UMC.
 */
function umc_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_ARCHETYPE:          return MOD_ARCHETYPE_RESOURCE; // Tipo "risorsa".
        case FEATURE_MOD_INTRO:              return true;
        case FEATURE_SHOW_DESCRIPTION:       return true;
        case FEATURE_BACKUP_MOODLE2:         return false;
        case FEATURE_MOD_PURPOSE:            return MOD_PURPOSE_CONTENT;
        default: return null;
    }
}

/**
 * Crea una nuova istanza dell'attività UMC.
 */
function umc_add_instance($data) {
    global $DB;

    $data->timecreated = time();
    $data->timemodified = time();
    $data->revision = 1;

    // Estrai i testi dagli editor
    if (isset($data->urluse_desc_editor)) {
        $data->urluse_desc = $data->urluse_desc_editor['text'];
        unset($data->urluse_desc_editor);
    }
    
    if (isset($data->urlmodify_desc_editor)) {
        $data->urlmodify_desc = $data->urlmodify_desc_editor['text'];
        unset($data->urlmodify_desc_editor);
    }
    
    if (isset($data->urlcreate_desc_editor)) {
        $data->urlcreate_desc = $data->urlcreate_desc_editor['text'];
        unset($data->urlcreate_desc_editor);
    }

    return $DB->insert_record('umc', $data);
}

/**
 * Aggiorna un'istanza esistente dell'attività UMC.
 */
function umc_update_instance($data) {
    global $DB;

    $data->timemodified = time();
    $data->revision++;
    $data->id = $data->instance;

    // Estrai i testi dagli editor
    if (isset($data->urluse_desc_editor)) {
        $data->urluse_desc = $data->urluse_desc_editor['text'];
        unset($data->urluse_desc_editor);
    }
    
    if (isset($data->urlmodify_desc_editor)) {
        $data->urlmodify_desc = $data->urlmodify_desc_editor['text'];
        unset($data->urlmodify_desc_editor);
    }
    
    if (isset($data->urlcreate_desc_editor)) {
        $data->urlcreate_desc = $data->urlcreate_desc_editor['text'];
        unset($data->urlcreate_desc_editor);
    }

    $DB->update_record('umc', $data);

    return true;
}

/**
 * Elimina un'istanza dell'attività UMC.
 */
function umc_delete_instance($id) {
    global $DB;

    if (!$record = $DB->get_record('umc', ['id' => $id])) {
        return false;
    }

    return $DB->delete_records('umc', ['id' => $id]);
}

/**
 * Restituisce l'icona del plugin per l'interfaccia Moodle.
 */
function umc_get_icon() {
    global $OUTPUT;
    $iconfile = file_exists(__DIR__ . '/pix/icon.svg') ? 'icon.svg' : 'icon.png';
    return $OUTPUT->pix_icon($iconfile, 'UMC', 'umc', ['class'=>'icon']);
}

/**
 * Restituisce il logo principale del plugin.
 */
function umc_get_logo($width = 200, $height = 200) {
    global $OUTPUT;

    $url = $OUTPUT->pix_url('incon', 'umc'); // Richiama pix/incon.png

    return html_writer::empty_tag('img', [
        'src' => $url,
        'alt' => 'Logo UMC',
        'width' => $width,
        'height' => $height,
        'style' => "max-width:100%;height:auto;"
    ]);
}