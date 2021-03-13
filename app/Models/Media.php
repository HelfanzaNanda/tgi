<?php

namespace App\Models;

use DB;
use File;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $model
 * @property int        $model_id
 * @property string     $type
 * @property string     $original_name
 * @property string     $filepath
 * @property string     $filename
 * @property string     $mime_type
 * @property string     $extension
 * @property int        $created_at
 * @property int        $updated_at
 */
class Media extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model', 'model_id', 'type', 'original_name', 'filepath', 'filename', 'mime_type', 'extension', 'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'model' => 'string', 'model_id' => 'int', 'type' => 'string', 'original_name' => 'string', 'filepath' => 'string', 'filename' => 'string', 'mime_type' => 'string', 'extension' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    // Scopes...

    // Functions ...

    // Relations ...

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $update = self::where('id', $params['id'])->update($params);

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses Memperbaharui Gambar'
            ]);
        }

        $res = [];

        if($request->hasFile('files')) {
            $allowedfileExtension = ['pdf', 'jpg', 'png'];
            $files = $request->file('files');

            $month_year_pfx = date('mY');
            $path_pfx = 'public/media/'.$params['type'].'/'.$month_year_pfx;
            $path = '/storage/'.$path_pfx;

            File::makeDirectory($path, 0777, true, true);

            foreach($files as $key => $file){
                $original_filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $mime = $file->getClientMimeType();
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $filename = md5(uniqid(rand(), true).time()).'.'.$extension;

                    $file->move(storage_path('app').'/'.$path_pfx, $filename);

                    $data = [
                        'model' => $params['model'],
                        'model_id' => $params['model_id'],
                        'type' => $params['type'],
                        'original_name' => $original_filename,
                        'filepath' => '/storage/media/'.$params['type'].'/'.$month_year_pfx,
                        'filename' => $filename,
                        'mime_type' => $mime,
                        'extension' => $extension,
                    ];

                    $save = self::create($data);

                    $data['id'] = $save->id;
                    $res[] = $data;
                } else {
                    DB::rollBack();
                    
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Only upload jpg, png, and pdf'
                    ]);
                }
            }
        } else {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'No Image Provided'
            ]);
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Menambah Gambar',
            'data' => $res
        ]);
    } 
}
