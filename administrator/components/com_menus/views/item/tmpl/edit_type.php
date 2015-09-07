<?php
if ($this->item->type == 'alias') {
    echo $this->form->getControlGroup('aliastip');
}

echo $this->form->getControlGroup('type');

if ($this->item->type == 'alias') {
    echo $this->form->getControlGroups('aliasoptions');
}

echo $this->form->getControlGroups('request');

if ($this->item->type == 'url') {
    $this->form->setFieldAttribute('link', 'readonly', 'false');
}
echo $this->form->getControlGroup('link');


?>