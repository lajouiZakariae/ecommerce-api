<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientStoreRequest;
use App\Http\Requests\Admin\ClientUpdateRequest;
use App\Http\Resources\Admin\ClientResource;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ClientController extends Controller
{

    public function __construct(private ClientService $clientService)
    {
    }

    /**
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $clients = Client::query()->paginate(10);

        return ClientResource::collection($clients);
    }

    /**
     * @param ClientStoreRequest $request
     * 
     * @return Response
     */
    public function store(ClientStoreRequest $request): Response
    {
        $data = $request->validated();

        $client = Client::create($data);

        return response(ClientResource::make($client), Response::HTTP_CREATED);
    }

    /**
     * @param int $clientId
     * 
     * @return ClientResource
     */
    public function show(int $clientId): ClientResource
    {
        return ClientResource::make($this->clientService->getClientById($clientId));
    }

    /**
     * @param ClientUpdateRequest $request
     * @param int $clientId
     * 
     * @return ClientResource
     */
    public function update(ClientUpdateRequest $request, int $clientId): ClientResource
    {
        $clientPayload = $request->validated();

        return ClientResource::make($this->clientService->updateClient($clientId, $clientPayload));
    }


    /**
     * @param int $clientId
     * 
     * @return Response
     */
    public function destroy(int $clientId): Response
    {
        $this->clientService->deleteClientById($clientId);

        return response()->noContent();
    }
}
