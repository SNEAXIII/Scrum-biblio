<?php
require "bootstrap.php";
$listermedia = new \App\UserStories\ListerNouveauMedia\ListerNouveauMedia($entityManager);

dump($listermedia->execute());