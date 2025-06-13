<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? 'Admin Panel' ?></title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="dashboard_assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dashboard_assets/css/adminlte.min.css">
    <link rel="stylesheet" href="dashboard_assets/css/custom.css">
  </head>
  <body class="hold-transition sidebar-mini">
   <div class="wrapper">

<?php
require_once dirname(__DIR__, 3) . '/App/core/messages.php';
require_once 'nav.php';
require_once 'sidebar.php';







