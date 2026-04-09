<?php
// Questo file definisce le informazioni di versione del plugin UMC per Moodle.

/**
 * Dettagli di versione per il modulo UMC.
 *
 * @package   mod_umc                        // Nome completo del plugin (modulo)
 * @copyright 2025 Il tuo nome o ente        // Copyright personalizzabile
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 o successiva
 */

// Impedisce l'accesso diretto a questo file
defined('MOODLE_INTERNAL') || die();

// Numero di versione del plugin (es. 2025061000 → [anno][mese][giorno][rev])
$plugin->version   = 2026032810;

// Numero minimo di versione di Moodle richiesta per usare questo plugin
$plugin->requires  = 2022112800; // Moodle 4.1 (release: 2022-11-28)

// Nome del componente (formato: mod_nomeplugin)
$plugin->component = 'mod_umc';

// Numero della versione del database del plugin (usato in upgrade.php)
$plugin->cron      = 0;

// Modo in cui il plugin supporta la cancellazione dei dati personali (GDPR API)
$plugin->privacy   = 'metadata';
