<?php

namespace srag\Notifications4Plugin\AttendanceList\UI;

use ilConfirmationGUI;
use ilSelectInputGUI;
use ilUtil;
use srag\CustomInputGUIs\AttendanceList\PropertyFormGUI\PropertyFormGUI;
use srag\DIC\AttendanceList\DICTrait;
use srag\DIC\AttendanceList\Plugin\PluginInterface;
use srag\Notifications4Plugin\AttendanceList\Ctrl\CtrlInterface;
use srag\Notifications4Plugin\AttendanceList\Notification\Notification;
use srag\Notifications4Plugin\AttendanceList\Utils\Notifications4PluginTrait;

/**
 * Class UI
 *
 * @package srag\Notifications4Plugin\AttendanceList\UI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class UI implements UIInterface {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var UIInterface
	 */
	protected static $instance = null;


	/**
	 * @return UIInterface
	 */
	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @var CtrlInterface
	 */
	protected $ctrl_class;
	/**
	 * @var PluginInterface|null
	 */
	protected $plugin = null;


	/**
	 * UI constructor
	 */
	private function __construct() {

	}


	/**
	 * @inheritdoc
	 */
	public function getPlugin() {
		return $this->plugin;
	}


	/**
	 * @inheritdoc
	 */
	public function withPlugin(PluginInterface $plugin) {
		$this->plugin = $plugin;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function withCtrlClass(CtrlInterface $ctrl_class) {
		$this->ctrl_class = $ctrl_class;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function notificationDeleteConfirmation(Notification $notification) {
		$confirmation = new ilConfirmationGUI();

		self::dic()->ctrl()->setParameter($this->ctrl_class, CtrlInterface::GET_PARAM, $notification->getId());
		$confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this->ctrl_class));
		self::dic()->ctrl()->setParameter($this->ctrl_class, CtrlInterface::GET_PARAM, null);

		$confirmation->setHeaderText($this->getPlugin()
			->translate("delete_notification_confirm", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ $notification->getTitle() ]));

		$confirmation->addItem(CtrlInterface::GET_PARAM, $notification->getId(), $notification->getTitle());

		$confirmation->setConfirm($this->getPlugin()
			->translate("delete", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN), CtrlInterface::CMD_DELETE_NOTIFICATION);
		$confirmation->setCancel($this->getPlugin()
			->translate("cancel", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN), CtrlInterface::CMD_LIST_NOTIFICATIONS);

		return $confirmation;
	}


	/**
	 * @inheritdoc
	 */
	public function notificationForm(Notification $notification) {
		ilUtil::sendInfo(self::output()->getHTML([
			$this->getPlugin()->translate("placeholder_types_info", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ CtrlInterface::NAME ]),
			"<br><br>",
			self::dic()->ui()->factory()->listing()->descriptive($this->ctrl_class->getPlaceholderTypes())
		]));

		$form = new NotificationFormGUI($this->getPlugin(), $this->ctrl_class, $notification);

		return $form;
	}


	/**
	 * @inheritdoc
	 */
	public function notificationTable($parent_cmd, callable $getNotifications, callable $getNotificationsCount) {
		$table = new NotificationsTableGUI($this->getPlugin(), $this->ctrl_class, $parent_cmd, $getNotifications, $getNotificationsCount);

		return $table;
	}


	/**
	 * @inheritdoc
	 */
	public function templateSelection(array $notifications, $post_key, $required = true) {
		return [
			$post_key => [
				PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
				PropertyFormGUI::PROPERTY_REQUIRED => $required,
				PropertyFormGUI::PROPERTY_OPTIONS => [ "" => "" ] + $notifications,
				"setTitle" => $this->getPlugin()
					->translate("template_selection", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN, [ CtrlInterface::NAME ]),
				"setInfo" => ""
			]
		];
	}
}
