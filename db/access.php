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
 * html content bank contenttype capabilities.
 *
 * @package    contenttype_html
 * @copyright  2020 Ferran Recio <ferran@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * All content types require 3 basic cababilities:
 * - access: which grants access to the content
 * - upload: to generate new content from a file
 * - user editor: if the user can edit online the content
 *
 * This 3 capabilities are combined with the existing content bank's capabilities
 * to handle all the possible scenarios. The Content Bank capabilities are:
 * - Access the content bank (moodle/contentbank:access)
 * - Delete any content from the content bank (moodle/contentbank:deleteanycontent)
 * - Delete content from own content bank (moodle/contentbank:deleteowncontent)
 * - Manage any content from the content bank (moodle/contentbank:manageanycontent)
 * - Manage content from own content bank (moodle/contentbank:manageowncontent)
 * - Upload new content to the content bank (moodle/contentbank:upload)
 */

$capabilities = [
    'contenttype/html:access' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
        )
    ),
    'contenttype/html:upload' => [
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
        ]
    ],
    'contenttype/html:useeditor' => [
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
        ]
    ],
];
