<?php

/*
 * (c) iLIKE IT Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ilis\Bundle\PaymentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Ilis\Bundle\PaymentBundle\Provider\Redsys\Webservice\Client;
use Ilis\Bundle\PaymentBundle\Provider\Paypal\PaymentsStandard\Button\BuyNow as PaypalButton;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ilis_payment');

        $rootNode
            ->children()
                ->scalarNode('transaction_identifier_suffix')
                ->end()
            ->arrayNode('methods')
                ->isRequired()
                ->children()
                    ->arrayNode('redsys_webservice')
                        ->canBeUnset()
                        ->canBeEnabled()
                        ->children()
                            ->scalarNode('merchant')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->end()
                            ->scalarNode('secret_key')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->end()
                            ->scalarNode('terminal')
                                ->defaultValue('001')
                                ->end()
                            ->enumNode('environment')
                                ->defaultValue(Client::ENV_PRODUCTION)
                                ->values(array(
                                    Client::ENV_INTEGRATION,
                                    Client::ENV_TESTING,
                                    Client::ENV_PRODUCTION
                                ))
                                ->end()
                        ->end()
                    ->end()
                    ->arrayNode('paypal_payments_standard')
                        ->canBeUnset()
                        ->canBeEnabled()
                        ->children()
                            ->scalarNode('business')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->booleanNode('sandbox')
                                ->defaultValue(false)
                            ->end()
                            ->enumNode('rm')
                                ->defaultValue(PaypalButton::RETURN_METHOD_GET)
                                ->values(array(
                                    PaypalButton::RETURN_METHOD_GET,
                                    PaypalButton::RETURN_METHOD_GET_NO_VARS,
                                    PaypalButton::RETURN_METHOD_POST,
                                ))
                            ->end()
                            ->scalarNode('return')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->end()
                            ->scalarNode('cancel_return')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->end()
                        ->end()
                    ->end()
                    //->arrayNode('free')
                    //    ->canBeUnset()
                    //    ->canBeEnabled()
                    // ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}