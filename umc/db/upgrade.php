<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade steps for mod_umc.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_umc_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // ---------------------------------------------------------------------
    // 2025061334:
    // - Add umc_userproject table (per-user Snap project for Create console)
    // ---------------------------------------------------------------------
    if ($oldversion < 2025061334) {

        $table = new xmldb_table('umc_userproject');

        // Fields
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('umcid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('snapusername', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '');
        $table->add_field('snapproject', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Keys
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('umc_fk', XMLDB_KEY_FOREIGN, ['umcid'], 'umc', ['id']);
        $table->add_key('user_fk', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Indexes
        // Lasciamo SOLO l'unico (umcid, userid). Niente indici singoli: evitano collisioni con le FK.
        $table->add_index('umcuser_uix', XMLDB_INDEX_UNIQUE, ['umcid', 'userid']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_mod_savepoint(true, 2025061334, 'umc');
    }

    // ---------------------------------------------------------------------
    // 2025061335:
    // - Add umc_usermodifyproject table (per-user Snap project for Modify console)
    // ---------------------------------------------------------------------
    if ($oldversion < 2025061335) {

        $table = new xmldb_table('umc_usermodifyproject');

        // Fields
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('umcid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('snapusername', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '');
        $table->add_field('snapproject', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Keys
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('umc_fk', XMLDB_KEY_FOREIGN, ['umcid'], 'umc', ['id']);
        $table->add_key('user_fk', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Indexes
        // Lasciamo SOLO l'unico (umcid, userid). Niente indici singoli: evitano collisioni con le FK.
        $table->add_index('umcuser_uix', XMLDB_INDEX_UNIQUE, ['umcid', 'userid']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_mod_savepoint(true, 2025061335, 'umc');
    }

    // ---------------------------------------------------------------------
    // 2025061337:
    // - Sicurezza: ricrea le tabelle se mancanti per qualsiasi motivo
    //   (utile se l'upgrade precedente è stato saltato o fallito)
    // ---------------------------------------------------------------------
    if ($oldversion < 2025061337) {

        // umc_userproject
        $table = new xmldb_table('umc_userproject');
        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('umcid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('snapusername', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '');
            $table->add_field('snapproject', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '');
            $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table->add_key('umc_fk', XMLDB_KEY_FOREIGN, ['umcid'], 'umc', ['id']);
            $table->add_key('user_fk', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
            $table->add_index('umcuser_uix', XMLDB_INDEX_UNIQUE, ['umcid', 'userid']);
            $dbman->create_table($table);
        }

        // umc_usermodifyproject
        $table2 = new xmldb_table('umc_usermodifyproject');
        if (!$dbman->table_exists($table2)) {
            $table2->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table2->add_field('umcid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table2->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table2->add_field('snapusername', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '');
            $table2->add_field('snapproject', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '');
            $table2->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table2->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table2->add_key('umc_fk', XMLDB_KEY_FOREIGN, ['umcid'], 'umc', ['id']);
            $table2->add_key('user_fk', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
            $table2->add_index('umcuser_uix', XMLDB_INDEX_UNIQUE, ['umcid', 'userid']);
            $dbman->create_table($table2);
        }

        upgrade_mod_savepoint(true, 2025061337, 'umc');
    }

    return true;
}
