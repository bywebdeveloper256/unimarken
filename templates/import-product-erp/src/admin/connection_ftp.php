<?php

use EskofrizImport\SPconnectionErp;

$conn   = new SPconnectionErp;
$msg    = $conn->sp_msg_connection_erp();
