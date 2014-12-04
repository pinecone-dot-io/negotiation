<?php 

namespace negotiation;

if( is_admin() )
	require __DIR__.'/admin.php';

require __DIR__.'/filters.php';	
require __DIR__.'/query.php';	
require __DIR__.'/rewrite.php';

