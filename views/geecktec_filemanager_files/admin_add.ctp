<?php
echo $this->Form->create('GeecktecFilemanagerFiles', array('action' => 'add', 'type' => 'file'));
echo $this->Form->input('GeecktecFilemanagerFile.filename', array('type' => 'file'));
echo $this->Form->end('Salvar');
?>