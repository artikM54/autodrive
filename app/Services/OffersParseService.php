<?php

namespace App\Services;

use App\Models\Offer;
use Exception;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;



class OffersParseService
{

    protected string $defaultFile;
    protected string $validExtensionFile = 'xml';
    protected array $softDeletesOfferIds = [];


    protected $output;
    protected $progress;


    public function __construct()
    {
        $this->defaultFile = storage_path('app\public\data.xml');
        $this->output = new ConsoleOutput();
        $this->progress = new ProgressBar($this->output, 100);
    }


    public function parse($file = null)
    {
        $file = $file ?? $this->defaultFile;
        $this->validateFile($file);

        $offers = $this->getOffers($file);

        $this->progress->start();

        foreach ($offers as $offer) {
            $this->handleOffer($offer);
            $this->progress->advance();
        }

        $this->progress->finish();

        $this->deleteIrrelevantOffers();
    }

    protected function validateFile($file): void
    {
        if (! file_exists($file)) {
            throw new Exception("File {$file} not found");
        }

        $extension = pathinfo($file)['extension'];

        if ($extension !== $this->validExtensionFile) {
            throw new Exception("File {$file} not valid");
        }
    }

    protected function getOffers($file): array
    {
        $file = file_get_contents($file);
        $xml = simplexml_load_string($file);
        $data = $this->xmlObjectToarray($xml);

        return $data['offers']['offer'];
    }

    protected function xmlObjectToarray($xmlObject)
    {
        $out = array();

        if (is_array($xmlObject) || (is_object($xmlObject) && $xmlObject->count() > 0)) {
            foreach ((array) $xmlObject as $index => $node) {
                $out[$index] = $this->xmlObjectToarray($node);
            }
        } else {
            $out = json_decode($xmlObject);

            if ($out === null and !empty((string) $xmlObject)) {
                $out = (string) $xmlObject;
            }
        }

        return $out;
    }

    private function handleOffer($offer): void
    {
        $offer = Offer::withTrashed()
            ->updateOrCreate([
                'id' => $offer['id']
            ],
            [
                'mark' => $offer['mark'],
                'model' => $offer['model'],
                'generation' => $offer['generation'],
                'year' => $offer['year'],
                'run' => $offer['run'],
                'color' => $offer['color'],
                'body_type' => $offer['body-type'],
                'engine_type' => $offer['engine-type'],
                'transmission' => $offer['transmission'],
                'gear_type' => $offer['gear-type'],
                'generation_id' => $offer['generation_id'],
            ]);

        if ($offer) {
            $offer->restore();
            $this->softDeletesOfferIds[] = $offer->id;
        }
    }

    private function deleteIrrelevantOffers(): void
    {
        Offer::whereNotIn('id', $this->softDeletesOfferIds)
            ->delete();
    }

}
