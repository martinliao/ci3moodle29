<?php

#$route['Javascript'] = 'javascript';

$route['Javascript/(:num)/(:any)/(:any)'] = "get/$1/$2/$3";
# http://localhost/ci3rjs/Javascript/1681467711/lib/javascript-static.js