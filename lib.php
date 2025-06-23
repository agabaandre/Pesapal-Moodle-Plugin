<?php
defined('MOODLE_INTERNAL') || die();

class enrol_pesapal_plugin extends enrol_plugin {

    public function enrol_page_hook(stdClass $instance) {
        global $CFG, $USER, $OUTPUT;

        if (isguestuser() or !isloggedin()) {
            return '';
        }

        $context = context_course::instance($instance->courseid);
        if (!has_capability('moodle/course:view', $context)) {
            return '';
        }

        // Payment form
        $cost = (float)$instance->cost;
        if ($cost < 0.01) {
            return '';
        }

        $fullname = fullname($USER);
        $useremail = $USER->email;
        $orderid = uniqid('psp_');

        $callbackurl = $CFG->wwwroot . '/enrol/pesapal/return.php';
        $ipnurl = $CFG->wwwroot . '/enrol/pesapal/ipn.php';

        $form = "
        <div class='text-center'>
            <form method='POST' action='{$CFG->wwwroot}/enrol/pesapal/return.php'>
                <input type='hidden' name='orderid' value='{$orderid}' />
                <input type='hidden' name='userid' value='{$USER->id}' />
                <input type='hidden' name='instanceid' value='{$instance->id}' />
                <button class='btn btn-primary'>Pay UGX {$cost} with Pesapal</button>
            </form>
        </div>";

        return $form;
    }

    public function get_coursemodule_info($coursemodule) {
        return null;
    }

    /**
     * Returns list of supported currencies.
     * @return array
     */
    public function get_currencies() {
        return ['KES' => 'Kenyan Shilling', 'UGX' => 'Ugandan Shilling', 'TZS' => 'Tanzanian Shilling', 'USD' => 'US Dollar'];
    }

    /**
     * Unenroll user from course.
     * @param stdClass $instance
     * @param int $userid
     * @return void
     */
    public function unenrol_user(stdClass $instance, $userid) {
        if ($instance->enrol !== 'pesapal') {
            throw new coding_exception('Invalid enrolment instance!');
        }
        parent::unenrol_user($instance, $userid);
    }

    /**
     * Can user be unenrolled by this plugin?
     *
     * @param stdClass $instance
     * @param stdClass $user
     * @return bool
     */
    public function can_unenrol_user(stdClass $instance, $user) {
        return true;
    }
}
