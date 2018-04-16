# This file is part of Moodle - http://moodle.org/
#
# Moodle is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# Moodle is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
#
# Tests for course resource and activity editing features.
#
# @package    theme_cass
# @copyright  2015 Guy Thomas <gthomas@moodlerooms.com>
# @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later


@theme @theme_cass
Feature: When the moodle theme is set to Cass, teachers edit assets without entering edit mode.

  Background:
   Given the following config values are set as admin:
      | theme | cass |
      | defaulthomepage | 0 |
    And the following "courses" exist:
      | fullname | shortname | category | format |
      | Course 1 | C1        | 0        | topics |
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | teacher2 | Teacher2  | 1        | teacher2@example.com |
      | student1 | Student   | 1        | student1@example.com |

    And the following "course enrolments" exist:
      | user     | course | role           |
      | admin    | C1     | editingteacher |
      | teacher1 | C1     | editingteacher |
      | teacher2 | C1     | teacher        |
      | student1 | C1     | student        |

  @javascript
  Scenario: Student cannot access edit actions.
    Given the following "activities" exist:
      | activity | course | idnumber | name            | intro           | section | assignsubmission_onlinetext_enabled |
      | assign   | C1     | assign1  | Test assignment | Test assignment | 1       | 1                                   |
    And I log in as "student1" (theme_cass)
    And I am on the course main page for "C1"
    And I follow "Topic 1"
   Then ".cass-activity[data-type='Assignment']" "css_element" should exist
    And "div.dropdown cass-edit-more-dropdown" "css_element" should not exist

  @javascript
  Scenario: In read mode, non-editing teacher can see teacher's actions.
  Given the following "activities" exist:
      | activity | course | idnumber | name            | intro           | section | assignsubmission_onlinetext_enabled |
      | assign   | C1     | assign1  | Test assignment | Test assignment | 1       | 1                                   |
    And I log in as "teacher2" (theme_cass)
    And I am on the course main page for "C1"
    And I follow "Topic 1"
   Then "#section-1" "css_element" should exist
    And ".cass-activity[data-type='Assignment']" "css_element" should exist
    And "div.dropdown cass-edit-more-dropdown" "css_element" should not exist

  @javascript
  Scenario: In read mode, teacher hides then shows activity.
  Given the following "activities" exist:
      | activity | course | idnumber | name            | intro           | section | assignsubmission_onlinetext_enabled |
      | assign   | C1     | assign1  | Test assignment | Test assignment | 1       | 1                                   |
    And I log in as "teacher1" (theme_cass)
    And I am on the course main page for "C1"
    And I follow "Topic 1"
   Then "#section-1" "css_element" should exist
    And ".cass-activity[data-type='Assignment']" "css_element" should exist
    And I click on ".cass-activity[data-type='Assignment'] a.cass-edit-asset-more" "css_element"
    And I click on ".cass-activity[data-type='Assignment'] a.js_cass_hide" "css_element"
   Then I wait until ".cass-activity[data-type='Assignment'].draft" "css_element" exists
    And I click on ".cass-activity[data-type='Assignment'] a.cass-edit-asset-more" "css_element"
    And I click on ".cass-activity[data-type='Assignment'] a.js_cass_show" "css_element"
   Then I wait until ".cass-activity[data-type='Assignment'].draft" "css_element" does not exist

  @javascript
  Scenario: In read mode, teacher hides then shows resource.
  Given I log in as "teacher1" (theme_cass)
    And I am on the course main page for "C1"
    And I follow "Topic 1"
   Then "#section-1" "css_element" should exist
    And "#cass-drop-file-1" "css_element" should exist
    And I upload file "test_text_file.txt" to section 1
    Then ".cass-resource[data-type='text']" "css_element" should exist
    And ".cass-resource[data-type='text'].draft" "css_element" should not exist
    And I click on ".cass-resource[data-type='text'] a.cass-edit-asset-more" "css_element"
    And I click on ".cass-resource[data-type='text'] a.js_cass_hide" "css_element"
   Then I wait until ".cass-resource[data-type='text'].draft" "css_element" exists
    And I click on ".cass-resource[data-type='text'] a.cass-edit-asset-more" "css_element"
    And I click on ".cass-resource[data-type='text'] a.js_cass_show" "css_element"
   Then I wait until ".cass-resource[data-type='text'].draft" "css_element" does not exist

  @javascript
  Scenario: In read mode, teacher duplicates activity.
  Given the following "activities" exist:
      | activity | course | idnumber | name            | intro           | section | assignsubmission_onlinetext_enabled |
      | assign   | C1     | assign1  | Test assignment | Test assignment | 1       | 1                                   |
    And I log in as "teacher1" (theme_cass)
    And I am on the course main page for "C1"
    And I follow "Topic 1"
   Then "#section-1" "css_element" should exist
    And ".cass-activity[data-type='Assignment']" "css_element" should exist
    And ".cass-activity[data-type='Assignment'] + .cass-activity[data-type='Assignment']" "css_element" should not exist
    And I click on ".cass-activity[data-type='Assignment'] a.cass-edit-asset-more" "css_element"
    And I click on ".cass-activity[data-type='Assignment'] a.js_cass_duplicate" "css_element"
   Then I wait until ".cass-activity[data-type='Assignment'] + .cass-activity[data-type='Assignment']" "css_element" exists

  @javascript
  Scenario: In read mode, teacher duplicates resource.
  Given I log in as "teacher1" (theme_cass)
    And I am on the course main page for "C1"
    And I follow "Topic 1"
   Then "#section-1" "css_element" should exist
    And "#cass-drop-file-1" "css_element" should exist
    When I upload file "test_text_file.txt" to section 1
    Then ".cass-resource[data-type='text']" "css_element" should exist
    And ".cass-resource[data-type='text'] + .cass-resource[data-type='text']" "css_element" should not exist
    And I click on ".cass-resource[data-type='text'] a.cass-edit-asset-more" "css_element"
    And I click on ".cass-resource[data-type='text'] a.js_cass_duplicate" "css_element"
   Then I wait until ".cass-resource[data-type='text'] + .cass-resource[data-type='text']" "css_element" exists

