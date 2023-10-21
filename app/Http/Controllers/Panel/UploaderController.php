<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\UploadCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploaderController extends Controller
{


    public function upload(Request $request)
    {
        $this->validate($request, [
            'files' => 'array|required',
            'files.*' => 'image|mimes:png,jpeg,jpg,gif|max:1000'
        ], [
            'files.array' => 'فایلی برای بارگذاری انتخاب نشده است.',
            'files.required' => 'فایلی برای بارگذاری انتخاب نشده است.',
            'files.*.mimes' => 'فرمت های مجاز: jpg - jpeg - png می باشند.',
            'files.*.image' => 'فرمت های مجاز: jpg - jpeg - png می باشند.',
            'files.*.max' => 'حداکثر سایز مجاز 1M می باشد.',
        ]);

        $files = [];

        foreach ($request->file('files') as $file) {
            $ext = $file->getClientOriginalExtension();
            $size = $file->getSize();
            $path = 'assets/uploads/' . now()->format('Y-m-d') . '/';
            $name = Str::random(15) . '.' . $ext;
            if (Storage::disk('_public')->putFileAs($path, $file, $name)) {
                $uploadedFile = UploadCenter::create([
                    'alt' => null,
                    'path' => $path . $name,
                    'size' => $size,
                    'ext' => $ext,
                    'model' => $request->model ?? null
                ]);

                $files[] = [
                    'id' => $uploadedFile->id,
                    'path' => url($uploadedFile->path),
                    'alt' => null,
                    'size' => $size,
                    'ext' => $ext,
                    'model' => $request->model ?? null
                ];
            }
        }

        return Response::success(
            'اطلاعات با موفقیت ذخیره شد',
            ['files' => $files],
            null
        );
    }

    public function setAltText(Request $request)
    {
        $this->validate($request, [
            'alts' => 'required'
        ], [
            'alts.required' => 'متن جایگزین تصویر الزامی است.'
        ]);

        $files = [];
        $alts = json_decode($request->alts, true);
        if (is_array($alts)) {
            foreach ($alts as $alt) {
                $uploadedFile = UploadCenter::find($alt['file_id']);
                if ($uploadedFile) {
                    $uploadedFile->update(['alt' => $alt['alt_text']]);
                    $files[] = [
                        'id' => $uploadedFile->id,
                        'path' => url($uploadedFile->path),
                        'alt' => $uploadedFile->alt,
                        'size' => $uploadedFile->size,
                        'ext' => $uploadedFile->ext,
                        'model' => $uploadedFile->model
                    ];
                }
            }
        }

        return Response::success('اطلاعات با موفقیت ذخیره شد', ['files' => $files]);
    }

    public function tinymce(Request $request)
    {
        return \response()->json(['location' => url('assets/panel/dist/img/user-avatar.jpg')]);
    }
}
