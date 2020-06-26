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
 * Provides the class that defines the form for the html authoring tool.
 *
 * @package    contenttype_html
 * @copyright  2020 Ferran Recio <ferran@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace contenttype_html\form;

use contenttype_html\content;
use contenttype_html\contenttype;
use core_contentbank\form\edit_content;
use context_user;
use context;
use stdClass;
use moodle_exception;

/**
 * Defines the form for editing an html content.
 *
 * This file is the integration between a content type editor and the content
 * bank creation form.
 *
 * @copyright 2020 Ferran Recio <ferran@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class editor extends edit_content {

    /**
     * Defines the form fields.
     */
    protected function definition() {
        global $DB, $OUTPUT;

        $mform = $this->_form;
        // $context = context::instance_by_id($this->contextid, MUST_EXIST);

        // This methos adds the save and cancel buttons.
        $this->add_action_buttons();

        // Id of the content to edit (null if it's creation).
        $id = $this->_customdata['id'] ?? null;
        $contextid = $this->_customdata['contextid'];

        $fullcontent = '';
        $name = '';

        // EXERCISE 1 step 4: Add code to edit existing content

        // Content name.
        $mform->addElement('text', 'name', get_string('name'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required');
        $mform->setDefault('name', $name);

        // Add HTML editor using the data information.
        $context = context::instance_by_id($contextid, MUST_EXIST);
        $label = get_string('fullcontent', 'contenttype_html');
        $mform->addElement('editor', 'fullcontent', $label, ['rows' => 20]);
        $mform->setType('fullcontent', PARAM_RAW); // no XSS prevention here, users must be trusted
        $mform->addRule('fullcontent', get_string('required'), 'required', null, 'client');
        $mform->setDefault('fullcontent', ['text' => $fullcontent]);

        $this->add_action_buttons();
    }

    /**
     * Modify or create an html content from the form data.
     *
     * @param stdClass $data Form data to create or modify an html content.
     *
     * @return int The id of the edited or created content.
     */
    public function save_content(stdClass $data): int {
        global $DB, $USER;

        if (empty($data->id)) {
            // Create a new content.
            $context = context::instance_by_id($data->contextid, MUST_EXIST);
            $contenttype = new contenttype($context);
            $record = new stdClass();
            $content = $contenttype->create_content($record);
        } else {
            // Update current content.
            $record = $DB->get_record('contentbank_content', ['id' => $data->id]);
            $content = new content($record);
        }

        // Update content.
        $content->set_name($data->name);
        $content->set_configdata($data->fullcontent);
        $content->update_content();

        return $content->get_id();
    }

    /**
     * Used to reformat the data from the editor component
     *
     * @return stdClass
     */
    public function get_data() {

        $data = parent::get_data();

        if ($data !== null and isset($data->fullcontent)) {
            $data->contentformat = $data->fullcontent['format'] ?? null;
            $data->fullcontent = $data->fullcontent['text'];
        }

        return $data;
    }
}
