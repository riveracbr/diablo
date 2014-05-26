<?php
class Conekta_Webhook_AjaxController extends Mage_Core_Controller_Front_Action {
    public function listenerAction() {
				$body = @file_get_contents('php://input');
				$event = json_decode($body);
				$charge = $event->data->object;
				$line_items = $charge->details->line_items;
				$order = Mage::getModel('sales/order')->loadByIncrementId($charge->reference_id);
				if (strpos($event->type, "charge.paid") !== false && $order->getId()) {
						if ($order->getState() !== Mage_Sales_Model_Order::STATE_COMPLETE) {
							$order->addStatusToHistory(Mage_Sales_Model_Order::STATE_COMPLETE);
							$order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE);
							$order->save();
						}
				}		
	}
}
?>
