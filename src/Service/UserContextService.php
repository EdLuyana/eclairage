<?php

namespace App\Service;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserContextService
{
    public function __construct(
        private SessionInterface $session,
        private LocationRepository $locationRepository
    ) {}

    public function getCurrentLocation(): ?Location
    {
        $locationId = $this->session->get('selected_location_id');

        if (!$locationId) {
            return null;
        }

        return $this->locationRepository->find($locationId);
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
