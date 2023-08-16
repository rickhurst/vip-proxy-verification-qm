<?php
/**
 * Output class
 *
 * Class QM_Output_VIPProxyVerification
 */
class QM_Output_VIPProxyVerification extends QM_Output_Html {

    public function __construct( QM_Collector $collector ) {

        parent::__construct( $collector );

        add_filter( 'qm/output/menus', array( $this, 'admin_menu' ), 101 );
        add_filter( 'qm/output/title', array( $this, 'admin_title' ), 101 );
        add_filter( 'qm/output/menu_class', array( $this, 'admin_class' ) );
    }

    /**
     * Outputs data in qm panel
     */
    public function output() {
        $data = $this->collector->get_data();
        ?>
        <div class="qm" id="<?php echo esc_attr($this->collector->id())?>">
            <table cellspacing="0">
            <?php foreach($data as $row): ?>
                <tr>
                    <th><?php echo esc_html( $row['label'] ) ?></th>
                    <td><?php echo esc_html( $row['value'] ) ?></th>
                </tr>
            <?php endforeach; ?>
            </table>
        </div>
        <?php
    }

    /**
     * Adds data to top admin bar
     *
     * @param array $title
     *
     * @return array
     */
    public function admin_title( array $title ) {
        return $title;
    }

    /**
     * @param array $class
     *
     * @return array
     */
    public function admin_class( array $class ) {
        $class[] = 'qm-vip_proxy_verification';
        return $class;
    }

    public function admin_menu( array $menu ) {

        $menu[] = $this->menu( array(
			'id'    => 'qm-vip_proxy_verification',
			'href'  => '#qm-vip_proxy_verification',
			'title' => __( 'VIP Proxy Verification', 'query-monitor' ),
		));

        return $menu;
    }
}