<?php 

	$pagesFilePath = base_path('/resources/views/livewire/public/tools.blade.php'); // Update with the actual path to your tools.blade.php file

	// Read the contents of the tools.blade.php file
	$fileContents = File::get($pagesFilePath);

    switch ( Config::get('app.pdf_addon_version') ) {

        case '1.0.1':
				$codeSnippet = '';
            break;

        default:

				// Define the code snippet to be inserted
				$codeSnippet = <<<EOD

						@case('Image to Text')
							@livewire('public.tools.image-to-text')
						@break

						@case('Image Compressor')
							@livewire('public.tools.image-compressor')
						@break

						@case('PowerPoint to PDF')
							@livewire('public.tools.powerpoint-to-pdf')
						@break

						@case('Word to PDF')
							@livewire('public.tools.word-to-pdf')
						@break

						@case('Excel to PDF')
							@livewire('public.tools.excel-to-pdf')
						@break

						@case('HTML to PDF')
							@livewire('public.tools.html-to-pdf')
						@break

						@case('PNG to PDF')
							@livewire('public.tools.png-to-pdf')
						@break

						@case('JPG to PDF')
							@livewire('public.tools.jpg-to-pdf')
						@break

						@case('Text to PDF')
							@livewire('public.tools.text-to-pdf')
						@break

						@case('RTF to PDF')
							@livewire('public.tools.rtf-to-pdf')
						@break

						@case('ODT to PDF')
							@livewire('public.tools.odt-to-pdf')
						@break

						@case('Word to ODT')
							@livewire('public.tools.word-to-odt')
						@break

						@case('Word to HTML')
							@livewire('public.tools.word-to-html')
						@break

						@case('WEBP to PDF')
							@livewire('public.tools.webp-to-pdf')
						@break
				EOD;
            break;
    }


    // Find the position to insert the code snippet
    $insertPosition = strpos($fileContents, '@switch($tool_name)') + strlen('@switch($tool_name)');

    // Insert the code snippet into the pages.blade.php file
    if ($insertPosition !== false) {
        $modifiedContents = substr_replace($fileContents, "\n" . $codeSnippet, $insertPosition, 0);
        File::put($pagesFilePath, $modifiedContents);
    }

    // Import Tools
    $addonsComponent->onImportTools();

    $env->changeEnv([
		'PDF_ADDON_VERSION' => '1.0.2'
    ]);