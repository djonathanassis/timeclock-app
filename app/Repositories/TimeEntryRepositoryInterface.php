<?php

declare(strict_types = 1);

namespace App\Repositories;

use Carbon\CarbonInterface;

interface TimeEntryRepositoryInterface
{
    /**
     * Gera um relatório detalhado de registros de ponto
     * 
     * @param CarbonInterface|null $startDateTime Data e hora inicial
     * @param CarbonInterface|null $endDateTime Data e hora final
     * @return array Resultado do relatório
     */
    public function getTimeRecordReport(
        ?CarbonInterface $startDateTime = null,
        ?CarbonInterface $endDateTime = null
    ): array;
} 