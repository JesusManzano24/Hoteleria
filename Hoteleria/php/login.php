<?php
// php/login.php

// Incluir el controlador
require_once __DIR__ . '/../controller/LoginController.php';

// Ejecutar la acción de login
LoginController::login();