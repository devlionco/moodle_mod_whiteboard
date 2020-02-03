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
 * Administration settings definitions for the mod_whiteboard module.
 *
 * @package   mod_whiteboard
 * @copyright   2019 Devlion <info@devlion.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$settings->add(new admin_setting_configtext('mod_whiteboard/token',
    get_string('whiteboard_token', 'mod_whiteboard'),
    get_string('whiteboard_token_desc', 'mod_whiteboard'),
    'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7Im1heEFnZSI6NTU1NTU1NTU1NTU1NTU1LCJmaXJzdF9uYW1lIjoiQ2xpZW50IiwibGFzdF9uYW1lIjoiT25lIiwiZW1haWwiOiJjbGllbnRAZGV2bGlvbi5jbyJ9LCJpYXQiOjE1ODA2NTg4MDMsImV4cCI6NTU3MTM2MjE0MzU4fQ.j1KDJ-agIsUBDVlUQWKUMQ4YA1nUvMLqPYT7pzLydec',
    PARAM_TEXT
));

$settings->add(new admin_setting_configtext('mod_whiteboard/url',
        get_string('whiteboard_url', 'mod_whiteboard'),
    get_string('whiteboard_url_desc', 'mod_whiteboard'),
    'https://wp.eduapps.io/',
    PARAM_TEXT
));

