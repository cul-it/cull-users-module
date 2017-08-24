#!/bin/bash
export SIMPLETEST_DB='mysql://pantheon:pantheon@cull-userslibrarycornelledu.kbox.site:32768/pantheon'
export SIMPLETEST_BASE_URL='http://cull-userslibrarycornelledu.kbox.site/'

cd /Users/jgr25/Kalabox/cull-userslibrarycornelledu/code/core
#sudo -u _www -E ../vendor/bin/phpunit /Users/jgr25/Kalabox/cull-userslibrarycornelledu/code/modules/cull_users/tests/src/Functional/CullUsersTest.php
kbox php /Users/jgr25/Kalabox/cull-userslibrarycornelledu/code/core/scripts/run-tests.sh --verbose --url http://cull-userslibrarycornelledu.kbox.site/ --color --dburl mysql://pantheon:pantheon@cull-userslibrarycornelledu.kbox.site:32768/pantheon --verbose cull_users
