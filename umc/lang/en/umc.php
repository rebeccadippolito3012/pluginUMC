<?php
// English language strings for the UMC module.

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'UMC';
$string['modulename'] = 'UMC';
$string['modulenameplural'] = 'UMCs';
$string['modulename_help'] =
    'The UMC activity allows teachers to provide students with three Snap! console links (Use, Modify, Create) '
  . 'with custom descriptions. Each Snap! console opens embedded within Moodle.';

// Capabilities
$string['umc:addinstance'] = 'Add a new UMC activity';
$string['umc:view'] = 'View UMC activity';
$string['umc:editcontent'] = 'Edit UMC content';

// General fields
$string['name'] = 'Activity name';
$string['name_help'] = 'Enter the name of this UMC activity.';

$string['intro'] = 'Description';
$string['intro_help'] = 'General introduction or instructions for the activity.';

// -----------------------------
// Snap console URLs (teacher)
// -----------------------------
$string['urluse'] = 'Snap! Use URL';
$string['urluse_help'] =
    'Paste the Snap! URL for the "Use" console. This will open embedded within Moodle.';
$string['urluse_desc'] = 'Use console instructions';
$string['urluse_desc_help'] =
    'Provide guidance for students on how to use the "Use" console.';

$string['urlmodify'] = 'Snap! Modify URL';
$string['urlmodify_help'] =
    'Paste the Snap! URL for the "Modify" console. This will open embedded within Moodle.';
$string['urlmodify_desc'] = 'Modify console instructions';
$string['urlmodify_desc_help'] =
    'Provide guidance for students on how to use the "Modify" console.';

$string['urlcreate'] = 'Snap! Create URL';
$string['urlcreate_help'] =
    'Paste the Snap! URL for the "Create" console. This will open embedded within Moodle.';
$string['urlcreate_desc'] = 'Create console instructions';
$string['urlcreate_desc_help'] =
    'Provide guidance for students on how to use the "Create" console.';

// Lists / errors
$string['nouwmcactivities'] = 'There are no UMC activities in this course.';
$string['activitynotfound'] = 'UMC activity not found';
$string['invalidcmid'] = 'Invalid course module ID';

// Console labels
$string['snapuse'] = 'Snap! Use';
$string['snapmodify'] = 'Snap! Modify';
$string['snapcreate'] = 'Snap! Create';

// -----------------------------
// Console flow (student view)
// -----------------------------
$string['console_use'] = 'Use';
$string['console_modify'] = 'Modify';
$string['console_create'] = 'Create';

// Navigation buttons
$string['goto_modify'] = 'Go to Modify →';
$string['goto_create'] = 'Go to Create →';
$string['back_to_use'] = '← Back to Use';
$string['back_to_modify'] = '← Back to Modify';

// -----------------------------
// Per-user CREATE project
// -----------------------------
$string['create_section'] = 'Create';
$string['snap_username'] = 'Snap username';
$string['snap_project'] = 'Snap project name (cloud)';
$string['save_create_project'] = 'Save Create project';

$string['create_help'] =
    'After finishing your work in Snap!, save your project in the Snap! Cloud. '
  . 'Then enter your Snap username and the EXACT project name here. '
  . 'When you reopen this page, the Create console will automatically load your project.';

$string['create_saved'] = 'Create project saved successfully.';

// -----------------------------
// Per-user MODIFY project
// -----------------------------
$string['modify_section'] = 'Modify';
$string['save_modify_project'] = 'Save Modify project';

$string['modify_help'] =
    'You first see the teacher\'s project. After modifying it in Snap! and saving it in the Snap! Cloud, '
  . 'enter your Snap username and the EXACT project name here. '
  . 'Next time you open this page, the Modify console will load YOUR modified project.';

$string['modify_saved'] = 'Modify project saved successfully.';

// -----------------------------
// Snap! Cloud save instructions (student help box)
// -----------------------------
$string['snap_help_title'] = 'How to save your project on Snap! Cloud';
$string['moodle_help_title'] = 'How to register your project in Moodle';

$string['snap_help_step1'] =
    'Click the cloud icon in Snap! and log in with your Snap! account.';

$string['snap_help_step2'] =
    'When you are done, click <strong>File</strong> (top-left) and choose <strong>Save as…</strong>.';

$string['snap_help_step3'] =
    'Save the project on Snap! Cloud with a name you can remember.';

$string['snap_help_step4'] =
    'Enter the same Snap username and project name in the fields below.';

$string['snap_help_step5'] =
    'Click <strong>Save project</strong> in Moodle before leaving the page.';

// -----------------------------
// Public warning checkbox
// -----------------------------
$string['publicwarning'] =
    '⚠️ <strong>Before saving on Moodle you must make the project public on Snap!</strong><br>'
  . 'Go to <strong>File → My Projects</strong>, find the project, click the three dots (...) and choose <strong>Publish</strong>.';

$string['publicconfirm'] =
    '✅ I have opened <strong>My Projects</strong> on Snap! and set the project as <strong>Public</strong>';
