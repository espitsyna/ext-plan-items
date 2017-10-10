<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class Modules_PlanItemsExample_SimpleList extends pm_Hook_SimpleList
{
    public function isEnabled($controller, $action, $activeList)
    {
        return $controller === 'customer-service-plan' && $action === 'list'
        || $controller === 'subscription' && $action === 'list'
        || $controller === 'customer' && $action === 'subscriptions'
        || $controller === 'reseller' && $action === 'subscriptions';
    }

    public function getData($controller, $action, $activeList, $data)
    {
        $registeredItems = Modules_PlanItemsExample_Config::getPlanItems();

        foreach ($data as &$row) {
            $row['extPlanItemsExamplePlanItem'] = 'customer-service-plan' == $controller
                ? $this->_getByPlan($row, $registeredItems)
                : $this->_getBySubscription($row, $registeredItems);
        }
        return $data;
    }


    public function getColumns($controller, $action, $activeList)
    {
        return [
            'extPlanItemsExamplePlanItem' => [
                'title' => 'Plan Item',
                'noEscape' => true,
                'insertAfter' => -1,
            ]
        ];
    }
    /**
     * @param array $data
     * @param array $registeredItems
     * @return string
     */
    private function _getByPlan($data, $registeredItems)
    {
        if ('domain_addon' == $data['planType']) {
            // Plan Items are not applicable to addons
            return '—';
        }

        $plan = new pm_Plan($data['id']);
        $planItems = $plan->getPlanItems();
        return $this->_getLink(
            ($planItem = reset($planItems)) ? $registeredItems[$planItem] : 'None',
            '/admin/customer-service-plan/edit/id/' . $plan->getId()
        );
    }

    /**
     * @param array $data
     * @param array $registeredItems
     * @return string
     */
    private function _getBySubscription($data, $registeredItems)
    {
        $domain = new pm_Domain($data['id']);
        $planItems = $domain->getPlanItems();
        return $this->_getLink(
            ($planItem = reset($planItems)) ? $registeredItems[$planItem] : 'None',
            '/admin/subscription/edit/id/' . $domain->getId()
        );
    }

    /**
     * @param string $title
     * @param string $href
     * @return string
     */
    private function _getLink($title, $href)
    {
        return '<span><span class="tooltipData">'
            . 'In order to include plan item follow the link and refer to "Additional Services" tab.'
            . "</span><a href='{$href}'>{$title}</a></span>";
    }
}
