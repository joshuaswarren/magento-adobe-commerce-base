<?php

namespace Creatuity\Base\Helpers\Creatuity\Subjects;

use Symfony\Component\Console\Output\OutputInterface;

class Processing extends SubjectAbstract
{
    /**
     * @param int $chunkSize
     * @return \Generator
     */
    public function inChunk(array $data, $chunkSize, OutputInterface $output = null)
    {
        $output = $output ?: $this->creatuity()->report()->output();
        $dataSize = count($data);
        $chunks = array_chunk($data, $chunkSize);
        $data = [];
        $chunksCount = count($chunks);

        $output->writeln(sprintf(' - There is %s items to process in %s chunk(s)...', number_format($dataSize, 0, '.', ' '), $chunksCount));
        $timeStart = time();
        foreach ($chunks as $chunkNo => $chunkData) {
            $chunkTime = time();
            yield $chunkData;

            $itemsToProcess = $chunkNo < $chunksCount - 1 ? ($chunkNo + 1) * $chunkSize : count($chunkData);
            $output->writeln(sprintf(
                '   - Finished %s items in %s second(s) (%s/%s chunks, %s%%)',
                $itemsToProcess,
                time() - $chunkTime,
                $chunkNo + 1,
                $chunksCount,
                round($itemsToProcess >= $chunkSize ? ($itemsToProcess / $dataSize) * 100 : 100, 3)
            ));
        }

        $output->writeln(sprintf(' - Total time: %s', date('H:i:s', time() - $timeStart)));
    }
}
