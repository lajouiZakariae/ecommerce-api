<?php

namespace App\Services;

use App\Exceptions\AppExceptions\BadRequestException;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Storage;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ImageService
{
    /**
     * @param int $productId
     * 
     * @return Collection
     */
    public function getPaginatedImagesOfProduct(int $productId): Collection
    {
        if (Product::exists($productId) === false) throw new ResourceNotFoundException('Product Not Found');

        return Image::where('id', $productId)->get();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function paginatedImages(): LengthAwarePaginator
    {
        $images = Image::paginate(10);

        return $images;
    }

    /**
     * @param int $imageId
     * 
     * @return Image
     */
    public function getImageById(int $imageId): Image
    {
        $image = Image::find($imageId);

        if ($image === null) throw new ResourceNotFoundException('Image not found');

        return $image;
    }

    /**
     * @param array $imagePayload
     * 
     * @return Image
     */
    public function createImage(array $imagePayload): Image
    {
        /** @var ?UploadedFile */
        $uploadedImage = $imagePayload['image'] ?? null;

        if ($uploadedImage === null || $uploadedImage->getError())
            throw new BadRequestException('Error on Image Upload');

        $imagePayload['path'] = $uploadedImage->store('products', 'public');

        $image = Image::create($imagePayload);

        return $image;
    }

    /**
     * @param int $imageId
     * @param array $imagePayload
     * 
     * @return Image
     */
    public function updateImage(int $imageId, array $imagePayload): Image
    {
        $image = Image::find($imageId);

        if ($image === null) throw new ResourceNotFoundException("Image Not Found");

        /** @var UploadedFile */
        $uploadedImage = $imagePayload['image'] ?? null;

        if ($uploadedImage) {

            if ($uploadedImage->getError()) {
                throw new BadRequestException('Image could not be uploaded');
            }

            /* delete previous image */
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }

            $imagePayload['path'] = $uploadedImage->store('products', 'public');
        }

        $image->update($imagePayload);

        return $image;
    }

    /**
     * @param int $imageId
     * 
     * @return void
     */
    public function deleteImageById(int $imageId): void
    {
        $image = Image::find($imageId);

        if ($image === null) throw new ResourceNotFoundException('Image Not Found');

        Storage::disk('public')->delete($image->path);

        $image->delete();
    }
}
