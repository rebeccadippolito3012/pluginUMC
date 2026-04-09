<?php
// Mostra tutte le attività UMC presenti in un corso.

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/umc/lib.php');

$id = required_param('id', PARAM_INT);
$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);

require_login($course);

$PAGE->set_url('/mod/umc/index.php', ['id' => $id]);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title(format_string($course->fullname));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->navbar->add(get_string('modulenameplural', 'mod_umc'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('modulenameplural', 'mod_umc'));

$umcs = get_all_instances_in_course('umc', $course);

if (!$umcs) {
    echo $OUTPUT->notification(get_string('nouwmcactivities', 'mod_umc'));
    echo $OUTPUT->footer();
    exit;
}

$table = new html_table();
$table->head  = [get_string('name'), get_string('description')];
$table->align = ['left', 'left'];

foreach ($umcs as $umc) {
    $cm = get_coursemodule_from_instance('umc', $umc->id, $course->id, false, MUST_EXIST);

    $link = html_writer::link(
        new moodle_url('/mod/umc/view.php', ['id' => $cm->id]),
        format_string($umc->name)
    );

    $intro = format_module_intro('umc', $umc, $cm->id);
    $table->data[] = [$link, $intro];
}

echo html_writer::table($table);
echo $OUTPUT->footer();
