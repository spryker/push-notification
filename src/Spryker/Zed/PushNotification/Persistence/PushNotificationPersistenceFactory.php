<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence;

use Orm\Zed\PushNotification\Persistence\SpyPushNotificationGroupQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLogQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface;
use Spryker\Zed\PushNotification\Persistence\Mapper\PaginationMapper;
use Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationGroupMapper;
use Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationMapper;
use Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationProviderMapper;
use Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationSubscriptionDeliveryLogMapper;
use Spryker\Zed\PushNotification\Persistence\Mapper\PushNotificationSubscriptionMapper;
use Spryker\Zed\PushNotification\PushNotificationDependencyProvider;

/**
 * @method \Spryker\Zed\PushNotification\PushNotificationConfig getConfig()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface getEntityManager()
 */
class PushNotificationPersistenceFactory extends AbstractPersistenceFactory
{
    public function createPushNotificationGroupQuery(): SpyPushNotificationGroupQuery
    {
        return SpyPushNotificationGroupQuery::create();
    }

    public function createPushNotificationProviderQuery(): SpyPushNotificationProviderQuery
    {
        return SpyPushNotificationProviderQuery::create();
    }

    public function createPushNotificationSubscriptionQuery(): SpyPushNotificationSubscriptionQuery
    {
        return SpyPushNotificationSubscriptionQuery::create();
    }

    public function createPushNotificationSubscriptionDeliveryLogQuery(): SpyPushNotificationSubscriptionDeliveryLogQuery
    {
        return SpyPushNotificationSubscriptionDeliveryLogQuery::create();
    }

    public function createPushNotificationGroupMapper(): PushNotificationGroupMapper
    {
        return new PushNotificationGroupMapper();
    }

    public function createPushNotificationSubscriptionMapper(): PushNotificationSubscriptionMapper
    {
        return new PushNotificationSubscriptionMapper($this->getUtilEncodingService());
    }

    public function createPushNotificationMapper(): PushNotificationMapper
    {
        return new PushNotificationMapper(
            $this->getUtilEncodingService(),
            $this->createPushNotificationGroupMapper(),
            $this->createPushNotificationProviderMapper(),
        );
    }

    public function createPushNotificationProviderMapper(): PushNotificationProviderMapper
    {
        return new PushNotificationProviderMapper();
    }

    public function createPushNotificationSubscriptionDeliveryLogMapper(): PushNotificationSubscriptionDeliveryLogMapper
    {
        return new PushNotificationSubscriptionDeliveryLogMapper(
            $this->createPushNotificationMapper(),
            $this->createPushNotificationSubscriptionMapper(),
        );
    }

    public function createPaginationMapper(): PaginationMapper
    {
        return new PaginationMapper();
    }

    public function getUtilEncodingService(): PushNotificationToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PushNotificationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    public function createPushNotificationQuery(): SpyPushNotificationQuery
    {
        return SpyPushNotificationQuery::create();
    }
}
