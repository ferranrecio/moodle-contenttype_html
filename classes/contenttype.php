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
 * html content type manager class
 *
 * @package    contenttype_html
 * @copyright  2020 Ferran Recio <ferran@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace contenttype_html;

use core\event\contentbank_content_viewed;
use core_contentbank\content;
use stored_file;
use context;
use stdClass;

/**
 * html content bank manager class.
 *
 * This class is the general manager for content type HTML. This class have
 * all the methods to create, delete, update and view HTML contents.
 *
 * @package    contenttype_html
 * @copyright  2020 Ferran Recio <ferran@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class contenttype extends \core_contentbank\contenttype {

    /**
     * Returns the HTML content to add to view.php visualizer.
     *
     * @param  content $content The content to be displayed.
     * @return string            HTML code to include in view.php.
     */
    public function get_view_content(content $content): string {
        // Trigger an event for viewing this content.
        $event = contentbank_content_viewed::create_from_record($content->get_content());
        $event->trigger();

        $html = '';

        // EXERCISE 1 step 3: display the HTML stored in the content configdata.
        // Solution:
        $configdata = $content->get_configdata();
        $html .= format_text($configdata);
        // ----

        return $html;
    }

    /**
     * Returns the HTML code to render the icon for html content types.
     *
     * Note that every content can define it's own icon. This mean that if your plugin
     * handle more thant one content format it is possible to assign a different icon to
     * each one of them.
     *
     * @param  content $content The content to be displayed.
     * @return string            HTML code to render the icon
     */
    public function get_icon(content $content): string {
        global $OUTPUT;
        $iconurl = $OUTPUT->image_url('f/html-64', 'moodle')->out(false);
        return $iconurl;
    }

    /**
     * Return an array of implemented features by this plugin.
     *
     * For now, the two main features are:
     * - self::CAN_UPLOAD: if the content can be created via a file
     * - self::CAN_EDIT: if the content can be edited online when is created
     *
     * This features list will increase in future versions to enable
     * embeding and other nice features.
     *
     * @return array
     */
    protected function get_implemented_features(): array {
        return [
            // Add the plugins features here.
            self::CAN_EDIT,
        ];
    }

    /**
     * Return an array of extensions this contenttype could manage.
     *
     * Note that on plugin can handle as many extensions as it wants.
     *
     * @return array
     */
    public function get_manageable_extensions(): array {
        return [
            // Add your file extensions here.
        ];
    }

    /**
     * Returns user has access capability for the content itself.
     *
     * @return bool     True if content could be accessed. False otherwise.
     */
    protected function is_access_allowed(): bool {
        return true;
    }

    /**
     * Returns the list of different html content types the user can create.
     *
     * @return array An object for each html content type:
     *     - string typename: descriptive name of the html content type.
     *     - string typeeditorparams: params required by the html editor.
     *          If no editor params are generated, the Content Bank will consider it
     *          a simple title or group of options.
     *     - url typeicon: html content type icon.
     */
    public function get_contenttype_types(): array {
        global $OUTPUT;
        $types = [
            // EXERCISE 1 step 1: get the html content types available. Add a new option to store
            // HTML content into the content bank.
            // Solution:
            (object)[
                'key' => 'html',
                'typename' => get_string('pluginname', 'contenttype_html'),
                'typeicon' => $OUTPUT->image_url('f/html-64', 'moodle')->out(false),
                'typeeditorparams' => 'template=none',
            ],
            // ----
        ];

        return $types;
    }
}
