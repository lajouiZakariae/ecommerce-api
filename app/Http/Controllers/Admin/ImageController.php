<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImageStoreRequest;
use App\Http\Requests\Admin\ImageUpdateRequest;
use App\Http\Resources\Admin\ImageResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\RouteAttributes\Attributes\ApiResource;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * @group Images
 */
#[ApiResource('images')]
class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $images = Image::all();

        return response(ImageResource::collection($images));
    }

    /**
     * Extract data from the request and handle image upload.
     *
     * @param  \App\Models\Image  $image
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function extractData(Image $image, $request): array
    {
        $data = $request->validated();

        /** @var UploadedFile */
        $uploaded_image = $data['image'] ?? null;

        if ($uploaded_image === null || $uploaded_image->getError()) {
            return $data;
        }

        /* delete image on upload */
        if ($image->path) {
            Storage::disk('public')->delete($image->path);
        }

        $data['path'] = $uploaded_image->store('products', 'public');

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\ImageStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageStoreRequest $request): Response
    {
        $data = $this->extractData(new Image(), $request);

        $image = Image::create($data);

        return response(new ImageResource($image), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image): Response
    {
        return response(new ImageResource($image));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\ImageUpdateRequest  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(ImageUpdateRequest $request, Image $image): Response
    {
        $data = $this->extractData($image, $request);

        $image->update($data);

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image): Response
    {
        Storage::disk('public')->delete($image->path);

        $image->delete();

        return response()->noContent();
    }

    /**
     * Get images associated with a product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    #[Get('products/{product}/images')]
    public function productImages(Product $product): Response
    {
        return response(ImageResource::collection($product->images));
    }
}
