<?php
/**
 * Plugin Name: VIP Proxy Verification: Query Monitor
 * Description: Adds a new panel to Query Monitor for helping to configure WordPress VIP Proxy Verification.
 * Version: 1.0
 * Author: Rick Hurst
 */

add_action('plugins_loaded', function() {

    /**
     * Register collector, only if Query Monitor is enabled.
     */
    if(class_exists('QM_Collectors')) {
        include 'inc/class.qm.collector.php';
        QM_Collectors::add( new QM_Collector_VIPProxyVerification() );
    }

    /**
     * Register output. The filter won't run if Query Monitor is not
     * installed so we don't have to explicity check for it.
     */
    add_filter( 'qm/outputter/html', function(array $output, QM_Collectors $collectors) {
        include 'inc/class.qm.output.php';
        if ( $collector = QM_Collectors::get( 'vip_proxy_verification' ) ) {
            $output['vip_proxy_verification'] = new QM_Output_VIPProxyVerification( $collector );
        }
        return $output;
    }, 10, 2 );

});