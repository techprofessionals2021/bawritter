<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Auth;
class AttachmentController extends Controller
{

    function upload(Request $request)
    {
        // dd(Auth::id());
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:2048', // Maximum file size of 2MB
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
    
        // Store the uploaded file in the 'attachments' directory
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('attachments');
    
            return response()->json([
                'name' => $path,
                'display_name' => $file->getClientOriginalName()
            ], 200);
        } else {
            return response()->json([
                'error' => 'No file uploaded'
            ], 400);
        }
    }

    function remove(Request $request)
    {

   
        $filePath = 'attachments/' . $request->name;


        if (Storage::exists($filePath)) {
            // Delete the file
            Storage::delete($filePath);
            
            // Return a JSON response indicating success
            return response()->json([
                'message' => 'File has been removed',
                'file' => $request->name,
                'status' => true
            ]);
        } else {
            // Return a JSON response indicating the file was not found
            return response()->json([
                'message' => 'File not found',
                'file' => $request->name,
                'status' => false
            ]);
        }
    }
    
    
    

    function download(Request $request)
    {
        try {

            return Storage::download($request->file);
        } catch (\Exception $e) {
            //
            abort(404);
        }
    }
}
