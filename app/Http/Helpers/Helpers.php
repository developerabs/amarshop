<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function mediaDisk(): string
{
    static $resolvedDisk = null;

    if ($resolvedDisk !== null) {
        return $resolvedDisk;
    }

    $fallbackDisk = config('filesystems.default', 'public');
    if (!in_array($fallbackDisk, ['public', 's3'], true)) {
        $fallbackDisk = 'public';
    }

    $configuredDisk = env('MEDIA_DISK', $fallbackDisk);
    $resolvedDisk = array_key_exists($configuredDisk, config('filesystems.disks', [])) ? $configuredDisk : 'public';

    return $resolvedDisk;
}

function uploadImage(UploadedFile $image, string $path): string
{
    $disk = mediaDisk();
    $safePath = trim($path, '/');
    $extension = strtolower((string) $image->getClientOriginalExtension());
    $fileName = now()->format('YmdHis') . '_' . Str::random(16) . ($extension ? '.' . $extension : '');

    return $image->storeAs($safePath, $fileName, $disk);
}

function updateImage(UploadedFile $image, string $path, ?string $oldImagePath = null): string
{
    if (!empty($oldImagePath)) {
        deleteImage($oldImagePath);
    }

    return uploadImage($image, $path);
}

function deleteImage(?string $imagePath): void
{
    if (empty($imagePath)) {
        return;
    }

    $disk = mediaDisk();

    if (Storage::disk($disk)->exists($imagePath)) {
        Storage::disk($disk)->delete($imagePath);
    }
}

function getImageUrl(?string $imagePath): string
{
    $defaultImage = asset('storage/default/default-image.png');

    if (empty($imagePath)) {
        return $defaultImage;
    }

    try {
        $disk = mediaDisk();

        if ($disk === 'public') {
            return asset('storage/' . ltrim($imagePath, '/'));
        }

        /** @var FilesystemAdapter $filesystem */
        $filesystem = Storage::disk($disk);

        return $filesystem->url($imagePath);
    } catch (Throwable $e) {
        return $defaultImage;
    }
}