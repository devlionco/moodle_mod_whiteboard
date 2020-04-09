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
 * Library of interface functions and constants.
 *
 * @package     mod_whiteboard
 * @copyright   2019 Devlion <info@devlion.co>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use \mod_whiteboard\api;

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function whiteboard_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_whiteboard into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param whiteboard_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function whiteboard_add_instance($moduleinstance, $mform = null) {

    $config = get_config('mod_whiteboard');

    $api = new api($config->token, $config->url);

    $data = [
            'name' => api::slug($moduleinstance->name),
            'description' => $moduleinstance->name,
            'additional_data' => [$moduleinstance->coursemodule]
    ];

    $oldmoduleid = [];
    if (!empty($moduleinstance->oldmoduleid)) {
        $oldmoduleid = [$moduleinstance->oldmoduleid];
    }

    // Create whiteboard.
    $api->createpage($data['name'], $data['description'], $data['additional_data'], $oldmoduleid);

    $record = (object) [
            'course' => $moduleinstance->course,
            'name' => $moduleinstance->name,
            'type' => $moduleinstance->type,
            'intro' => $moduleinstance->intro ?? '',
            'introformat' => $moduleinstance->introformat ?? 0,
    ];

    $whiteboard = new \mod_whiteboard\whiteboard(0, $record);
    $whiteboard->create();

    return $whiteboard->get('id');
}

/**
 * Updates an instance of the mod_whiteboard in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param whiteboard_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function whiteboard_update_instance($moduleinstance, $mform = null) {

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    $record = (object) [
            'course' => $moduleinstance->course,
            'name' => $moduleinstance->name,
            'intro' => $moduleinstance->intro ?? '',
            'type' => $moduleinstance->type,
            'introformat' => $moduleinstance->introformat ?? 0,
    ];

    $whiteboard = new \mod_whiteboard\whiteboard($moduleinstance->instance);
    $whiteboard->from_record($record);

    return $whiteboard->update();
}

/**
 * Removes an instance of the mod_whiteboard from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function whiteboard_delete_instance($id) {

    try {
        $whiteboard = new \mod_whiteboard\whiteboard($id);
        $whiteboard->delete();
    } catch (\Exception $e) {
        return false;
    }

    return true;
}
