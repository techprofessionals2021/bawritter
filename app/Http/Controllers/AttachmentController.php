<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{

    function upload(Request $request)
    {
        $attachment = Storage::putFile('attachments', $request->file('file'));

        return response()->json([
            'name' => $attachment,
            'display_name' => $request->file->getClientOriginalName()
        ], 200);
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
