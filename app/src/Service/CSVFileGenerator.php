<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class CSVFileGenerator implements FileGeneratorInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function generate(Collection $data, string $filename): string
    {
        $context = (new ObjectNormalizerContextBuilder())->withGroups('transactions_log')->toArray();
        $serializedData = $this->serializer->serialize($data, 'csv', $context);

        $filesystem = new Filesystem();
        $path = 'transaction_files/'.$filename.'.csv';
        $filesystem->dumpFile($path, $serializedData);

        return $path;
    }
}
