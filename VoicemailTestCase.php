<?php

namespace KazooTests\Applications\Callflow;

use \KazooTests\TestCase;
use \MakeBusy\Kazoo\Applications\Crossbar\TestAccount;

class VoicemailTestCase extends TestCase
{
    public static $a_device;
    public static $b_user;
    public static $b_device;
    public static $b_voicemail_box;
    
    const VM_CHECK_NUMBER   = '3001';
    const VM_ACCESS_NUMBER  = '3002';
    const VM_BOX_ID         = '3000';
    const VM_CHECK_CODE     = '*97';
    const VM_COMPOSE_B_CODE = '**3000';
    const B_USER_NUMBER     = '3000';

    const DEFAULT_PIN = '0000';
    const CHANGE_PIN  = '1111';

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        $acc = new TestAccount("VoicemailTestCase");

        self::$b_voicemail_box = $acc->createVm(self::VM_BOX_ID);
        self::$b_user          = $acc->createUser();
        self::$a_device        = $acc->createDevice("auth");
        self::$b_device        = $acc->createDevice("auth", TRUE, ['owner_id' => self::$b_user->getId()]);

        self::$b_voicemail_box->createCallflow([self::VM_ACCESS_NUMBER]);
        self::$b_voicemail_box->createUserVmCallflow([self::B_USER_NUMBER], self::$b_user->getId());
        self::$b_voicemail_box->createCheckCallflow([self::VM_CHECK_NUMBER]);

        //set defaults, box should be setup when entering a test, we can force setup by setting is_setup=FALSE later.
        self::$b_voicemail_box->setVoicemailboxParam('owner_id', self::$b_user->getId());
        self::$b_voicemail_box->setVoicemailboxParam('is_setup', TRUE);

        self::sync_sofia_profile("auth", self::$a_device->isLoaded(), 1);
    }

}
