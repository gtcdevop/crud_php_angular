<?php

// This file will expose the JSON rest API headers 
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);