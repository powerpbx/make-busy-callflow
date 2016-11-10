<?php

namespace KazooTests\Applications\Callflow;

use \KazooTests\TestCase;

use \MakeBusy\Kazoo\Applications\Crossbar\TestAccount;
use \MakeBusy\FreeSWITCH\Kazoo\Gateways as KazooGateways;

class CallflowTestCase extends TestCase
{
    protected static $test_account;

    protected static $a_device;
    protected static $b_device;
    protected static $c_device;
    protected static $register_device;
    protected static $no_device;
    protected static $offnet_resource;
    protected static $emergency_resource;
    protected static $ring_group;
    protected static $realm;

    const A_NUMBER          = '5552221001';
    const A_EXT             = '1001';
    const B_NUMBER          = '5552221002';
    const B_EXT             = '1002';
    const C_NUMBER          = '5022221003';
    const C_EXT             = '1003';
    const NO_NUMBER         = '5552221100';
    const NO_EXT            = '1100';
    const RINGGROUP_EXT     = '1111';
    const MILLIWATT_NUMBER  = '5555555551';
    const CALL_FWD_ENABLE   = '*72';
    const CALL_FWD_DISABLE  = '*73';
    const OFFNET_NUMBER     = '5552345678';
    const EMERGENCY_NUMBER  = '911';
    const RESTRICTED_NUMBER = '6845551234';

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        $acc = new TestAccount(get_class());
        self::$realm = $acc->getAccountRealm();

        self::$a_device = $acc->createDevice("auth");
        self::$a_device->createCallflow(array(self::A_EXT, self::A_NUMBER));

        self::$b_device = $acc->createDevice("auth");
        self::$b_device->createCallflow(array(self::B_EXT, self::B_NUMBER));

        self::$c_device = $acc->createDevice("auth");
        self::$c_device->createCallflow(array(self::C_EXT, self::C_NUMBER));

        self::$no_device = $acc->createDevice("auth", FALSE);
        self::$no_device->createCallflow(array(self::NO_EXT, self::NO_NUMBER));

        self::$register_device = $acc->createDevice("auth");

        self::$offnet_resource = $acc->createResource(array("^\\+1(\d{10})$"), "+1");
        self::$emergency_resource = $acc->createResource(array("^(911)$"), null, TRUE);

        self::$ring_group = new RingGroup(
            self::$test_account,
            [ self::RINGGROUP_EXT ],
            [
                [
                    "id" => self::$b_device->getId(),
                    "type" => "device"
                ],
                [
                    "id" => self::$no_device->getId(),
                    "type" => "device"
                ]
            ]
        );

        KazooGateways::loadFromAccounts();
    }

    public function setUp() {
        self::getEsl()->flushEvents();
        self::getEsl()->api("hupall");
    }

    public static function getTestAccount() {
        return self::$test_account;
    }

}
