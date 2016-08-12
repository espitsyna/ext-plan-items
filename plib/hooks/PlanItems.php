<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

class Modules_PlanItemsExample_PlanItems extends pm_Hook_PlanItems
{
    public function getPlanItems()
    {
        return [
            'starter' => 'Starter',
            'lite' => 'Lite',
            'premium' => 'Premium',
        ];
    }
}
