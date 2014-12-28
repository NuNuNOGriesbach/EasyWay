#!/bin/bash
"/usr/bin/php" "/usr/bin/phpunit" "--colors" "--log-junit" "/tmp/nb-phpunit-log.xml" "--bootstrap" "/home/nununo/NetBeansProjects/Mantrask/Ew/test/bootstrap.php" "/home/nununo/.netbeans/8.0/phpunit/NetBeansSuite.php" "--run=/home/nununo/NetBeansProjects/Mantrask/Ew/test/Db"
