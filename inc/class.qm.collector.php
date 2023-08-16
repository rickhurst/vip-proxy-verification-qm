<?php
/**
 * Data collector class
 */
class QM_Collector_VIPProxyVerification extends QM_Collector {

    public $id = 'vip_proxy_verification';
    public $data = [];

    public function name() {
        return __( 'VIP Proxy Verification', 'vip_proxy_verification' );
    }

    public function process() {

        // VIP proxy verification header as configured for this application at VIP
        $vip_proxy_verification = defined('WPCOM_VIP_PROXY_VERIFICATION') ? WPCOM_VIP_PROXY_VERIFICATION : '';

        // Proxy verification request header sent as a custom header by the Remote Proxy
        $remote_proxy_verification = isset($_SERVER['HTTP_X_VIP_PROXY_VERIFICATION']) ? $_SERVER['HTTP_X_VIP_PROXY_VERIFICATION'] : '';

        // The remote user IP address as provided by Akamai and others
        $true_client_ip = isset($_SERVER['HTTP_TRUE_CLIENT_IP']) ? $_SERVER['HTTP_TRUE_CLIENT_IP'] : '';

        // Cloudflare connecting IP (https://support.cloudflare.com/hc/en-us/articles/200170986)
        $cf_connecting_ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : '';

        // Proxy verifications strings should match
        $verification_status = ($vip_proxy_verification !== '' && $remote_proxy_verification !== '' && $vip_proxy_verification === $remote_proxy_verification) ? 'correct' : 'incorrect';

        // If the VIP proxy header verification code has been applied correctly, the remote address
        // will mirror the user's remote IP https://docs.wpvip.com/how-tos/configure-a-reverse-proxy/#h-the-x-vip-proxy-verification-method-recommended
        $remote_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

        // Other IP address headers which might be useful for debugging
        $ip_forwarded_for = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
        $ip_trail = isset($_SERVER['HTTP_X_IP_TRAIL']) ? $_SERVER['HTTP_X_IP_TRAIL'] : '';

        $this->data = [
            'proxy-verification-vip' => [
                'label' => 'Proxy Verification Secret (VIP)',
                'value' => ($vip_proxy_verification !== '') ? $this->obfuscate_string($vip_proxy_verification) : "(not found)",
            ],
            'proxy-verification-remote' => [
                'label' => 'Proxy Verification Secret (Reverse Proxy)',
                'value' => ($remote_proxy_verification !== '') ? $this->obfuscate_string($remote_proxy_verification) : "(not found)"
            ],
            'proxy-verification-status' => [
                'label' => 'Proxy Header Verification Status',
                'value' => $verification_status,
            ],
            'true-client-ip' => [
                'label' => 'True Client IP (sent by Akamai and others)',
                'value' => ($true_client_ip !== '') ? $true_client_ip : "(not found)"
            ],
            'cf-connecting-ip' => [
                'label' => 'Cloudflare connecting IP',
                'value' => ($cf_connecting_ip !== '') ? $cf_connecting_ip : "(not found)"
            ],
            'remote-ip' => [
                'label' => 'Remote IP',
                'value' => ($remote_ip !== '') ? $remote_ip : "(not found)"
            ],
            'ip-forwarded-for' => [
                'label' => 'IP Forwarded For',
                'value' => ($ip_forwarded_for !== '') ? $ip_forwarded_for : "(not found)"
            ],
            'ip-trail' => [
                'label' => 'IP Trail',
                'value' => ($ip_trail !== '') ? $ip_trail : "(not found)"
            ],
        ];
    }

    private function obfuscate_string($string) {
        $length = strlen($string);
        if ($length <= 3) {
            return $string;
        }

        $obfuscated = str_repeat('*', $length - 3) . substr($string, -3);
        return $obfuscated;
    }

}

