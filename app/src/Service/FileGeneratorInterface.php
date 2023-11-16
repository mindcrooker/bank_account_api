<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;

interface FileGeneratorInterface
{
    public function generate(Collection $data, string $filename): string;
}
