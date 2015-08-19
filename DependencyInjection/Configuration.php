<?php

namespace PM\Bundle\YubikeyOtpBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package PM\Bundle\YubikeyOtpBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pm_yubikey_otp');

        $rootNode
            ->children()
            ->arrayNode('server')
            ->children()
            ->scalarNode('client_id')->isRequired()->end()
            ->scalarNode('client_secret')->isRequired()->end()
            ->scalarNode('uri')->isRequired()->end()
            ->end()
            ->end()// Server
            ->end();

        return $treeBuilder;
    }
}