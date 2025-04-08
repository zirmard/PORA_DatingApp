<?php

namespace App\Helpers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * AWSHelper SDK
 */
class AWSHelper {

    /**
     * uploadS3Image
     * @param Request $request
     * @param type $fileName
     * @param type $path
     * @return string
     */
    public static function uploadS3Image(Request $request, $fileName, $path = '') {
        $name = "";

        if ($request->hasFile($fileName)) {
            $file = $request->file($fileName);
            $name = time() . random_int(1111, 9999) . '.' . $file->getClientOriginalExtension();

            $img = Image::make($file);
            $img->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            //detach method is the key! Hours to find it... :/
            $resource = $img->stream()->detach();

            $filePath = $path . $name;
            // echo $filePath;die;
            Storage::disk('s3')->put($filePath, $resource);

            $img = Image::make($file);
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            //detach method is the key! Hours to find it... :/
            $resource = $img->stream()->detach();

            $filePath = $path . '/thumb/' . $name;
            Storage::disk('s3')->put($filePath, $resource);
        }
        return $name;
    }

    /**
     * uploadMultipleImage
     * @param Request $request
     * @param type $fileName
     * @param type $path
     * @return string
     */
    public static function uploadMultipleImage(Request $request, $fileName, $path = '') {

        $images = [];
        if ($request->hasFile($fileName)) {
            foreach ($request->file($fileName) as $file) {
                $name = time() . '_' . random_int(1111, 9999) . '.' . $file->getClientOriginalExtension();

                /* Compress Original Image */
                $img = Image::make($file);
                $img->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                //detach method is the key! Hours to find it... :/
                $resource = $img->stream()->detach();

                $filePath = $path . $name;
                Storage::disk('s3')->put($filePath, $resource, 'public');

                /* Compress Thumb Image */
                $img = Image::make($file);
                $img->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                //detach method is the key! Hours to find it... :/
                $resource = $img->stream()->detach();

                $filePath = $path . '/thumb/' . $name;
                Storage::disk('s3')->put($filePath, $resource, 'public');
                $images[] = $name;
            }
        }
        return $images;
    }

    /**
     * uploadBase64FileS3
     * @param type $base64Context
     * @param type $fileName
     * @param type $path
     * @return type
     */
    public static function uploadBase64FileS3($base64Context, $fileName, $path = '') {
        if (!empty($base64Context)) {
            $filePath = $path . '/' . $fileName;
            Storage::disk('s3')->put($filePath, $base64Context, 'public');
        }
        return $fileName;
    }

    /**
     * getSize
     * @param type $file
     * @return type
     */
    public static function getSize($file) {
        return Storage::disk('s3')->size($file);
    }

    /**
     * removeS3Image
     * @param Request $request
     * @param type $path
     * @return boolean
     */
    public static function removeS3Image(Request $request, $path = '') {

        $isAvailable = Storage::disk('s3')->exists($path . "/" . $request->name);
        if ($isAvailable) {
            Storage::disk('s3')->delete($path . "/" . $request->name);
        }
        $isAvailable = Storage::disk('s3')->exists($path . "/thumb/" . $request->name);
        if ($isAvailable) {
            Storage::disk('s3')->delete($path . "/thumb/" . $request->name);
        }

        return true;
    }

    /**
     * uploadPdfFileToS3
     * @param Request $request
     * @param type $fileName
     * @param type $path
     * @return string
     */
    public static function uploadPdfFileToS3(Request $request, $fileName, $path = '') { // Upload Mobi File to S3
        $name = "";
        if ($request->hasFile($fileName)) {
            $file = $request->file($fileName);
            $name = time() . random_int(1111, 9999) . '.' . $file->getClientOriginalExtension();
            $filePath = $path . $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file));
        }
        return $name;
    }

    /**
     * getCloundFrontUrl
     * @param type $fileName
     * @param type $path
     * @return type
     */
    public static function getCloundFrontUrl($fileName, $path = "") {
        return AWS_CLOUDFRONT_URL . $path . $fileName;
    }

    /**
     * getUrl
     * @param type $fileName
     * @param type $path
     * @return string
     */
    public static function getUrl($fileName, $path = "") {


        $value = $path . $fileName;
        $disk = Storage::disk('s3');
        if ($disk->exists($value)) {
            $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                'Bucket' => env("AWS_BUCKET", "examprep193534-dev"),
                'Key' => $value,
                'ResponseContentDisposition' => 'attachment;',
            ]);

            $request = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+30 minutes');
            return (string) $request->getUri();
        } else {
            return "";
        }
    }

}
