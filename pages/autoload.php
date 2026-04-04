<?php
function loadClass($maClasse) {
  require './classes/'.$maClasse . '.php';
}

spl_autoload_register('loadClass');
?>