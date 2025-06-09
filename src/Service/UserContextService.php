<?php

namespace App\Service;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class UserContextService
{
    public function __construct(
        private RequestStack $requestStack,
        private LocationRepository $locationRepository
    ) {}

    public function getCurrentLocation(): ?Location
    {
        $session = $this->requestStack->getSession();
        $locationId = $session?->get('selected_location_id');

        return $locationId ? $this->locationRepository->find($locationId) : null;
    }

    public function ensureCurrentLocation(): Location
    {
        $location = $this->getCurrentLocation();

        if (!$location) {
            throw new \RuntimeException('Aucun magasin sélectionné.');
        }

        return $location;
    }
}
