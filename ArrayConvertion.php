<?php
interface ArrayConvertion
{
    public static function fromArray(array $data);

    public function toArray(): array;
}
?>