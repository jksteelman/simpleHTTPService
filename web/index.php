<?php

require "bootstrap.php";

/** Inject routes */
\app\http\routes::register();

/** Resolve the current route */
\app\http\router::resolve();