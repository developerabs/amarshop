<?php

use Illuminate\Support\Facades\Storage;

function uploadImage($image, $path)
{
    $imageName = time() . '_' . $image->getClientOriginalName();
    $image->storeAs($path, $imageName, 'public');
    return $path . '/' . $imageName;
}
function updateImage($image, $path, $oldImagePath = null)
{
    if ($oldImagePath) {
        deleteImage($oldImagePath);
    }
    return uploadImage($image, $path);
}
function deleteImage($imagePath)
{
    if (Storage::disk('public')->exists($imagePath)) {
        Storage::disk('public')->delete($imagePath);
    }
}
function getImageUrl($imagePath)
{
    if ($imagePath && Storage::disk('public')->exists($imagePath)) {
        return asset('storage/' . $imagePath);
    }
    return null;
}