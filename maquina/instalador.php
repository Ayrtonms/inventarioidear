<?php
    date_default_timezone_set('America/Fortaleza');

    if (substr(mb_strtoupper(PHP_OS), 0, 3) === 'WIN') {
        $x = [];

        exec("where php", $x);

        $php_path = trim($x[0]);
        $datetime = date('Y-m-d') . 'T' . date('H:i:s');
        $task_path = getcwd() . DIRECTORY_SEPARATOR . 'task.php';

        $corpo_xml = '<?xml version="1.0" encoding="UTF-16"?>
<Task version="1.2" xmlns="http://schemas.microsoft.com/windows/2004/02/mit/task">
  <RegistrationInfo>
    <URI>\InventarioIdear</URI>
  </RegistrationInfo>
  <Triggers>
    <TimeTrigger>
      <Repetition>
        <Interval>PT30M</Interval>
        <StopAtDurationEnd>false</StopAtDurationEnd>
      </Repetition>
      <StartBoundary>' . $datetime . '</StartBoundary>
      <Enabled>true</Enabled>
    </TimeTrigger>
  </Triggers>
  <Principals>
    <Principal id="Author">
      <RunLevel>HighestAvailable</RunLevel>
    </Principal>
  </Principals>
  <Settings>
    <MultipleInstancesPolicy>IgnoreNew</MultipleInstancesPolicy>
    <DisallowStartIfOnBatteries>false</DisallowStartIfOnBatteries>
    <StopIfGoingOnBatteries>false</StopIfGoingOnBatteries>
    <AllowHardTerminate>true</AllowHardTerminate>
    <StartWhenAvailable>false</StartWhenAvailable>
    <RunOnlyIfNetworkAvailable>false</RunOnlyIfNetworkAvailable>
    <IdleSettings>
      <StopOnIdleEnd>false</StopOnIdleEnd>
      <RestartOnIdle>false</RestartOnIdle>
    </IdleSettings>
    <AllowStartOnDemand>true</AllowStartOnDemand>
    <Enabled>true</Enabled>
    <Hidden>false</Hidden>
    <RunOnlyIfIdle>false</RunOnlyIfIdle>
    <WakeToRun>false</WakeToRun>
    <ExecutionTimeLimit>PT0S</ExecutionTimeLimit>
    <Priority>7</Priority>
  </Settings>
  <Actions Context="Author">
    <Exec>
      <Command>' . $php_path . '</Command>
      <Arguments>-f "' . $task_path . '"</Arguments>
    </Exec>
  </Actions>
</Task>';

        $tmp = file_put_contents('InventarioIdear.xml', $corpo_xml);

        if ($tmp === false) {
            echo 'Erro ao instalar o programa.';
            exit;
        }

        exec("schtasks /create /tn InventarioIdear /xml InventarioIdear.xml");

        unlink('InventarioIdear.xml');
    } else {
        $data = include(('linux' . DIRECTORY_SEPARATOR . 'dados_computador.php'));
    }
?>