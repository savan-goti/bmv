<?php
//FOR GLOBAL FUNCTIONS

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Request;


// Generate Filename
function fileName($ext, $prefix = "img_")
{
    return $prefix . now()->format('dmYHisv') . rand(100000, 999999) . '.' . $ext;
}

// Upload File
function uploadFile($file,$path,$prefix = "img_")
{
    $fileName = fileName($file->getClientOriginalExtension(),$prefix);
    $file->move(public_path($path), $fileName);
    return $fileName;
}

if (!function_exists('uploadImgFile')) {
    function uploadImgFile($file, $path, $prefix = 'img_')
    {
        $fileName = fileName($file->getClientOriginalExtension(),$prefix);

        $destinationPath = public_path($path);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $fullPath = $destinationPath . '/' . $fileName;

        // ✅ Use ImageManager in v3
        $manager = new ImageManager(Driver::class); // uses default GD driver
        $image = $manager->read($file->getRealPath())
            ->toWebp(quality: 70);

        // Save image
        $image->save($fullPath);

        // Optional: Optimize with Spatie
        if (class_exists(OptimizerChainFactory::class)) {
            OptimizerChainFactory::create()->optimize($fullPath);
        }

        return $fileName;
    }
}

if (!function_exists('uploadMultipleImages')) {
    /**
     * Upload multiple images directly to public/uploads/bike_image
     *
     * @param array $files
     * @param string $folder
     * @return array
     */
    /*function uploadMultipleImages(array $files, string $folder,$prefix = "img_"): array
    {
        $paths = [];
        $destination = public_path($folder);

        // Create folder if not exists
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755,true);
        }

        foreach ($files as $file) {
            $fileName = fileName($file->getClientOriginalExtension(),$prefix);
            $file->move($destination, $fileName);
            $paths[] = $fileName;
        }

        return $paths;
    }*/

    function uploadMultipleImages(array $files, string $folder,$prefix = "img_"): array
    {
        $paths = [];
        $destination = public_path($folder);

        // Create folder if not exists
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755,true);
        }

        foreach ($files as $file) {
            $fileName = fileName($file->getClientOriginalExtension(),$prefix);
//            $file->move($destination, $fileName);

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $fullPath = $destination . '/' . $fileName;

            // ✅ Use ImageManager in v3
            $manager = new ImageManager(Driver::class); // uses default GD driver
            $image = $manager->read($file->getRealPath())
                ->toWebp(quality: 70);

            // Save image
            $image->save($fullPath);

            // Optional: Optimize with Spatie
            if (class_exists(\Spatie\ImageOptimizer\OptimizerChainFactory::class)) {
                OptimizerChainFactory::create()->optimize($fullPath);
            }
            $paths[] = $fileName;
        }

        return $paths;
    }
}

function slug($name){
    return Str::slug($name . '-' . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT));
}

function uploadFilepondFile($file, $path, $prefix = "img_")
{
    // $extension = $file->getExtension() != "" ? $file->getExtension() : $file->getClientOriginalExtension();
    $extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);

    if (!$extension) {
        $extension = $file->getClientOriginalExtension();
    }

    $fileName = fileName($extension, $prefix);
    $file->move(public_path($path), $fileName);
    return $fileName;
}

function uploadFilepondEncodedFile($json, $path, $prefix = 'img_')
{
    $bannerJson = json_decode($json, true);

    $base64Image = base64_decode($bannerJson['data']);

    // Get extension from MIME type
    $mimeType = $bannerJson['type'];
    $extension = explode('/', $mimeType)[1];

    // Create ImageManager instance with driver (e.g., 'gd')

    $manager = new ImageManager(Driver::class);
    $image = $manager->read($base64Image); // Use `read()` instead of `make()`

    $tempPath = tempnam(sys_get_temp_dir(), $prefix);
    $tempPathWithExt = $tempPath . '.' . $extension;
    $image->save($tempPathWithExt);


    $uploadedFile = new \Illuminate\Http\File($tempPathWithExt);
    $fileName = uploadFilepondFile($uploadedFile, $path, $prefix);
    unlink($tempPath);

    return $fileName;
}


function uploadImgFilepondFile($file, $path, $prefix = "img_")
{
    // $extension = $file->getExtension() != "" ? $file->getExtension() : $file->getClientOriginalExtension();
    $extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);

    if (!$extension) {
        $extension = $file->getClientOriginalExtension();
    }

    $fileName = fileName($extension, $prefix);
    $destination = public_path($path);
    if (!file_exists($destination)) {
        mkdir($destination, 0755, true);
    }
    $fullPath = $destination . '/' . $fileName;

    // ✅ Use ImageManager in v3
    $manager = new ImageManager(Driver::class); // uses default GD driver
    $image = $manager->read($file->getRealPath())
        ->toWebp(quality: 70);

    // Save image
    $image->save($fullPath);

    // Optional: Optimize with Spatie
    if (class_exists(\Spatie\ImageOptimizer\OptimizerChainFactory::class)) {
        OptimizerChainFactory::create()->optimize($fullPath);
    }
    return $fileName;
}

function uploadImgFilepondEncodedFile($json, $path, $prefix = 'img_')
{
    $bannerJson = json_decode($json, true);

    $base64Image = base64_decode($bannerJson['data']);

    // Get extension from MIME type
    $mimeType = $bannerJson['type'];
    $extension = explode('/', $mimeType)[1];

    // Create ImageManager instance with driver (e.g., 'gd')

    $manager = new ImageManager(Driver::class);
    $image = $manager->read($base64Image); // Use `read()` instead of `make()`

    $tempPath = tempnam(sys_get_temp_dir(), $prefix);
    $tempPathWithExt = $tempPath . '.' . $extension;
    $image->save($tempPathWithExt);


    $uploadedFile = new \Illuminate\Http\File($tempPathWithExt);
    $fileName = uploadImgFilepondFile($uploadedFile, $path, $prefix);
    unlink($tempPath);

    return $fileName;
}

function formatAmount($amount) {
    if ($amount >= 1000000) {
        return round($amount / 1000000, 1) . 'M';
    } elseif ($amount >= 1000) {
        return round($amount / 1000, 1) . 'K';
    } else {
        return $amount;
    }
}


function navPagination($totalPages, $active=1){
    $html = '<nav>
        <ul class="pagination">';
            if($totalPages>0){
                $html .= '<li class="previous-page-btn" data-page="" data-totalPage="'. $totalPages .'">
                    <a href="javascript:void(0);">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </li>
                <div class="d-flex overflow-auto">';
            }
                for($i = 1; $i <= $totalPages; $i++){
                        $html .= '<li class="'. (($i == $active)?'active':'') .' page-btn" data-page="'. $i .'"><a href="javascript:void(0);">'. $i .'</a></li>';
                }
        if($totalPages>0) {
            $html .= '</div>
            <li class="next-page-btn" data-page="' . (($totalPages > 1) ? '2' : '') . '" data-totalPage="' . $totalPages . '" >
                <a href="javascript:void(0);">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </li>';
        }
        $html .= '</ul>
    </nav>';

    return $html;
}


function currency($amount, $type=0){
//    $amt = \Illuminate\Support\Number::currency($amount);
    $amt = '€'.$amount;
    return $amt;
}
function currency_icon(){
    return '€';
}

function deleteImage($file,$path){
    $filepath = public_path($path).basename($file);
    if(File::exists($filepath)) {
        File::delete($filepath);
        return true;
    }
    return false;
}

if (!function_exists('deleteImgFile')) {
    function deleteImgFile($file, $path)
    {
        return deleteImage($file, $path);
    }
}

