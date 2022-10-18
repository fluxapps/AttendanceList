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
git clone https://github.com/fluxapps/AttendanceList.git
```

Please also install and enable [AttendanceListCron](https://github.com/fluxapps/AttendanceListCron).

## ILIAS 7 core ilCtrl patch 

For make this plugin work with ilCtrl in ILIAS 7, you may need to patch the core, before you update the plugin (At your own risk) 

Start at the plugin directory 

```shell
./vendor/srag/dic/bin/ilias7_core_apply_ilctrl_patch.sh
```

## Rebuild & Maintenance

fluxlabs ag, support@fluxlabs.ch

This project needs to be rebuilt before it can be maintained.

Are you interested in a rebuild and would you like to participate?
Take advantage of the crowdfunding opportunity under [discussions](https://github.com/fluxapps/AttendanceList/discussions/1).
