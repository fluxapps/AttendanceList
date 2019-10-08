## Usage

#### Object creation:

* AttendanceList objects can only be created in courses (course participants are used for the lists)
* When creating an object, the start and end of the event and the available weekdays can already be specified.
* When creating, empty attendance lists are created for all the dates specified here (one list always stands for one date). 
* The empty lists are needed to calculate the minimum attendance (so that 100% is not reached after filling in the first list).

#### Tab Settings/Settings:

* Minimum Presence: Percentage of lists/data for which a participant needs a 'Present' status in order to make progress.
* Start of event: From this date, attendance lists can be created and filled in.
* End of event: Attendance lists can be created and filled in up to this date.
* Weekdays: On these weekdays empty lists will be created (if checkbox below is selected) 
* Create lists: If selected, empty lists are created for the selected weekdays from the beginning to the end of the event after saving.
* Delete lists: If selected, empty lists outside the defined event period will be deleted after saving.

#### Tab Content/Content:

* The attendance list of the current day is displayed here.
* An attendance list lists all course members of the course above.
* For each participant the status 'Present' or 'Absent' can be selected (depending on the plugin configuration also "Not relevant").
* After saving, a 'Reason for absence' appears for all 'Absent' (see below, Absences).

#### Tab Overview

* This tab is divided into two subtabs: Users and Lists
* The user overview lists all course participants and provides information about them.
* The 'Percentage present / minimum attendance' column appears green or red, depending on the minimum attendance reached or not yet reached (and thus learning progress).
* If you click on a user name, a detailed view of this user appears with all its Presence statuses.
* The list overview shows all available lists (also empty lists, i.e. not yet filled in).
* The lists can be opened directly via the date
* New lists for a specific date can also be created directly via a button.

#### Absences

* If a participant is absent, a 'reason for absence' appears in the list.
* The available justifications can be defined in the plugin administration
* The reason can either be filled in by the lecturer or by the participant himself - the participant will receive an email notification with a link
* As long as a reason is not filled in, the participant cannot pass the learning progress
* In the plugin configuration, you can also specify the interval at which a reminder should be sent about unfilled absence reasons (e.g. every 7 days).
