<?php

namespace App\Services;

use App\Models\UserTransaction;
use App\Utils\DataProviderUtil;
use Illuminate\Http\UploadedFile;
use JsonMachine\Exception\InvalidArgumentException;
use JsonMachine\Items;

class UserTransactionService
{
    const IMPORT_BATCH_SIZE = 50;

    /**
     * @param UploadedFile $file
     * @param string $provider
     * @throws InvalidArgumentException
     */
    public function importFromUploadedFile(UploadedFile $file, string $provider)
    {
        $items = Items::fromString($file->getContent());
        $this->import($items, $provider);
    }

    /**
     * @param $items
     * @param string $provider
     */
    private function import($items, string $provider)
    {
        $data = [];
        $dataProvider = resolve(DataProviderUtil::getProviderClass($provider));
        foreach ($items as $item) {
            $data[] = array_merge($dataProvider->parse($item), [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (count($data) == self::IMPORT_BATCH_SIZE) {
                UserTransaction::insert($data);
                $data = [];
            }
        }

        if (!empty($data))
            UserTransaction::insert($data);
    }
}