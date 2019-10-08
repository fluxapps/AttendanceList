AttendanceList
=================

Virtual attendance lists as repository objects in ILIAS. Some of the features:
* automatically generate, delete and schedule lists
* configure a minimum attendance and automatically calculate the progress
* ILIAS learning progress supported

Installation
------------
#### AttendanceList
Navigate into the ILIAS root directory, then use:

```bash
mkdir -p Customizing/global/plugins/Services/Repository/RepositoryObject
cd Customizing/global/plugins/Services/Repository/RepositoryObject
git clone https://github.com/studer-raimann/AttendanceList.git
```

#### Dependencies

#### Cronjob
The AttendanceList-Plugin includes a cronjob which sends reminder emails to all course participants which still need to fill out the reason for one or multiple of their absences.

To install the cronjob, open or create the file /etc/cron.d/ilias on your server and add the following line:
```bash
* 7 * * * www-data /usr/bin/php /var/www/ilias/Customizing/global/plugins/Services/Repository/RepositoryObject/AttendanceList/cron.php $USER $PASSWORD $ILIAS_CLIENT_ID > /dev/null
```
Change the $USER and $PASSWORD to the login and password of an ilias user and $ILIAS_CLIENT_ID to your ilias client id.

### Adjustment suggestions
* Adjustment suggestions by pull requests
* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLATT
* Bug reports under https://jira.studer-raimann.ch/projects/PLATT
* For external users you can report it at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_PLATT

### ILIAS Plugin SLA
Wir lieben und leben die Philosophie von Open Source Software! Die meisten unserer Entwicklungen, welche wir im Kundenauftrag oder in Eigenleistung entwickeln, stellen wir öffentlich allen Interessierten kostenlos unter https://github.com/studer-raimann zur Verfügung.

Setzen Sie eines unserer Plugins professionell ein? Sichern Sie sich mittels SLA die termingerechte Verfügbarkeit dieses Plugins auch für die kommenden ILIAS Versionen. Informieren Sie sich hierzu unter https://studer-raimann.ch/produkte/ilias-plugins/plugin-sla.

Bitte beachten Sie, dass wir nur Institutionen, welche ein SLA abschliessen Unterstützung und Release-Pflege garantieren.
