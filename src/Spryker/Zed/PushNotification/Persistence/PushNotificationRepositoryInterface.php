<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence;

use Generated\Shared\Transfer\PushNotificationCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationGroupCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationGroupCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer;

interface PushNotificationRepositoryInterface
{
    public function getPushNotificationCollection(
        PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
    ): PushNotificationCollectionTransfer;

    public function getPushNotificationProviderCollection(
        PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
    ): PushNotificationProviderCollectionTransfer;

    public function getPushNotificationGroupCollection(
        PushNotificationGroupCriteriaTransfer $pushNotificationGroupCriteriaTransfer
    ): PushNotificationGroupCollectionTransfer;

    public function getPushNotificationSubscriptionCollection(
        PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
    ): PushNotificationSubscriptionCollectionTransfer;

    public function pushNotificationSubscriptionExists(
        PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
    ): bool;

    public function pushNotificationExists(
        PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
    ): bool;
}
