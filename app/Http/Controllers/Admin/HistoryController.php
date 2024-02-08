<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HistoryStoreRequest;
use App\Http\Requests\Admin\HistoryUpdateRequest;
use App\Http\Resources\Admin\HistoryCollection;
use App\Http\Resources\Admin\HistoryResource;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;

#[ApiResource('histories', only: ['index', 'destroy'])]
class HistoryController extends Controller
{
    public function index(): Response
    {
        $histories = History::all();

        return response(HistoryResource::collection($histories));
    }

    public function destroy(History $history): Response
    {
        $history->delete();

        return response()->noContent();
    }
}
