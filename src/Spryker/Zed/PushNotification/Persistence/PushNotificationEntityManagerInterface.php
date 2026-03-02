<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence;

use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;

interface PushNotificationEntityManagerInterface
{
    public function createPushNotificationSubscription(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer;

    public function createPushNotification(
        PushNotificationTransfer $pushNotificationTransfer
    ): PushNotificationTransfer;

    public function createPushNotificationProvider(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer;

    public function createPushNotificationGroup(
        PushNotificationGroupTransfer $pushNotificationGroupTransfer
    ): PushNotificationGroupTransfer;

    public function createPushNotificationSubscriptionDeliverLog(
        PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer
    ): PushNotificationSubscriptionDeliveryLogTransfer;

    public function updatePushNotificationProvider(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer;

    /**
     * @param list<string> $pushNotificationProviderUuids
     *
     * @return void
     */
    public function deletePushNotificationProviders(
        array $pushNotificationProviderUuids
    ): void;

    public function deletePushNotificationSubscription(PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer): void;
}
