AttendanceList
=================

Virtual attendance lists as repository objects in ILIAS. Some of the features:
* automatically generate, delete and schedule lists
* configure a minimum attendance and automatically calculate the progress
* ILIAS learning progress supported

Installation
------------
####AttendanceList
Navigate into the ILIAS root directory, then use:

```bash
mkdir -p Customizing/global/plugins/Services/Repository/RepositoryObject
cd Customizing/global/plugins/Services/Repository/RepositoryObject
git clone https://github.com/studer-raimann/AttendanceList.git
```

####Dependencies
#####Notifications4Plugins
This plugin is required for the AttendanceList plugin to send notifications. Install the plugin from the Github Repository (Installation Guide can be found in the README.md): 

https://github.com/studer-raimann/Notifications4Plugins

After installation, two notifications must be configured in order for the AttendancePlugin to work correctly. Navigate to the ILIAS Plugin Administration, configure the Notifications4Plugins and add the following two notifications (only the 'name' is important, the rest can be changed according to your needs):

**Absence**:
* *Name:* absence
* *Title:* Absence
* *Description:* Mail which will be sent directly after a user has been defined as absent
* *Default Language:* en
* *Subject:* Absence
* *Text*: 

		Hello {{user.getFirstname}} {{user.getLastname}},
	          
	    You were absent in one of your courses:
	         
	    {{absence}}
	          
	    Please click on the link and specify a reason for your absence.

**Absence Reminder**:
* *Name:* absence_reminder
* *Title:* Absence Reminder
* *Description:* Reminder email listing all open absence reasons
* *Default Language:* en
* *Subject:* Reminder: reasons for absence still open
* *Text*: 

		Hello {{user.getFirstname}} {{user.getLastname}},
	          
	    You haven't yet specified the reason for your absence in the following courses:
	         
	    {{open_absences}}
	          
	    Please click on the link(s) and specify a reason for your absence.

### Adjustment suggestions
* Adjustment suggestions by pull requests
* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLATT
* Bug reports under https://jira.studer-raimann.ch/projects/PLATT
* For external users you can report it at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_PLATT

### ILIAS Plugin SLA
Wir lieben und leben die Philosophie von Open Source Software! Die meisten unserer Entwicklungen, welche wir im Kundenauftrag oder in Eigenleistung entwickeln, stellen wir öffentlich allen Interessierten kostenlos unter https://github.com/studer-raimann zur Verfügung.

Setzen Sie eines unserer Plugins professionell ein? Sichern Sie sich mittels SLA die termingerechte Verfügbarkeit dieses Plugins auch für die kommenden ILIAS Versionen. Informieren Sie sich hierzu unter https://studer-raimann.ch/produkte/ilias-plugins/plugin-sla.

Bitte beachten Sie, dass wir nur Institutionen, welche ein SLA abschliessen Unterstützung und Release-Pflege garantieren.
