<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ClientService
{
    private $notFoundMessage = "Client Not Found";

    /**
     * @param array $filters
     * 
     * @return LengthAwarePaginator
     */
    public function getFilteredClients(array $filters): LengthAwarePaginator
    {
        $clients = Client::query()->paginate(10);

        return $clients;
    }

    /**
     * @param int $clientId
     * 
     * @return Client
     */
    public function getClientById(int $clientId): Client
    {
        $client = Client::find($clientId);

        if ($client === null) throw new ResourceNotFoundException($this->notFoundMessage);

        return $client;
    }

    /**
     * @param array $clientPayload
     * 
     * @return Client
     */
    // public function createClient(array $clientPayload)
    // {
    // }

    /**
     * @param int $clientId
     * @param array $clientPayload
     *
     * @return Client
     */
    public function updateClient(int $clientId, array $clientPayload): Client
    {
        $affectedRowCount = Client::whereId($clientId)->update($clientPayload);

        if ($affectedRowCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return Client::find($clientId);
    }

    /**
     * @param int $clientId
     * 
     * @return void
     */
    public function deleteClientById(int $clientId): void
    {
        $affectedRowsCount = Client::destroy($clientId);

        if ($affectedRowsCount === 0) throw new ResourceNotFoundException($this->notFoundMessage);
    }
}
