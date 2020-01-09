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
 * Define all the backup steps that will be used by the backup_whiteboard_activity_task
 *
 * @package     mod_whiteboard
 * @copyright   2019 Devlion <info@devlion.co>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Structure step to backup one whiteboard activity
 */
class backup_whiteboard_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // Define each element separated.
        $whiteboard = new backup_nested_element(
            'whiteboard',
            ['id'],
            [
                'name',
                'intro',
                'introformat',
                'type',
                'usermodified',
                'timecreated',
                'timemodified'
            ]
        );

        // Define sources.
        $whiteboard->set_source_table('whiteboard', ['id' => backup::VAR_ACTIVITYID]);

        // Define file annotations.
        $whiteboard->annotate_files('mod_whiteboard', 'intro', null);

        // Return the root element (whiteboard), wrapped into standard activity structure.
        return $this->prepare_activity_structure($whiteboard);
    }
}
