<?php
// File mod_form.php - definisce il form usato per creare/modificare l’attività UMC (Snap! URL).

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_umc_mod_form extends moodleform_mod {

    /**
     * Definisce il form per la creazione/modifica dell’attività UMC.
     */
    public function definition() {
        $mform = $this->_form;

        // ===== NOME ATTIVITÀ =====
        $mform->addElement('text', 'name', get_string('name', 'mod_umc'), ['size' => '64']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        // ===== DESCRIZIONE (standard) =====
        $this->standard_intro_elements();

        // Nota: Snap usa spesso URL con frammento (#present:...) che PARAM_URL può "ripulire".
        // Per evitare che Moodle tronchi/alteri l'URL, usiamo PARAM_RAW_TRIMMED e lasciamo la validazione lato logica/view.
        // Se vuoi forzare la validazione URL, puoi aggiungere una regola 'regex' o 'rule' personalizzata.

        // ===== SNAP! USE =====
        $mform->addElement('text', 'urluse', get_string('urluse', 'mod_umc'), ['size' => '64']);
        $mform->setType('urluse', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('urluse', 'urluse', 'mod_umc');

        $mform->addElement('editor', 'urluse_desc_editor', get_string('urluse_desc', 'mod_umc'), null, ['maxfiles' => 0]);
        $mform->addHelpButton('urluse_desc_editor', 'urluse_desc', 'mod_umc');

        // ===== SNAP! MODIFY =====
        $mform->addElement('text', 'urlmodify', get_string('urlmodify', 'mod_umc'), ['size' => '64']);
        $mform->setType('urlmodify', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('urlmodify', 'urlmodify', 'mod_umc');

        $mform->addElement('editor', 'urlmodify_desc_editor', get_string('urlmodify_desc', 'mod_umc'), null, ['maxfiles' => 0]);
        $mform->addHelpButton('urlmodify_desc_editor', 'urlmodify_desc', 'mod_umc');

        // ===== SNAP! CREATE =====
        // Con la nuova view.php, CREATE apre la console "vuota" (senza progetto) su Snap.
        // Quindi questo campo può essere lasciato vuoto: se vuoto, in view.php verrà usato snap.html.
        // Se inserisci un URL NON Snap (es. una tua istanza/self-host), verrà usato quello.
        $mform->addElement('text', 'urlcreate', get_string('urlcreate', 'mod_umc'), ['size' => '64']);
        $mform->setType('urlcreate', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('urlcreate', 'urlcreate', 'mod_umc');

        $mform->addElement('editor', 'urlcreate_desc_editor', get_string('urlcreate_desc', 'mod_umc'), null, ['maxfiles' => 0]);
        $mform->addHelpButton('urlcreate_desc_editor', 'urlcreate_desc', 'mod_umc');

        // ===== IMPOSTAZIONI STANDARD MOODLE =====
        $this->standard_coursemodule_elements();

        // ===== PULSANTI SALVA/ANNULLA =====
        $this->add_action_buttons();
    }

    /**
     * Prepara i dati prima di visualizzare il form (es. per modifiche).
     */
    public function data_preprocessing(&$default_values) {
        if (!empty($this->current->instance)) {
            // Prepara editor "Use"
            $default_values['urluse_desc_editor'] = [
                'text' => $default_values['urluse_desc'] ?? '',
                'format' => FORMAT_HTML
            ];

            // Prepara editor "Modify"
            $default_values['urlmodify_desc_editor'] = [
                'text' => $default_values['urlmodify_desc'] ?? '',
                'format' => FORMAT_HTML
            ];

            // Prepara editor "Create"
            $default_values['urlcreate_desc_editor'] = [
                'text' => $default_values['urlcreate_desc'] ?? '',
                'format' => FORMAT_HTML
            ];
        }
    }

    /**
     * Popola correttamente i campi editor quando si ricarica il form.
     */
    public function set_data($default_values) {
        // $default_values qui è un oggetto.
        if (isset($default_values->urluse_desc)) {
            $default_values->urluse_desc_editor = [
                'text' => $default_values->urluse_desc,
                'format' => FORMAT_HTML
            ];
        }

        if (isset($default_values->urlmodify_desc)) {
            $default_values->urlmodify_desc_editor = [
                'text' => $default_values->urlmodify_desc,
                'format' => FORMAT_HTML
            ];
        }

        if (isset($default_values->urlcreate_desc)) {
            $default_values->urlcreate_desc_editor = [
                'text' => $default_values->urlcreate_desc,
                'format' => FORMAT_HTML
            ];
        }

        parent::set_data($default_values);
    }
}
