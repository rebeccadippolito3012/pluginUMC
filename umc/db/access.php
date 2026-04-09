<?php
// Questo file definisce le capability (permessi) del modulo UMC per Moodle.

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    // Permette agli utenti con ruolo docente o manager di aggiungere l'attività UMC in un corso.
    'mod/umc:addinstance' => [
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
        'clonepermissionsfrom' => 'moodle/course:manageactivities',
    ],

    // Permette di visualizzare l'attività UMC e i suoi contenuti.
    'mod/umc:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'guest' => CAP_ALLOW,
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
    ],

    // Permette di caricare/modificare contenuti all'interno dell'attività UMC (URL).
    'mod/umc:editcontent' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
    ],
];
