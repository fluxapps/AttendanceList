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

## Contributing :purple_heart:
Please create pull requests :fire:

## Adjustment suggestions / bug reporting :feet:
Please ...
1. ... ask us for a Service Level Agreement: support@fluxlabs.ch :kissing_heart:
2. [Read and create issues](https://github.com/fluxapps/AttendanceList/issues)
