<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImageStoreRequest;
use App\Http\Requests\Admin\ImageUpdateRequest;
use App\Http\Resources\Admin\ImageResource;
use App\Services\ImageService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ImageController extends Controller
{
    public function __construct(private ImageService $imageService)
    {
    }

    public function index(): ResourceCollection
    {

        return ImageResource::collection($this->imageService->paginatedImages());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\ImageStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageStoreRequest $request): Response
    {
        $image = $this->imageService->createImage($request->validated());

        return response(ImageResource::make($image), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(int $imageId): ImageResource
    {
        return ImageResource::make($this->imageService->getImageById($imageId));
    }

    /**
     * @param ImageUpdateRequest $request
     * @param int $imageId
     * 
     * @return ImageResource
     */
    public function update(ImageUpdateRequest $request, int $imageId): ImageResource
    {
        $updatedImage = $this->imageService->updateImage($imageId, $request->validated());

        return ImageResource::make($updatedImage);
    }


    /**
     * @param int $imageId
     * 
     * @return Response
     */
    public function destroy(int $imageId): Response
    {
        $this->imageService->deleteImageById($imageId);

        return response()->noContent();
    }

    /**
     * @param int $productId
     * 
     * @return ResourceCollection
     */
    public function productImages(int $productId): ResourceCollection
    {
        return ImageResource::collection($this->imageService->getPaginatedImagesOfProduct($productId));
    }
}
