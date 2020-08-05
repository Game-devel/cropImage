<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use App\CropImage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    protected function validator()
    {
        $rules = request()->validate([
            'image' => 'required|image|max:5000|dimensions:min_width=100,min_height=100'
        ]);
        return $rules;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crop_images = CropImage::all();
        return view('image', [
            'crop_images' => $crop_images
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->validator()) {
            return redirect()->route('index')->withErrors($this->validateRequest());
        }
        $positions = json_decode($request->positions) ?? [
            ['id' => 0, 'x' => rand(10,100), 'y' => rand(10,100), 'width' => rand(10,100), 'height' => rand(10,100)],
            ['id' => 1, 'x' => rand(10,100), 'y' => rand(10,100), 'width' => rand(10,100), 'height' => rand(10,100)],
            ['id' => 2, 'x' => rand(10,100), 'y' => rand(10,100), 'width' => rand(10,100), 'height' => rand(10,100)],
            ['id' => 3, 'x' => rand(10,100), 'y' => rand(10,100), 'width' => rand(10,100), 'height' => rand(10,100)]
        ];
        if($request->hasFile('image')) {
            //get filename with extension
            $filenamewithextension = $request->file('image')->getClientOriginalName();
    
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
    
            //get file extension
            $extension = $request->file('image')->getClientOriginalExtension();
    
            //filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;
    
            //Upload File
            $request->file('image')->storeAs('public/images', $filenametostore);
    
            if(!file_exists(public_path('storage/images/crop'))) {
                mkdir(public_path('storage/images/crop'), 0755);
            }
            $croped_img = [];
            foreach ($positions as $position) {
                // crop image
                $img = Image::make(public_path('storage/images/'.$filenametostore));
                $name = $filename.'_'.$position->id. '_'.time().'.'.$extension;
                $croppath = public_path('storage/images/crop/'.$name);                
                $img->crop(intval($position->width) ?? 0,intval($position->height) ?? 0, intval($position->x) ?? 0, intval($position->y) ?? 0);
                $img->save($croppath);
                array_push($croped_img, 'storage/images/crop/'.$name);
            }
            $crop_image = new CropImage();
            CropImage::create([
                'name_img' => $filename.'.'.$extension,
                'full_img' => 'storage/images/'.$filenametostore,
                'croped_img' => json_encode($croped_img)
            ]);                            
            return redirect()->route('index')->with(['success' => "Image cropped successfully."]);
        }
    }

    public function getImages($id = null, $part = null) {
        $rules = Validator::make(['id' => $id, 'part' => $part], [
            'id' => 'nullable|integer|exists:crop_images,id',
            'part' => 'nullable|integer|min:1|max:4'
        ]);     
        if ($rules->fails()) {
            return response()->json($rules->failed(), 423);
        }                    
        if ($id != null) {
            $image = CropImage::find($id);
            if ($part != null) {
                $croped_img = \json_decode($image->croped_img)[$part-1];                
                return Image::make(public_path($croped_img))->response();
            }
            return Image::make(public_path($image->full_img))->response();
        }
        return response()->json(CropImage::all(), 200);
    }
}
