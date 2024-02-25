<?php

namespace App\Services;

use App\Http\Resources\Admin\ClientResource;
use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ClientService
{
    private $notFoundMessage = "Client Not Found";

    /**
     * @param array $filters
     * 
     * @return Collection<int,Client>
     */
    public function getFilteredClients(array $filters) //: Collection
    {
        $clients = Client::query()->get();

        return ClientResource::collection($clients);
    }

    /**
     * @param int $clientId
     * 
     * @return Client
     */
    public function getClientById(int $clientId) //: Client
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
    public function createClient(array $clientPayload) //: Client
    {
    }

    /**
     * @param int $clientId
     * @param array $clientPayload
     *
     * @return bool
     */
    public function updateClient(int $clientId, array $clientPayload): bool
    {
        $affectedRowCount = Client::whereId($clientId)->update($clientPayload);

        if ($affectedRowCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }

    /**
     * @param int $clientId
     * 
     * @return bool
     */
    public function deleteClient(int $clientId)
    {
        $affectedRowsCount = Client::destroy($clientId);

        if ($affectedRowsCount === 0)
            throw new ResourceNotFoundException($this->notFoundMessage);

        return true;
    }
}
