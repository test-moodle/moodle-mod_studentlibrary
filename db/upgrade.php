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
 * Upgrade steps for the plugintype_pluginname plugin.
 *
 * @package   plugintype_pluginname
 * @copyright 2025 shekhovtcev <plagin@geotar.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
function xmldb_studentlibrary_upgrade($oldversion): bool {
    if ($oldversion < 2025012201) {
        upgrade_plugin_savepoint(true, 2025012201, 'mod', 'studentlibrary');
    }
    return true;
}