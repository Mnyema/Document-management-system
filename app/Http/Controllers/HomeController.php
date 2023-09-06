<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\IOFactory as PhpWordIOFactory;
use PhpOffice\PhpPresentation\IOFactory as PhpPresentationIOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
use Smalot\PdfParser\Parser;


class HomeController extends Controller
{
    // public function upload(){
    //         $file = Document::all(); // Fetch all documents from the database
    //         return view('upload', compact('file'));
    // }

    public function index()
    {
        $posts = Post::all();
        return view('welcome',compact('posts'));
    }


    // public function download($id) {
    //     $post = Post::findOrFail($id);
    //     $media = $post->getFirstMedia('docs');
    //    // $media = Media::find($id);
    //     if ($media) {
    //         return response()->download($media->getPath(), $media->file_name);
    //     // } else {
    //     //     return view('posts.create');
    //     } else {
    //                 // Handle the case where the media file is not found
    //                 return redirect()->back()->with('error', 'File not found');
    //             }
    // }

    

public function download($id) {
    $media = Media::find($id);
    
    if ($media) {
        return response()->download($media->getPath(), $media->file_name);
    } else {
        // Handle the case where the media file is not found
        return redirect()->back()->with('error', 'File not found');
    }
}


// public function search(Request $request) {
//     $query = $request->input('q');

//     // Perform a search on the 'file_name' column of the 'media' table
//     $results = DB::table('media')
//         ->where('file_name', 'like', '%' . $query . '%')
//         ->get();

//     return view('search_results', compact('results', 'query'));
// }


// public function search(Request $request) {
//     $query = $request->input('q');

//     // Perform a search on the 'file_name' column of the 'media' table
//     $fileNameResults = DB::table('media')
//         ->where('file_name', 'like', '%' . $query . '%')
//         ->get();

//     // Perform a search on the content of DOCX files
//     $docxFiles = DB::table('media')
//         ->where('mime_type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
//         ->get();

//     $docxResults = [];

//     foreach ($docxFiles as $file) {
        
//         // dd(storage_path('app/public/' .$file->file_name));
//         // $docxPath = $media->getPath();
//         $phpWord = IOFactory::load(storage_path('app/public/' . $file->id . '/' .$file->file_name));
        
//         $text = '';

//         foreach ($phpWord->getSections() as $section) {
//             foreach ($section->getElements() as $element) {
//                 if ($element instanceof TextRun) {
//                     foreach ($element->getElements() as $textElement) {
//                         if ($textElement instanceof Text) {
//                             $text .= $textElement->getText();
//                         }
//                     }
//                 } elseif ($element instanceof Text) {
//                     $text .= $element->getText();
//                 }
//             }
//         }

//         if (str_contains($text, $query)) {
//             // The word was found in this file
//             // Add this file to the results
//             preg_match('/[^.]*' . preg_quote($query) . '[^.]*\./', $text, $matches);
//             $sentence = isset($matches[0]) ? trim($matches[0]) : '';

//             $docxResults[] = [
//                 'file_name' => $file->file_name,
//                 'sentence' => $sentence,
//             ];
//         }
//     }

//     // Merge the results
//     $results = [
//         'file_name_results' => $fileNameResults,
//         'docx_results' => collect($docxResults),
//     ];

//     return view('search_results', compact('results', 'query'));
// }


public function search(Request $request) {
    $query = $request->input('q');

    // Perform a search on the 'file_name' column of the 'media' table
    $fileNameResults = DB::table('media')
        ->where('file_name', 'like', '%' . $query . '%')
        ->get();

    // Perform a search on the content of DOCX files
    $docxFiles = DB::table('media')
        ->where('mime_type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
        ->get();

    $docxResults = [];

    foreach ($docxFiles as $file) {
        $phpWord = PhpWordIOFactory::load(storage_path('app/public/' . $file->id . '/' . $file->file_name));
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof TextRun) {
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof Text) {
                            $text .= $textElement->getText();
                        }
                    }
                } elseif ($element instanceof Text) {
                    $text .= $element->getText();
                }
            }
        }

        if (str_contains($text, $query)) {
            // The word was found in this file
            // Add this file to the results
            preg_match('/[^.]*' . preg_quote($query) . '[^.]*\./', $text, $matches);
            $sentence = isset($matches[0]) ? trim($matches[0]) : '';

            array_push($docxResults, [
                'file_name' => $file->file_name,
                'sentence' => htmlspecialchars($sentence),
                'id' => $file->id, // Add this line to include the id property
            ]);
        }
    }

    // Perform a search on the content of XLSX files
    $xlsxFiles = DB::table('media')
        ->where('mime_type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ->get();

    $xlsxResults = [];

    foreach ($xlsxFiles as$file) {
        try{
            $spreadsheet = IOFactory::load(storage_path('app/public/' . $file->id . '/' .$file->file_name));
            foreach ($spreadsheet->getAllSheets() as$worksheet) {
                foreach ($worksheet->getRowIterator() as$row) {
                    foreach ($row->getCellIterator() as$cell) {
                        if (str_contains($cell->getValue(), (string)$query)) {
                            // The word was found in this file
                            // Add this file to the results
                            // Return the entire cell value
                            array_push($xlsxResults, [
                                'file_name' => $file->file_name,
                                'cell_value' => htmlspecialchars("{$cell->getValue()}"),
                                'id' => $file->id, // Add this line to include the id property
                            ]);
                        }
                    }
                }
            }
        }catch(\Exception$e){
            continue;
        }
    }

    // Perform a search on the content of PPTX files
    $pptxFiles = DB::table('media')
        ->where('mime_type', 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
        ->get();

    $pptxResults = [];

    foreach ($pptxFiles as$file) {
        try{
            $presentation = PhpPresentationIOFactory::load(storage_path('app/public/' . $file->id . '/' .$file->file_name));
            foreach ($presentation->getAllSlides() as$slide) {
                foreach ($slide->getShapeCollection() as$shape) {
                    if ($shape instanceof RichText) {
                        foreach ($shape->getParagraphs() as$paragraph) {
                            foreach ($paragraph->getRichTextElements() as$textElement) {
                                if (str_contains($textElement->getText(), (string)$query)) {
                                    preg_match('/[^.]*' . preg_quote($query) . '[^.]*\./',  strip_tags($textElement->getText()),$matches);
                                    if(isset($matches[0])){
                                        // The word was found in this file
                                        // Add this file to the results
                                        // Return the entire sentence that contains the word or number
                                        array_push($pptxResults, [
                                            'file_name' => $file->file_name,
                                            'sentence' => htmlspecialchars($matches[0]),
                                            'id' => $file->id, // Add this line to include the id property
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }catch(\Exception$e){
            continue;
        }
    }

    // Perform a search on the content of TXT files
    $txtFiles = DB::table('media')
        ->where('mime_type', 'text/plain')
        ->get();

    $txtResults = [];

    foreach ($txtFiles as$file) {
        try{
            $text = file_get_contents(storage_path('app/public/' . $file->id . '/' .$file->file_name));

            if (str_contains($text, (string)$query)) {
                preg_match('/[^.]*' . preg_quote($query) . '[^.]*\./',  strip_tags($text),$matches);
                if(isset($matches[0])){
                    // The word was found in this file
                    // Add this file to the results
                    // Return the entire sentence that contains the word or number
                    array_push($txtResults, [
                        'file_name' => $file->file_name,
                        'sentence' => htmlspecialchars($matches[0]),
                        'id' => $file->id, // Add this line to include the id property
                    ]);
                }
            }
        }catch(\Exception$e){
            continue;
        }
    }

    // Perform a search on the content of PDF files
    $pdfFiles = DB::table('media')
        ->where('mime_type', 'application/pdf')
        ->get();

    $pdfResults = [];

    foreach ($pdfFiles as$file) {
        try{
            $parser = new Parser();
            $pdf = $parser->parseFile(storage_path('app/public/' . $file->id . '/' .$file->file_name));
            $text = $pdf->getText();

            if (str_contains($text, (string)$query)) {
                preg_match('/[^.]*' . preg_quote($query) . '[^.]*\./',  strip_tags($text),$matches);
                if(isset($matches[0])){
                    // The word was found in this file
                    // Add this file to the results
                    // Return the entire sentence that contains the word or number
                    array_push($pdfResults, [
                        'file_name' => $file->file_name,
                        'sentence' => htmlspecialchars($matches[0]),
                        'id' => $file->id, // Add this line to include the id property
                    ]);
                }
            }
        }catch(\Exception$e){
            continue;
        }
    }

    // Merge the results
    $results = [
        'fileNameResults' => $fileNameResults,
        'docxResults' => collect($docxResults),
        'xlsxResults' => collect($xlsxResults),
        'pptxResults' => collect($pptxResults),
        'txtResults' => collect($txtResults),
        'pdfResults' => collect($pdfResults),
    ];

    session(['search_results' => $results]);

    return redirect()->back();
}






//     public function store(Request $request){
//         $request->validate([
//             'path' => 'required',
//         ]);

//         foreach($request->file('path') as $key => $value){
//             if(Auth::id()){
//                 $user_id=Auth::user()->id;
//                } 
//             $originalName = $value->getClientOriginalName();
//             $fileName = $originalName;
//             $value->storeAs('public/documents', $fileName);
            
//             $file = new Document;
//             $file->user_id = $user_id ?? null;
//             $file->fileName = $originalName;
//             $file->path = 'public/documents/' . $fileName;
//             $file->size = $value->getSize();
//             $file->save();
//     }
//     return redirect()->back()->with('message','Document uploaded successfully');
// }



}