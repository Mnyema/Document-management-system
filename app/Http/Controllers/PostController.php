<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use Spatie\PdfToImage\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use App\Http\Requests\PostCreateRequest;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
//use PhpOffice\PhpSpreadsheet\Reader
use PhpOffice\PhpWord\IOFactory as PhpWordIOFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use PhpOffice\PhpPresentation\Shape\RichText\TextElementInterface;
use PhpOffice\PhpSpreadsheet\IOFactory as PhpSpreadsheetIOFactory;

class PostController extends Controller
{
    
    
    
    
    // ...
    
    

// ...

// public function show($id) {
//     $media = Media::find($id);

//     if ($media && $media->mime_type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
//         $docxPath = $media->getPath();
//         $pdfPath = storage_path('app/temp/') . uniqid() . '.pdf';

//         // Convert .docx to PDF using Ghostscript or Poppler binary
//         $phpWord = IOFactory::load($docxPath);
        
//         // Set the path to Ghostscript or Poppler binary
//         $pdfOptions = [
//             'pdfRenderer' => 'C:\Program Files\gs\gs10.01.2\bin\gswin64c', // Update this path
//         ];
        
//         $phpWord->save($pdfPath, 'PDF', $pdfOptions);

//         // Get PDF content
//         $pdfContent = file_get_contents($pdfPath);

//         // Return the PDF content to the view
//         $data = [
//             'pdfContent' => $pdfContent,
//         ];

//         return view('posts.show', $data);
//     } else {
//         return response()->json(['error' => 'Invalid or unsupported media type'], 400);
//     }
// }

    
public function show($id) {
    $posts = Post::all();
    $media = Media::find($id);
//dd($media->mime_type);
    if ($media) {
        if ($media->mime_type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            // Handle .docx files using PHPWord
            $docxPath = $media->getPath();
            $phpWord = PhpWordIOFactory::load($docxPath);
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

            $data = [
                'media' => $media,
                'contents' => $text,
            ];

            return view('showDoc', compact('data','posts'));
        }elseif ($media->mime_type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            // Handle .xls files using PHPExcel
            $xlsPath = $media->getPath();
            $spreadsheet = PhpSpreadsheetIOFactory::load($xlsPath);

           // Get the active sheet
           $worksheet = $spreadsheet->getActiveSheet();

           // Create an HTML table representation
           $htmlContent = '<table>';
           foreach ($worksheet->getRowIterator() as $row) {
               $htmlContent .= '<tr>';
               foreach ($row->getCellIterator() as $cell) {
                   $htmlContent .= '<td>' . $cell->getValue() . '</td>';
               }
               $htmlContent .= '</tr>';
           }
           $htmlContent .= '</table>';
            $data = [
                'htmlContent' => $htmlContent,
                'media' => $media,
            ];

            return view('showDoc', compact('data','posts'));
        }  elseif ($media->mime_type === 'application/vnd.openxmlformats-officedocument.presentationml.presentation') {
            // Handle .pptx files using PHPPresentation
            $pptxPath = $media->getPath();
            $presentation = IOFactory::load($pptxPath);
            $htmlContent = '';
        
            foreach ($presentation->getAllSlides() as $slide) {
                foreach ($slide->getShapeCollection() as $shape) {
                    if ($shape instanceof RichText) {
                        foreach ($shape->getParagraphs() as $paragraph) {
                            foreach ($paragraph->getRichTextElements() as $textElement) {
                                if ($textElement instanceof TextElementInterface) {
                                    $htmlContent .= $textElement->getText();
                                }
                            }
                        }
                    }
                }
            }
        
            $data = [
                'htmlContent' => $htmlContent,
                'media' => $media,
            ];
        
            return view('showDoc', compact('data','posts'));
        }
        
        else {
            // Handle other media types like PDF, images, etc.
            $contents = file_get_contents($media->getPath());

            $data = [
                'media' => $media,
                'contents' => $contents,
            ];

            return view('showDoc', compact('data','posts'));
        }
    } else {
        return response()->json(['error' => 'Media not found'], 404);
    }
}
    
   

    public function create() {
        $posts = Post::all();
        return view('upload',compact('posts'));
    }
    
    public function index()
    {
        $posts = Post::all();
        return view('welcome',compact('posts'));
    }

    public function store(PostCreateRequest $request) {
        $post = Post::create($request->validated());
        if($request->hasFile('file')){
            $post->addMediaFromRequest('file')->toMediaCollection('docs');
        }
    // $posts =Post::all();
    // return view('upload', compact('posts'));
    return redirect()->back()->with('message','Document uploaded successfully');
    }
    //  public function store(PostCreateRequest $request) {
    //     // dd($request->all());
    //      $post = Post::create($request->validated());
        
    //      $this->validate($request, [
    //         'file' => 'required|array',
    //         'file.*' => 'file',
    //     ]);
        

        // if($request->hasFile('file')){
        //     foreach ($request->file('file') as $file) {
        //         $post->addMedia($file)->toMediaCollection('docs');
        //     }
        // }

        // if ($request->hasFile('file')) {
        //     $fileAdders = $post->addMultipleMediaFromRequest(['file'])
        //         ->each(function ($fileAdder) {
        //             $fileAdder->toMediaCollection('docs');
        //         });
        // }
        
   // }
   
    
    public function edit(Post $post){
        return view('posts.edit', compact('post'));
    }

    public function update(PostCreateRequest $request, Post $post){
     $post->update($request->validated());
     if($request->hasFile('file')){
       // $post->clearMediaCollection('documents');
        $post->addMediaFromRequest('file')->toMediaCollection('docs');
     }
     return redirect()->route('posts.create');
    }

    public function destroy($id){
        $post= Post::findOrFail($id);
        $posts = Post::all();
   $post->delete();
   return view('welcome', compact('posts'))->with('message','Document deleted successfully');
    }

    
    public function download($id) {
        $post = Post::findOrFail($id);
        $media = $post->getFirstMedia('docs');
        
        if ($media) {
            return response()->download($media->getPath(), $media->file_name);
        // } else {
        //     return view('posts.create');
        } else {
                    // Handle the case where the media file is not found
                    return redirect()->back()->with('error', 'File not found');
                }
    }
   

// ...

// public function download($id) {
//     $post = Post::findOrFail($id);
//     $media = $post->getFirstMedia('docs');

//     if ($media) {
//         $file = $media->getPath();
//         $fileName = $media->file_name;

//         // You can also force a download by setting the appropriate headers
//         return response()->download($file, $fileName);
//     } else {
//         // Handle the case where the media file is not found
//         return redirect()->back()->with('error', 'File not found');
//     }
// }

    
}