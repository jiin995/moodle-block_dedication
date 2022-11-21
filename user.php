<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Dedication report.
 *
 * @package    block_dedication
 * @copyright  2022 Canterbury University
 * @author     Dan Marsden
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once("{$CFG->libdir}/adminlib.php");
require_once($CFG->dirroot.'/grade/lib.php');

use core_reportbuilder\system_report_factory;
use block_dedication\local\systemreports\userreport;

$courseid = required_param('id', PARAM_INT);
$userid = required_param('userid', PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);
$context = context_course::instance($course->id);
require_capability('block/dedication:viewreports', $context);

$user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);

$PAGE->set_url('/block/dedication/user.php', ['id' => $courseid, 'userid' => $userid]);
$PAGE->set_pagelayout('report');
$PAGE->add_body_class('limitedwidth');
$PAGE->set_title("$course->fullname: ".get_string('sessionduration', 'block_dedication').": ".fullname($user));
$PAGE->set_heading($course->fullname);
$PAGE->set_context($context);

echo $OUTPUT->header();
$usercontext = context_user::instance($user->id);
$headerinfo = array('heading' => fullname($user), 'user' => $user, 'usercontext' => $usercontext);
echo $OUTPUT->context_header($headerinfo, 2);

$report = system_report_factory::create(userreport::class, context_course::instance($courseid));

echo $report->output();
echo $OUTPUT->footer();
