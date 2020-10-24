<?php

$socket = stream_socket_server('tcp://localhost:8001');

$con = stream_socket_accept($socket);

$espera = sleep(rand(1, 5));
fwrite($con, "Resposta do socket após $espera segundos");
