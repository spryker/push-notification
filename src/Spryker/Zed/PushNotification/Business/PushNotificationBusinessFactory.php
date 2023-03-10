<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PushNotification\Business\Creator\ErrorCreator;
use Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotification\Business\Creator\PushNotificationCreator;
use Spryker\Zed\PushNotification\Business\Creator\PushNotificationCreatorInterface;
use Spryker\Zed\PushNotification\Business\Creator\PushNotificationProviderCreator;
use Spryker\Zed\PushNotification\Business\Creator\PushNotificationProviderCreatorInterface;
use Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionCreator;
use Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionCreatorInterface;
use Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionDeliveryLogCreator;
use Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionDeliveryLogCreatorInterface;
use Spryker\Zed\PushNotification\Business\Deleter\PushNotificationSubscriptionDeleter;
use Spryker\Zed\PushNotification\Business\Deleter\PushNotificationSubscriptionDeleterInterface;
use Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpander;
use Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface;
use Spryker\Zed\PushNotification\Business\Extractor\ErrorEntityIdentifierExtractor;
use Spryker\Zed\PushNotification\Business\Extractor\ErrorEntityIdentifierExtractorInterface;
use Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionDeliveryLogExtractor;
use Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionDeliveryLogExtractorInterface;
use Spryker\Zed\PushNotification\Business\Filter\PushNotificationFilter;
use Spryker\Zed\PushNotification\Business\Filter\PushNotificationFilterInterface;
use Spryker\Zed\PushNotification\Business\Filter\PushNotificationProviderFilter;
use Spryker\Zed\PushNotification\Business\Filter\PushNotificationProviderFilterInterface;
use Spryker\Zed\PushNotification\Business\Filter\PushNotificationSubscriptionFilter;
use Spryker\Zed\PushNotification\Business\Filter\PushNotificationSubscriptionFilterInterface;
use Spryker\Zed\PushNotification\Business\Generator\PushNotificationSubscriptionCheckSumGenerator;
use Spryker\Zed\PushNotification\Business\Generator\PushNotificationSubscriptionCheckSumGeneratorInterface;
use Spryker\Zed\PushNotification\Business\Mapper\PushNotificationSubscriptionMapper;
use Spryker\Zed\PushNotification\Business\Mapper\PushNotificationSubscriptionMapperInterface;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationGroupReader;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationGroupReaderInterface;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReader;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationSubscriptionReader;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationSubscriptionReaderInterface;
use Spryker\Zed\PushNotification\Business\Sender\PushNotificationSender;
use Spryker\Zed\PushNotification\Business\Sender\PushNotificationSenderInterface;
use Spryker\Zed\PushNotification\Business\Validator\PushNotificationProviderValidator;
use Spryker\Zed\PushNotification\Business\Validator\PushNotificationProviderValidatorInterface;
use Spryker\Zed\PushNotification\Business\Validator\PushNotificationSubscriptionValidator;
use Spryker\Zed\PushNotification\Business\Validator\PushNotificationSubscriptionValidatorInterface;
use Spryker\Zed\PushNotification\Business\Validator\PushNotificationValidator;
use Spryker\Zed\PushNotification\Business\Validator\PushNotificationValidatorInterface;
use Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotification\PushNotificationExistsValidatorRule;
use Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotification\PushNotificationProviderExistsValidatorRule as PushNotificationPushNotificationProviderExistsValidatorRule;
use Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotification\PushNotificationValidatorRuleInterface;
use Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationProviderExistsValidatorRule;
use Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationProviderValidatorRuleInterface;
use Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionGroupNameAllowedValidatorRule;
use Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionProviderExistsValidatorRule;
use Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionUniqueValidatorRule;
use Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionValidatorRuleInterface;
use Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface;
use Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilTextServiceInterface;
use Spryker\Zed\PushNotification\PushNotificationDependencyProvider;

/**
 * @method \Spryker\Zed\PushNotification\PushNotificationConfig getConfig()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface getRepository()
 */
class PushNotificationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionCreatorInterface
     */
    public function createPushNotificationSubscriptionCreator(): PushNotificationSubscriptionCreatorInterface
    {
        return new PushNotificationSubscriptionCreator(
            $this->getEntityManager(),
            $this->createPushNotificationProviderReader(),
            $this->createPushNotificationSubscriptionCreateValidator(),
            $this->getConfig(),
            $this->createPushNotificationSubscriptionFilter(),
            $this->createPushNotificationSubscriptionCheckSumGenerator(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Creator\PushNotificationCreatorInterface
     */
    public function createPushNotificationCreator(): PushNotificationCreatorInterface
    {
        return new PushNotificationCreator(
            $this->getEntityManager(),
            $this->createPushNotificationCreateValidator(),
            $this->createPushNotificationFilter(),
            $this->createPushNotificationProviderReader(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Creator\PushNotificationProviderCreatorInterface
     */
    public function createPushNotificationProviderCreator(): PushNotificationProviderCreatorInterface
    {
        return new PushNotificationProviderCreator(
            $this->createPushNotificationProviderCreateValidator(),
            $this->createPushNotificationProviderFilter(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionDeliveryLogCreatorInterface
     */
    public function createPushNotificationSubscriptionDeliveryLogCreator(): PushNotificationSubscriptionDeliveryLogCreatorInterface
    {
        return new PushNotificationSubscriptionDeliveryLogCreator(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Validator\PushNotificationSubscriptionValidatorInterface
     */
    public function createPushNotificationSubscriptionCreateValidator(): PushNotificationSubscriptionValidatorInterface
    {
        return new PushNotificationSubscriptionValidator(
            $this->getPushNotificationSubscriptionCreateValidatorRules(),
            $this->getPushNotificationSubscriptionValidatorPlugins(),
            $this->createErrorCollectionExpander(),
        );
    }

    /**
     * @return list<\Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionValidatorRuleInterface>
     */
    public function getPushNotificationSubscriptionCreateValidatorRules(): array
    {
        return [
            $this->createPushNotificationSubscriptionProviderExistsValidatorRule(),
            $this->createPushNotificationSubscriptionGroupNameAllowedValidatorRule(),
            $this->createPushNotificationSubscriptionUniqueValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Validator\PushNotificationValidatorInterface
     */
    public function createPushNotificationCreateValidator(): PushNotificationValidatorInterface
    {
        return new PushNotificationValidator(
            [
                $this->createPushNotificationPushNotificationProviderExistsValidatorRule(),
            ],
            $this->getPushNotificationValidatorPlugins(),
            $this->createErrorCollectionExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Validator\PushNotificationProviderValidatorInterface
     */
    public function createPushNotificationProviderCreateValidator(): PushNotificationProviderValidatorInterface
    {
        return new PushNotificationProviderValidator(
            [
                $this->createPushNotificationProviderExistsValidatorRule(),
            ],
            $this->createErrorCollectionExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Validator\PushNotificationValidatorInterface
     */
    public function createPushNotificationUpdateValidator(): PushNotificationValidatorInterface
    {
        return new PushNotificationValidator(
            [
                $this->createPushNotificationPushNotificationProviderExistsValidatorRule(),
                $this->createPushNotificationExistsValidatorRule(),
            ],
            $this->getPushNotificationValidatorPlugins(),
            $this->createErrorCollectionExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Sender\PushNotificationSenderInterface
     */
    public function createPushNotificationSender(): PushNotificationSenderInterface
    {
        return new PushNotificationSender(
            $this->getPushNotificationSenderPlugins(),
            $this->getRepository(),
            $this->getConfig(),
            $this->createPushNotificationSubscriptionDeliveryLogExtractor(),
            $this->createPushNotificationSubscriptionDeliveryLogCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Deleter\PushNotificationSubscriptionDeleterInterface
     */
    public function createPushNotificationSubscriptionDeleter(): PushNotificationSubscriptionDeleterInterface
    {
        return new PushNotificationSubscriptionDeleter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig(),
            $this->createPushNotificationSubscriptionMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Filter\PushNotificationSubscriptionFilterInterface
     */
    public function createPushNotificationSubscriptionFilter(): PushNotificationSubscriptionFilterInterface
    {
        return new PushNotificationSubscriptionFilter(
            $this->createErrorEntityIdentifierExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Filter\PushNotificationFilter
     */
    public function createPushNotificationFilter(): PushNotificationFilterInterface
    {
        return new PushNotificationFilter(
            $this->createErrorEntityIdentifierExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Filter\PushNotificationProviderFilterInterface
     */
    public function createPushNotificationProviderFilter(): PushNotificationProviderFilterInterface
    {
        return new PushNotificationProviderFilter(
            $this->createErrorEntityIdentifierExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Mapper\PushNotificationSubscriptionMapperInterface
     */
    public function createPushNotificationSubscriptionMapper(): PushNotificationSubscriptionMapperInterface
    {
        return new PushNotificationSubscriptionMapper();
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotification\PushNotificationValidatorRuleInterface
     */
    public function createPushNotificationPushNotificationProviderExistsValidatorRule(): PushNotificationValidatorRuleInterface
    {
        return new PushNotificationPushNotificationProviderExistsValidatorRule(
            $this->createPushNotificationProviderReader(),
            $this->createErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotification\PushNotificationValidatorRuleInterface
     */
    public function createPushNotificationExistsValidatorRule(): PushNotificationValidatorRuleInterface
    {
        return new PushNotificationExistsValidatorRule(
            $this->getRepository(),
            $this->createErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionValidatorRuleInterface
     */
    public function createPushNotificationSubscriptionProviderExistsValidatorRule(): PushNotificationSubscriptionValidatorRuleInterface
    {
        return new PushNotificationSubscriptionProviderExistsValidatorRule(
            $this->createPushNotificationProviderReader(),
            $this->createErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationProviderValidatorRuleInterface
     */
    public function createPushNotificationProviderExistsValidatorRule(): PushNotificationProviderValidatorRuleInterface
    {
        return new PushNotificationProviderExistsValidatorRule(
            $this->createPushNotificationProviderReader(),
            $this->createErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionValidatorRuleInterface
     */
    public function createPushNotificationSubscriptionGroupNameAllowedValidatorRule(): PushNotificationSubscriptionValidatorRuleInterface
    {
        return new PushNotificationSubscriptionGroupNameAllowedValidatorRule(
            $this->getConfig(),
            $this->createErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionValidatorRuleInterface
     */
    public function createPushNotificationSubscriptionUniqueValidatorRule(): PushNotificationSubscriptionValidatorRuleInterface
    {
        return new PushNotificationSubscriptionUniqueValidatorRule(
            $this->createPushNotificationSubscriptionReader(),
            $this->createPushNotificationProviderReader(),
            $this->createPushnotificationGroupReader(),
            $this->createPushNotificationSubscriptionCheckSumGenerator(),
            $this->createErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface
     */
    public function createPushNotificationProviderReader(): PushNotificationProviderReaderInterface
    {
        return new PushNotificationProviderReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Reader\PushNotificationGroupReaderInterface
     */
    public function createPushNotificationGroupReader(): PushNotificationGroupReaderInterface
    {
        return new PushNotificationGroupReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Reader\PushNotificationSubscriptionReaderInterface
     */
    public function createPushNotificationSubscriptionReader(): PushNotificationSubscriptionReaderInterface
    {
        return new PushNotificationSubscriptionReader(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface
     */
    public function createErrorCollectionExpander(): ErrorCollectionExpanderInterface
    {
        return new ErrorCollectionExpander();
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface
     */
    public function createErrorCreator(): ErrorCreatorInterface
    {
        return new ErrorCreator();
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Extractor\ErrorEntityIdentifierExtractorInterface
     */
    public function createErrorEntityIdentifierExtractor(): ErrorEntityIdentifierExtractorInterface
    {
        return new ErrorEntityIdentifierExtractor();
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionDeliveryLogExtractorInterface
     */
    public function createPushNotificationSubscriptionDeliveryLogExtractor(): PushNotificationSubscriptionDeliveryLogExtractorInterface
    {
        return new PushNotificationSubscriptionDeliveryLogExtractor();
    }

    /**
     * @return \Spryker\Zed\PushNotification\Business\Generator\PushNotificationSubscriptionCheckSumGeneratorInterface
     */
    public function createPushNotificationSubscriptionCheckSumGenerator(): PushNotificationSubscriptionCheckSumGeneratorInterface
    {
        return new PushNotificationSubscriptionCheckSumGenerator(
            $this->getUtilEncodingService(),
            $this->getUtilTextService(),
        );
    }

    /**
     * @return array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSubscriptionValidatorPluginInterface>
     */
    public function getPushNotificationSubscriptionValidatorPlugins(): array
    {
        return $this->getProvidedDependency(
            PushNotificationDependencyProvider::PLUGINS_PUSH_NOTIFICATION_SUBSCRIPTION_VALIDATOR,
        );
    }

    /**
     * @return array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationValidatorPluginInterface>
     */
    public function getPushNotificationValidatorPlugins(): array
    {
        return $this->getProvidedDependency(
            PushNotificationDependencyProvider::PLUGINS_PUSH_NOTIFICATION_VALIDATOR,
        );
    }

    /**
     * @return array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface>
     */
    public function getPushNotificationSenderPlugins(): array
    {
        return $this->getProvidedDependency(
            PushNotificationDependencyProvider::PLUGINS_PUSH_NOTIFICATION_SENDER,
        );
    }

    /**
     * @return \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PushNotificationToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PushNotificationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilTextServiceInterface
     */
    public function getUtilTextService(): PushNotificationToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(PushNotificationDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
