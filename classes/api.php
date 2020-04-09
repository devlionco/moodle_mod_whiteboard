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
 * The mod_whiteboard whiteboard API.
 *
 * @package     mod_whiteboard
 * @copyright   2019 Devlion <info@devlion.co>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_whiteboard;

defined('MOODLE_INTERNAL') || die();

class api {

    private $baseurl;
    private $customertoken;

    public function __construct(string $customertoken, $baseurl) {
        $this->customertoken = $customertoken;
        $this->baseurl = $baseurl;
    }

    /**
     * @param string $name
     * @param string $description
     * @param array $additionaldata - should contain activityId data
     * @param array $copyfrom - should contain activityId data which you want to copy from
     *
     * Example: $additionaldata = [766]; 766 - activity_id
     *          $copyfrom = [765]; 765 - activity_id
     *
     * @return array
     */
    public function createpage(string $name, string $description, array $additionaldata = [], array $copyfrom = []) {
        $response = $this->request(
            $this->baseurl . 'whiteboard/create',
                [
                    'name' => $name,
                    'description' => $description,
                    'additional_data' => $additionaldata,
                    'copy_from' => $copyfrom
                ]
        );

        return $response['data'];
    }

    /**
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param array $additionaldata - should contains activity_id
     *
     * Example: $additionaldata = [766]; 766 - activity_id
     *
     * @return array
     */
    public function registeruser(string $email, string $firstname, string $lastname, array $additionaldata = []) {
        $response = $this->request(
                $this->baseurl . 'users/register',
                [
                    'email' => $email,
                    'first_name' => static::slug($firstname),
                    'last_name' => $lastname,
                    'additional_data' => $additionaldata
                ]
        );

        return $response['data'];
    }

    /**
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param array $additionaldata - should contains activity_id
     *
     * Example: $additionaldata = [766]; 766 - activity_id
     *
     * @return string
     */
    public function geturlforuser(string $email, string $firstname, string $lastname, array $additionaldata = []) {
        $user = $this->registeruser($email, $firstname, $lastname, $additionaldata);

        return $user['url'];
    }

    /**
     * @param string $urlparam
     *
     * @return string
     */
    public function getsessionurl(string $urlparam, string $whiteboard) {
        return $this->baseurl . "users/set-session/?token={$this->getcustomertoken()}&url_param={$urlparam}&board={$whiteboard}";
    }

    /**
     * @param string $url
     * @param array $body
     *
     * @return array
     */
    private function request(string $url, array $body): array {

        $curl = new \curl();

        $headers = [
            "Authorization: Bearer {$this->getcustomertoken()}",
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $curl->setHeader($headers);

        $response = $curl->post($url, http_build_query($body, '', '&'));

        if ($curl->error) {
            throw new \moodle_exception('curlerror', 'mod_whiteboard', '', $curl->error);
        }

        $decoded = json_decode($response, true);

        if ($decoded['status'] == false) {
            throw new \moodle_exception('curlerror', 'mod_whiteboard', '', $decoded['message']);
        }

        return $decoded;
    }

    /**
     * Function to get private customer token
     *
     * @return string
     */
    public function getcustomertoken() {

        return $this->customertoken;
    }

    /**
     * Function to replace slashes with spaces
     *
     * @param $str
     * @return string
     */
    public static function slug(string $str) {

        return trim(str_replace('/', ' ', $str));
    }
}
