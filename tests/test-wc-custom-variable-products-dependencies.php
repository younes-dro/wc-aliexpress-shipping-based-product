<?php
require_once '../woocommerce/woocommerce.php';
require_once '../ali2woo/ali2woo.php';
require_once 'includes/class-wc-ali-dependencies.php';


class WC_Ali_DependenciesTest extends WP_UnitTestCase {

    public function setUp() {
        parent::setUp();
        $this->class_instance = new WC_Ali_Dependencies();
    }
    
    
    public function test_check_php_version() {
        $result = $this->class_instance->check_php_version();
        $this->assertTrue($result);
    }

    public function test_check_wp_version() {
        $reslut = $this->class_instance->check_wp_version();
        $this->assertTrue($reslut);
    }

    public function test_check_wc_version() {
        
        $result = $this->class_instance->check_wc_version();
        $this->assertTrue($result);
    }
    
    public function test_check_ali2woo_version() {
        
        $result = $this->class_instance->check_ali2woo_version();
        $this->assertTrue($result);
    }    

}
