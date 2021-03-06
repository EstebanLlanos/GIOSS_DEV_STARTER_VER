<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Classes\AAC;
use App\Classes\AEH;
use App\Classes\AMS;
use App\Classes\AVA;
use App\Classes\APS;
use App\Classes\RAD;
use App\Classes\ATP;
use App\Classes\ARQ;
use App\Classes\ARC;
use App\Classes\FileValidator;
use App\Models\FileStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class filesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = DB::table('file_statuses')->join('archivo', 'file_statuses.archivoid', '=', 'archivo.id_archivo_seq')->paginate(10);

        return view('archivos.upload_files')->with('files', $files);
    }

    /**
     * Show the form for creating a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function upload()
    {
        return view('archivos.upload_files');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        ini_set("log_errors", 1);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);

        $consecutive = $request->consecutive;
        
        $files = $request->file('archivo');
        $fileTypes = $request->tipo_file;
        $current_date = $request->current_date;
        $current_time = $request->current_time;
        $current_user = $request->current_user;
        
        Log::info("------- El usuario actual es: ".$current_user."------ La fecha actual es:".$current_date."----- El tiempo actual es:".$current_time );
        $datos_creacion = array();
        $datos_creacion[0] = $current_user;
        $datos_creacion[1] = $current_date;
        $datos_creacion[2] = $current_time;

        Log::info("------- El arreglo datos creacion es: ".print_r($datos_creacion, true));

        $count = 0;
        foreach($files as $file) {
            $rules = array('file' => 'required'); //'required|mimes:png,gif,jpeg,txt,pdf,doc'
            $validator = Validator::make(array('file'=> $file), $rules);
            $validator->validate();
            $folder = '/'.$consecutive.'/'.$fileTypes[$count].$consecutive.'/';

            $routeFolder = storage_path('archivos').$folder;

            //Log::info("-------------------- Se creo la ruta = ".$routeFolder);

            $routeFile = $folder.$file->getClientOriginalName();
            $fileName = $file->getClientOriginalName();

            $FileValidator = new FileValidator($fileName);

            Storage::disk('archivos')->put($routeFile, \File::get($file));
            
            //try {
                switch ($fileTypes[$count]) {
                    //Se está llamando AAC 
                    case 'AAC':
                        $AAC = new AAC($routeFolder, $fileName, $consecutive, $datos_creacion);
                        $AAC->manageContent();
                        break;
                    case 'AEH':
                        $AEH = new AEH($routeFolder, $fileName, $consecutive, $datos_creacion);
                        $AEH->manageContent();
                        break;
                    case 'ASM':
                        $AMS = new AMS($routeFolder, $fileName, $consecutive, $datos_creacion);
                        $AMS->manageContent();
                        break;
                    case 'APS':
                        $APS = new APS($routeFolder, $fileName, $consecutive, $datos_creacion);
                        $APS->manageContent();
                        break;
                    case 'AVA':
                        $AVA = new AVA($routeFolder, $fileName, $consecutive, $datos_creacion);
                        $AVA->manageContent();
                        break;
                    case 'RAD':
                        $RAD = new RAD($routeFolder, $fileName, $consecutive, $datos_creacion);
                        $RAD->manageContent();
                        break;
                    case 'ATP':
                        $ATP = new ATP($routeFolder, $fileName, $consecutive, $datos_creacion);
                        $ATP->manageContent();
                        break;
                    case 'ARQ':
                        $ARQ = new ARQ($routeFolder,$fileName, $consecutive, $datos_creacion);
                        $ARQ->manageContent();
                        break;
                    case 'ARC':
                        $ARC = new ARC($routeFolder,$fileName, $consecutive, $datos_creacion);
                        $ARC->manageContent();
                        break;
                }

                $count++;
          
        }

        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //dd($request->consecutive);
        

        $files = DB::table('file_statuses')->join('archivo', 'file_statuses.archivoid', '=', 'archivo.id_archivo_seq')->get();

        //Log::info(print_r($result, true));

        echo json_encode($files);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
